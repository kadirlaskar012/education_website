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
        return $art ? self::localize($art) : ($this->getLatestPublished(1)[0] ?? null);
    }

    public static function localize(array $article, ?string $locale = null): array {
        $locale = $locale ?: \App\Core\I18n::getLocale();
        if ($locale === 'en' || empty($article)) {
            return $article;
        }

        $struct = is_array($article['structured_data'] ?? null) 
            ? $article['structured_data'] 
            : (json_decode($article['structured_data'] ?? '{}', true) ?: []);

        // 1. If translation is already saved in DB
        if (!empty($struct['translations'][$locale]['title'])) {
            $trans = $struct['translations'][$locale];
            if (!empty($trans['title'])) $article['title'] = $trans['title'];
            if (!empty($trans['summary'])) {
                $article['summary'] = $trans['summary'];
                $article['excerpt'] = $trans['summary'];
            }
            return $article;
        }

        // 2. On-the-fly Indic synthesis fallback for instant 100% localization
        try {
            $transService = new \App\Services\TranslationService();
            $rawText = ($article['title'] ?? '') . "\n" . ($article['summary'] ?? $article['excerpt'] ?? '');
            $synth = $transService->synthesizeIndicNotice($rawText, $locale);
            
            $article['title'] = $synth['title'];
            if (!empty($synth['summary'])) {
                $article['summary'] = $synth['summary'];
                $article['excerpt'] = $synth['summary'];
            }
            
            // Cache in structured_data for this article
            $struct['translations'][$locale] = [
                'title'   => $synth['title'],
                'summary' => $synth['summary'],
                'content' => $synth['content'] ?? '',
                'score'   => 85,
            ];
            
            // Auto-persist cache to database if article has valid ID
            if (!empty($article['id'])) {
                $db = \Database::getConnection();
                $stmt = $db->prepare("UPDATE articles SET structured_data = :data WHERE id = :id");
                $stmt->execute([
                    ':data' => json_encode($struct, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ':id'   => (int)$article['id']
                ]);
            }
        } catch (\Throwable $e) {
            // gracefully continue
        }

        return $article;
    }

    public static function localizeList(array $articles, ?string $locale = null): array {
        return array_map(fn($a) => self::localize($a, $locale), $articles);
    }

    public function saveStructuredData(int $id, array $data): void {
        $stmt = $this->db->prepare("UPDATE articles SET structured_data = :data WHERE id = :id");
        $stmt->execute([
            ':data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ':id'   => $id
        ]);
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
        return self::localizeList($stmt->fetchAll());
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
        return self::localizeList($stmt->fetchAll());
    }

    public function incrementViews(int $id): void {
        $stmt = $this->db->prepare("UPDATE articles SET views_count = views_count + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function getLatest(int $limit = 10): array {
        return $this->getLatestPublished($limit);
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
        return self::localizeList($stmt->fetchAll());
    }

    /**
     * Get recent published articles for Google News Sitemap (last 48 hours or latest updates)
     */
    public function getGoogleNewsArticles(int $limit = 100): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
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
        return self::localizeList($stmt->fetchAll());
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
            WHERE a.status = 'published' AND (
                a.title LIKE :q 
                OR a.official_source_name LIKE :q 
                OR a.summary LIKE :q
                OR a.content_html LIKE :q
                OR a.state_name LIKE :q
                OR a.structured_data LIKE :q
                OR c.name LIKE :q
            )
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':q', '%' . $query . '%', \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return self::localizeList($stmt->fetchAll());
    }

    public function countSearch(string $query): int {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND (
                a.title LIKE :q 
                OR a.official_source_name LIKE :q 
                OR a.summary LIKE :q
                OR a.content_html LIKE :q
                OR a.state_name LIKE :q
                OR a.structured_data LIKE :q
                OR c.name LIKE :q
            )
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
            WHERE c.slug = :slug AND a.status = 'published'
            ORDER BY a.published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':slug', $slug, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return self::localizeList($stmt->fetchAll());
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
        return self::localizeList($stmt->fetchAll());
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
            SELECT id, title, slug, published_at, template_type, structured_data
            FROM articles
            WHERE status = 'published' AND category_id = :cat_id AND id != :exclude_id
            ORDER BY published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':cat_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', $excludeId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return self::localizeList($stmt->fetchAll());
    }

    public function getRelatedBySource(string $authorityName, int $excludeId, int $limit = 3): array {
        if (empty($authorityName)) return [];
        $stmt = $this->db->prepare("
            SELECT id, title, slug, published_at, template_type, structured_data
            FROM articles
            WHERE status = 'published' AND official_source_name = :authority AND id != :exclude_id
            ORDER BY published_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':authority', $authorityName, \PDO::PARAM_STR);
        $stmt->bindValue(':exclude_id', $excludeId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return self::localizeList($stmt->fetchAll());
    }

    public function getAdjacentArticles(int $currentId): array {
        // Previous (older)
        $prevStmt = $this->db->prepare("
            SELECT id, title, slug, published_at, structured_data
            FROM articles
            WHERE status = 'published' AND id < :id
            ORDER BY id DESC
            LIMIT 1
        ");
        $prevStmt->execute([':id' => $currentId]);
        $prev = $prevStmt->fetch() ?: null;

        // Next (newer)
        $nextStmt = $this->db->prepare("
            SELECT id, title, slug, published_at, structured_data
            FROM articles
            WHERE status = 'published' AND id > :id
            ORDER BY id ASC
            LIMIT 1
        ");
        $nextStmt->execute([':id' => $currentId]);
        $next = $nextStmt->fetch() ?: null;

        return [
            'prev' => $prev ? self::localize($prev) : null,
            'next' => $next ? self::localize($next) : null,
        ];
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
