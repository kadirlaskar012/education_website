<?php
/**
 * Category Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Article;

class CategoryController extends Controller {
    public function show(string $slug): void {
        $categoryModel = new Category();
        $category = $categoryModel->findBySlug($slug);

        if (!$category) {
            http_response_code(404);
            $this->render('portal/404', ['page_title' => 'Category Not Found — EduGov News']);
            return;
        }

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $articleModel = new Article();
        $articles = $articleModel->getByCategory($category['id'], $limit, $offset);
        $total = $articleModel->countByCategory($category['id']);
        $totalPages = ceil($total / $limit);

        $this->render('portal/category', [
            'page_title'   => $category['name'] . ' — EduGov News',
            'category'     => $category,
            'articles'     => $articles,
            'current_page' => $page,
            'total_pages'  => $totalPages,
            'total_items'  => $total,
        ]);
    }
}
