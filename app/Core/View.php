<?php
/**
 * View Rendering Engine with Layout support and Global Context
 */

namespace App\Core;

class View {
    public static function render(string $viewPath, array $data = [], string $layout = 'main'): void {
        // Extract variables for view
        extract($data);

        // Fetch global context (categories, site settings, breaking ticker)
        $globalContext = self::getGlobalContext();
        extract($globalContext, EXTR_SKIP);

        // Capture view content
        ob_start();
        $fullViewFile = __DIR__ . '/../Views/' . $viewPath . '.php';
        if (file_exists($fullViewFile)) {
            require $fullViewFile;
        } else {
            echo "<p>View not found: {$viewPath}</p>";
        }
        $content = ob_get_clean();

        // Render layout with view content
        if ($layout) {
            $layoutFile = __DIR__ . '/../Views/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    private static function getGlobalContext(): array {
        static $context = null;
        if ($context !== null) {
            return $context;
        }

        try {
            $categoryModel = new \App\Models\Category();
            $allCategories = $categoryModel->getActiveCategories();

            $primarySlugs = ['results', 'admit-card', 'recruitment', 'exam', 'answer-key', 'latest-news'];
            $primaryCategories = array_filter($allCategories, fn($c) => in_array($c['slug'], $primarySlugs));
            $moreCategories = array_filter($allCategories, fn($c) => !in_array($c['slug'], $primarySlugs));

            $articleModel = new \App\Models\Article();
            $breakingArticles = $articleModel->getBreakingArticles(6);
            if (empty($breakingArticles)) {
                $breakingArticles = $articleModel->getLatestArticles(3);
            }

            $trendingArticles = $articleModel->getTrendingArticles(5);

            $settingModel = new \App\Models\SiteSetting();
            $siteSettings = $settingModel->getSettings();

            $context = [
                'nav_categories'     => $allCategories,
                'primary_categories' => $primaryCategories,
                'more_categories'    => $moreCategories,
                'breaking_articles'  => $breakingArticles,
                'trending_articles'  => $trendingArticles,
                'site_settings'      => $siteSettings,
                'current_time'       => new \DateTime(),
                'current_year'       => date('Y'),
            ];
        } catch (\Exception $e) {
            $context = [
                'nav_categories'     => [],
                'primary_categories' => [],
                'more_categories'    => [],
                'breaking_articles'  => [],
                'trending_articles'  => [],
                'site_settings'      => null,
                'current_time'       => new \DateTime(),
                'current_year'       => date('Y'),
            ];
        }

        return $context;
    }
}
