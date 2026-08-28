<?php
/**
 * Command Line Interface (CLI)
 * Usage:
 *   php cli.php seed          (Seeds database with initial categories and sources)
 *   php cli.php run-pipeline  (Runs automated scraper pipeline)
 *   php cli.php stats         (Displays article and database statistics)
 */

declare(strict_types=1);

spl_autoload_register(function (string $class) {
    if (str_starts_with($class, 'App\\')) {
        $relativeClass = substr($class, 4);
        $file = __DIR__ . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    } elseif ($class === 'Database') {
        require_once __DIR__ . '/config/database.php';
    }
});

require_once __DIR__ . '/config/database.php';

$action = $argv[1] ?? 'help';

switch ($action) {
    case 'seed':
        echo "--> Initializing database schema and seed data...\n";
        $db = Database::getConnection();
        $schema = file_get_contents(__DIR__ . '/database/schema.sql');
        $db->exec($schema);
        $seed = file_get_contents(__DIR__ . '/database/seed.sql');
        $db->exec($seed);
        echo "--> Database seeded successfully with 14 categories and official sources!\n";
        break;

    case 'run-pipeline':
        echo "--> Running automated scraper pipeline...\n";
        $runner = new \App\Pipeline\Services\PipelineRunner();
        $stats = $runner->runAll();
        echo "--> Pipeline finished in {$stats['execution_time']}s!\n";
        print_r($stats);
        break;

    case 'stats':
        $articleModel = new \App\Models\Article();
        $stats = $articleModel->getAdminStats();
        echo "=== EDUGOV DATABASE STATS ===\n";
        foreach ($stats as $k => $v) {
            echo ucfirst($k) . ": {$v}\n";
        }
        break;

    default:
        echo "EduGov CLI Usage:\n";
        echo "  php cli.php seed          - Seed categories and sources\n";
        echo "  php cli.php run-pipeline  - Fetch and scrape sources\n";
        echo "  php cli.php stats         - View article statistics\n";
        break;
}
