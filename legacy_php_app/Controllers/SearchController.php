<?php
/**
 * Search Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class SearchController extends Controller {
    public function index(): void {
        $q = trim($_GET['q'] ?? '');
        $articles = [];
        $total = 0;

        if ($q !== '') {
            $articleModel = new Article();
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 20;
            $offset = ($page - 1) * $limit;

            $articles = $articleModel->search($q, $limit, $offset);
            $total = $articleModel->countSearch($q);
        }

        $this->render('portal/search', [
            'page_title' => ($q !== '' ? 'Search: ' . htmlspecialchars($q) : 'Search Education News') . ' — EduGov News',
            'query'      => $q,
            'articles'   => $articles,
            'total_items'=> $total,
        ]);
    }
}
