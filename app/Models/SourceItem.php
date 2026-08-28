<?php
/**
 * SourceItem Model
 */

namespace App\Models;

class SourceItem {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function existsByHash(string $hash): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM source_items WHERE source_hash = :hash");
        $stmt->execute([':hash' => $hash]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM source_items WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }
}
