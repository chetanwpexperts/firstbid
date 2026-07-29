<?php

namespace App\Services;

use DOMDocument;
use DOMNode;
use DOMXPath;

/**
 * Robust HTML parser for Upwork job-alert emails.
 */
class UpworkEmailParser
{
    public function parse(string $html, string $subject = ''): array
    {
        if (trim($html) === '') {
            return [];
        }

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $anchors = $xpath->query('//a[@href]');

        $groups = [];
        $order = [];

        foreach ($anchors as $anchor) {
            $href = $anchor->getAttribute('href');
            $decoded = urldecode($href);

            // Match ciphertext starting with ~01 or ~ followed by alphanumeric/underscore/hyphen
            if (! preg_match('/(~01[a-zA-Z0-9_-]{12,}|~[a-zA-Z0-9_-]{15,})/', $decoded, $m)) {
                continue;
            }

            $ciphertext = $m[1];
            $text = trim(preg_replace('/\s+/', ' ', $anchor->textContent ?? ''));

            if (! isset($groups[$ciphertext])) {
                $groups[$ciphertext] = [
                    'ciphertext' => $ciphertext,
                    'url'        => 'https://www.upwork.com/jobs/' . $ciphertext,
                    'title'      => null,
                    'node'       => $anchor,
                ];
                $order[] = $ciphertext;
            }

            // Avoid generic button labels like "View Job" or "Apply" as titles
            if ($groups[$ciphertext]['title'] === null && mb_strlen($text) >= 6 && ! preg_match('/^(view|apply|click|open|read|more|details)$/i', $text)) {
                $groups[$ciphertext]['title'] = $text;
            }
        }

        // Fallback: search raw HTML regex if no DOM links matched ~01...
        if (empty($order)) {
            if (preg_match_all('/(~01[a-zA-Z0-9_-]{12,}|~[a-zA-Z0-9_-]{15,})/', $html, $matches)) {
                foreach (array_unique($matches[1]) as $ciphertext) {
                    $groups[$ciphertext] = [
                        'ciphertext' => $ciphertext,
                        'url'        => 'https://www.upwork.com/jobs/' . $ciphertext,
                        'title'      => null,
                        'node'       => null,
                    ];
                    $order[] = $ciphertext;
                }
            }
        }

        $jobs = [];

        foreach ($order as $ciphertext) {
            $group = $groups[$ciphertext];
            $blockText = '';

            if ($group['node']) {
                $block = $this->findContextBlock($group['node']);
                $blockText = $block ? trim(preg_replace('/\s+/', ' ', $block->textContent ?? '')) : '';
            }

            [$budget, $jobType] = $this->extractBudget($blockText !== '' ? $blockText : $html);
            $description = $blockText !== '' ? mb_substr($blockText, 0, 1200) : mb_substr(strip_tags($html), 0, 1200);

            // Clean title fallback using subject or first heading
            $title = $group['title'];
            if (! $title && $subject) {
                $cleanSubject = preg_replace('/^(Fwd:|FW:|Re:|New job alert:?|Upwork Job:?)\s*/i', '', $subject);
                $title = trim($cleanSubject);
            }

            $jobs[] = [
                'title'            => $title ?: 'Upwork Job Posting',
                'url'              => $group['url'],
                'ciphertext'       => $ciphertext,
                'description'      => $description,
                'budget'           => $budget,
                'job_type'         => $jobType,
                'payment_verified' => true,
            ];
        }

        return $jobs;
    }

    private function findContextBlock(DOMNode $node): ?DOMNode
    {
        $current = $node->parentNode;
        $depth = 0;

        while ($current !== null && $depth < 5) {
            $tag = strtolower($current->nodeName ?? '');
            if (in_array($tag, ['td', 'div', 'table', 'section', 'article'], true) && mb_strlen(trim($current->textContent ?? '')) > 80) {
                return $current;
            }
            $current = $current->parentNode;
            $depth++;
        }

        return null;
    }

    private function extractBudget(string $text): array
    {
        if (preg_match('/Hourly[:\s]*\$?([\d,]+(?:\.\d+)?)\s*-\s*\$?([\d,]+(?:\.\d+)?)/i', $text, $m)) {
            return [sprintf('$%s-%s/hr', $m[1], $m[2]), 'HOURLY'];
        }

        if (preg_match('/(?:Fixed-price|Budget)[:\s]*\$?([\d,]+(?:\.\d+)?)/i', $text, $m)) {
            return ['$' . $m[1] . ' fixed', 'FIXED'];
        }

        return [null, null];
    }
}
