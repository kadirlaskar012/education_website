<?php
/**
 * Article Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

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
        $articleModel->incrementViews($article['id']);
        $relatedArticles = $articleModel->getRelatedArticles($article['category_id'], $article['id'], 4);
        $structuredData = json_decode($article['structured_data'] ?? '{}', true);

        $this->render('portal/article_detail', [
            'page_title'       => $article['title'] . ' — EduGov News',
            'article'          => $article,
            'structured_data'  => $structuredData,
            'related_articles' => $relatedArticles,
        ]);
    }
}
