<?php
/**
 * Fast & Reliable cURL HTTP Fetcher with SSL warning suppression and custom User-Agent
 */

namespace App\Pipeline\Services;

class HttpFetcher {
    public static function fetch(string $url, int $timeout = 15): ?string {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 EduGovBot/2.0',
            CURLOPT_HTTPHEADER => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9',
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 400 && $response !== false) {
            return $response;
        }

        return null;
    }
}

class Deduplicator {
    public static function computeContentHash(string $content, string $url): string {
        return hash('sha256', trim($content) . '||' . trim($url));
    }

    public static function computeTitleHash(string $title): string {
        $normalized = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $title)));
        return hash('sha256', $normalized);
    }
}
