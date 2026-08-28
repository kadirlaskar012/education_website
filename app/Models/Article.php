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

    public function getFeaturedBreaking(): ?array {
        $stmt = $this->db->query("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND a.is_breaking = 1
            ORDER BY a.published_at DESC
            LIMIT 1
        ");
        $art = $stmt->fetch();
        return $art ?: ($this->getLatestPublished(1)[0] ?? null);
    }

    public function getBreakingArticles(int $limit = 8): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.is_breaking DESC, a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTrendingArticles(int $limit = 5): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.views_count DESC, a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function incrementViews(int $id): void {
        $stmt = $this->db->prepare("UPDATE articles SET views_count = views_count + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function getLatestPublished(int $limit = 10, int $offset = 0): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCategory(int $categoryId, int $limit = 15, int $offset = 0, ?string $stateCode = null): array {
        $sql = "
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND a.category_id = :cat_id
        ";
        if (!empty($stateCode) && $stateCode !== 'ALL') {
            $sql .= " AND a.state_code = :state_code";
        }
        $sql .= " ORDER BY a.published_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cat_id', $categoryId, \PDO::PARAM_INT);
        if (!empty($stateCode) && $stateCode !== 'ALL') {
            $stmt->bindValue(':state_code', $stateCode, \PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByCategory(int $categoryId, ?string $stateCode = null): int {
        $sql = "SELECT COUNT(*) FROM articles WHERE status = 'published' AND category_id = :cat_id";
        if (!empty($stateCode) && $stateCode !== 'ALL') {
            $sql .= " AND state_code = :state_code";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cat_id', $categoryId, \PDO::PARAM_INT);
        if (!empty($stateCode) && $stateCode !== 'ALL') {
            $stmt->bindValue(':state_code', $stateCode, \PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get distinct states that have active published posts in this category
     */
    public function getDistinctStatesByCategory(int $categoryId): array {
        $stmt = $this->db->prepare("
            SELECT state_code, state_name, COUNT(*) as count
            FROM articles
            WHERE category_id = :cat_id AND status = 'published' AND state_code IS NOT NULL AND state_code != ''
            GROUP BY state_code, state_name
            ORDER BY count DESC, state_name ASC
        ");
        $stmt->execute([':cat_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function search(string $query, int $limit = 20, int $offset = 0): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND (a.title LIKE :q OR a.official_source_name LIKE :q OR a.summary LIKE :q)
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':q', '%' . $query . '%', \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countSearch(string $query): int {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM articles
            WHERE status = 'published' AND (title LIKE :q OR official_source_name LIKE :q OR summary LIKE :q)
        ");
        $stmt->bindValue(':q', '%' . $query . '%', \PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getByCategorySlug(string $slug, int $limit = 6): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND c.slug = :slug
            ORDER BY a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':slug', $slug, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByStateCode(string $stateCode, int $limit = 20): array {
        $isAll = ($stateCode === 'ALL' || empty($stateCode)) ? 1 : 0;
        $sql = "
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
        ";
        if (!$isAll) {
            $sql .= " AND a.state_code = :state_code";
        }
        $sql .= " ORDER BY a.published_at DESC LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        if (!$isAll) {
            $stmt->bindValue(':state_code', $stateCode, \PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.slug = :slug
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        $art = $stmt->fetch();
        return $art ?: null;
    }

    public function getRelatedArticles(int $categoryId, int $excludeId, int $limit = 4): array {
        return $this->getRelated($categoryId, $excludeId, $limit);
    }

    public function getRelated(int $categoryId, int $excludeId, int $limit = 4): array {
        $stmt = $this->db->prepare("
            SELECT id, title, slug, published_at, template_type
            FROM articles
            WHERE status = 'published' AND category_id = :cat_id AND id != :exclude_id
            ORDER BY published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':cat_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', $excludeId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFilteredAdminArticles(array $filters = [], int $limit = 100): array {
        $sql = "SELECT a.*, c.name as category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND a.category_id = :category_id";
            $params[':category_id'] = (int)$filters['category_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (a.title LIKE :search OR a.official_source_name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['min_score'])) {
            $sql .= " AND a.quality_score >= :min_score";
            $params[':min_score'] = (int)$filters['min_score'];
        }

        $sql .= " ORDER BY a.published_at DESC LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Bulk update status of articles
     */
    public function bulkUpdateStatus(array $ids, string $status): int {
        if (empty($ids)) return 0;
        $validStatuses = ['published', 'draft', 'in_review'];
        if (!in_array($status, $validStatuses)) return 0;

        $inClause = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("UPDATE articles SET status = ? WHERE id IN ($inClause)");
        $stmt->execute(array_merge([$status], $ids));
        return $stmt->rowCount();
    }

    /**
     * Bulk delete articles
     */
    public function bulkDelete(array $ids): int {
        if (empty($ids)) return 0;
        $inClause = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id IN ($inClause)");
        $stmt->execute($ids);
        return $stmt->rowCount();
    }

    public function getAdminStats(): array {
        $stats = [
            'total'      => 0,
            'published'  => 0,
            'review'     => 0,
            'draft'      => 0,
            'duplicates' => 0,
            'sources'    => 0,
        ];

        $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM articles GROUP BY status");
        while ($row = $stmt->fetch()) {
            $s = $row['status'];
            if (isset($stats[$s])) {
                $stats[$s] = (int)$row['count'];
            }
        }

        $stats['total'] = (int)$this->db->query("SELECT COUNT(*) FROM articles")->fetchColumn();
        $stats['duplicates'] = (int)$this->db->query("SELECT COUNT(*) FROM source_items WHERE status = 'duplicate'")->fetchColumn();
        $stats['sources'] = (int)$this->db->query("SELECT COUNT(*) FROM sources WHERE is_active = 1")->fetchColumn();

        return $stats;
    }

    public function getLatestArticles(int $limit = 50): array {
        return $this->getFilteredAdminArticles([], $limit);
    }
}
