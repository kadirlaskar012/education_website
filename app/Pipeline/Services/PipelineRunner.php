<?php
/**
 * Master Pipeline Runner
 * Connects Fetching -> Fact Extraction -> AI Human-Tone Rewriting -> SEO -> Internal Linking -> Quality Validation -> Auto-Publish
 */

namespace App\Pipeline\Services;

use App\Models\Source;
use App\Models\SourceItem;
use App\Models\SiteSetting;
use App\Pipeline\Scraper\SourceFetcher;
use App\Pipeline\Scraper\HTMLParser;
use App\Pipeline\Scraper\FactExtractor;
use App\Pipeline\Scraper\DuplicateChecker;
use App\Pipeline\AI\ArticleGenerator;
use App\Pipeline\Quality\ArticleValidator;
use App\Pipeline\Quality\InternalLinker;
use App\Pipeline\Adapters\Registry;

class PipelineRunner {
    private \PDO $db;
    private DuplicateChecker $dupChecker;
    private ArticleGenerator $generator;
    private InternalLinker $internalLinker;
    private UpdateDetector $updateDetector;

    public function __construct() {
        $this->db = \Database::getConnection();
        $this->dupChecker = new DuplicateChecker();
        $this->generator = new ArticleGenerator();
        $this->internalLinker = new InternalLinker();
        $this->updateDetector = new UpdateDetector();
    }

    public function runAll(): array {
        $startTime = microtime(true);
        $sourceModel = new Source();
        $sources = $sourceModel->getActiveSources();

        $stats = [
            'sources_processed'  => 0,
            'items_found'        => 0,
            'articles_created'   => 0,
            'articles_updated'   => 0,
            'articles_in_review' => 0,
            'duplicates_skipped' => 0,
            'errors'             => 0,
        ];

        foreach ($sources as $source) {
            $sStats = $this->processSource($source);
            $stats['sources_processed']++;
            $stats['items_found'] += $sStats['found'];
            $stats['articles_created'] += $sStats['created'];
            $stats['articles_updated'] += $sStats['updated'];
            $stats['articles_in_review'] += $sStats['review'];
            $stats['duplicates_skipped'] += $sStats['duplicates'];
            $stats['errors'] += $sStats['errors'];
        }

        $stats['execution_time'] = round(microtime(true) - $startTime, 2);
        return $stats;
    }

