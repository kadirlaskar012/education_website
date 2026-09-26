<?php
session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['role'] = 'admin';

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Controllers/AdminController.php';
require_once __DIR__ . '/../app/Services/TranslationService.php';
require_once __DIR__ . '/../app/Pipeline/AI/GeminiClient.php';
require_once __DIR__ . '/../app/Models/SiteSetting.php';

$db = Database::getConnection();
$article = $db->query("SELECT id, slug, title FROM articles WHERE slug LIKE '%scholarship%' LIMIT 1")->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    echo "Article not found\n";
    exit;
}

echo "Found Article ID: " . $article['id'] . " - " . $article['title'] . "\n";

// Let's test the AI expansion on this article
$_SESSION['admin_logged_in'] = true;
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['role'] = 'admin';
$_POST['csrf_token'] = \App\Core\Auth::csrfToken();

$admin = new \App\Controllers\AdminController();

// We will capture JSON output
ob_start();
try {
    $admin->aiExpandArticle((string)$article['id']);
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
$output = ob_get_clean();

echo "Result:\n" . $output . "\n";
