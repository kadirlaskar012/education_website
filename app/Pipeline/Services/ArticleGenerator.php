<?php
/**
 * Structured Article Generator
 * Converts raw notices into rich, grounded, structured education articles
 */

namespace App\Pipeline\Services;

class ArticleGenerator {
    public static function generate(array $source, array $item): array {
        $rawTitle = $item['raw_title'];
        $rawUrl = $item['raw_url'];
        $authority = $source['authority_name'];
        $domain = parse_url($source['base_url'], PHP_URL_HOST) ?: $source['base_url'];

        // Determine template type and category
        $templateType = self::detectTemplateType($rawTitle);
        $categorySlug = self::detectCategorySlug($rawTitle, $templateType);
        
        $categoryModel = new \App\Models\Category();
        $cat = $categoryModel->findBySlug($categorySlug);
        $categoryId = $cat ? $cat['id'] : ($source['default_category_id'] ?: 1);

        // Generate Slug
        $slug = self::generateSlug($rawTitle);

        // Grounded Structured Data
        $structuredData = [
            'authority' => $authority,
            'exam_name' => $rawTitle,
            'dates' => [
                ['label' => 'Notification Released', 'value' => date('F j, Y')],
                ['label' => 'Application / Exam Status', 'value' => 'Check Official Portal'],
                ['label' => 'Official Authority Portal', 'value' => $domain],
            ],
            'vacancies' => 'Refer to the official PDF notification.',
            'eligibility' => 'As specified in the official notification guidelines.',
            'age_limit' => 'As per standard rules mentioned in notice.',
            'fees' => 'Check official notification for category-wise fee details.',
            'important_links' => [
                ['title' => 'Official Notification / Notice Link', 'url' => $rawUrl, 'is_primary' => true],
                ['title' => 'Official Website (' . $domain . ')', 'url' => $source['base_url'], 'is_primary' => false],
            ],
            'steps' => [
                "Visit the official website of {$authority} ({$domain}).",
                "Navigate to the 'Latest Notices / Announcements' section on the homepage.",
                "Locate and click on the official notice for '{$rawTitle}'.",
                "Review the official document and save/download a copy for future reference."
            ],
            'faq' => [
                [
                    'question' => "Where can I check official updates for {$authority}?",
                    'answer' => "All official notices are published directly on {$domain}. Always verify details directly from the official portal."
                ],
                [
                    'question' => "Where is the direct notification link?",
                    'answer' => "The verified official link is provided in the Important Links table on this page."
                ]
            ]
        ];

        // Summary
        $summary = "Official notice issued by {$authority} regarding {$rawTitle}. Candidates can check all official dates, key requirements, step-by-step instructions, and direct links below.";
        $excerpt = mb_substr($rawTitle . ' — Official notification published by ' . $authority . '. Check complete schedule and download links.', 0, 200);

        // Build Responsive HTML
        $contentHtml = self::buildHtml($templateType, $rawTitle, $authority, $structuredData, $summary);

        return [
            'category_id'          => $categoryId,
            'template_type'        => $templateType,
            'title'                => $rawTitle,
            'slug'                 => $slug,
            'summary'              => $summary,
            'excerpt'              => $excerpt,
            'content_html'         => $contentHtml,
            'structured_data'      => json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'official_source_name' => $authority,
            'official_source_url'  => $rawUrl,
            'status'               => 'published',
            'is_breaking'          => 1,
            'is_featured'          => 1,
        ];
    }

    private static function detectTemplateType(string $title): string {
        $t = strtolower($title);
        if (str_contains($t, 'result') || str_contains($t, 'score') || str_contains($t, 'merit') || str_contains($t, 'cutoff')) {
            return 'result';
        }
        if (str_contains($t, 'admit card') || str_contains($t, 'hall ticket') || str_contains($t, 'call letter') || str_contains($t, 'city slip')) {
            return 'admit_card';
        }
        if (str_contains($t, 'recruitment') || str_contains($t, 'vacancy') || str_contains($t, 'apply online') || str_contains($t, 'employment')) {
            return 'recruitment';
        }
        if (str_contains($t, 'answer key') || str_contains($t, 'response sheet')) {
            return 'answer_key';
        }
        if (str_contains($t, 'exam date') || str_contains($t, 'schedule') || str_contains($t, 'time table') || str_contains($t, 'cbt')) {
            return 'exam_date';
        }
        return 'general_news';
    }

