<?php
/**
 * Clean URL Routes Mapping
 */

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\StateController;
use App\Controllers\SearchController;
use App\Controllers\FeedController;
use App\Controllers\LegalController;
use App\Controllers\AdminController;

$router = new Router();

// Portal Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/news/{slug}', [ArticleController::class, 'show']);
$router->get('/category/{slug}', [CategoryController::class, 'show']);
$router->get('/state/{slug}', [StateController::class, 'show']);
$router->get('/search', [SearchController::class, 'index']);

// Dedicated Category Hubs (Direct Aliases)
$router->get('/results', function() {
    (new CategoryController())->show('results');
});
$router->get('/admit-card', function() {
    (new CategoryController())->show('admit-card');
});
$router->get('/recruitment', function() {
    (new CategoryController())->show('recruitment');
});
$router->get('/exam', function() {
    (new CategoryController())->show('exam');
});
$router->get('/answer-key', function() {
    (new CategoryController())->show('answer-key');
});

// Feeds & SEO
$router->get('/sitemap.xml', [FeedController::class, 'sitemap']);
$router->get('/rss.xml', [FeedController::class, 'rss']);
$router->get('/feed', [FeedController::class, 'rss']);
$router->get('/robots.txt', [FeedController::class, 'robots']);

// Legal Pages
$router->get('/about', [LegalController::class, 'about']);
$router->get('/contact', [LegalController::class, 'contact']);
$router->get('/privacy-policy', [LegalController::class, 'privacy']);
$router->get('/terms-and-conditions', [LegalController::class, 'terms']);
$router->get('/disclaimer', [LegalController::class, 'disclaimer']);
$router->get('/copyright-policy', [LegalController::class, 'copyright']);

// Admin Control Center Routes
$router->get('/admin', [AdminController::class, 'dashboard']);
$router->get('/admin/login', [AdminController::class, 'login']);
$router->post('/admin/login', [AdminController::class, 'login']);
$router->get('/admin/logout', [AdminController::class, 'logout']);
$router->get('/admin/articles', [AdminController::class, 'articles']);
$router->post('/admin/articles/bulk', [AdminController::class, 'bulkArticles']);
$router->get('/admin/articles/edit/{id}', [AdminController::class, 'editArticle']);
$router->post('/admin/articles/edit/{id}', [AdminController::class, 'editArticle']);
$router->get('/admin/sources', [AdminController::class, 'sources']);
$router->get('/admin/settings', [AdminController::class, 'settings']);
$router->post('/admin/settings', [AdminController::class, 'settings']);
$router->post('/admin/pipeline/run', [AdminController::class, 'triggerScraper']);

return $router;
