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
use App\Controllers\CronController;

$router = new Router();

// Portal Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/news/{slug}', [ArticleController::class, 'show']);
$router->get('/og-image/{slug}', [ArticleController::class, 'ogImage']);
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
$router->get('/news-sitemap.xml', [FeedController::class, 'newsSitemap']);
$router->get('/rss.xml', [FeedController::class, 'rss']);
$router->get('/feed', [FeedController::class, 'rss']);
$router->get('/robots.txt', [FeedController::class, 'robots']);
$router->get('/indexnow.txt', [FeedController::class, 'indexNowKey']);

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
$router->post('/admin/articles/ai-expand/{id}', [AdminController::class, 'aiExpandArticle']);
$router->get('/admin/sources', [AdminController::class, 'sources']);
$router->get('/admin/settings', [AdminController::class, 'settings']);
$router->post('/admin/settings', [AdminController::class, 'settings']);
$router->post('/admin/pipeline/start-all', [AdminController::class, 'startBackgroundAll']);
$router->post('/admin/pipeline/start-source/{id}', [AdminController::class, 'startBackgroundSource']);
$router->get('/admin/pipeline/status', [AdminController::class, 'getPipelineStatus']);
$router->post('/admin/pipeline/stop', [AdminController::class, 'stopPipeline']);
$router->post('/admin/pipeline/run', [AdminController::class, 'triggerScraper']);
$router->post('/admin/pipeline/run-source/{id}', [AdminController::class, 'triggerSource']);
$router->post('/admin/articles/reset-all', [AdminController::class, 'resetAllArticles']);
$router->post('/admin/social/test', [AdminController::class, 'testSocialBroadcast']);

// Admin Translator & Fact-Checker Studio
$router->get('/admin/translator', [AdminController::class, 'translatorStudio']);
$router->post('/admin/translator/test', [AdminController::class, 'testTranslation']);

// Multi-Language Switcher
$router->get('/set-language/{lang}', function($lang) {
    \App\Core\I18n::setLocale($lang);
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    // Remove old ?lang= from referer to avoid loop
    $referer = preg_replace('/([?&])lang=[a-z]{2}/i', '', $referer);
    header('Location: ' . $referer);
    exit;
});

// Automated Cron Webhook (CLI, cPanel Cron, or Cron-Job.org)
$router->get('/api/cron/run', [CronController::class, 'run']);
$router->post('/api/cron/run', [CronController::class, 'run']);
$router->get('/cron/run', [CronController::class, 'run']);
$router->post('/cron/run', [CronController::class, 'run']);

return $router;
