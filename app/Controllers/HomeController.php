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

        $latest10Notices = $articleModel->getLatestPublished(10);
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
            'top10_notices'        => $latest10Notices,
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
