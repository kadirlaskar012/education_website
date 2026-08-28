<?php
/**
 * Master Human-Tone Article Generator (Gemini AI + High-Performance Deterministic Fallback)
 */

namespace App\Pipeline\AI;

use App\Models\Category;
use App\Models\SiteSetting;

class ArticleGenerator {
    private GeminiClient $gemini;

    public function __construct() {
        $this->gemini = new GeminiClient();
    }

    public function generate(array $source, array $facts, string $baseUrl = 'http://127.0.0.1:8000'): array {
        $categoryModel = new Category();
        $cat = $categoryModel->findBySlug($facts['category_slug'] ?? 'latest-news');
        $categoryId = $cat ? $cat['id'] : ($source['default_category_id'] ?: 1);
        $categoryName = $cat ? $cat['name'] : 'Latest News';

        $settingModel = new SiteSetting();
        $settings = $settingModel->getSettings();
        $aiEnabled = (bool)($settings['ai_rewrite'] ?? true);

        $aiResult = null;
        if ($aiEnabled && $this->gemini->isConfigured()) {
            $prompt = PromptBuilder::build($facts);
            $rawAi = $this->gemini->generate($prompt);

            if (!empty($rawAi)) {
                // Strip markdown backticks if any
                $cleanedJson = preg_replace('/^```(?:json)?\s*/i', '', trim($rawAi));
                $cleanedJson = preg_replace('/\s*```$/', '', $cleanedJson);
                $aiResult = json_decode($cleanedJson, true);
            }
        }

        // Determine title, summary, excerpt, and lead paragraph
        $title = !empty($aiResult['title']) ? trim($aiResult['title']) : $facts['exam_name'];
        $summary = !empty($aiResult['summary']) ? trim($aiResult['summary']) : "Official announcement released by {$facts['organization']} regarding {$facts['exam_name']}. Verified dates, schedule, and direct links are detailed below.";
        $excerpt = !empty($aiResult['excerpt']) ? trim($aiResult['excerpt']) : mb_substr("{$facts['exam_name']} — Official update by {$facts['organization']}. Check schedule and official direct links.", 0, 160);
        $leadParagraph = !empty($aiResult['lead_paragraph']) ? trim($aiResult['lead_paragraph']) : "The {$facts['organization']} has officially published a new notice concerning {$facts['exam_name']}. Candidates interested in this update can review the structured schedule, eligibility rules, and official links below.";

        // Build Full HTML Content Body
        $contentHtml = self::buildStructuredHtml($facts, $leadParagraph, $summary, $aiResult);

        // Generate SEO Metadata & Schema.org JSON-LD
        $articleDraft = [
            'title'        => $title,
            'summary'      => $summary,
            'excerpt'      => $excerpt,
            'published_at' => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];
        $seo = SEOGenerator::generate($articleDraft, $facts, $categoryName, $baseUrl);

        return [
            'category_id'          => $categoryId,
            'category_slug'        => $facts['category_slug'],
            'category_name'        => $categoryName,
            'template_type'        => $facts['template_type'],
            'title'                => $title,
            'slug'                 => $seo['slug'],
            'seo_title'            => $seo['seo_title'],
            'meta_description'     => $seo['meta_description'],
            'canonical_url'        => $seo['canonical_url'],
            'schema_json'          => $seo['schema_json'],
            'summary'              => $summary,
            'excerpt'              => $excerpt,
            'content_html'         => $contentHtml,
            'structured_data'      => json_encode($facts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'official_source_name' => $facts['organization'],
            'official_source_url'  => $facts['official_url'],
            'official_pdf_url'     => $facts['official_pdf_url'],
            'is_breaking'          => 1,
            'is_featured'          => 1,
        ];
    }

    public static function buildStructuredHtml(array $facts, string $leadParagraph, string $summary, ?array $aiResult = null): string {
        $type = $facts['template_type'] ?? 'general_news';
        $org = htmlspecialchars($facts['organization'] ?? 'Government Authority');

        // Dates Table Rows
        $datesRows = '';
        foreach ($facts['dates'] as $d) {
            $datesRows .= "<tr><td class='font-medium'>" . htmlspecialchars($d['label']) . "</td><td class='text-navy-900'>" . htmlspecialchars($d['value']) . "</td></tr>";
        }

        // Links Table Rows
        $linksRows = '';
        foreach ($facts['important_links'] as $link) {
            $btnText = !empty($link['is_primary']) ? 'Click Here ↗' : 'Visit Official Portal ↗';
            $linksRows .= "<tr><td>" . htmlspecialchars($link['title']) . "</td><td><a href='" . htmlspecialchars($link['url']) . "' target='_blank' rel='noopener noreferrer nofollow' class='link-btn'>{$btnText}</a></td></tr>";
        }

        // Step-by-Step Items
        $stepsItems = '';
        foreach ($facts['steps'] as $step) {
            $stepsItems .= "<li>" . htmlspecialchars($step) . "</li>";
        }

        // FAQ Items
        $faqList = !empty($aiResult['faqs']) ? $aiResult['faqs'] : $facts['faqs'];
        $faqItems = '';
        foreach ($faqList as $faq) {
            $faqItems .= "<details class='faq-item'><summary class='faq-question'><strong>" . htmlspecialchars($faq['question']) . "</strong></summary><div class='faq-answer'><p>" . htmlspecialchars($faq['answer']) . "</p></div></details>";
        }

        // Recruitment specific info box
        $recruitmentHtml = '';
        if ($type === 'recruitment') {
            $recruitmentHtml = "
            <div class='info-box mb-6' style='margin-bottom: 1.5rem;'>
                <h3 class='section-heading'>Recruitment & Eligibility Summary</h3>
                <div class='table-responsive'>
                    <table class='data-table'>
                        <tbody>
                            <tr><td><strong>Total Vacancies</strong></td><td>" . htmlspecialchars($facts['vacancies']) . "</td></tr>
                            <tr><td><strong>Educational Qualifications</strong></td><td>" . htmlspecialchars($facts['eligibility']) . "</td></tr>
                            <tr><td><strong>Age Limit Criteria</strong></td><td>" . htmlspecialchars($facts['age_limit']) . "</td></tr>
                            <tr><td><strong>Application Fee</strong></td><td>" . htmlspecialchars($facts['application_fee']) . "</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>";
        }

        // Additional AI body paragraphs if available
        $aiExtraHtml = '';
        if (!empty($aiResult['article_body_html'])) {
            $aiExtraHtml = "<div class='ai-editorial-body mb-6' style='margin-bottom: 1.5rem;'>" . $aiResult['article_body_html'] . "</div>";
        }

        return "
        <div class='article-content-body'>
            <div class='lead-summary'>
                <p>" . htmlspecialchars($leadParagraph) . "</p>
            </div>

            <!-- Important Dates Schedule -->
            <div class='dates-container mb-6' style='margin-bottom: 1.5rem;'>
                <h3 class='section-heading'>Key Highlights & Important Dates</h3>
                <div class='table-responsive'>
                    <table class='data-table'>
                        <thead>
                            <tr>
                                <th>Event / Activity</th>
                                <th>Official Date / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$datesRows}
                        </tbody>
                    </table>
                </div>
            </div>

            {$recruitmentHtml}
            {$aiExtraHtml}

            <!-- Step by Step Instructions -->
            <div class='steps-container mb-6' style='margin-bottom: 1.5rem;'>
                <h3 class='section-heading'>Step-by-Step Instructions</h3>
                <ol class='step-list'>
                    {$stepsItems}
                </ol>
            </div>

            <!-- Important Direct Links -->
            <div class='links-container mb-6' style='margin-bottom: 1.5rem;'>
                <h3 class='section-heading'>Verified Direct Links</h3>
                <div class='table-responsive'>
                    <table class='data-table links-table'>
                        <thead>
                            <tr>
                                <th>Resource / Document</th>
                                <th>Direct Official Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$linksRows}
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Frequently Asked Questions -->
            <div class='faq-container mb-6' style='margin-bottom: 1.5rem;'>
                <h3 class='section-heading'>Frequently Asked Questions (FAQs)</h3>
                <div class='faq-list'>
                    {$faqItems}
                </div>
            </div>
        </div>";
    }
}
