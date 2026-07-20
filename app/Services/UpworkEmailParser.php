<?php

namespace App\Services;

use DOMDocument;
use DOMNode;
use DOMXPath;

/**
 * Heuristic HTML parser for Upwork job-alert emails — by design, Upwork doesn't
 * publish a stable email format. Raw emails are stored in inbound_emails so
 * these heuristics can be tuned when Upwork's markup changes.
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

        // Group every anchor that links to a job by ciphertext (a job can be
        // linked more than once — e.g. a plain-text title link + an image link).
        $groups = [];
        $order = [];

        foreach ($anchors as $anchor) {
            $href = $anchor->getAttribute('href');
            if (! str_contains($href, 'upwork.com/jobs/')) {
                continue;
            }

            $decoded = strtok(urldecode($href), '?'); // strip trailing query junk

            if (! preg_match('#upwork\.com/jobs/([~_%a-zA-Z0-9]+)#', $decoded, $m)) {
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

            if ($groups[$ciphertext]['title'] === null && mb_strlen($text) >= 8) {
                $groups[$ciphertext]['title'] = $text;
            }
        }

        $jobs = [];

        foreach ($order as $ciphertext) {
            $group = $groups[$ciphertext];

            $block = $this->findContextBlock($group['node']);
            $blockText = $block ? trim(preg_replace('/\s+/', ' ', $block->textContent ?? '')) : '';

            [$budget, $jobType] = $this->extractBudget($blockText);

            $description = $blockText !== '' ? mb_substr($blockText, 0, 1200) : '';

            if ($group['title'] === null && $description === '') {
                continue;
            }

            $jobs[] = [
                'title'            => $group['title'] ?? 'Untitled job',
                'url'              => $group['url'],
                'ciphertext'       => $ciphertext,
                'description'      => $description,
                'budget'           => $budget,
                'job_type'         => $jobType,
                'payment_verified' => true, // unknown in emails — don't false-flag
            ];
        }

        return $jobs;
    }

    private function findContextBlock(DOMNode $node): ?DOMNode
    {
        $current = $node->parentNode;
        $depth = 0;

        while ($current !== null && $depth < 4) {
            $tag = strtolower($current->nodeName ?? '');
            if (in_array($tag, ['td', 'div', 'table'], true) && mb_strlen(trim($current->textContent ?? '')) > 120) {
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
