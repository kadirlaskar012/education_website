<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'EduGov News') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Verified Official Education News, Exam Dates, Admit Cards, Results, Answer Keys, and Recruitment Updates.') ?>">
    <link rel="canonical" href="<?= htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">

    <!-- Google Fonts: Inter & Merriweather -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Merriweather:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">

    <!-- CSS Design System -->
    <link rel="stylesheet" href="/static/css/main.css?v=2.0">
</head>
<body>

<header class="site-header">
    <!-- Top Utility Bar -->
    <div class="top-bar">
        <div class="container top-bar-inner">
            <div class="top-date">
                <span>🗓️ <?= date('l, F j, Y') ?></span>
                <?php if (!empty($site_settings['top_breaking_announcement'])): ?>
                <span class="top-announcement">📢 <?= htmlspecialchars($site_settings['top_breaking_announcement']) ?></span>
                <?php endif; ?>
            </div>
            <div class="top-links">
                <a href="/about">About</a>
                <a href="/contact">Contact</a>
                <a href="/disclaimer">Disclaimer</a>
                <a href="/rss.xml">RSS Feed</a>
            </div>
        </div>
    </div>

    <!-- Main Branding Header -->
    <div class="main-header">
        <div class="container header-branding-row">
            <!-- Mobile Menu Toggle Button -->
            <button id="mobileMenuBtn" class="mobile-drawer-toggle" aria-label="Open Navigation Drawer">
                <span>☰</span>
            </button>

            <!-- Site Logo & Tagline -->
            <div class="site-logo">
                <a href="/" class="brand-name">
                    <?= htmlspecialchars($site_settings['site_name'] ?? 'EduGov News') ?><span>.</span>
                </a>
                <div class="brand-tagline">
                    <?= htmlspecialchars($site_settings['site_tagline'] ?? 'Verified Official Education Updates & Notifications') ?>
                </div>
            </div>

            <!-- Desktop Search Bar -->
            <form action="/search" method="get" class="header-search-box">
                <div class="search-input-group">
                    <input type="text" name="q" placeholder="Search exams, results, admit cards..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" aria-label="Search">
                    <button type="submit" aria-label="Submit Search">🔍</button>
                </div>
            </form>

            <!-- Mobile Search Icon Button -->
            <button id="mobileSearchTrigger" class="mobile-search-btn" aria-label="Open Search">
                🔍
            </button>
        </div>
    </div>

    <!-- Desktop Navigation Bar (Primary Hubs + "More Categories ▾" Dropdown) -->
    <nav class="desktop-nav-bar" aria-label="Desktop Navigation">
        <div class="container nav-container">
            <ul class="desktop-nav-links">
                <?php $currUri = $_SERVER['REQUEST_URI']; ?>
                <li><a href="/" class="<?= $currUri === '/' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/results" class="<?= str_contains($currUri, 'results') ? 'active' : '' ?>">Results</a></li>
                <li><a href="/admit-card" class="<?= str_contains($currUri, 'admit-card') ? 'active' : '' ?>">Admit Cards</a></li>
                <li><a href="/recruitment" class="<?= str_contains($currUri, 'recruitment') ? 'active' : '' ?>">Recruitment</a></li>
                <li><a href="/exam" class="<?= str_contains($currUri, 'exam') ? 'active' : '' ?>">Exams</a></li>
                <li><a href="/answer-key" class="<?= str_contains($currUri, 'answer-key') ? 'active' : '' ?>">Answer Key</a></li>
                <li><a href="/category/latest-news" class="<?= str_contains($currUri, 'latest-news') ? 'active' : '' ?>">Latest News</a></li>

                <?php if (!empty($more_categories)): ?>
                <li class="nav-dropdown">
                    <button class="nav-dropdown-btn" id="moreCategoriesBtn" aria-haspopup="true" aria-expanded="false">
                        More Categories <span class="dropdown-arrow">▾</span>
                    </button>
                    <div class="dropdown-menu" id="moreCategoriesMenu">
                        <?php foreach ($more_categories as $cat): ?>
                        <a href="/category/<?= htmlspecialchars($cat['slug']) ?>" class="dropdown-item <?= str_contains($currUri, $cat['slug']) ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Mobile Horizontal Swipeable Category Ribbon (Zero clutter, smooth thumb swipe) -->
    <div class="mobile-category-ribbon" aria-label="Quick Category Filter">
        <div class="ribbon-scroll-track">
            <a href="/" class="ribbon-chip <?= $currUri === '/' ? 'active' : '' ?>">
                🏠 All
            </a>
            <a href="/results" class="ribbon-chip <?= str_contains($currUri, 'results') ? 'active' : '' ?>">
                📋 Results
            </a>
            <a href="/admit-card" class="ribbon-chip <?= str_contains($currUri, 'admit-card') ? 'active' : '' ?>">
                🎫 Admit Cards
            </a>
            <a href="/recruitment" class="ribbon-chip <?= str_contains($currUri, 'recruitment') ? 'active' : '' ?>">
                💼 Recruitment
            </a>
            <a href="/exam" class="ribbon-chip <?= str_contains($currUri, 'exam') ? 'active' : '' ?>">
                📝 Exams
            </a>
            <a href="/answer-key" class="ribbon-chip <?= str_contains($currUri, 'answer-key') ? 'active' : '' ?>">
                🔑 Answer Key
            </a>
            <?php foreach ($nav_categories as $cat): ?>
                <?php if (!in_array($cat['slug'], ['results', 'admit-card', 'recruitment', 'exam', 'answer-key'])): ?>
                <a href="/category/<?= htmlspecialchars($cat['slug']) ?>" class="ribbon-chip <?= str_contains($currUri, $cat['slug']) ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</header>

