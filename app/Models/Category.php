<?php
/**
 * Category Model
 */

namespace App\Models;

class Category {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function getActiveCategories(): array {
        $stmt = $this->db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY display_order ASC, name ASC");
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = :slug AND is_active = 1 LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        $cat = $stmt->fetch();
        return $cat ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $cat = $stmt->fetch();
        return $cat ?: null;
    }
}
