<?php
/**
 * SourceItem Model & SiteSetting Model
 */

namespace App\Models;

class SourceItem {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function existsByHash(string $hash): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM source_items WHERE content_hash = :hash");
        $stmt->execute([':hash' => $hash]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function findExistingByTitle(int $sourceId, string $title): ?array {
        $stmt = $this->db->prepare("
            SELECT si.*, a.id as article_id, a.version_number
            FROM source_items si
            LEFT JOIN articles a ON si.id = a.source_item_id
            WHERE si.source_id = :source_id AND si.raw_title = :title
            LIMIT 1
        ");
        $stmt->execute([':source_id' => $sourceId, ':title' => $title]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO source_items (source_id, external_id, raw_title, raw_url, content_hash, title_hash, published_date, raw_html, status)
            VALUES (:source_id, :external_id, :raw_title, :raw_url, :content_hash, :title_hash, :published_date, :raw_html, :status)
        ");
        $stmt->execute([
            ':source_id' => $data['source_id'],
            ':external_id' => $data['external_id'] ?? null,
            ':raw_title' => $data['raw_title'],
            ':raw_url' => $data['raw_url'],
            ':content_hash' => $data['content_hash'],
            ':title_hash' => $data['title_hash'] ?? hash('sha256', $data['raw_title']),
            ':published_date' => $data['published_date'] ?? null,
            ':raw_html' => $data['raw_html'] ?? null,
            ':status' => $data['status'] ?? 'pending',
        ]);
        return (int)$this->db->lastInsertId();
    }
}

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
