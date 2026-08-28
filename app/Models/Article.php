<?php
/**
 * Article Model
 */

namespace App\Models;

class Article {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function getLatestArticles(int $limit = 10, int $offset = 0): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, s.authority_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            LEFT JOIN source_items si ON a.source_item_id = si.id
            LEFT JOIN sources s ON si.source_id = s.id
            WHERE a.status IN ('published', 'updated')
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFeaturedArticles(int $limit = 5): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, s.authority_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            LEFT JOIN source_items si ON a.source_item_id = si.id
            LEFT JOIN sources s ON si.source_id = s.id
            WHERE a.status IN ('published', 'updated') AND a.is_featured = 1
            ORDER BY a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBreakingArticles(int $limit = 6): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status IN ('published', 'updated') AND a.is_breaking = 1
            ORDER BY a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTrendingArticles(int $limit = 5): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status IN ('published', 'updated')
            ORDER BY a.views_count DESC, a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCategory(int $categoryId, int $limit = 15, int $offset = 0): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, s.authority_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            LEFT JOIN source_items si ON a.source_item_id = si.id
            LEFT JOIN sources s ON si.source_id = s.id
            WHERE a.status IN ('published', 'updated') AND a.category_id = :cat_id
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':cat_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByCategory(int $categoryId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM articles WHERE status IN ('published', 'updated') AND category_id = :cat_id");
        $stmt->execute([':cat_id' => $categoryId]);
        return (int)$stmt->fetchColumn();
    }

    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, s.authority_name, s.base_url as source_domain
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            LEFT JOIN source_items si ON a.source_item_id = si.id
            LEFT JOIN sources s ON si.source_id = s.id
            WHERE a.slug = :slug
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        $article = $stmt->fetch();
        return $article ?: null;
    }

    public function incrementViews(int $id): void {
        $stmt = $this->db->prepare("UPDATE articles SET views_count = views_count + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function getRelatedArticles(int $categoryId, int $excludeId, int $limit = 4): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status IN ('published', 'updated') AND a.category_id = :cat_id AND a.id != :exclude_id
            ORDER BY a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':cat_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', $excludeId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search(string $query, int $limit = 20, int $offset = 0): array {
        $term = '%' . trim($query) . '%';
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, s.authority_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            LEFT JOIN source_items si ON a.source_item_id = si.id
            LEFT JOIN sources s ON si.source_id = s.id
            WHERE a.status IN ('published', 'updated') AND (a.title LIKE :q1 OR a.summary LIKE :q2 OR a.excerpt LIKE :q3)
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':q1', $term);
        $stmt->bindValue(':q2', $term);
        $stmt->bindValue(':q3', $term);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countSearch(string $query): int {
        $term = '%' . trim($query) . '%';
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM articles 
            WHERE status IN ('published', 'updated') AND (title LIKE :q1 OR summary LIKE :q2 OR excerpt LIKE :q3)
        ");
        $stmt->execute([':q1' => $term, ':q2' => $term, ':q3' => $term]);
        return (int)$stmt->fetchColumn();
    }

    public function getAdminStats(): array {
        $total = (int)$this->db->query("SELECT COUNT(*) FROM articles")->fetchColumn();
        $published = (int)$this->db->query("SELECT COUNT(*) FROM articles WHERE status IN ('published', 'updated')")->fetchColumn();
        $review = (int)$this->db->query("SELECT COUNT(*) FROM articles WHERE status = 'review'")->fetchColumn();
        $draft = (int)$this->db->query("SELECT COUNT(*) FROM articles WHERE status = 'draft'")->fetchColumn();
        $duplicates = (int)$this->db->query("SELECT COUNT(*) FROM source_items WHERE status = 'duplicate'")->fetchColumn();
        $sources = (int)$this->db->query("SELECT COUNT(*) FROM sources WHERE is_active = 1")->fetchColumn();

        return [
            'total' => $total,
            'published' => $published,
            'review' => $review,
            'draft' => $draft,
            'duplicates' => $duplicates,
            'sources' => $sources,
        ];
    }
}
