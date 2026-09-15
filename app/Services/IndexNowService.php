<?php
/**
 * Instant Search Engine Indexing Service
 * Supports:
 * 1. IndexNow Protocol (Bing, Yandex, Seznam, Naver) - Instant crawling within 2 minutes
 * 2. Google WebSub (PubSubHubbub) - Real-time RSS/Atom feed notification to Google
 */

declare(strict_types=1);

namespace App\Services;

require_once __DIR__ . '/../Core/Auth.php';

class IndexNowService {
    private const INDEXNOW_API_URL = 'https://api.indexnow.org/indexnow';
    private const WEBSUB_HUB_URL = 'https://pubsubhubbub.appspot.com/';

    /**
     * Submit newly published or updated URLs to IndexNow protocol
     */
    public static function submitUrls(array $urls): array {
        if (empty($urls)) {
            return ['success' => false, 'message' => 'No URLs provided'];
        }

        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');
        $host = parse_url($baseUrl, PHP_URL_HOST) ?? 'localhost';
        
        // 32-character IndexNow API Key
        $apiKey = md5($host . '-edugov-indexnow-key');
        
        $payload = [
            'host'        => $host,
            'key'         => $apiKey,
            'keyLocation' => "{$baseUrl}/indexnow-{$apiKey}.txt",
            'urlList'     => array_values($urls),
        ];

        $ch = curl_init(self::INDEXNOW_API_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 5,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $isSuccess = ($httpCode === 200 || $httpCode === 202);

        // Record in audit log
        \App\Core\Auth::logAudit(
            'INDEXNOW_PING',
            "Submitted " . count($urls) . " URL(s) to IndexNow. HTTP Status: {$httpCode}"
        );

        return [
            'success'   => $isSuccess,
            'http_code' => $httpCode,
            'response'  => $response,
            'urls'      => $urls,
        ];
    }

    /**
     * Submit a single article URL
     */
    public static function submitArticle(string $slug): array {
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');
        $url = "{$baseUrl}/news/" . ltrim($slug, '/');

        // Trigger both IndexNow and WebSub
        $res = self::submitUrls([$url]);
        self::pingWebSubHub();

        return $res;
    }

    /**
     * Ping Google WebSub (PubSubHubbub) Hub for instant RSS feed crawling
     */
    public static function pingWebSubHub(): bool {
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');
        $feedUrl = "{$baseUrl}/rss.xml";

        $postData = http_build_query([
            'hub.mode' => 'publish',
            'hub.url'  => $feedUrl,
        ]);

        $ch = curl_init(self::WEBSUB_HUB_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $postData,
            CURLOPT_TIMEOUT        => 5,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpCode === 204 || $httpCode === 200);
    }
}
