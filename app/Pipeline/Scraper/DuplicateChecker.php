<?php
/**
 * Multi-Tier Duplicate Checker
 * Checks Source URL, Normalized Title, Source ID, and SHA-256 Hash
 */

namespace App\Pipeline\Scraper;

class DuplicateChecker {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public static function computeContentHash(string $title, string $url): string {
        return hash('sha256', trim($title) . '||' . trim($url));
    }

    public static function computeTitleHash(string $title): string {
        $normalized = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $title)));
        return hash('sha256', $normalized);
    }

    public function isDuplicate(int $sourceId, string $title, string $url): bool {
        $contentHash = self::computeContentHash($title, $url);

        // 1. Check content hash
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM source_items WHERE source_hash = :hash");
        $stmt->execute([':hash' => $contentHash]);
        if ((int)$stmt->fetchColumn() > 0) {
            return true;
        }

        // 2. Check source URL
        $stmtUrl = $this->db->prepare("SELECT COUNT(*) FROM source_items WHERE source_id = :source_id AND source_url = :url");
        $stmtUrl->execute([':source_id' => $sourceId, ':url' => $url]);
        if ((int)$stmtUrl->fetchColumn() > 0) {
            return true;
        }

        return false;
    }

    public function findExistingItemForUpdate(int $sourceId, string $title): ?array {
        $titleHash = self::computeTitleHash($title);
        $stmt = $this->db->prepare("
            SELECT si.*, a.id as article_id, a.slug as article_slug, a.version_number
            FROM source_items si
            LEFT JOIN articles a ON si.id = a.source_item_id
            WHERE si.source_id = :source_id AND (si.title_hash = :title_hash OR si.source_title = :title)
            LIMIT 1
        ");
        $stmt->execute([
            ':source_id'  => $sourceId,
            ':title_hash' => $titleHash,
            ':title'      => $title,
        ]);
        $item = $stmt->fetch();
        return $item ?: null;
    }
}
