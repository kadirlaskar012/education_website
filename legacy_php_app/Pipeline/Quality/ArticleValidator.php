<?php
/**
 * 10-Point Pre-Publish Quality Validator
 * Validates article before publishing. If any critical rule fails, flags as 'review' or 'error'.
 */

namespace App\Pipeline\Quality;

class ArticleValidator {
    public static function validate(array $articleData, int $minScore = 80): array {
        $errors = [];
        $warnings = [];
        $score = 100;

        // 1. Title Validation
        $title = trim($articleData['title'] ?? '');
        if (empty($title) || mb_strlen($title) < 10) {
            $errors[] = 'Title is missing or too short (must be >= 10 characters).';
            $score -= 30;
        }

        // 2. Content HTML Validation
        $content = trim($articleData['content_html'] ?? '');
        if (empty($content) || mb_strlen($content) < 200) {
            $errors[] = 'Content is missing or thin (< 200 characters).';
            $score -= 40;
        }

        // 3. Category Validation
        if (empty($articleData['category_id']) || (int)$articleData['category_id'] <= 0) {
            $errors[] = 'Valid category ID is required.';
            $score -= 20;
        }

        // 4. Source URL Validation
        $sourceUrl = trim($articleData['official_source_url'] ?? '');
        if (empty($sourceUrl) || !filter_var($sourceUrl, FILTER_VALIDATE_URL)) {
            $errors[] = 'Valid official source URL is missing.';
            $score -= 25;
        }

        // 5. Placeholder & Spin Detection
        $placeholders = ['lorem ipsum', '[insert ', 'todo', 'sample text', 'replace this', 'as an ai', 'test test'];
        $lowContent = strtolower($content . ' ' . $title);
        foreach ($placeholders as $ph) {
            if (str_contains($lowContent, $ph)) {
                $errors[] = "Prohibited placeholder text detected: '{$ph}'.";
                $score -= 35;
                break;
            }
        }

        // 6. Mandatory Tables & Sections Check
        if (!str_contains($content, 'table') || !str_contains($content, 'Important')) {
            $warnings[] = 'Structured tables missing from article content.';
            $score -= 15;
        }

        // 7. SEO Title & Description Validation
        if (empty($articleData['seo_title'])) {
            $warnings[] = 'SEO title is missing.';
            $score -= 10;
        }
        if (empty($articleData['meta_description']) || mb_strlen($articleData['meta_description']) < 40) {
            $warnings[] = 'Meta description is missing or too short.';
            $score -= 10;
        }

        // 8. Canonical URL Validation
        if (empty($articleData['canonical_url']) || !filter_var($articleData['canonical_url'], FILTER_VALIDATE_URL)) {
            $warnings[] = 'Canonical URL is invalid.';
            $score -= 10;
        }

        $finalScore = max(0, $score);
        $passed = empty($errors) && ($finalScore >= $minScore);

        $status = 'published';
        if (!empty($errors)) {
            $status = 'error';
        } elseif (!$passed) {
            $status = 'review';
        }

        return [
            'passed'           => $passed,
            'score'            => $finalScore,
            'status'           => $status,
            'errors'           => $errors,
            'warnings'         => $warnings,
            'validation_notes' => implode(' | ', array_merge($errors, $warnings)),
        ];
    }
}