<!-- Breaking News Ticker -->
<?php if (!empty($breaking_articles)): ?>
<div class="breaking-ticker" role="region" aria-label="Breaking Announcements">
    <div class="ticker-badge">
        ⚡ BREAKING
    </div>
    <div class="ticker-content">
        <div class="ticker-items">
            <?php foreach ($breaking_articles as $item): ?>
            <a href="/news/<?= htmlspecialchars($item['slug']) ?>" class="ticker-item">
                • [<?= htmlspecialchars($item['category_name']) ?>] <?= htmlspecialchars($item['title']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Content Area -->
<div class="container portal-grid">
    <main class="portal-main-col">
        <?= $content ?>
    </main>

    <!-- Sidebar -->
    <aside class="portal-sidebar" aria-label="Sidebar">
        <!-- Search Widget -->
        <div class="sidebar-widget">
            <div class="sidebar-widget-header">
                🔍 Find Updates
            </div>
            <div style="padding: 0.85rem;">
                <form action="/search" method="get">
                    <div class="search-input-group">
                        <input type="text" name="q" placeholder="Enter exam / board..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Trending Articles -->
        <?php if (!empty($trending_articles)): ?>
        <div class="sidebar-widget">
            <div class="sidebar-widget-header">
                🔥 Trending & Popular
            </div>
            <ul class="sidebar-news-list">
                <?php foreach ($trending_articles as $art): ?>
                <li class="sidebar-news-item">
                    <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                        <?= htmlspecialchars($art['title']) ?>
                    </a>
                    <div class="sidebar-meta">
                        <?= htmlspecialchars($art['category_name']) ?> • <?= date('M j, Y', strtotime($art['published_at'])) ?>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Categories Cloud -->
        <div class="sidebar-widget">
            <div class="sidebar-widget-header">
                📂 Browse Categories
            </div>
            <div class="category-pills">
                <?php foreach ($nav_categories as $c): ?>
                <a href="/category/<?= htmlspecialchars($c['slug']) ?>" class="category-pill">
                    <?= htmlspecialchars($c['icon']) ?> <?= htmlspecialchars($c['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Transparency Box -->
        <div class="sidebar-widget">
            <div class="sidebar-widget-header">
                🛡️ Verified Information
            </div>
            <div class="source-trust-box">
                <p>All notices, exam dates, and admit cards published here are automatically collected and verified strictly from official government portals (.gov.in, .nic.in, .ac.in).</p>
            </div>
        </div>
    </aside>
</div>

<!-- Footer -->
<footer class="main-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4><?= htmlspecialchars($site_settings['site_name'] ?? 'EduGov News') ?></h4>
                <p style="font-size: 0.8125rem; line-height: 1.6; margin-bottom: 0.75rem;">
                    <?= htmlspecialchars($site_settings['site_tagline'] ?? 'Instant & Verified Official Education Updates, Results, Admit Cards & Jobs.') ?>
                </p>
                <p style="font-size: 0.75rem; color: #64748b;">
                    Contact: <a href="mailto:<?= htmlspecialchars($site_settings['contact_email'] ?? 'contact@edugovnews.in') ?>"><?= htmlspecialchars($site_settings['contact_email'] ?? 'contact@edugovnews.in') ?></a>
                </p>
            </div>
            <div class="footer-col">
                <h4>Major Portals</h4>
                <ul>
                    <li><a href="/results">Results Portal</a></li>
                    <li><a href="/admit-card">Admit Cards</a></li>
                    <li><a href="/recruitment">Recruitment Notices</a></li>
                    <li><a href="/exam">Exam Schedules</a></li>
                    <li><a href="/answer-key">Answer Keys</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Browse Categories</h4>
                <ul>
                    <?php foreach (array_slice($nav_categories, 0, 5) as $c): ?>
                    <li><a href="/category/<?= htmlspecialchars($c['slug']) ?>"><?= htmlspecialchars($c['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal & Policies</h4>
                <ul>
                    <li><a href="/about">About Us</a></li>
                    <li><a href="/contact">Contact Us</a></li>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/terms-and-conditions">Terms & Conditions</a></li>
                    <li><a href="/disclaimer">Official Sources & Disclaimer</a></li>
                    <li><a href="/copyright-policy">Copyright Policy</a></li>
                    <li><a href="/sitemap.xml">XML Sitemap</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?= date('Y') ?> <?= htmlspecialchars($site_settings['site_name'] ?? 'EduGov News') ?>. All rights reserved. Data collected automatically from official government authorities.</p>
        </div>
    </div>
</footer>

<!-- Mobile Slide-over Offcanvas Drawer & Backdrop -->
<div id="drawerBackdrop" class="offcanvas-backdrop"></div>

<aside id="mobileDrawer" class="mobile-offcanvas-drawer" aria-label="Mobile Navigation Drawer">
    <div class="drawer-header">
        <div class="drawer-brand">
            <?= htmlspecialchars($site_settings['site_name'] ?? 'EduGov News') ?><span>.</span>
        </div>
        <button id="closeDrawerBtn" class="drawer-close-btn" aria-label="Close Navigation Drawer">
            ✕
        </button>
    </div>

    <div class="drawer-search-box">
        <form action="/search" method="get">
            <div class="search-input-group">
                <input type="text" name="q" id="drawerSearchInput" placeholder="Search exams, notices..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit">🔍</button>
            </div>
        </form>
    </div>

    <div class="drawer-content-scroll">
        <div class="drawer-section-title">Major Portals</div>
        <ul class="drawer-nav-list">
            <li><a href="/" class="<?= $currUri === '/' ? 'active' : '' ?>"><span>🏠</span> Home</a></li>
            <li><a href="/results" class="<?= str_contains($currUri, 'results') ? 'active' : '' ?>"><span>📋</span> Results Portal</a></li>
            <li><a href="/admit-card" class="<?= str_contains($currUri, 'admit-card') ? 'active' : '' ?>"><span>🎫</span> Admit Cards</a></li>
            <li><a href="/recruitment" class="<?= str_contains($currUri, 'recruitment') ? 'active' : '' ?>"><span>💼</span> Latest Recruitment</a></li>
            <li><a href="/exam" class="<?= str_contains($currUri, 'exam') ? 'active' : '' ?>"><span>📝</span> Examination Notices</a></li>
            <li><a href="/answer-key" class="<?= str_contains($currUri, 'answer-key') ? 'active' : '' ?>"><span>🔑</span> Answer Keys</a></li>
        </ul>

        <div class="drawer-section-title" style="margin-top: 1.25rem;">All Categories</div>
        <div class="drawer-category-grid">
            <?php foreach ($nav_categories as $cat): ?>
            <a href="/category/<?= htmlspecialchars($cat['slug']) ?>" class="drawer-cat-chip <?= str_contains($currUri, $cat['slug']) ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="drawer-section-title" style="margin-top: 1.5rem;">Quick Links</div>
        <ul class="drawer-quick-links">
            <li><a href="/about">About Us</a></li>
            <li><a href="/contact">Contact Us</a></li>
            <li><a href="/disclaimer">Disclaimer & Official Sources</a></li>
            <li><a href="/privacy-policy">Privacy Policy</a></li>
            <li><a href="/rss.xml">RSS Feeds</a></li>
        </ul>
    </div>
</aside>

<script src="/static/js/main.js?v=2.0"></script>
</body>
</html>
