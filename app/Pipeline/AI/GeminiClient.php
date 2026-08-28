<?php
/**
 * Google Gemini REST API Client (Pure Plain PHP & cURL)
 * Supports gemini-1.5-flash & gemini-pro models with retry & timeout safety
 */

namespace App\Pipeline\AI;

class GeminiClient {
    private string $apiKey;
    private string $model;
    private float $temperature;

    public function __construct(?string $apiKey = null, string $model = 'gemini-1.5-flash', float $temperature = 0.4) {
        $config = require __DIR__ . '/../../../config/config.php';
        $this->apiKey = $apiKey ?: ($config['ai']['api_key'] ?? '');
        $this->model = $model ?: ($config['ai']['model'] ?? 'gemini-1.5-flash');
        $this->temperature = $temperature;
    }

    public function isConfigured(): bool {
        return !empty(trim($this->apiKey));
    }

    public function generate(string $prompt, int $maxRetries = 2): ?string {
        if (!$this->isConfigured()) {
            return null;
        }

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key=" . urlencode($this->apiKey);

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature'     => $this->temperature,
                'topP'            => 0.95,
                'topK'            => 40,
                'maxOutputTokens' => 2048,
            ]
        ];

        $jsonData = json_encode($payload);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $jsonData,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 25,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ]
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($httpCode === 200 && !empty($response)) {
                $decoded = json_decode($response, true);
                $candidateText = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if (!empty($candidateText)) {
                    return trim($candidateText);
                }
            }

            // Exponential backoff before retry
            if ($attempt < $maxRetries) {
                usleep(500000 * $attempt); // 0.5s, 1.0s
            }
        }

        return null;
    }
}
