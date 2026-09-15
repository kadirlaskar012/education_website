<?php
/**
 * SiteSetting Model with AI, SEO, Analytics & Multi-Channel Social Auto-Publishing toggles
 */

namespace App\Models;

class SiteSetting {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
        $this->ensureSocialColumns();
    }

    private function ensureSocialColumns(): void {
        try {
            $cols = [];
            $rawCols = $this->db->query("PRAGMA table_info(site_settings)")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rawCols as $c) {
                $cols[] = $c['name'];
            }

            $newColumns = [
                'telegram_bot_token'      => 'VARCHAR(255) DEFAULT ""',
                'telegram_channel_id'     => 'VARCHAR(100) DEFAULT ""',
                'telegram_auto_post'      => 'INTEGER DEFAULT 0',
                'facebook_page_id'        => 'VARCHAR(100) DEFAULT ""',
                'facebook_access_token'   => 'TEXT DEFAULT ""',
                'facebook_auto_post'      => 'INTEGER DEFAULT 0',
                'twitter_webhook_url'     => 'VARCHAR(500) DEFAULT ""',
                'twitter_auto_post'       => 'INTEGER DEFAULT 0',
                'ga4_measurement_id'      => 'VARCHAR(50) DEFAULT ""',
                'google_site_verification'=> 'VARCHAR(100) DEFAULT ""',
                'cron_secret_key'         => 'VARCHAR(100) DEFAULT "edugov_auto_cron_secret_2026"',
            ];

            foreach ($newColumns as $colName => $colDef) {
                if (!in_array($colName, $cols)) {
                    $this->db->exec("ALTER TABLE site_settings ADD COLUMN {$colName} {$colDef}");
                }
            }
        } catch (\Throwable $e) {
            // Ignore if columns exist
        }
    }

    public function getSettings(): array {
        $stmt = $this->db->query("SELECT * FROM site_settings ORDER BY id ASC LIMIT 1");
        $settings = $stmt->fetch();
        if (!$settings) {
            return [
                'site_name'                 => 'EduGov News',
                'site_tagline'              => 'Verified Official Education Updates & Notifications',
                'contact_email'             => 'contact@edugovnews.in',
                'top_breaking_announcement' => 'RRB NTPC 2026 Notification Out — Check Dates & Links',
                'auto_publish'              => 1,
                'ai_rewrite'                => 1,
                'gemini_api_key'            => '',
                'min_quality_score'         => 80,
                'telegram_bot_token'        => '',
                'telegram_channel_id'       => '',
                'telegram_auto_post'        => 0,
                'facebook_page_id'          => '',
                'facebook_access_token'     => '',
                'facebook_auto_post'        => 0,
                'twitter_webhook_url'       => '',
                'twitter_auto_post'         => 0,
                'ga4_measurement_id'        => '',
                'google_site_verification'  => '',
                'cron_secret_key'           => 'edugov_auto_cron_secret_2026',
            ];
        }
        return $settings;
    }

    public function updateSettings(array $data): void {
        $stmt = $this->db->prepare("
            UPDATE site_settings SET
                site_name = :site_name,
                site_tagline = :site_tagline,
                contact_email = :contact_email,
                top_breaking_announcement = :top_breaking_announcement,
                auto_publish = :auto_publish,
                ai_rewrite = :ai_rewrite,
                gemini_api_key = :gemini_api_key,
                min_quality_score = :min_quality_score,
                telegram_bot_token = :telegram_bot_token,
                telegram_channel_id = :telegram_channel_id,
                telegram_auto_post = :telegram_auto_post,
                facebook_page_id = :facebook_page_id,
                facebook_access_token = :facebook_access_token,
                facebook_auto_post = :facebook_auto_post,
                twitter_webhook_url = :twitter_webhook_url,
                twitter_auto_post = :twitter_auto_post,
                ga4_measurement_id = :ga4_measurement_id,
                google_site_verification = :google_site_verification,
                cron_secret_key = :cron_secret_key,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = 1
        ");
        $stmt->execute([
            ':site_name'                 => $data['site_name'] ?? 'EduGov News',
            ':site_tagline'              => $data['site_tagline'] ?? '',
            ':contact_email'             => $data['contact_email'] ?? '',
            ':top_breaking_announcement' => $data['top_breaking_announcement'] ?? '',
            ':auto_publish'              => isset($data['auto_publish']) ? 1 : 0,
            ':ai_rewrite'                => isset($data['ai_rewrite']) ? 1 : 0,
            ':gemini_api_key'            => trim($data['gemini_api_key'] ?? ''),
            ':min_quality_score'         => (int)($data['min_quality_score'] ?? 80),
            ':telegram_bot_token'        => trim($data['telegram_bot_token'] ?? ''),
            ':telegram_channel_id'       => trim($data['telegram_channel_id'] ?? ''),
            ':telegram_auto_post'        => isset($data['telegram_auto_post']) ? 1 : 0,
            ':facebook_page_id'          => trim($data['facebook_page_id'] ?? ''),
            ':facebook_access_token'     => trim($data['facebook_access_token'] ?? ''),
            ':facebook_auto_post'        => isset($data['facebook_auto_post']) ? 1 : 0,
            ':twitter_webhook_url'       => trim($data['twitter_webhook_url'] ?? ''),
            ':twitter_auto_post'         => isset($data['twitter_auto_post']) ? 1 : 0,
            ':ga4_measurement_id'        => trim($data['ga4_measurement_id'] ?? ''),
            ':google_site_verification'  => trim($data['google_site_verification'] ?? ''),
            ':cron_secret_key'           => trim($data['cron_secret_key'] ?? 'edugov_auto_cron_secret_2026'),
        ]);
    }
}
