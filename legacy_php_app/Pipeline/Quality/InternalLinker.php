<?php
/**
 * Contextual Internal Linking Engine
 * Automatically discovers and injects non-spammy, relevant internal links
 */

namespace App\Pipeline\Quality;

class InternalLinker {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public function injectLinks(string $contentHtml, string $authorityName, int $categoryId, ?int $excludeArticleId = null): array {
        // Find relevant articles
        $stmt = $this->db->prepare("
            SELECT id, title, slug, template_type 
            FROM articles 
            WHERE status IN ('published', 'updated') 
              AND (official_source_name = :org OR category_id = :cat_id)
              AND (:exclude_id IS NULL OR id != :exclude_id)
            ORDER BY published_at DESC 
            LIMIT 4
        ");
        $stmt->execute([
            ':org'        => $authorityName,
            ':cat_id'     => $categoryId,
            ':exclude_id' => $excludeArticleId,
        ]);
        $related = $stmt->fetchAll();

        if (empty($related)) {
            return [
                'content_html'   => $contentHtml,
                'internal_links' => [],
            ];
        }

        $linksList = [];
        $linksBoxHtml = "<div class='internal-links-box' style='background-color: #f8fafc; border: 1px solid #e2e8f0; border-left: 3px solid #2563eb; padding: 1rem; margin: 1.5rem 0; border-radius: 4px;'>";
        $linksBoxHtml .= "<h4 style='font-size: 0.875rem; font-weight: 700; color: #0a192f; margin-bottom: 0.5rem;'>📌 Related Official Updates & Notices</h4>";
        $linksBoxHtml .= "<ul style='list-style: none; padding-left: 0; margin-bottom: 0;'>";

        foreach ($related as $rel) {
            $url = '/news/' . htmlspecialchars($rel['slug']);
            $title = htmlspecialchars($rel['title']);
            $linksBoxHtml .= "<li style='margin-bottom: 0.4rem; font-size: 0.8125rem;'><a href='{$url}' style='color: #1e3a8a; font-weight: 600; text-decoration: underline;'>• {$title}</a></li>";
            $linksList[] = ['title' => $rel['title'], 'url' => $url, 'slug' => $rel['slug']];
        }

        $linksBoxHtml .= "</ul></div>";

        // Inject right before the FAQs or at the end
        if (str_contains($contentHtml, '<!-- Frequently Asked Questions -->')) {
            $newContent = str_replace('<!-- Frequently Asked Questions -->', $linksBoxHtml . "\n<!-- Frequently Asked Questions -->", $contentHtml);
        } else {
            $newContent = $contentHtml . "\n" . $linksBoxHtml;
        }

        return [
            'content_html'   => $newContent,
            'internal_links' => $linksList,
        ];
    }
}
