<?php
/**
 * Feed Controller (Sitemap XML, RSS 2.0 Feed, Robots.txt) & Legal Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Category;

class FeedController extends Controller {
    public function sitemap(): void {
        header('Content-Type: application/xml; charset=utf-8');
        $articleModel = new Article();
        $articles = $articleModel->getLatestArticles(500);

        $categoryModel = new Category();
        $categories = $categoryModel->getActiveCategories();

        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Static URLs
        echo "<url><loc>{$baseUrl}/</loc><changefreq>always</changefreq><priority>1.0</priority></url>";
        echo "<url><loc>{$baseUrl}/results</loc><changefreq>hourly</changefreq><priority>0.9</priority></url>";
        echo "<url><loc>{$baseUrl}/admit-card</loc><changefreq>hourly</changefreq><priority>0.9</priority></url>";
        echo "<url><loc>{$baseUrl}/recruitment</loc><changefreq>hourly</changefreq><priority>0.9</priority></url>";
        echo "<url><loc>{$baseUrl}/exam</loc><changefreq>hourly</changefreq><priority>0.9</priority></url>";
        echo "<url><loc>{$baseUrl}/answer-key</loc><changefreq>hourly</changefreq><priority>0.9</priority></url>";

        // Categories
        foreach ($categories as $cat) {
            echo "<url><loc>{$baseUrl}/category/" . htmlspecialchars($cat['slug']) . "</loc><changefreq>hourly</changefreq><priority>0.8</priority></url>";
        }

        // Articles
        foreach ($articles as $art) {
            $pub = date('c', strtotime($art['published_at']));
            echo "<url><loc>{$baseUrl}/news/" . htmlspecialchars($art['slug']) . "</loc><lastmod>{$pub}</lastmod><changefreq>daily</changefreq><priority>0.7</priority></url>";
        }

        echo '</urlset>';
        exit;
    }

    public function rss(): void {
        header('Content-Type: application/rss+xml; charset=utf-8');
        $articleModel = new Article();
        $articles = $articleModel->getLatestArticles(30);

        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
        echo '<channel>';
        echo '<title>EduGov News — Verified Official Education Updates</title>';
        echo "<link>{$baseUrl}/</link>";
        echo '<description>Real-time official education news, exam notifications, admit cards, and job results.</description>';
        echo '<language>en-us</language>';
        echo "<atom:link href=\"{$baseUrl}/rss.xml\" rel=\"self\" type=\"application/rss+xml\" />";

        foreach ($articles as $art) {
            $link = "{$baseUrl}/news/" . htmlspecialchars($art['slug']);
            $pub = date(DATE_RSS, strtotime($art['published_at']));
            echo '<item>';
            echo '<title>' . htmlspecialchars($art['title']) . '</title>';
            echo "<link>{$link}</link>";
            echo "<guid isPermaLink=\"true\">{$link}</guid>";
            echo '<pubDate>' . $pub . '</pubDate>';
            echo '<description>' . htmlspecialchars($art['excerpt']) . '</description>';
            echo '<category>' . htmlspecialchars($art['category_name']) . '</category>';
            echo '</item>';
        }

        echo '</channel>';
        echo '</rss>';
        exit;
    }

    /**
     * Google News XML Sitemap (Standards-Compliant for Google News & Discover indexing)
     */
    public function newsSitemap(): void {
        header('Content-Type: application/xml; charset=utf-8');
        $articleModel = new Article();
        $articles = $articleModel->getGoogleNewsArticles(100);

        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');
        $siteName = 'EduGov News';

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        echo '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

        foreach ($articles as $art) {
            $loc = htmlspecialchars($baseUrl . '/news/' . $art['slug']);
            $pubDate = date('c', strtotime($art['published_at']));
            $cleanTitle = htmlspecialchars($art['title'], ENT_XML1, 'UTF-8');

            echo "  <url>\n";
            echo "    <loc>{$loc}</loc>\n";
            echo "    <news:news>\n";
            echo "      <news:publication>\n";
            echo "        <news:name>" . htmlspecialchars($siteName, ENT_XML1, 'UTF-8') . "</news:name>\n";
            echo "        <news:language>en</news:language>\n";
            echo "      </news:publication>\n";
            echo "      <news:publication_date>{$pubDate}</news:publication_date>\n";
            echo "      <news:title>{$cleanTitle}</news:title>\n";
            echo "    </news:news>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
        exit;
    }

    public function indexNowKey(): void {
        header('Content-Type: text/plain; charset=utf-8');
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');
        $host = parse_url($baseUrl, PHP_URL_HOST) ?? 'localhost';
        $apiKey = md5($host . '-edugov-indexnow-key');
        echo $apiKey;
        exit;
    }

    public function robots(): void {
        header('Content-Type: text/plain; charset=utf-8');
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app_url'], '/');

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /search\n\n";
        echo "Sitemap: {$baseUrl}/sitemap.xml\n";
        echo "Sitemap: {$baseUrl}/news-sitemap.xml\n";
        exit;
    }
}

class LegalController extends Controller {
    public function about(): void {
        $this->render('portal/legal/about', ['page_title' => 'About Us — EduGov News']);
    }

    public function contact(): void {
        $this->render('portal/legal/contact', ['page_title' => 'Contact Editorial Team — EduGov News']);
    }

    public function privacy(): void {
        $this->render('portal/legal/privacy', ['page_title' => 'Privacy Policy — EduGov News']);
    }

    public function terms(): void {
        $this->render('portal/legal/terms', ['page_title' => 'Terms & Conditions — EduGov News']);
    }

    public function disclaimer(): void {
        $this->render('portal/legal/disclaimer', ['page_title' => 'Official Sources & Legal Disclaimer — EduGov News']);
    }

    public function copyright(): void {
        $this->render('portal/legal/copyright', ['page_title' => 'Copyright & Fair Use Policy — EduGov News']);
    }
}
