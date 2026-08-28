<?php
/**
 * HTML & XPath Parser for Government Portals and Notice Boards
 */

namespace App\Pipeline\Scraper;

class HTMLParser {
    public static function parseNotices(string $html, string $baseUrl): array {
        $items = [];
        if (empty(trim($html))) {
            return $items;
        }

        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        $xpath = new \DOMXPath($dom);

        // Find candidate links that look like notices or PDF attachments
        $links = $xpath->query("//a[contains(@href, 'pdf') or contains(@href, 'notice') or contains(@href, 'download') or contains(@href, 'archive') or contains(@href, 'news')]");

        foreach ($links as $link) {
            $title = trim($link->textContent);
            $href = trim($link->getAttribute('href'));

            // Clean title whitespace
            $title = preg_replace('/\s+/', ' ', $title);

            if (strlen($title) >= 15 && !empty($href)) {
                $cleanUrl = self::resolveUrl($href, $baseUrl);
                $isPdf = str_contains(strtolower($cleanUrl), '.pdf');

                $items[] = [
                    'source_title'   => $title,
                    'source_url'     => $cleanUrl,
                    'source_pdf_url' => $isPdf ? $cleanUrl : null,
                    'source_date'    => date('Y-m-d'),
                    'source_content' => $title,
                ];
            }
        }

        return $items;
    }

    public static function resolveUrl(string $href, string $baseUrl): string {
        $href = trim($href);
        if (empty($href)) {
            return $baseUrl;
        }
        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            return $href;
        }
        if (str_starts_with($href, '//')) {
            return 'https:' . $href;
        }
        return rtrim($baseUrl, '/') . '/' . ltrim($href, '/');
    }
}
