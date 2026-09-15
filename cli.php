<?php
/**
 * Command Line Interface (CLI) for EduGov News Engine
 * Usage:
 *   php cli.php seed              (Seeds database schema, categories and sources)
 *   php cli.php run-pipeline      (Executes complete scraping, AI generation & publishing pipeline)
 *   php cli.php fetch-sources     (Cron step 1: Scrapes active sources)
 *   php cli.php process-articles  (Cron step 2: AI rewrites & SEO optimizes)
 *   php cli.php publish-articles  (Cron step 3: Auto-publishes validated articles)
 *   php cli.php stats             (Displays database & quality statistics)
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
        echo "--> Running complete automated AI scraper pipeline...\n";
        $runner = new \App\Pipeline\Services\PipelineRunner();
        $stats = $runner->runAll();
        echo "--> Pipeline finished in {$stats['execution_time']}s!\n";
        print_r($stats);
        break;

    case 'fetch-sources':
        require_once __DIR__ . '/cron/fetch_sources.php';
        break;

    case 'process-articles':
        require_once __DIR__ . '/cron/process_articles.php';
        break;

    case 'publish-articles':
        require_once __DIR__ . '/cron/publish_articles.php';
        break;

    case 'stats':
        $articleModel = new \App\Models\Article();
        $stats = $articleModel->getAdminStats();
        echo "=== EDUGOV DATABASE STATS ===\n";
        foreach ($stats as $k => $v) {
            echo ucfirst($k) . ": {$v}\n";
        }
        break;

    case 'reset-password':
        $username = $argv[2] ?? 'admin';
        $newPassword = $argv[3] ?? null;

        if (empty($newPassword)) {
            $newPassword = substr(bin2hex(random_bytes(8)), 0, 12);
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, username FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if (!$user) {
            echo "❌ Error: User '{$username}' was not found in the database.\n";
            exit(1);
        }

        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $update = $db->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        $update->execute([':hash' => $hash, ':id' => $user['id']]);

        // Unlock account by clearing failed login attempts
        try {
            $db->prepare("DELETE FROM login_attempts WHERE username = :username")->execute([':username' => $username]);
        } catch (\Throwable $e) {}

        \App\Core\Auth::logAudit('PASSWORD_RESET_CLI', "Password for user '{$username}' reset via CLI.");

        echo "====================================================\n";
        echo "✅ ADMIN PASSWORD RESET SUCCESSFUL\n";
        echo "====================================================\n";
        echo "Username:     {$username}\n";
        echo "New Password: {$newPassword}\n";
        echo "Login URL:    http://127.0.0.1:8000/admin/login\n";
        echo "Lockout:      Any active IP/Account lockout cleared\n";
        echo "====================================================\n";
        break;

    default:
        echo "EduGov CLI Usage:\n";
        echo "  php cli.php seed                          - Seed categories and sources\n";
        echo "  php cli.php reset-password [user] [pass]  - Reset admin password instantly\n";
        echo "  php cli.php run-pipeline                  - Fetch, AI rewrite, validate & publish\n";
        echo "  php cli.php fetch-sources                 - Step 1: Scrape active sources\n";
        echo "  php cli.php process-articles              - Step 2: Extract & generate articles\n";
        echo "  php cli.php publish-articles              - Step 3: Auto-publish validated articles\n";
        echo "  php cli.php stats                         - View article statistics\n";
        break;
}

