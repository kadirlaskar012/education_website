<?php
/**
 * Master Pipeline Runner
 * Runs automated scraping, SHA-256 deduplication, grounded article generation, and auto-publishing
 */

namespace App\Pipeline\Services;

use App\Models\Source;
use App\Models\SourceItem;
use App\Pipeline\Adapters\Registry;

class PipelineRunner {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function runAll(): array {
        $startTime = microtime(true);
        $sourceModel = new Source();
        $sources = $sourceModel->getActiveSources();

        $stats = [
            'sources_processed' => 0,
            'items_found' => 0,
            'articles_created' => 0,
            'articles_updated' => 0,
            'duplicates_skipped' => 0,
            'errors' => 0,
        ];

        foreach ($sources as $source) {
            $sourceStats = $this->processSource($source);
            $stats['sources_processed']++;
            $stats['items_found'] += $sourceStats['found'];
            $stats['articles_created'] += $sourceStats['created'];
            $stats['articles_updated'] += $sourceStats['updated'];
            $stats['duplicates_skipped'] += $sourceStats['duplicates'];
            $stats['errors'] += $sourceStats['errors'];
        }

        $stats['execution_time'] = round(microtime(true) - $startTime, 2);
        return $stats;
    }

    public function processSource(array $source): array {
        $sourceStats = ['found' => 0, 'created' => 0, 'updated' => 0, 'duplicates' => 0, 'errors' => 0];
        $sourceItemModel = new SourceItem();

        try {
            $adapter = Registry::getAdapter($source);
            $items = $adapter->fetchItems();
            $sourceStats['found'] = count($items);

            foreach ($items as $item) {
                // 1. SHA-256 duplicate detection
                if ($sourceItemModel->existsByHash($item['content_hash'])) {
                    $sourceStats['duplicates']++;
                    continue;
                }

                // 2. Check for existing title update
                $existing = $sourceItemModel->findExistingByTitle($source['id'], $item['raw_title']);

                if ($existing && !empty($existing['article_id'])) {
                    // Update existing article
                    $this->updateArticle($existing['article_id'], $source, $item);
                    $sourceStats['updated']++;
                } else {
                    // Create new SourceItem and Article
                    $item['source_id'] = $source['id'];
                    $item['status'] = 'processed';
                    $sourceItemId = $sourceItemModel->create($item);

                    $this->createArticle($sourceItemId, $source, $item);
                    $sourceStats['created']++;
                }
            }

            // Update source last fetched timestamp
            $sourceModel = new Source();
            $sourceModel->updateLastFetched($source['id']);

        } catch (\Exception $e) {
            $sourceStats['errors']++;
        }

        return $sourceStats;
    }

    private function createArticle(int $sourceItemId, array $source, array $item): void {
        $articleData = ArticleGenerator::generate($source, $item);

        $stmt = $this->db->prepare("
            INSERT INTO articles (source_item_id, category_id, template_type, title, slug, summary, excerpt, content_html, structured_data, official_source_name, official_source_url, is_breaking, is_featured, status, version_number, published_at, updated_at)
            VALUES (:source_item_id, :category_id, :template_type, :title, :slug, :summary, :excerpt, :content_html, :structured_data, :official_source_name, :official_source_url, :is_breaking, :is_featured, :status, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");

        // Handle slug collision
        $slug = $articleData['slug'];
        $check = $this->db->prepare("SELECT COUNT(*) FROM articles WHERE slug = :slug");
        $check->execute([':slug' => $slug]);
        if ((int)$check->fetchColumn() > 0) {
            $slug .= '-' . substr(md5(uniqid()), 0, 6);
        }

        $stmt->execute([
            ':source_item_id'      => $sourceItemId,
            ':category_id'         => $articleData['category_id'],
            ':template_type'       => $articleData['template_type'],
            ':title'               => $articleData['title'],
            ':slug'                => $slug,
            ':summary'             => $articleData['summary'],
            ':excerpt'             => $articleData['excerpt'],
            ':content_html'        => $articleData['content_html'],
            ':structured_data'     => $articleData['structured_data'],
            ':official_source_name'=> $articleData['official_source_name'],
            ':official_source_url' => $articleData['official_source_url'],
            ':is_breaking'         => $articleData['is_breaking'],
            ':is_featured'         => $articleData['is_featured'],
            ':status'              => $articleData['status'],
        ]);
    }

    private function updateArticle(int $articleId, array $source, array $item): void {
        $articleData = ArticleGenerator::generate($source, $item);

        $stmt = $this->db->prepare("
            UPDATE articles SET
                content_html = :content_html,
                structured_data = :structured_data,
                summary = :summary,
                status = 'updated',
                version_number = version_number + 1,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
        $stmt->execute([
            ':content_html'    => $articleData['content_html'],
            ':structured_data' => $articleData['structured_data'],
            ':summary'         => $articleData['summary'],
            ':id'              => $articleId,
        ]);
    }
}
