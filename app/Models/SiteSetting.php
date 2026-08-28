<?php
/**
 * SiteSetting Model
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
                'site_name' => 'EduGov News',
                'site_tagline' => 'Verified Official Education Updates & Notifications',
                'contact_email' => 'contact@edugovnews.in',
                'top_breaking_announcement' => 'RRB NTPC 2026 Notification Out — Check Dates & Links',
            ];
        }
        return $settings;
    }
}
