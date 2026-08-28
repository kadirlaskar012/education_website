<?php
/**
 * Cron Step 1: Fetch Sources & Store Raw Notices
 * Schedule: Every 15 or 30 minutes
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
use App\Pipeline\Adapters\Registry;
use App\Pipeline\Scraper\DuplicateChecker;

echo "[" . date('Y-m-d H:i:s') . "] CRON STEP 1: Fetching Official Sources...\n";

$db = Database::getConnection();
$sourceModel = new Source();
$sources = $sourceModel->getActiveSources();
$dupChecker = new DuplicateChecker();

$foundTotal = 0;
$newTotal = 0;
$dupTotal = 0;

foreach ($sources as $source) {
    try {
        $adapter = Registry::getAdapter($source);
        $items = $adapter->fetchItems();
        $foundTotal += count($items);

        foreach ($items as $item) {
            $title = trim($item['raw_title'] ?? $item['source_title'] ?? '');
            $url = trim($item['raw_url'] ?? $item['source_url'] ?? $source['base_url']);

            if (empty($title)) continue;

            $contentHash = DuplicateChecker::computeContentHash($title, $url);
            $titleHash = DuplicateChecker::computeTitleHash($title);

            if ($dupChecker->isDuplicate($source['id'], $title, $url)) {
                $dupTotal++;
                continue;
            }

            // Store new item
            $stmt = $db->prepare("
                INSERT INTO source_items (source_id, source_title, source_url, source_pdf_url, source_date, source_content, source_hash, title_hash, status, created_at, updated_at)
                VALUES (:source_id, :source_title, :source_url, :source_pdf_url, :source_date, :source_content, :source_hash, :title_hash, 'new', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ");
            $stmt->execute([
                ':source_id'      => $source['id'],
                ':source_title'   => $title,
                ':source_url'     => $url,
                ':source_pdf_url' => $item['source_pdf_url'] ?? (str_contains(strtolower($url), '.pdf') ? $url : null),
                ':source_date'    => date('Y-m-d'),
                ':source_content' => $title,
                ':source_hash'    => $contentHash,
                ':title_hash'     => $titleHash,
            ]);
            $newTotal++;
        }

        $sourceModel->updateLastFetched($source['id']);
    } catch (\Throwable $e) {
        echo "Error fetching source {$source['name']}: " . $e->getMessage() . "\n";
    }
}

echo "--> Step 1 Complete: Found={$foundTotal}, New={$newTotal}, Duplicates={$dupTotal}\n";
