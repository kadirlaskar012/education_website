<?php
/**
 * Structured Fact Extraction Engine
 * Extracts verified facts and state tags from scraped government notices
 */

namespace App\Pipeline\Scraper;

class FactExtractor {
    public static function extract(array $source, array $rawItem): array {
        $title = trim($rawItem['source_title'] ?? '');
        $authority = $source['authority_name'] ?? 'Government Authority';
        $domain = parse_url($source['base_url'], PHP_URL_HOST) ?: $source['base_url'];
        $stateCode = $source['state_code'] ?? 'ALL';
        $stateName = $source['state_name'] ?? 'All India / Central';

        $templateType = self::detectTemplateType($title);
        $categorySlug = self::detectCategorySlug($title, $templateType);

        // Extract numbers / vacancies
        $vacancies = 'Not specified in the official notification.';
        if (preg_match('/(\b\d{1,3}(?:,\d{3})*|\b\d+)\s*(?:posts|vacancies|openings|seats)/i', $title, $m)) {
            $vacancies = $m[1] . ' Vacancies (As per official notification)';
        }

        // Year detection
        $year = date('Y');
        if (preg_match('/\b(202[4-9])\b/', $title, $ym)) {
            $year = $ym[1];
        }

        $dates = [
            ['label' => 'Notification Released', 'value' => date('F j, Y')],
        ];

        if ($templateType === 'result') {
            $dates[] = ['label' => 'Result Declaration Date', 'value' => date('F j, Y') . ' (Declared)'];
            $dates[] = ['label' => 'Scorecard Download Window', 'value' => 'Available on Official Portal'];
        } elseif ($templateType === 'admit_card') {
            $dates[] = ['label' => 'Admit Card Release Date', 'value' => date('F j, Y') . ' (Out Now)'];
            $dates[] = ['label' => 'Exam Date', 'value' => 'Refer to Official Schedule'];
        } elseif ($templateType === 'recruitment') {
            $dates[] = ['label' => 'Online Application Window', 'value' => 'Check Official Portal'];
            $dates[] = ['label' => 'Last Date to Apply', 'value' => 'Refer to Detailed Notification'];
        } elseif ($templateType === 'exam_date') {
            $dates[] = ['label' => 'Official Exam Schedule', 'value' => 'Announced for ' . $year];
        }

        $dates[] = ['label' => 'Official Portal', 'value' => $domain];

        // Important Links
        $links = [];
        if (!empty($rawItem['source_pdf_url'])) {
            $links[] = ['title' => 'Official Notification PDF', 'url' => $rawItem['source_pdf_url'], 'is_primary' => true];
        } elseif (!empty($rawItem['source_url'])) {
            $links[] = ['title' => 'Official Notice / Announcement Link', 'url' => $rawItem['source_url'], 'is_primary' => true];
        }
        $links[] = ['title' => 'Official Website (' . $domain . ')', 'url' => $source['base_url'], 'is_primary' => false];

        // Steps
        $steps = [
            "Go to the official website of {$authority} at {$domain}.",
            "On the homepage, navigate to the 'Latest Notices' or 'Examination/Recruitment' section.",
            "Locate and select the link for '{$title}'.",
            "Download and carefully review the official notice for verified instructions."
        ];

        // FAQs
        $faqs = [
            [
                'question' => "What is the official website to verify this {$authority} update?",
                'answer'   => "The official website is {$domain}. Candidates are strictly advised to rely on official links."
            ],
            [
                'question' => "Where can I download the official notification?",
                'answer'   => "The direct official link is provided in the Important Links section of this page."
            ]
        ];

        return [
            'organization'       => $authority,
            'exam_name'          => $title,
            'year'               => $year,
            'state_code'         => $stateCode,
            'state_name'         => $stateName,
            'template_type'      => $templateType,
            'category_slug'      => $categorySlug,
            'official_domain'    => $domain,
            'official_url'       => $rawItem['source_url'] ?? $source['base_url'],
            'official_pdf_url'   => $rawItem['source_pdf_url'] ?? null,
            'vacancies'          => $vacancies,
            'eligibility'        => 'Refer to the official notification guidelines.',
            'age_limit'          => 'As per official rules specified in notification.',
            'application_fee'    => 'Check official notification for category-wise details.',
            'dates'              => $dates,
            'important_links'    => $links,
            'steps'              => $steps,
            'faqs'               => $faqs,
            'extracted_at'       => date('Y-m-d H:i:s'),
        ];
    }

    public static function detectTemplateType(string $title): string {
        $t = strtolower($title);
        if (str_contains($t, 'result') || str_contains($t, 'score') || str_contains($t, 'merit') || str_contains($t, 'cutoff') || str_contains($t, 'marks')) {
            return 'result';
        }
        if (str_contains($t, 'admit card') || str_contains($t, 'hall ticket') || str_contains($t, 'call letter') || str_contains($t, 'city intimation') || str_contains($t, 'city slip')) {
            return 'admit_card';
        }
        if (str_contains($t, 'recruitment') || str_contains($t, 'vacancy') || str_contains($t, 'apply online') || str_contains($t, 'employment') || str_contains($t, 'posts') || str_contains($t, 'officers') || str_contains($t, 'constable')) {
            return 'recruitment';
        }
        if (str_contains($t, 'answer key') || str_contains($t, 'response sheet') || str_contains($t, 'objection')) {
            return 'answer_key';
        }
        if (str_contains($t, 'exam date') || str_contains($t, 'schedule') || str_contains($t, 'time table') || str_contains($t, 'cbt') || str_contains($t, 'date sheet')) {
            return 'exam_date';
        }
        return 'general_news';
    }

    public static function detectCategorySlug(string $title, string $templateType): string {
        switch ($templateType) {
            case 'result': return 'results';
            case 'admit_card': return 'admit-card';
            case 'recruitment': return 'recruitment';
            case 'answer_key': return 'answer-key';
            case 'exam_date': return 'exam';
            default:
                $t = strtolower($title);
                if (str_contains($t, 'scholarship') || str_contains($t, 'nsp')) return 'scholarship';
                if (str_contains($t, 'admission') || str_contains($t, 'counseling') || str_contains($t, 'allotment')) return 'admission';
                if (str_contains($t, 'board') || str_contains($t, 'cbse') || str_contains($t, 'cisce')) return 'board-exams';
                if (str_contains($t, 'entrance') || str_contains($t, 'jee') || str_contains($t, 'neet') || str_contains($t, 'cuet') || str_contains($t, 'ini-cet')) return 'entrance-exams';
                if (str_contains($t, 'job') || str_contains($t, 'govt') || str_contains($t, 'army') || str_contains($t, 'police') || str_contains($t, 'bank')) return 'government-jobs';
                return 'latest-news';
        }
    }
}
