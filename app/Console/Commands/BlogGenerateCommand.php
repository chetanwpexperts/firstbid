<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BlogGenerateCommand extends Command
{
    protected $signature = 'blog:generate {--topic= : Specific topic to generate}';
    protected $description = 'Automated AI Blog Generator for Upwork proposal strategy & SEO traffic';

    public function handle()
    {
        $this->info('Starting AI Blog post generation...');

        // Deduplicate pre-existing duplicate articles in DB
        $allBlogs = Blog::orderBy('id', 'asc')->get();
        $seenTitles = [];
        foreach ($allBlogs as $b) {
            $norm = strtolower(trim($b->title));
            if (in_array($norm, $seenTitles)) {
                $b->delete();
            } else {
                $seenTitles[] = $norm;
            }
        }

        $existingTitles = Blog::pluck('title')->toArray();
        $existingTitlesList = ! empty($existingTitles)
            ? implode("\n- ", $existingTitles)
            : 'None yet.';

        $this->info('Fetched ' . count($existingTitles) . ' existing blog titles from database to avoid duplicates.');

        $specificTopic = $this->option('topic');

        if ($specificTopic) {
            $prompt = <<<PROMPT
You are a senior freelance growth strategist and SEO content writer. Write a detailed, highly engaging 1,200+ word blog article for Upwork freelancers on the topic: "{$specificTopic}".

Use Markdown headers (##, ###), bold text, bullet points, and clean paragraphs. Sound professional, specific, actionable, and practical. Do NOT use cheesy buzzwords ("seamless", "game-changer").

Structure your response using these exact demarcations:
[TITLE] Write Title Here [/TITLE]
[CATEGORY] Pick one: Upwork Strategy / Proposal Hooks / Screening Q&A / Estimating & Pricing / Upwork Compliance / Client Management [/CATEGORY]
[META_TITLE] Write Meta Title Here [/META_TITLE]
[META_DESCRIPTION] Write Meta Description Here [/META_DESCRIPTION]
[CONTENT]
Write full markdown article body here...
[/CONTENT]
PROMPT;
        } else {
            $prompt = <<<PROMPT
You are a senior freelance growth strategist and SEO content writer for FirstBidIn (an AI proposal assistant for Upwork freelancers).

Your task is to invent a COMPLETELY UNIQUE, fresh, high-demand blog article topic for Upwork freelancers.

CRITICAL REQUIREMENT: Here is the list of titles ALREADY published on our blog:
- {$existingTitlesList}

DO NOT write about any of the titles listed above or create minor variations of them. Invent a brand new, specific, highly valuable topic that has NOT been covered yet.

Ideas for unique angles:
- How to spot and avoid client red flags & scams on Upwork
- How to increase your hourly rate on Upwork from \$25/hr to \$90/hr
- How to win enterprise & long-term retainer clients on Upwork
- Optimizing your Upwork Profile Title, Overview & Portfolio for 2026 algorithm search
- Handling client scope creep, refund demands, and dispute resolution safely
- How to get your first 5-star review on Upwork in 7 days without underpricing
- Top 7 Connect bidding mistakes that waste money on Upwork
- How to transition from a solo freelancer to an Upwork Agency

Write a detailed, highly engaging 1,200+ word blog article. Use Markdown headers (##, ###), bold text, bullet points, and clean paragraphs. Sound professional, specific, actionable, and practical. Do NOT use cheesy buzzwords ("seamless", "game-changer").

Structure your response using these exact demarcations:
[TITLE] Write Title Here [/TITLE]
[CATEGORY] Pick one: Upwork Strategy / Proposal Hooks / Screening Q&A / Estimating & Pricing / Upwork Compliance / Client Management / Agency & Growth [/CATEGORY]
[META_TITLE] Write Meta Title Here [/META_TITLE]
[META_DESCRIPTION] Write Meta Description Here [/META_DESCRIPTION]
[CONTENT]
Write full markdown article body here...
[/CONTENT]
PROMPT;
        }

        try {
            $res = Http::connectTimeout(10)
                ->timeout(75)
                ->withHeaders([
                    'x-api-key'         => config('services.anthropic.key'),
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => config('services.anthropic.model', 'claude-sonnet-4-6'),
                    'max_tokens' => 4096,
                    'messages'   => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ])
                ->json();

            $text = collect($res['content'] ?? [])->where('type', 'text')->pluck('text')->implode("\n");

            preg_match('/\[TITLE\](.*?)\[\/TITLE\]/s', $text, $tM);
            preg_match('/\[CATEGORY\](.*?)\[\/CATEGORY\]/s', $text, $catM);
            preg_match('/\[META_TITLE\](.*?)\[\/META_TITLE\]/s', $text, $mtM);
            preg_match('/\[META_DESCRIPTION\](.*?)\[\/META_DESCRIPTION\]/s', $text, $mdM);
            preg_match('/\[CONTENT\](.*?)\[\/CONTENT\]/s', $text, $cM);

            // Closing [/CONTENT] tag can be missing if the model runs out of tokens or
            // drifts from the format — fall back to "everything after [CONTENT]" rather
            // than the entire raw response (which still contains the other tag blocks).
            if (! isset($cM[1]) && preg_match('/\[CONTENT\](.*)$/s', $text, $cMOpen)) {
                $cM = $cMOpen;
            }

            $title = isset($tM[1]) ? trim($tM[1]) : 'Upwork Freelance Strategy Guide';
            $category = isset($catM[1]) ? trim($catM[1]) : 'Upwork Strategy';
            $metaTitle = isset($mtM[1]) ? trim($mtM[1]) : $title;
            $metaDescription = isset($mdM[1]) ? trim($mdM[1]) : Str::limit(strip_tags($text), 150);
            $rawContent = isset($cM[1]) ? trim($cM[1]) : null;

            if (empty($rawContent)) {
                $this->error('Could not parse [CONTENT] block from API response — skipping publish to avoid a garbled post.');
                return 1;
            }

            // Defense in depth: if any of the other section tags leaked into the
            // extracted content, parsing genuinely failed — don't publish it.
            if (preg_match('/\[(TITLE|CATEGORY|META_TITLE|META_DESCRIPTION|CONTENT)\]/', $rawContent)) {
                $this->error('Extracted content still contains template tags — skipping publish to avoid a garbled post.');
                return 1;
            }

            // Check if title or very similar title already exists
            if (Blog::where('title', $title)->exists()) {
                $this->warn("Generated title '{$title}' already exists. Skipping duplicate save.");
                return 0;
            }

            // Convert basic Markdown headers & paragraphs to clean HTML
            $contentHtml = preg_replace('/^### (.*?)$/m', '<h3>$1</h3>', $rawContent);
            $contentHtml = preg_replace('/^## (.*?)$/m', '<h2>$1</h2>', $contentHtml);
            $contentHtml = preg_replace('/^\* (.*?)$/m', '<li>$1</li>', $contentHtml);
            $contentHtml = preg_replace('/^- (.*?)$/m', '<li>$1</li>', $contentHtml);
            $contentHtml = nl2br($contentHtml);

            $slug = Str::slug($title);
            if (Blog::where('slug', $slug)->exists()) {
                $slug .= '-' . Str::random(5);
            }

            $blog = Blog::create([
                'slug'                 => $slug,
                'title'                => $title,
                'meta_title'           => $metaTitle,
                'meta_description'     => $metaDescription,
                'content'              => $contentHtml,
                'category'             => $category,
                'reading_time_minutes' => max(1, (int) ceil(str_word_count(strip_tags($rawContent)) / 200)),
                'is_published'         => true,
                'published_at'         => now(),
            ]);

            $this->info("Successfully generated and published blog: {$blog->title} (Slug: {$blog->slug})");
            return 0;
        } catch (\Throwable $e) {
            $this->error("Blog generation error: " . $e->getMessage());
            return 1;
        }
    }
}
