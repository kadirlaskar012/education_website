<?php
/**
 * Front Controller (Entry point for all web requests)
 */

declare(strict_types=1);

// Error Reporting
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Autoloader for App\* namespace
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

// Auto-seed SQLite database if running for the first time
try {
    $db = Database::getConnection();
    $check = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='categories'");
    if (!$check || !$check->fetch()) {
        $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
        $db->exec($schema);
        $seed = file_get_contents(__DIR__ . '/../database/seed.sql');
        $db->exec($seed);

        // Run scraper pipeline once to populate initial articles
        $runner = new \App\Pipeline\Services\PipelineRunner();
        $runner->runAll();
    }
} catch (\Throwable $e) {
    // If not SQLite, schema is already imported
}

// Dispatch Router
$router = require __DIR__ . '/../config/routes.php';
$router->dispatch();
