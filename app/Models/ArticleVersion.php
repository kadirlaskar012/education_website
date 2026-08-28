<?php
/**
 * ArticleVersion Model
 */

namespace App\Models;

class ArticleVersion {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function getVersionsForArticle(int $articleId): array {
        $stmt = $this->db->prepare("SELECT * FROM article_versions WHERE article_id = :article_id ORDER BY version_number DESC");
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll();
    }
}