    public function processSource(array $source): array {
        $stats = ['found' => 0, 'created' => 0, 'updated' => 0, 'review' => 0, 'duplicates' => 0, 'errors' => 0];

        try {
            // 1. Fetch raw items from source adapter
            $adapter = Registry::getAdapter($source);
            $items = $adapter->fetchItems();
            $stats['found'] = count($items);

            $config = require __DIR__ . '/../../../config/config.php';
            $baseUrl = $config['app_url'] ?? 'http://127.0.0.1:8000';

            $settingModel = new SiteSetting();
            $settings = $settingModel->getSettings();
            $autoPublish = (bool)($settings['auto_publish'] ?? true);
            $minQualityScore = (int)($settings['min_quality_score'] ?? 80);

            foreach ($items as $item) {
                $title = trim($item['raw_title'] ?? $item['source_title'] ?? '');
                $url = trim($item['raw_url'] ?? $item['source_url'] ?? $source['base_url']);
                $contentHash = DuplicateChecker::computeContentHash($title, $url);
                $titleHash = DuplicateChecker::computeTitleHash($title);

                // 2. Duplicate Detection
                if ($this->dupChecker->isDuplicate($source['id'], $title, $url)) {
                    $stats['duplicates']++;
                    continue;
                }

                // 3. Extract Verified Facts (Zero Hallucination Grounding)
                $rawPayload = [
                    'source_title'   => $title,
                    'source_url'     => $url,
                    'source_pdf_url' => $item['source_pdf_url'] ?? (str_contains(strtolower($url), '.pdf') ? $url : null),
                ];
                $facts = FactExtractor::extract($source, $rawPayload);

                // 4. Check for existing title update
                $existing = $this->dupChecker->findExistingItemForUpdate($source['id'], $title);

                // 5. Generate AI Human-Tone Article & SEO Metadata
                $articleData = $this->generator->generate($source, $facts, $baseUrl);

                // 6. Contextual Internal Linking
                $linked = $this->internalLinker->injectLinks(
                    $articleData['content_html'],
                    $facts['organization'],
                    $articleData['category_id'],
                    $existing['article_id'] ?? null
                );
                $articleData['content_html'] = $linked['content_html'];
                $articleData['internal_links'] = $linked['internal_links'];

                // 7. Pre-Publish Quality Validation
                $valResult = ArticleValidator::validate($articleData, $minQualityScore);
                $articleData['quality_score'] = $valResult['score'];
                $articleData['validation_notes'] = $valResult['validation_notes'];

                // Determine final publication status
                if (!$autoPublish || !$valResult['passed']) {
                    $articleData['status'] = $valResult['status']; // 'review' or 'error'
                } else {
                    $articleData['status'] = 'published';
                }

                if ($existing && !empty($existing['article_id'])) {
                    // Update existing article preserving URL slug
                    $this->updateDetector->updateArticle((int)$existing['article_id'], $articleData, $facts);
                    $stats['updated']++;
                } else {
                    // Create SourceItem record
                    $sourceItemId = $this->createSourceItem($source['id'], [
                        'source_title'   => $title,
                        'source_url'     => $url,
                        'source_pdf_url' => $facts['official_pdf_url'],
                        'source_date'    => date('Y-m-d'),
                        'source_content' => $title,
                        'source_hash'    => $contentHash,
                        'title_hash'     => $titleHash,
                        'extracted_data' => json_encode($facts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'status'         => 'processed',
                    ]);

                    // Insert Article
                    $this->createArticle($sourceItemId, $articleData);

                    if ($articleData['status'] === 'published') {
                        $stats['created']++;
                    } else {
                        $stats['review']++;
                    }
                }
            }

            // Update source last fetched timestamp
            $sourceModel = new Source();
            $sourceModel->updateLastFetched($source['id']);

        } catch (\Throwable $e) {
            $stats['errors']++;
        }

        return $stats;
    }

    private function createSourceItem(int $sourceId, array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO source_items (source_id, source_title, source_url, source_pdf_url, source_date, source_content, source_hash, title_hash, extracted_data, status, created_at, updated_at)
            VALUES (:source_id, :source_title, :source_url, :source_pdf_url, :source_date, :source_content, :source_hash, :title_hash, :extracted_data, :status, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            ':source_id'      => $sourceId,
            ':source_title'   => $data['source_title'],
            ':source_url'     => $data['source_url'],
            ':source_pdf_url' => $data['source_pdf_url'],
            ':source_date'    => $data['source_date'],
            ':source_content' => $data['source_content'],
            ':source_hash'    => $data['source_hash'],
            ':title_hash'     => $data['title_hash'],
            ':extracted_data' => $data['extracted_data'],
            ':status'         => $data['status'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    private function createArticle(int $sourceItemId, array $data): void {
        // Handle slug collision
        $slug = $data['slug'];
        $check = $this->db->prepare("SELECT COUNT(*) FROM articles WHERE slug = :slug");
        $check->execute([':slug' => $slug]);
        if ((int)$check->fetchColumn() > 0) {
            $slug .= '-' . substr(md5(uniqid()), 0, 5);
        }

        $stmt = $this->db->prepare("
            INSERT INTO articles (
                source_item_id, category_id, template_type, title, slug,
                seo_title, meta_description, summary, excerpt, content_html,
                structured_data, schema_json, internal_links_json,
                official_source_name, official_source_url, official_pdf_url,
                is_breaking, is_featured, quality_score, validation_notes,
                status, version_number, published_at, updated_at
            ) VALUES (
                :source_item_id, :category_id, :template_type, :title, :slug,
                :seo_title, :meta_description, :summary, :excerpt, :content_html,
                :structured_data, :schema_json, :internal_links_json,
                :official_source_name, :official_source_url, :official_pdf_url,
                :is_breaking, :is_featured, :quality_score, :validation_notes,
                :status, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
            )
        ");

        $stmt->execute([
            ':source_item_id'      => $sourceItemId,
            ':category_id'         => $data['category_id'],
            ':template_type'       => $data['template_type'],
            ':title'               => $data['title'],
            ':slug'                => $slug,
            ':seo_title'           => $data['seo_title'],
            ':meta_description'    => $data['meta_description'],
            ':summary'             => $data['summary'],
            ':excerpt'             => $data['excerpt'],
            ':content_html'        => $data['content_html'],
            ':structured_data'     => $data['structured_data'],
            ':schema_json'         => $data['schema_json'],
            ':internal_links_json' => json_encode($data['internal_links'] ?? []),
            ':official_source_name'=> $data['official_source_name'],
            ':official_source_url' => $data['official_source_url'],
            ':official_pdf_url'    => $data['official_pdf_url'] ?? null,
            ':is_breaking'         => $data['is_breaking'] ?? 0,
            ':is_featured'         => $data['is_featured'] ?? 0,
            ':quality_score'       => $data['quality_score'] ?? 100,
            ':validation_notes'    => $data['validation_notes'] ?? 'Validated successfully',
            ':status'              => $data['status'] ?? 'published',
        ]);
    }
}
