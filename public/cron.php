<?php
/**
 * Automated Cron Runner (For cPanel Cron Jobs)
 * Cron schedule: Every 15 minutes
 */

declare(strict_types=1);

// Set unlimited execution time
set_time_limit(300);
ini_set('memory_limit', '256M');

// Autoloader
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

echo "[" . date('Y-m-d H:i:s') . "] Starting Automated Education Scraper Pipeline...\n";

try {
    $runner = new \App\Pipeline\Services\PipelineRunner();
    $stats = $runner->runAll();

    echo "Finished successfully in {$stats['execution_time']}s\n";
    echo "Sources Processed: {$stats['sources_processed']}\n";
    echo "Items Found: {$stats['items_found']}\n";
    echo "Articles Created: {$stats['articles_created']}\n";
    echo "Articles Updated: {$stats['articles_updated']}\n";
    echo "Duplicates Skipped: {$stats['duplicates_skipped']}\n";
    echo "Errors: {$stats['errors']}\n";
} catch (\Throwable $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
}
