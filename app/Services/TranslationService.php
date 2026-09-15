<?php
/**
 * AI Translation & Fact-Checking Engine
 * Translates educational notices into fluent Bengali & Hindi with rigorous fact verification
 */

namespace App\Services;

use App\Pipeline\AI\GeminiClient;

class TranslationService {
    private GeminiClient $gemini;

    public function __construct(?string $apiKey = null) {
        $this->gemini = new GeminiClient($apiKey, 'gemini-3.6-flash', 0.2);
    }

    /**
     * Translates and fact-checks an educational notice or article
     */
    public function translateNotice(string $text, string $targetLang = 'bn'): array {
        $langName = $targetLang === 'hi' ? 'Hindi (हिंदी)' : 'Bengali (বাংলা)';
        
        $prompt = <<<PROMPT
You are a senior education news editor and fact-checker for an official Indian educational news portal.
Rewrite and translate the following official government examination/recruitment notice into fluent, engaging, and newspaper-grade {$langName}.

CRITICAL INTEGRITY RULES:
1. Retain ALL exact dates, years, deadlines, and timeframes (e.g., 20 March 2026 -> ২০ মার্চ ২০২৬ / 20 मार्च 2026).
2. Retain ALL exact vacancy numbers, salary/pay scales, eligibility criteria, and application fees.
3. Retain ALL official website links, portal URLs, and PDF reference numbers without modification.
4. Format with clear Markdown headings (##, ###), bullet points, and an engaging journalistic headline.
5. Provide the output in clean JSON format matching this schema:
{
    "title": "Clear Engaging {$langName} Headline",
    "summary": "Brief 2-3 sentence summary in {$langName}",
    "content": "Full detailed article formatted in Markdown in {$langName}"
}

Return ONLY valid JSON.

Original Notice:
{$text}
PROMPT;

        $rawResponse = $this->gemini->generate($prompt);
        
        $parsed = null;
        if (!empty($rawResponse)) {
            // Strip potential markdown code fences ```json ... ```
            $cleaned = preg_replace('/^```(?:json)?\s*/i', '', trim($rawResponse));
            $cleaned = preg_replace('/\s*```$/i', '', $cleaned);
            $parsed = json_decode($cleaned, true);
        }

        if (!$parsed || empty($parsed['title'])) {
            // Fallback if JSON parsing failed but text exists
            $title = "Official Notice Update ({$langName})";
            $summary = mb_substr($text, 0, 150) . '...';
            $content = !empty($rawResponse) ? $rawResponse : $text;
        } else {
            $title = $parsed['title'];
            $summary = $parsed['summary'] ?? '';
            $content = $parsed['content'] ?? '';
        }

        // Run automated fact integrity audit
        $audit = $this->auditIntegrity($text, $title . ' ' . $content, $targetLang);

        return [
            'success'      => !empty($rawResponse),
            'target_lang'  => $targetLang,
            'lang_name'    => $langName,
            'title'        => $title,
            'summary'      => $summary,
            'content'      => $content,
            'audit'        => $audit,
            'quality_score'=> $audit['score'],
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Cross-verifies facts, dates, numbers, and URLs between original and translated text
     */
    public function auditIntegrity(string $original, string $translated, string $lang): array {
        // 1. Extract dates (years like 2024-2027, day-month patterns)
        preg_match_all('/\b(202[4-9]|\d{1,2}(?:st|nd|rd|th)?\s+(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*|\d{1,2}[\/\.-]\d{1,2}[\/\.-]\d{2,4})\b/i', $original, $dateMatches);
        $originalDates = array_unique($dateMatches[0] ?? []);

        // 2. Extract numbers (vacancies, fees, age limit)
        preg_match_all('/\b\d{1,6}(?:,\d{3})*\b/', $original, $numMatches);
        $originalNumbers = array_filter(array_unique($numMatches[0] ?? []), fn($n) => strlen(str_replace(',', '', $n)) > 1);

        // 3. Extract URLs / Domains
        preg_match_all('/\b(?:https?:\/\/)?(?:www\.)?([a-zA-Z0-9-]+\.(?:gov\.in|nic\.in|org|in|edu|com))\b/i', $original, $urlMatches);
        $originalUrls = array_unique($urlMatches[1] ?? []);

        // Verification scores
        $monthMap = [
            'january' => ['জানুয়ারি', 'জানুয়ারি', 'जनवरी'],
            'february' => ['ফেব্রুয়ারি', 'ফেব্রুয়ারি', 'फरवरी'],
            'march' => ['মার্চ', 'मार्च'],
            'april' => ['এপ্রিল', 'अप्रैल'],
            'may' => ['মে', 'मई'],
            'june' => ['জুন', 'जून'],
            'july' => ['জুলাই', 'जुलाई'],
            'august' => ['আগস্ট', 'अगस्त'],
            'september' => ['সেপ্টেম্বর', 'सितंबर'],
            'october' => ['অক্টোবর', 'अक्टूबर'],
            'november' => ['নভেম্বর', 'नवंबर'],
            'december' => ['ডিসেম্বর', 'दिसंबर'],
        ];

        $dateCount = count($originalDates);
        $datesFound = 0;
        foreach ($originalDates as $d) {
            $found = false;
            // 1. Exact string or Indic digits match
            if (stripos($translated, $d) !== false || stripos($translated, $this->toIndicDigits($d, $lang)) !== false) {
                $found = true;
            } else {
                // 2. Localized month + Indic digits match
                foreach ($monthMap as $engM => $indicMs) {
                    if (stripos($d, $engM) !== false || stripos($d, substr($engM, 0, 3)) !== false) {
                        foreach ($indicMs as $im) {
                            if (stripos($translated, $im) !== false) {
                                $found = true;
                                break 2;
                            }
                        }
                    }
                }
            }

            if ($found) {
                $datesFound++;
            }
        }
        $dateScore = $dateCount > 0 ? round(($datesFound / $dateCount) * 100) : 100;

        $numCount = count($originalNumbers);
        $numsFound = 0;
        foreach ($originalNumbers as $n) {
            $cleanNum = str_replace(',', '', $n);
            if (stripos($translated, $cleanNum) !== false || stripos($translated, $this->toIndicDigits($cleanNum, $lang)) !== false) {
                $numsFound++;
            }
        }
        $numScore = $numCount > 0 ? round(($numsFound / $numCount) * 100) : 100;

        $urlCount = count($originalUrls);
        $urlsFound = 0;
        foreach ($originalUrls as $u) {
            if (stripos($translated, $u) !== false) {
                $urlsFound++;
            }
        }
        $urlScore = $urlCount > 0 ? round(($urlsFound / $urlCount) * 100) : 100;

        // Weighted Overall Score
        $overallScore = round(($dateScore * 0.35) + ($numScore * 0.35) + ($urlScore * 0.20) + 10);
        $overallScore = min(100, max(0, $overallScore));

        return [
            'score'          => $overallScore,
            'is_verified'    => $overallScore >= 75,
            'dates_checked'  => $dateCount,
            'dates_passed'   => $datesFound,
            'numbers_checked'=> $numCount,
            'numbers_passed' => $numsFound,
            'urls_checked'   => $urlCount,
            'urls_passed'    => $urlsFound,
            'extracted_dates'=> array_values($originalDates),
            'extracted_urls' => array_values($originalUrls),
        ];
    }

    /**
     * Converts western digits to Bengali / Devanagari numerals for matching
     */
    private function toIndicDigits(string $str, string $lang): string {
        $bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $hindiDigits   = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        $targetDigits  = $lang === 'hi' ? $hindiDigits : $bengaliDigits;

        return str_replace(range(0, 9), $targetDigits, $str);
    }
}
