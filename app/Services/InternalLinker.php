<?php
/**
 * 100% Automated SEO Internal Linking & Topic Cluster Engine
 * - Dynamic Article-to-Article Contextual Keyword Autolinking
 * - "Also Read" Editorial In-Content Callout Inserter
 * - Entity Authority & Board Cross-Linking
 */

declare(strict_types=1);

namespace App\Services;

class InternalLinker {
    private static array $keywordMap = [
        'Staff Selection Commission' => ['/search?q=SSC', 'Staff Selection Commission Official Updates'],
        'Union Public Service Commission' => ['/search?q=UPSC', 'UPSC Notifications'],
        'Railway Recruitment Board' => ['/search?q=Railway', 'RRB Railway Recruitment'],
        'SSC CGL'                 => ['/search?q=SSC+CGL', 'SSC CGL Recruitment & Results'],
        'SSC CHSL'                => ['/search?q=SSC+CHSL', 'SSC CHSL Updates'],
        'RRB NTPC'                => ['/search?q=RRB+NTPC', 'RRB NTPC Exams & Results'],
        'RRB Group D'             => ['/search?q=RRB+Group+D', 'RRB Group D Recruitment'],
        'UPSC NDA'                => ['/search?q=UPSC+NDA', 'UPSC National Defence Academy'],
        'WBCS'                    => ['/state/west-bengal', 'West Bengal Civil Service'],
        'WBPSC'                   => ['/state/west-bengal', 'West Bengal Public Service Commission'],
        'West Bengal Police'      => ['/state/west-bengal', 'West Bengal Police Recruitment'],
        'IBPS PO'                 => ['/search?q=IBPS', 'IBPS Probationary Officer Exams'],
        'State Bank of India'     => ['/search?q=SBI', 'SBI Banking Careers'],
        'UPPSC'                   => ['/state/uttar-pradesh', 'Uttar Pradesh Public Service Commission'],
        'BPSC'                    => ['/state/bihar', 'Bihar Public Service Commission'],
        'RPSC'                    => ['/state/rajasthan', 'Rajasthan Public Service Commission'],
        'NEET UG'                 => ['/search?q=NEET', 'National Eligibility cum Entrance Test'],
        'JEE Main'                => ['/search?q=JEE', 'Joint Entrance Examination Main'],
        'National Scholarship Portal' => ['/category/scholarship', 'NSP National Scholarship Portal'],
        'Oasis Scholarship'       => ['/category/scholarship', 'WB Oasis Scholarship Portal'],
        'Swami Vivekananda Scholarship' => ['/category/scholarship', 'SVMCM Scholarship Portal'],
    ];

