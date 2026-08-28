<?php
/**
 * Portal Homepage Controller
 * Delivers category-wise grid blocks, top 5 trending hero showcase, and breaking alerts
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller {
    public function index(): void {
        $articleModel = new Article();
        $categoryModel = new Category();

        // Top 5 Latest / Trending updates for the dynamic Hero Showcase
        $top5Articles = $articleModel->getTrendingArticles(5);
        if (count($top5Articles) < 5) {
            $top5Articles = $articleModel->getLatestPublished(5);
        }
        $heroMain = $top5Articles[0] ?? null;
        $heroTrending = array_slice($top5Articles, 1, 4);

        $latestArticles = $articleModel->getLatestPublished(12);

        // Fetch category-specific blocks for structured homepage layout
        $resultsArticles = $articleModel->getByCategorySlug('results', 6);
        $admitCardArticles = $articleModel->getByCategorySlug('admit-card', 6);
        $recruitmentArticles = $articleModel->getByCategorySlug('recruitment', 6);
        $examArticles = $articleModel->getByCategorySlug('exam', 6);
        $admissionArticles = $articleModel->getByCategorySlug('admission', 6);

        $categories = $categoryModel->getActiveCategories();

        $this->render('portal/home', [
            'page_title'           => 'EduGov News — Instant & Verified Official Education & Job Notifications',
            'meta_description'     => 'Real-time verified government notifications, exam dates, admit cards, results, and job vacancy alerts across India.',
            'hero_main'            => $heroMain,
            'hero_trending'        => $heroTrending,
            'top5_articles'        => $top5Articles,
            'latest_articles'      => $latestArticles,
            'results_articles'     => $resultsArticles,
            'admit_articles'       => $admitCardArticles,
            'recruitment_articles' => $recruitmentArticles,
            'exam_articles'        => $examArticles,
            'admission_articles'   => $admissionArticles,
            'categories'           => $categories,
        ]);
    }
}
