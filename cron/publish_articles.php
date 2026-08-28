<?php
/**
 * Cron Step 3: Auto-Publish Approved & Validated Articles
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

use App\Models\SiteSetting;

echo "[" . date('Y-m-d H:i:s') . "] CRON STEP 3: Publishing Validated Articles...\n";

$db = Database::getConnection();
$settingModel = new SiteSetting();
$settings = $settingModel->getSettings();

if (!($settings['auto_publish'] ?? true)) {
    echo "--> Auto-publish is disabled in settings. Skipping.\n";
    exit;
}

$minScore = (int)($settings['min_quality_score'] ?? 80);

$stmt = $db->prepare("
    UPDATE articles
    SET status = 'published', updated_at = CURRENT_TIMESTAMP
    WHERE status = 'review' AND quality_score >= :min_score
");
$stmt->execute([':min_score' => $minScore]);
$count = $stmt->rowCount();

echo "--> Step 3 Complete: {$count} articles transitioned to 'published'.\n";
