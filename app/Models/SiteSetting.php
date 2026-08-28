<?php
/**
 * SiteSetting Model with AI & Automation toggles
 */

namespace App\Models;

class SiteSetting {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
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
        ]);
    }
}
