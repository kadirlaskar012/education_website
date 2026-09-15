<?php
/**
 * Asynchronous Background Worker CLI Script
 * Runs completely detached in background without holding HTTP web server threads.
 * Writes live status to storage/pipeline_status.json.
 */

declare(strict_types=1);

set_time_limit(0);
ini_set('memory_limit', '512M');
ignore_user_abort(true);

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
use App\Pipeline\Services\PipelineRunner;

$storageDir = __DIR__ . '/../storage';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}
$statusFile = $storageDir . '/pipeline_status.json';

function updateStatus(string $file, array $data): void {
    $existing = [];
    if (file_exists($file)) {
        $raw = @file_get_contents($file);
        if ($raw) $existing = json_decode($raw, true) ?: [];
    }
    $merged = array_merge($existing, $data, ['updated_at' => time()]);
    @file_put_contents($file, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function checkStopRequested(string $file): bool {
    if (!file_exists($file)) return false;
    $raw = @file_get_contents($file);
    if (!$raw) return false;
    $data = json_decode($raw, true);
    return !empty($data['stop_requested']);
}

$target = $argv[1] ?? 'all';

$sourceModel = new Source();
$runner = new PipelineRunner();

if ($target === 'all') {
    $sources = $sourceModel->getActiveSources();
    $total = count($sources);

    updateStatus($statusFile, [
        'status'         => 'running',
        'target'         => 'all',
        'percent'        => 0,
        'current_source' => 'Initializing pipeline...',
        'total_sources'  => $total,
        'processed'      => 0,
        'created_total'  => 0,
        'duplicates'     => 0,
        'errors'         => 0,
        'logs'           => ["[Ready] Starting background ingestion across {$total} government sources..."],
        'started_at'     => time(),
        'stop_requested' => false,
    ]);

    $createdTotal = 0;
    $dupsTotal = 0;
    $errorsTotal = 0;
    $logs = [];

    foreach ($sources as $idx => $source) {
        if (checkStopRequested($statusFile)) {
            $logs[] = "[Stopped] Worker safely terminated by admin.";
            updateStatus($statusFile, [
                'status' => 'idle',
                'logs'   => $logs,
            ]);
            exit(0);
        }

        $currentIdx = $idx + 1;
        $percent = (int)round(($idx / $total) * 100);

        updateStatus($statusFile, [
            'percent'        => $percent,
            'current_source' => "Ingesting [{$currentIdx}/{$total}]: {$source['name']}",
            'processed'      => $currentIdx,
        ]);

        try {
            $stats = $runner->processSource($source);
            $c = $stats['created'] ?? 0;
            $d = $stats['duplicates'] ?? 0;
            $e = $stats['errors'] ?? 0;

            $createdTotal += $c;
            $dupsTotal += $d;
            $errorsTotal += $e;

            if ($c > 0) {
                $logs[] = "✓ [{$source['name']}] Generated {$c} new AdSense article(s)!";
            } elseif ($d > 0) {
                $logs[] = "✓ [{$source['name']}] Up-to-date ({$d} duplicate notices skipped)";
            } else {
                $logs[] = "✓ [{$source['name']}] No new notices";
            }
        } catch (\Throwable $err) {
            $errorsTotal++;
            $logs[] = "⚠️ [{$source['name']}] Error: " . $err->getMessage();
        }

        updateStatus($statusFile, [
            'created_total' => $createdTotal,
            'duplicates'    => $dupsTotal,
            'errors'        => $errorsTotal,
            'logs'          => array_slice($logs, -30), // keep latest 30 log lines
        ]);
    }

    updateStatus($statusFile, [
        'status'         => 'completed',
        'percent'        => 100,
        'current_source' => "Finished! {$createdTotal} articles generated.",
        'completed_at'   => time(),
    ]);

} else {
    // Single specific source
    $sourceId = (int)$target;
    $source = $sourceModel->findById($sourceId);

    if (!$source) {
        updateStatus($statusFile, [
            'status' => 'error',
            'error'  => "Source #{$sourceId} not found.",
        ]);
        exit(1);
    }

    updateStatus($statusFile, [
        'status'         => 'running',
        'target'         => 'single',
        'percent'        => 25,
        'current_source' => "Ingesting: {$source['name']}",
        'logs'           => ["Connecting to {$source['name']}..."],
        'started_at'     => time(),
        'stop_requested' => false,
    ]);

    try {
        $stats = $runner->processSource($source);
        $c = $stats['created'] ?? 0;
        $d = $stats['duplicates'] ?? 0;

        $msg = $c > 0 
            ? "✓ Generated {$c} new AdSense article(s) for {$source['name']}!" 
            : ($d > 0 ? "✓ {$source['name']} is already up to date ({$d} duplicates skipped)." : "✓ No new notices for {$source['name']}.");

        updateStatus($statusFile, [
            'status'         => 'completed',
            'percent'        => 100,
            'current_source' => $msg,
            'created_total'  => $c,
            'logs'           => [$msg],
            'completed_at'   => time(),
        ]);
    } catch (\Throwable $err) {
        updateStatus($statusFile, [
            'status' => 'error',
            'error'  => $err->getMessage(),
            'logs'   => ["❌ Error: " . $err->getMessage()],
        ]);
    }
}
