<?php
/**
 * Update Detector & Version Snapshot Manager
 * Preserves canonical article URLs when government notices update
 */

namespace App\Pipeline\Services;

class UpdateDetector {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function updateArticle(int $articleId, array $articleData, array $facts): void {
        // 1. Fetch current article snapshot
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $articleId]);
        $current = $stmt->fetch();

        if (!$current) {
            return;
        }

        // 2. Save previous version in article_versions
        $versionStmt = $this->db->prepare("
            INSERT INTO article_versions (article_id, version_number, change_summary, title, content_snapshot, structured_snapshot, created_at)
            VALUES (:article_id, :version_number, :change_summary, :title, :content_snapshot, :structured_snapshot, CURRENT_TIMESTAMP)
        ");
        $versionStmt->execute([
            ':article_id'          => $articleId,
            ':version_number'      => (int)$current['version_number'],
            ':change_summary'      => 'Official government notice updated by ' . ($facts['organization'] ?? 'Authority'),
            ':title'               => $current['title'],
            ':content_snapshot'    => $current['content_html'],
            ':structured_snapshot' => $current['structured_data'],
        ]);

        // 3. Update existing article while PRESERVING slug and id
        $updateStmt = $this->db->prepare("
            UPDATE articles SET
                title               = :title,
                summary             = :summary,
                excerpt             = :excerpt,
                content_html        = :content_html,
                structured_data     = :structured_data,
                schema_json         = :schema_json,
                internal_links_json = :internal_links_json,
                status              = 'updated',
                version_number      = version_number + 1,
                quality_score       = :quality_score,
                validation_notes    = :validation_notes,
                updated_at          = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
        $updateStmt->execute([
            ':title'               => $articleData['title'],
            ':summary'             => $articleData['summary'],
            ':excerpt'             => $articleData['excerpt'],
            ':content_html'        => $articleData['content_html'],
            ':structured_data'     => $articleData['structured_data'],
            ':schema_json'         => $articleData['schema_json'],
            ':internal_links_json' => json_encode($articleData['internal_links'] ?? []),
            ':quality_score'       => $articleData['quality_score'] ?? 100,
            ':validation_notes'    => $articleData['validation_notes'] ?? 'Updated with latest government data',
            ':id'                  => $articleId,
        ]);
    }
}
