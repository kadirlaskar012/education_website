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
        // English Entities
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

        // Bengali Indic Entities & Terms
        'পশ্চিমবঙ্গ পুলিশ রিক্রুটমেন্ট বোর্ড' => ['/state/west-bengal', 'পশ্চিমবঙ্গ পুলিশ রিক্রুটমেন্ট বোর্ড (WBPRB)'],
        'পশ্চিমবঙ্গ পুলিশ'        => ['/state/west-bengal', 'পশ্চিমবঙ্গ পুলিশ নিয়োগ ও আপডেট'],
        'রেলওয়ে রিক্রুটমেন্ট বোর্ড' => ['/search?q=Railway', 'রেলওয়ে রিক্রুটমেন্ট বোর্ড (RRB)'],
        'রেলওয়ে রিক্রুটমেন্ট'    => ['/search?q=Railway', 'রেলওয়ে নিয়োগ বিজ্ঞপ্তি'],
        'স্টাফ সিলেকশন কমিশন'      => ['/search?q=SSC', 'স্টাফ সিলেকশন কমিশন (SSC)'],
        'পাবলিক সার্ভিস কমিশন'     => ['/state/west-bengal', 'পাবলিক সার্ভিস কমিশন নোটিফিকেশন'],
        'ন্যাশনাল স্কলারশিপ পোর্টাল' => ['/category/scholarship', 'ন্যাশনাল স্কলারশিপ পোর্টাল (NSP)'],
        'স্কলারশিপ পোর্টাল'         => ['/category/scholarship', 'সরকারি স্কলারশিপ পোর্টাল'],
        'অ্যাডমিট কার্ড'          => ['/admit-card', 'পরীক্ষার অ্যাডমিট কার্ড ডাউনলোড'],
        'মেধা তালিকা'             => ['/results', 'অফিসিয়াল ফলাফল ও চূড়ান্ত মেধা তালিকা'],
        'উত্তর সংকেত'             => ['/answer-key', 'অফিসিয়াল উত্তর সংকেত (Answer Key)'],
        'চাকরি ও নিয়োগ'           => ['/recruitment', 'সরকারি চাকরি ও নিয়োগ বিজ্ঞপ্তি'],
        'পরীক্ষার দিনক্ষণ'         => ['/exam', 'পরীক্ষার সময়সূচি ও দিনক্ষণ'],

        // Hindi Indic Entities & Terms
        'कर्मचारी चयन आयोग'       => ['/search?q=SSC', 'कर्मचारी चयन आयोग (SSC)'],
        'रेलवे भर्ती बोर्ड'         => ['/search?q=Railway', 'रेलवे भर्ती बोर्ड (RRB)'],
        'संघ लोक सेवा आयोग'       => ['/search?q=UPSC', 'संघ लोक सेवा आयोग (UPSC)'],
        'पश्चिम बंगाल पुलिस'       => ['/state/west-bengal', 'पश्चिम बंगाल पुलिस भर्ती'],
        'राष्ट्रीय छात्रवृत्ति पोर्टल' => ['/category/scholarship', 'राष्ट्रीय छात्रवृत्ति पोर्टल (NSP)'],
        'प्रवेश पत्र'             => ['/admit-card', 'एडमिट कार्ड डाउनलोड'],
        'परीक्षा परिणाम'          => ['/results', 'परीक्षा परिणाम एवं मेरिट सूची'],
        'उत्तर कुंजी'             => ['/answer-key', 'उत्तर कुंजी (Answer Key)'],
        'भर्ती अधिसूचना'          => ['/recruitment', 'सरकारी नौकरी एवं भर्ती'],
    ];

    /**
     * Parse HTML and inject contextual internal links for matching keywords & other published articles
     */
    public static function linkify(string $html, int $currentArticleId = 0): string {
        if (empty($html)) {
            return $html;
        }

        $linkCount = 0;
        $maxLinks = 5; // Balanced for rich SEO interlinking without spam penalty

        // 1. Dynamic Database Articles Matching
        try {
            $db = \Database::getConnection();
            $stmt = $db->prepare("
                SELECT id, title, slug, structured_data 
                FROM articles 
                WHERE status = 'published' AND id != :current_id 
                ORDER BY published_at DESC 
                LIMIT 20
            ");
            $stmt->execute([':current_id' => $currentArticleId]);
            $rawArticles = $stmt->fetchAll();
            $dbArticles = \App\Models\Article::localizeList($rawArticles);

            foreach ($dbArticles as $art) {
                if ($linkCount >= $maxLinks) break;

                $title = $art['title'];
                $entities = self::extractKeyPhrases($title);

                foreach ($entities as $phrase) {
                    if (mb_strlen($phrase) < 4) continue;
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
        uksort($keywords, fn($a, $b) => mb_strlen($b) <=> mb_strlen($a));

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
                SELECT id, title, slug, published_at, structured_data 
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
            $raw = $stmt->fetch();

            if ($raw) {
                $localized = \App\Models\Article::localizeList([$raw]);
                $related = $localized[0] ?? $raw;

                $locale = \App\Core\I18n::getLocale();
                $badgeText = ($locale === 'bn') ? '📌 আরও পড়ুন' : (($locale === 'hi') ? '📌 यह भी पढ़ें' : '📌 ALSO READ');

                $calloutHtml = "
                <div class=\"also-read-callout-box\">
                    <div class=\"also-read-badge\">{$badgeText}</div>
                    <div class=\"also-read-content\">
                        <a href=\"/news/" . htmlspecialchars($related['slug']) . "\" class=\"also-read-link\">
                            " . htmlspecialchars($related['title']) . " <span class=\"also-read-arrow\">↗</span>
                        </a>
                    </div>
                </div>";

                // Inject after the 2nd paragraph if possible, or after first heading/div
                if (preg_match('/(<\/p>[\s\S]*?<\/p>)/i', $html, $match, PREG_OFFSET_CAPTURE)) {
                    $pos = $match[0][1] + strlen($match[0][0]);
                    $html = substr_replace($html, "\n" . $calloutHtml . "\n", $pos, 0);
                } elseif (preg_match('/(<\/div>)/i', $html, $match, PREG_OFFSET_CAPTURE)) {
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
        if (preg_match('/(SSC\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(UPSC\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(RRB\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(WBPSC\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(WBPRB(?:\s+কনস্টেবল|\s+Constable)?)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(West Bengal Police(?:\s+[A-Za-z0-9]+)?)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(IBPS\s+[A-Za-z0-9]+(?:\s+202[0-9])?)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(National Scholarship Portal|NSP|স্কলারশিপ পোর্টাল)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        if (preg_match('/(RPSC|MPPSC|UPPSC|BPSC|MPSC)/iu', $title, $m)) {
            $phrases[] = $m[1];
        }
        return array_unique($phrases);
    }
}