    private static function detectCategorySlug(string $title, string $templateType): string {
        switch ($templateType) {
            case 'result': return 'results';
            case 'admit_card': return 'admit-card';
            case 'recruitment': return 'recruitment';
            case 'answer_key': return 'answer-key';
            case 'exam_date': return 'exam';
            default:
                $t = strtolower($title);
                if (str_contains($t, 'scholarship')) return 'scholarship';
                if (str_contains($t, 'admission') || str_contains($t, 'allotment')) return 'admission';
                if (str_contains($t, 'board') || str_contains($t, 'cbse')) return 'board-exams';
                return 'latest-news';
        }
    }

    public static function generateSlug(string $title): string {
        $slug = preg_replace('~[^\pL\d]+~u', '-', $title);
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $slug = trim($slug, '-');
        $slug = preg_replace('~-+~', '-', $slug);
        $slug = strtolower($slug);
        return $slug ?: 'notification-' . time();
    }

    public static function buildHtml(string $templateType, string $title, string $authority, array $data, string $summary): string {
        $datesRows = '';
        foreach ($data['dates'] as $d) {
            $datesRows .= "<tr><td class='font-medium'>" . htmlspecialchars($d['label']) . "</td><td class='text-navy-900'>" . htmlspecialchars($d['value']) . "</td></tr>";
        }

        $linksRows = '';
        foreach ($data['important_links'] as $link) {
            $btnText = !empty($link['is_primary']) ? 'Click Here ↗' : 'Visit Website ↗';
            $linksRows .= "<tr><td>" . htmlspecialchars($link['title']) . "</td><td><a href='" . htmlspecialchars($link['url']) . "' target='_blank' rel='noopener noreferrer nofollow' class='link-btn'>{$btnText}</a></td></tr>";
        }

        $stepsItems = '';
        foreach ($data['steps'] as $s) {
            $stepsItems .= "<li>" . htmlspecialchars($s) . "</li>";
        }

        $faqItems = '';
        foreach ($data['faq'] as $faq) {
            $faqItems .= "<details class='faq-item'><summary class='faq-question'><strong>" . htmlspecialchars($faq['question']) . "</strong></summary><div class='faq-answer'><p>" . htmlspecialchars($faq['answer']) . "</p></div></details>";
        }

        $recruitmentHtml = '';
        if ($templateType === 'recruitment') {
            $recruitmentHtml = "
            <div class='info-box mb-6'>
                <h3 class='section-heading'>Eligibility & Vacancy Details</h3>
                <div class='table-responsive'>
                    <table class='data-table'>
                        <tbody>
                            <tr><td><strong>Total Vacancies</strong></td><td>" . htmlspecialchars($data['vacancies']) . "</td></tr>
                            <tr><td><strong>Eligibility Criteria</strong></td><td>" . htmlspecialchars($data['eligibility']) . "</td></tr>
                            <tr><td><strong>Age Limit</strong></td><td>" . htmlspecialchars($data['age_limit']) . "</td></tr>
                            <tr><td><strong>Application Fee</strong></td><td>" . htmlspecialchars($data['fees']) . "</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>";
        }

        return "
        <div class='article-content-body'>
            <div class='lead-summary'>
                <p>" . htmlspecialchars($summary) . "</p>
            </div>

            <!-- Important Dates Table -->
            <div class='dates-container mb-6'>
                <h3 class='section-heading'>Important Dates & Schedule</h3>
                <div class='table-responsive'>
                    <table class='data-table'>
                        <thead>
                            <tr>
                                <th>Event / Activity</th>
                                <th>Date / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$datesRows}
                        </tbody>
                    </table>
                </div>
            </div>

            {$recruitmentHtml}

            <!-- Step by Step Guide -->
            <div class='steps-container mb-6'>
                <h3 class='section-heading'>How to Check / Apply / Download</h3>
                <ol class='step-list'>
                    {$stepsItems}
                </ol>
            </div>

            <!-- Important Links Table -->
            <div class='links-container mb-6'>
                <h3 class='section-heading'>Important Direct Links</h3>
                <div class='table-responsive'>
                    <table class='data-table links-table'>
                        <thead>
                            <tr>
                                <th>Resource / Action</th>
                                <th>Direct Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$linksRows}
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Frequently Asked Questions -->
            <div class='faq-container mb-6'>
                <h3 class='section-heading'>Frequently Asked Questions (FAQs)</h3>
                <div class='faq-list'>
                    {$faqItems}
                </div>
            </div>
        </div>";
    }
}
