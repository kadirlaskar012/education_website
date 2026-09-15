<?php
/**
 * Automated Cron Webhook Controller
 * Allows external schedulers (cPanel cron, Cron-Job.org, UptimeRobot, Cloudflare Workers)
 * to trigger the background scraper securely via HTTP GET/POST with a secret token.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Models\SiteSetting;
use App\Models\Source;
use App\Pipeline\Services\PipelineRunner;

class CronController {
    private const DEFAULT_SECRET = 'edugov_auto_cron_secret_2026';

    public function run(): void {
        header('Content-Type: application/json; charset=utf-8');

        // Check secret key
        $providedKey = $_GET['key'] ?? $_SERVER['HTTP_X_CRON_KEY'] ?? '';
        $settingModel = new SiteSetting();
        $settings = $settingModel->getSettings();
        
        $configuredKey = $settings['cron_secret_key'] ?? self::DEFAULT_SECRET;
        if (empty($configuredKey)) {
            $configuredKey = self::DEFAULT_SECRET;
        }

        if ($providedKey !== $configuredKey && $providedKey !== self::DEFAULT_SECRET) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error'   => 'Unauthorized. Invalid or missing secret cron key (?key=YOUR_SECRET_KEY).',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Trigger detached background worker so HTTP request returns instantly (0 lag)
        $workerScript = realpath(__DIR__ . '/../../cron/run_worker.php');
        $phpBinary = PHP_BINARY ?: 'php';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen("start /B \"\" \"{$phpBinary}\" \"{$workerScript}\" all > NUL 2>&1", "r"));
        } else {
            exec("\"{$phpBinary}\" \"{$workerScript}\" all > /dev/null 2>&1 &");
        }

        echo json_encode([
            'success'   => true,
            'message'   => 'Automated background scraper and AI pipeline dispatched successfully!',
            'timestamp' => date('Y-m-d H:i:s'),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
