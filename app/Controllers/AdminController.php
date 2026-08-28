<?php
/**
 * Secure Admin Control Center Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Article;
use App\Models\Source;
use App\Models\Category;
use App\Pipeline\Services\PipelineRunner;

class AdminController extends Controller {
    public function dashboard(): void {
        Auth::requireAuth();
        $articleModel = new Article();
        $stats = $articleModel->getAdminStats();
        $recentArticles = $articleModel->getLatestArticles(10);

        $this->render('admin/dashboard', [
            'page_title'      => 'Control Center Dashboard — EduGov Administration',
            'stats'           => $stats,
            'recent_articles' => $recentArticles,
            'user'            => Auth::user(),
        ], 'admin');
    }

    public function login(): void {
        if (Auth::check()) {
            $this->redirect('/admin');
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (Auth::attempt($username, $password)) {
                $this->redirect('/admin');
            } else {
                $error = 'Invalid username or password. Please try again.';
            }
        }

        $this->render('admin/login', [
            'page_title' => 'Log in | EduGov News Control Center',
            'error'      => $error,
        ], 'admin');
    }

    public function logout(): void {
        Auth::logout();
        $this->redirect('/admin/login');
    }

    public function articles(): void {
        Auth::requireAuth();
        $articleModel = new Article();
        $articles = $articleModel->getLatestArticles(50);

        $this->render('admin/articles', [
            'page_title' => 'Articles & Notifications — EduGov Admin',
            'articles'   => $articles,
        ], 'admin');
    }

    public function editArticle(string $id): void {
        Auth::requireAuth();
        $db = \Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM articles WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => (int)$id]);
        $article = $stmt->fetch();

        if (!$article) {
            $this->redirect('/admin/articles');
        }

        $message = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $status = trim($_POST['status'] ?? 'published');
            $summary = trim($_POST['summary'] ?? '');
            $contentHtml = $_POST['content_html'] ?? '';

            $update = $db->prepare("
                UPDATE articles SET
                    title = :title,
                    status = :status,
                    summary = :summary,
                    content_html = :content_html,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            $update->execute([
                ':title'        => $title,
                ':status'       => $status,
                ':summary'      => $summary,
                ':content_html' => $contentHtml,
                ':id'           => (int)$id,
            ]);

            $message = 'Article updated successfully!';
            $stmt->execute([':id' => (int)$id]);
            $article = $stmt->fetch();
        }

        $this->render('admin/article_edit', [
            'page_title' => 'Edit Article — ' . htmlspecialchars($article['title']),
            'article'    => $article,
            'message'    => $message,
        ], 'admin');
    }

    public function sources(): void {
        Auth::requireAuth();
        $sourceModel = new Source();
        $sources = $sourceModel->getActiveSources();

        $this->render('admin/sources', [
            'page_title' => 'Scraper Sources & Adapters — EduGov Admin',
            'sources'    => $sources,
        ], 'admin');
    }

    public function triggerScraper(): void {
        Auth::requireAuth();
        $runner = new PipelineRunner();
        $stats = $runner->runAll();

        $this->json([
            'success' => true,
            'message' => 'Scraper pipeline executed successfully!',
            'stats'   => $stats,
        ]);
    }
}
