<?php
/**
 * Source Model
 */

namespace App\Models;

class Source {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function getActiveSources(): array {
        $stmt = $this->db->query("SELECT * FROM sources WHERE is_active = 1 ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM sources WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $s = $stmt->fetch();
        return $s ?: null;
    }

    public function updateLastFetched(int $id): void {
        $stmt = $this->db->prepare("UPDATE sources SET last_fetched_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
