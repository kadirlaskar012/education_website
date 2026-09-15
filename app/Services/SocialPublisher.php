<?php
/**
 * 100% Automated Multi-Channel Social Media Broadcaster
 * Automatically posts breaking education news & job alerts to:
 * 1. Telegram Channels & Groups (Telegram Bot API)
 * 2. Facebook Pages (Meta Graph API)
 * 3. Twitter / X & Multi-Platform Social Webhooks (Make.com / Zapier / IFTTT / Pabbly)
 */

declare(strict_types=1);

namespace App\Services;

use App\Models\SiteSetting;
use App\Core\Auth;

class SocialPublisher {

    /**
     * Broadcast an article to all enabled social media channels
     */
    public static function broadcast(array $article): array {
        $settingModel = new SiteSetting();
        $settings = $settingModel->getSettings();

        $results = [
            'telegram' => null,
            'facebook' => null,
            'twitter'  => null,
        ];

        // 1. Telegram Channel Auto-Post
        if (!empty($settings['telegram_auto_post']) && !empty($settings['telegram_bot_token']) && !empty($settings['telegram_channel_id'])) {
            $results['telegram'] = self::postToTelegram($article, $settings['telegram_bot_token'], $settings['telegram_channel_id']);
        }

        // 2. Facebook Page Auto-Post
        if (!empty($settings['facebook_auto_post']) && !empty($settings['facebook_page_id']) && !empty($settings['facebook_access_token'])) {
            $results['facebook'] = self::postToFacebook($article, $settings['facebook_page_id'], $settings['facebook_access_token']);
        }

        // 3. Twitter / Social Webhook Auto-Post
        if (!empty($settings['twitter_auto_post']) && !empty($settings['twitter_webhook_url'])) {
            $results['twitter'] = self::postToTwitterWebhook($article, $settings['twitter_webhook_url']);
        }

        return $results;
    }

    /**
     * Post to Telegram Channel or Group using Bot API
     */
    public static function postToTelegram(array $article, string $botToken, string $chatId): array {
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');
        $articleUrl = "{$baseUrl}/news/" . ($article['slug'] ?? '');
        $title = $article['title'] ?? 'Official Notification';
        $authority = $article['official_source_name'] ?? 'Government Authority';
        $category = $article['category_name'] ?? 'Job Alert';
        $excerpt = $article['excerpt'] ?? $article['meta_description'] ?? '';

        // Truncate excerpt cleanly
        if (mb_strlen($excerpt) > 220) {
            $excerpt = mb_substr($excerpt, 0, 215) . '...';
        }

        $tag = preg_replace('/[^a-zA-Z0-9]/', '', $authority);
        $catTag = preg_replace('/[^a-zA-Z0-9]/', '', $category);

        $text = "📢 *{$title}*\n\n"
              . "🏛️ *Board/Org:* {$authority}\n"
              . "📂 *Category:* {$category}\n"
              . "📝 *Highlights:* {$excerpt}\n\n"
              . "🔗 *Check Details & Apply Online:*\n"
              . "{$articleUrl}\n\n"
              . "#GovtJobs #Recruitment #{$catTag} " . (!empty($tag) ? "#{$tag}" : "");

        $apiUrl = "https://api.telegram.org/bot" . trim($botToken) . "/sendMessage";

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => [
                'chat_id'                  => trim($chatId),
                'text'                     => $text,
                'parse_mode'               => 'Markdown',
                'disable_web_page_preview' => false,
            ],
            CURLOPT_TIMEOUT        => 8,
        ]);

        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $isOk = ($httpCode === 200);
        Auth::logAudit('SOCIAL_TELEGRAM', "Telegram post: " . ($isOk ? 'Success' : "Failed (HTTP {$httpCode})"));

        return [
            'channel'   => 'telegram',
            'success'   => $isOk,
            'http_code' => $httpCode,
            'response'  => $res,
        ];
    }

    /**
     * Post to Facebook Page using Meta Graph API
     */
    public static function postToFacebook(array $article, string $pageId, string $accessToken): array {
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');
        $articleUrl = "{$baseUrl}/news/" . ($article['slug'] ?? '');
        $title = $article['title'] ?? 'Official Education Update';
        $authority = $article['official_source_name'] ?? 'Government Authority';
        $excerpt = $article['excerpt'] ?? '';

        $message = "🏛️ [OFFICIAL NOTICE] {$title}\n\n"
                 . "Authority: {$authority}\n\n"
                 . "{$excerpt}\n\n"
                 . "👉 Read full details, eligibility, official dates and download notification PDF here:";

        $apiUrl = "https://graph.facebook.com/v19.0/" . trim($pageId) . "/feed";

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => [
                'message'      => $message,
                'link'         => $articleUrl,
                'access_token' => trim($accessToken),
            ],
            CURLOPT_TIMEOUT        => 8,
        ]);

        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $isOk = ($httpCode === 200);
        Auth::logAudit('SOCIAL_FACEBOOK', "Facebook post: " . ($isOk ? 'Success' : "Failed (HTTP {$httpCode})"));

        return [
            'channel'   => 'facebook',
            'success'   => $isOk,
            'http_code' => $httpCode,
            'response'  => $res,
        ];
    }

    /**
     * Post to Twitter (X) or Multi-Platform Webhook (Make / Zapier / IFTTT / Pabbly)
     */
    public static function postToTwitterWebhook(array $article, string $webhookUrl): array {
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');
        $articleUrl = "{$baseUrl}/news/" . ($article['slug'] ?? '');
        $title = $article['title'] ?? 'Official Notice';
        $authority = $article['official_source_name'] ?? 'Government Authority';

        $tweetText = "🚨 Official Update: {$title}\n\n"
                   . "🏛️ {$authority}\n"
                   . "🔗 {$articleUrl}\n\n"
                   . "#GovtJobs #Recruitment #Education";

        $payload = [
            'title'       => $title,
            'url'         => $articleUrl,
            'authority'   => $authority,
            'tweet_text'  => $tweetText,
            'excerpt'     => $article['excerpt'] ?? '',
            'category'    => $article['category_name'] ?? 'Education',
            'published_at'=> $article['published_at'] ?? date('c'),
        ];

        $ch = curl_init(trim($webhookUrl));
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 8,
        ]);

        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $isOk = ($httpCode >= 200 && $httpCode < 300);
        Auth::logAudit('SOCIAL_TWITTER', "Twitter/Webhook post: " . ($isOk ? 'Success' : "Failed (HTTP {$httpCode})"));

        return [
            'channel'   => 'twitter',
            'success'   => $isOk,
            'http_code' => $httpCode,
            'response'  => $res,
        ];
    }
}