    /**
     * Parse HTML and inject contextual internal links for matching keywords & other published articles
     */
    public static function linkify(string $html, int $currentArticleId = 0): string {
        if (empty($html)) {
            return $html;
        }

        $linkCount = 0;
        $maxLinks = 4; // Strict cap to prevent over-optimization / Google penalty

        // 1. Dynamic Database Articles Matching
        try {
            $db = \Database::getConnection();
            $stmt = $db->prepare("
                SELECT id, title, slug 
                FROM articles 
                WHERE status = 'published' AND id != :current_id 
                ORDER BY published_at DESC 
                LIMIT 15
            ");
            $stmt->execute([':current_id' => $currentArticleId]);
            $dbArticles = $stmt->fetchAll();

            foreach ($dbArticles as $art) {
                if ($linkCount >= $maxLinks) break;

                // Extract core clean entity name from article title (e.g., "SSC CHSL 2026", "SSC CGL 2026", "UPSC NDA")
                $title = $art['title'];
                $entities = self::extractKeyPhrases($title);

                foreach ($entities as $phrase) {
                    if (mb_strlen($phrase) < 5) continue;
                    $quoted = preg_quote($phrase, '/');
                    $pattern = '/(?!(?:[^<]+>|[^>]+<\/a>))\b(' . $quoted . ')\b/iu';

                    if (preg_match($pattern, $html)) {
                        $url = '/news/' . $art['slug'];
                        $href = htmlspecialchars($url);
                        $titleAttr = htmlspecialchars($art['title']);

                        $replaced = false;
                        $html = preg_replace_callback($pattern, function ($matches) use ($href, $titleAttr, &$replaced) {
                            if ($replaced) return $matches[0];
                            $replaced = true;
                            $text = htmlspecialchars($matches[0]);
                            return "<a href=\"{$href}\" class=\"internal-topic-link\" title=\"{$titleAttr}\">{$text}</a>";
                        }, $html, 1);

                        if ($replaced) {
                            $linkCount++;
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently fallback if DB query unavailable
        }

        // 2. Static Authority & Hub Keywords Matching
        $keywords = self::$keywordMap;
        uksort($keywords, fn($a, $b) => strlen($b) <=> strlen($a));

        foreach ($keywords as $term => [$url, $titleTip]) {
            if ($linkCount >= $maxLinks) break;

            $quoted = preg_quote($term, '/');
            $pattern = '/(?!(?:[^<]+>|[^>]+<\/a>))\b(' . $quoted . ')\b/iu';

            if (preg_match($pattern, $html)) {
                $href = htmlspecialchars($url);
                $tip = htmlspecialchars($titleTip);

                $replaced = false;
                $html = preg_replace_callback($pattern, function ($matches) use ($href, $tip, &$replaced) {
                    if ($replaced) return $matches[0];
                    $replaced = true;
                    $text = htmlspecialchars($matches[0]);
                    return "<a href=\"{$href}\" class=\"internal-topic-link\" title=\"{$tip}\">{$text}</a>";
                }, $html, 1);

                if ($replaced) {
                    $linkCount++;
                }
            }
        }

        return $html;
    }

    /**
     * Auto-inject high-converting "Also Read" callout cards into article content
     */
    public static function injectAlsoReadCards(string $html, int $currentArticleId, string $authorityName = '', int $categoryId = 0): string {
        if (empty($html)) return $html;

        try {
            $db = \Database::getConnection();
            $stmt = $db->prepare("
                SELECT id, title, slug, published_at 
                FROM articles 
                WHERE status = 'published' AND id != :current_id
                ORDER BY 
                  (CASE WHEN official_source_name = :authority AND :authority != '' THEN 1 WHEN category_id = :cat_id THEN 2 ELSE 3 END),
                  published_at DESC 
                LIMIT 1
            ");
            $stmt->execute([
                ':current_id' => $currentArticleId,
                ':authority'  => $authorityName,
                ':cat_id'     => $categoryId,
            ]);
            $related = $stmt->fetch();

            if ($related) {
                $calloutHtml = "
                <div class=\"also-read-callout-box\">
                    <div class=\"also-read-badge\">📌 ALSO READ</div>
                    <div class=\"also-read-content\">
                        <a href=\"/news/" . htmlspecialchars($related['slug']) . "\" class=\"also-read-link\">
                            " . htmlspecialchars($related['title']) . " <span class=\"also-read-arrow\">↗</span>
                        </a>
                    </div>
                </div>";

                // Inject after the 2nd paragraph if possible, or after the first closing tag
                if (preg_match('/(<\/p>.*?<\/p>)/is', $html, $match, PREG_OFFSET_CAPTURE)) {
                    $pos = $match[0][1] + strlen($match[0][0]);
                    $html = substr_replace($html, "\n" . $calloutHtml . "\n", $pos, 0);
                } elseif (preg_match('/(<\/div>)/is', $html, $match, PREG_OFFSET_CAPTURE)) {
                    $pos = $match[0][1] + strlen($match[0][0]);
                    $html = substr_replace($html, "\n" . $calloutHtml . "\n", $pos, 0);
                } else {
                    $html = $calloutHtml . "\n" . $html;
                }
            }
        } catch (\Throwable $e) {
            // Fallback safely
        }

        return $html;
    }

    /**
     * Extract recognizable sub-entities from a headline (e.g. "SSC CGL 2026", "RRB NTPC", "UPSC NDA")
     */
    private static function extractKeyPhrases(string $title): array {
        $phrases = [];
        if (preg_match('/(SSC\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/i', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(UPSC\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/i', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(RRB\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/i', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(WBPSC\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/i', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(West Bengal Police(?:\s+[A-Za-z0-9]+)?)/i', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(IBPS\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/i', $title, $m)) {
            $phrases[] = $m[1];
        }
        return array_unique($phrases);
    }
}
