<?php
/**
 * Cron Step 2: Extract Facts, Generate Human-Tone Article, and SEO Optimize
 */

declare(strict_types=1);

set_time_limit(300);
ini_set('memory_limit', '256M');

spl_autoload_register(function (string $class) {
    if (str_starts_with($class, 'App\\')) {
        $relativeClass = substr($class, 4);
        $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    } elseif ($class === 'Database') {
        require_once __DIR__ . '/../config/database.php';
    }
});

require_once __DIR__ . '/../config/database.php';

use App\Models\Source;
use App\Pipeline\Scraper\FactExtractor;
use App\Pipeline\AI\ArticleGenerator;
use App\Pipeline\Quality\InternalLinker;
use App\Pipeline\Quality\ArticleValidator;
use App\Pipeline\Services\UpdateDetector;

echo "[" . date('Y-m-d H:i:s') . "] CRON STEP 2: Processing Articles & Generating Content...\n";

$db = Database::getConnection();
$stmt = $db->query("SELECT si.*, s.authority_name, s.base_url, s.default_category_id FROM source_items si JOIN sources s ON si.source_id = s.id WHERE si.status = 'new' ORDER BY si.id ASC LIMIT 20");
$pendingItems = $stmt->fetchAll();

$generator = new ArticleGenerator();
$internalLinker = new InternalLinker();
$updateDetector = new UpdateDetector();

$processed = 0;

foreach ($pendingItems as $item) {
    try {
        $source = [
            'id'                  => $item['source_id'],
            'authority_name'      => $item['authority_name'],
            'base_url'            => $item['base_url'],
            'default_category_id' => $item['default_category_id'],
        ];

        // 1. Extract verified facts
        $facts = FactExtractor::extract($source, $item);

        // Update extracted_data on source_item
        $up = $db->prepare("UPDATE source_items SET extracted_data = :data, status = 'processing' WHERE id = :id");
        $up->execute([
            ':data' => json_encode($facts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ':id'   => $item['id'],
        ]);

        // 2. Generate Human-Tone Article with Gemini AI or Fallback
        $articleData = $generator->generate($source, $facts);

        // 3. Inject Contextual Internal Links
        $linked = $internalLinker->injectLinks($articleData['content_html'], $facts['organization'], $articleData['category_id']);
        $articleData['content_html'] = $linked['content_html'];
        $articleData['internal_links'] = $linked['internal_links'];

        // 4. Quality Validation
        $val = ArticleValidator::validate($articleData);
        $articleData['quality_score'] = $val['score'];
        $articleData['validation_notes'] = $val['validation_notes'];
        $articleData['status'] = $val['passed'] ? 'published' : 'review';

        // 5. Check if title is update of existing article
        $checkExisting = $db->prepare("
            SELECT a.id as article_id, a.version_number
            FROM articles a
            WHERE a.source_item_id = :item_id OR (a.official_source_name = :org AND a.title = :title)
            LIMIT 1
        ");
        $checkExisting->execute([
            ':item_id' => $item['id'],
            ':org'     => $facts['organization'],
            ':title'   => $articleData['title'],
        ]);
        $existing = $checkExisting->fetch();

        if ($existing) {
            $updateDetector->updateArticle((int)$existing['article_id'], $articleData, $facts);
        } else {
            // Create New Article
            $insert = $db->prepare("
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
                    1, 1, :quality_score, :validation_notes,
                    :status, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
                )
            ");
            $insert->execute([
                ':source_item_id'      => $item['id'],
                ':category_id'         => $articleData['category_id'],
                ':template_type'       => $articleData['template_type'],
                ':title'               => $articleData['title'],
                ':slug'                => $articleData['slug'],
                ':seo_title'           => $articleData['seo_title'],
                ':meta_description'    => $articleData['meta_description'],
                ':summary'             => $articleData['summary'],
                ':excerpt'             => $articleData['excerpt'],
                ':content_html'        => $articleData['content_html'],
                ':structured_data'     => $articleData['structured_data'],
                ':schema_json'         => $articleData['schema_json'],
                ':internal_links_json' => json_encode($articleData['internal_links'] ?? []),
                ':official_source_name'=> $articleData['official_source_name'],
                ':official_source_url' => $articleData['official_source_url'],
                ':official_pdf_url'    => $articleData['official_pdf_url'] ?? null,
                ':quality_score'       => $articleData['quality_score'],
                ':validation_notes'    => $articleData['validation_notes'],
                ':status'              => $articleData['status'],
            ]);
        }

        // Mark source item processed
        $mark = $db->prepare("UPDATE source_items SET status = 'processed', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $mark->execute([':id' => $item['id']]);

        $processed++;
    } catch (\Throwable $e) {
        echo "Error processing item {$item['id']}: " . $e->getMessage() . "\n";
    }
}

echo "--> Step 2 Complete: Processed {$processed} articles.\n";
