<?php
/**
 * Master Unified Cron Runner (For cPanel Cron Jobs)
 * Single 1-line cron command:
 * *\/15 * * * * php /home/username/public_html/cron/run_all.php >/dev/null 2>&1
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

use App\Pipeline\Services\PipelineRunner;

echo "[" . date('Y-m-d H:i:s') . "] Executing Complete Automated Education Pipeline...\n";

try {
    $runner = new PipelineRunner();
    $stats = $runner->runAll();

    echo "========================================\n";
    echo "PIPELINE SUMMARY (Duration: {$stats['execution_time']}s)\n";
    echo "Sources Processed : {$stats['sources_processed']}\n";
    echo "Notices Found     : {$stats['items_found']}\n";
    echo "Articles Created  : {$stats['articles_created']}\n";
    echo "Articles Updated  : {$stats['articles_updated']}\n";
    echo "In Review         : {$stats['articles_in_review']}\n";
    echo "Duplicates Skipped: {$stats['duplicates_skipped']}\n";
    echo "Errors            : {$stats['errors']}\n";
    echo "========================================\n";
} catch (\Throwable $e) {
    echo "CRITICAL PIPELINE ERROR: " . $e->getMessage() . "\n";
}
