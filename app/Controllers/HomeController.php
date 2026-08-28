<?php
/**
 * Home Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class HomeController extends Controller {
    public function index(): void {
        $articleModel = new Article();
        $latestArticles = $articleModel->getLatestArticles(12);
        $featuredArticles = $articleModel->getFeaturedArticles(1);
        $featured = !empty($featuredArticles) ? $featuredArticles[0] : (!empty($latestArticles) ? $latestArticles[0] : null);

        $this->render('portal/home', [
            'page_title'        => 'EduGov News — Instant & Verified Official Education Updates, Results, Admit Cards & Jobs',
            'latest_articles'   => $latestArticles,
            'featured_article'  => $featured,
        ]);
    }

    public function notFound(): void {
        $this->render('portal/404', [
            'page_title' => 'Page Not Found — EduGov News',
        ]);
    }
}
