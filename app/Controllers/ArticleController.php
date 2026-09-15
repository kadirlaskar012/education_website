<?php
/**
 * Article Controller & Dynamic OpenGraph Social Image Generator
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Services\InternalLinker;

class ArticleController extends Controller {
    public function show(string $slug): void {
        $articleModel = new Article();
        $article = $articleModel->findBySlug($slug);

        if (!$article) {
            http_response_code(404);
            $this->render('portal/404', ['page_title' => 'Article Not Found — EduGov News']);
            return;
        }

        // Increment views
        $articleModel->incrementViews((int)$article['id']);
        $relatedArticles = $articleModel->getRelatedArticles((int)$article['category_id'], (int)$article['id'], 4);
        $relatedSourceArticles = $articleModel->getRelatedBySource($article['official_source_name'] ?? '', (int)$article['id'], 3);
        $adjacent = $articleModel->getAdjacentArticles((int)$article['id']);
        $structuredData = json_decode($article['structured_data'] ?? '{}', true);

        // 3. Multi-Language Content Selection
        $activeLocale = \App\Core\I18n::getLocale();
        if ($activeLocale !== 'en' && !empty($structuredData['translations'][$activeLocale])) {
            $trans = $structuredData['translations'][$activeLocale];
            if (!empty($trans['title'])) $article['title'] = $trans['title'];
            if (!empty($trans['summary'])) $article['summary'] = $trans['summary'];
            if (!empty($trans['content'])) {
                // Parse markdown to HTML if formatted in markdown
                $contentHtml = htmlspecialchars($trans['content']);
                $contentHtml = preg_replace('/### (.*?)\n/', '<h3 class="article-h3">$1</h3>', $contentHtml);
                $contentHtml = preg_replace('/## (.*?)\n/', '<h2 class="article-h2">$1</h2>', $contentHtml);
                $contentHtml = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $contentHtml);
                $contentHtml = preg_replace('/^\* (.*?)$/m', '<li>$1</li>', $contentHtml);
                $contentHtml = nl2br($contentHtml);
                $article['content_html'] = '<div class="translated-editorial-content">' . $contentHtml . '</div>';
            }
        }

        $this->render('portal/article_detail', [
            'page_title'             => $article['title'] . ' — EduGov News',
            'meta_description'       => $article['meta_description'] ?? $article['excerpt'],
            'canonical_url'          => 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost:8000') . '/news/' . $article['slug'],
            'article'                => $article,
            'structured_data'        => $structuredData,
            'related_articles'       => $relatedArticles,
            'related_source_articles'=> $relatedSourceArticles,
            'prev_article'           => $adjacent['prev'],
            'next_article'           => $adjacent['next'],
        ]);
    }

    /**
     * Dynamic 1200x630 OpenGraph & Twitter Social Share Image Generator
     */
    public function ogImage(string $slug): void {
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('Cache-Control: public, max-age=86400');

        $articleModel = new Article();
        $article = $articleModel->findBySlug($slug);

        $title = $article ? $article['title'] : 'EduGov News — Official Notification';
        $category = $article ? ($article['category_name'] ?? 'Official Notice') : 'Education Portal';
        $authority = $article ? ($article['official_source_name'] ?? 'Government Authority') : 'Government of India';
        $date = $article ? date('F j, Y', strtotime($article['published_at'])) : date('F j, Y');

        // Word-wrap title into max 3 lines
        $words = explode(' ', $title);
        $lines = [];
        $currentLine = '';
        foreach ($words as $w) {
            if (mb_strlen($currentLine . ' ' . $w) > 36) {
                $lines[] = trim($currentLine);
                $currentLine = $w;
                if (count($lines) >= 3) break;
            } else {
                $currentLine .= ' ' . $w;
            }
        }
        if (!empty($currentLine) && count($lines) < 3) {
            $lines[] = trim($currentLine);
        }
        if (count($lines) === 3 && count($words) > 14) {
            $lines[2] .= '...';
        }

        $line1 = htmlspecialchars($lines[0] ?? '', ENT_XML1, 'UTF-8');
        $line2 = htmlspecialchars($lines[1] ?? '', ENT_XML1, 'UTF-8');
        $line3 = htmlspecialchars($lines[2] ?? '', ENT_XML1, 'UTF-8');
        $catText = htmlspecialchars(strtoupper($category), ENT_XML1, 'UTF-8');
        $authText = htmlspecialchars($authority, ENT_XML1, 'UTF-8');
        $dateText = htmlspecialchars($date, ENT_XML1, 'UTF-8');

        echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#070d17" />
      <stop offset="60%" stop-color="#0f172a" />
      <stop offset="100%" stop-color="#1e1b4b" />
    </linearGradient>
    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
      <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1" />
    </pattern>
  </defs>

  <!-- Background -->
  <rect width="1200" height="630" fill="url(#bgGrad)" />
  <rect width="1200" height="630" fill="url(#grid)" />

  <!-- Top Brand Row -->
  <g transform="translate(80, 70)">
    <rect width="42" height="42" rx="8" fill="#2563eb" />
    <text x="13" y="29" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="22" font-weight="900" fill="#ffffff">E</text>
    <text x="58" y="30" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="26" font-weight="800" fill="#ffffff">EDUGOV <tspan fill="#38bdf8">NEWS</tspan></text>
    
    <!-- Category Badge -->
    <rect x="270" y="6" width="220" height="32" rx="16" fill="rgba(56, 189, 248, 0.15)" stroke="#38bdf8" stroke-width="1" />
    <text x="285" y="27" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="13" font-weight="700" fill="#38bdf8" letter-spacing="1">⚡ {$catText}</text>
  </g>

  <!-- Authority Tag -->
  <g transform="translate(80, 165)">
    <text x="0" y="0" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="18" font-weight="700" fill="#10b981" letter-spacing="0.5">🏛️ {$authText}</text>
  </g>

  <!-- Big Article Headline -->
  <g transform="translate(80, 235)">
    <text x="0" y="0" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="44" font-weight="800" fill="#f8fafc">
      <tspan x="0" dy="0">{$line1}</tspan>
      <tspan x="0" dy="58">{$line2}</tspan>
      <tspan x="0" dy="58">{$line3}</tspan>
    </text>
  </g>

  <!-- Bottom Details Bar -->
  <g transform="translate(80, 520)">
    <rect width="1040" height="2" fill="rgba(255, 255, 255, 0.1)" />
    <text x="0" y="38" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="16" font-weight="600" fill="#94a3b8">📅 {$dateText}  •  ✓ 100% Grounded Official Notice</text>
    <text x="1040" y="38" text-anchor="end" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="16" font-weight="700" fill="#38bdf8">Check Official PDF &amp; Apply Link →</text>
  </g>
</svg>
SVG;
        exit;
    }
}
