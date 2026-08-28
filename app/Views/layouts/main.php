<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= htmlspecialchars($page_title ?? 'EduGov News') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Verified Official Education News, Exam Dates, Admit Cards, Results, Answer Keys, and Recruitment Updates.') ?>">
    <link rel="canonical" href="<?= htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">

    <!-- Google Fonts: Inter & Merriweather -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Merriweather:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">

    <!-- CSS Design System -->
    <link rel="stylesheet" href="/static/css/main.css?v=3.0">
</head>
<body class="site-body">

<header class="site-header">
    <!-- Top Utility Bar (Desktop only) -->
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
            <button id="mobileMenuBtn" class="mobile-nav-toggle-btn" aria-label="Open Navigation Menu">
                <span class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>

            <!-- Site Logo & Tagline -->
            <div class="site-logo">
                <a href="/" class="brand-name">
                    <?= htmlspecialchars($site_settings['site_name'] ?? 'EduGov News') ?><span class="brand-dot">.</span>
                </a>
                <div class="brand-tagline">
                    <span class="live-pulse-dot" title="Live updates active"></span> <?= htmlspecialchars($site_settings['site_tagline'] ?? 'Verified Official Education Updates & Notifications') ?>
                </div>
            </div>

            <!-- Desktop Search Bar -->
            <form action="/search" method="get" class="header-search-box">
                <div class="search-input-group">
                    <input type="text" name="q" placeholder="Search exams, results, admit cards..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" aria-label="Search">
                    <button type="submit" aria-label="Submit Search">🔍</button>
                </div>
            </form>

            <!-- Mobile Action Icons (Search & Explore) -->
            <div class="mobile-header-actions">
                <button id="mobileSearchTrigger" class="mobile-action-icon" aria-label="Search Updates">
                    🔍
                </button>
                <button id="mobileExploreTrigger" class="mobile-action-icon" aria-label="Explore Categories">
                    ⚡
                </button>
            </div>
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

    <!-- Mobile Enhanced Top Smart Tabs (Horizontal Swipeable Tab Bar) -->
    <div class="mobile-smart-tabs-bar" aria-label="Category Tabs">
        <div class="smart-tabs-scroll-track" id="categoryScrollTrack">
            <a href="/" class="smart-tab-pill <?= $currUri === '/' ? 'active' : '' ?>">
                <span class="tab-icon">🏠</span> <span class="tab-text">All</span>
            </a>
            <a href="/results" class="smart-tab-pill <?= str_contains($currUri, 'results') ? 'active' : '' ?>">
                <span class="tab-icon">📋</span> <span class="tab-text">Results</span>
            </a>
            <a href="/admit-card" class="smart-tab-pill <?= str_contains($currUri, 'admit-card') ? 'active' : '' ?>">
                <span class="tab-icon">🎫</span> <span class="tab-text">Admit Cards</span>
            </a>
            <a href="/recruitment" class="smart-tab-pill <?= str_contains($currUri, 'recruitment') ? 'active' : '' ?>">
                <span class="tab-icon">💼</span> <span class="tab-text">Recruitment</span>
            </a>
            <a href="/exam" class="smart-tab-pill <?= str_contains($currUri, 'exam') ? 'active' : '' ?>">
                <span class="tab-icon">📝</span> <span class="tab-text">Exams</span>
            </a>
            <a href="/answer-key" class="smart-tab-pill <?= str_contains($currUri, 'answer-key') ? 'active' : '' ?>">
                <span class="tab-icon">🔑</span> <span class="tab-text">Answer Key</span>
            </a>
            <?php foreach ($nav_categories as $cat): ?>
                <?php if (!in_array($cat['slug'], ['results', 'admit-card', 'recruitment', 'exam', 'answer-key', 'latest-news'])): ?>
                <a href="/category/<?= htmlspecialchars($cat['slug']) ?>" class="smart-tab-pill <?= str_contains($currUri, $cat['slug']) ? 'active' : '' ?>">
                    <span class="tab-icon"><?= htmlspecialchars($cat['icon'] ?? '📰') ?></span> <span class="tab-text"><?= htmlspecialchars($cat['name']) ?></span>
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

<!-- =========================================================================
     APP-STYLE MOBILE BOTTOM NAVIGATION BAR (Fixed at bottom on mobile)
     ========================================================================= -->
<nav class="mobile-bottom-nav" aria-label="Mobile Bottom Navigation">
    <div class="bottom-nav-inner">
        <a href="/" class="bottom-nav-item <?= $currUri === '/' ? 'active' : '' ?>">
            <div class="bottom-nav-icon">🏠</div>
            <div class="bottom-nav-label">Home</div>
        </a>
        <a href="/results" class="bottom-nav-item <?= str_contains($currUri, 'results') ? 'active' : '' ?>">
            <div class="bottom-nav-icon">📋</div>
            <div class="bottom-nav-label">Results</div>
        </a>
        <a href="/admit-card" class="bottom-nav-item <?= str_contains($currUri, 'admit-card') ? 'active' : '' ?>">
            <div class="bottom-nav-icon">🎫</div>
            <div class="bottom-nav-label">Admit Card</div>
        </a>
        <a href="/recruitment" class="bottom-nav-item <?= str_contains($currUri, 'recruitment') ? 'active' : '' ?>">
            <div class="bottom-nav-icon">💼</div>
            <div class="bottom-nav-label">Jobs</div>
        </a>
        <button id="bottomMenuTrigger" class="bottom-nav-item" aria-label="Open Categories Sheet">
            <div class="bottom-nav-icon">⚡</div>
            <div class="bottom-nav-label">Explore</div>
        </button>
    </div>
</nav>

<!-- =========================================================================
     ENHANCED APP-STYLE CATEGORIZED BOTTOM SHEET / DRAWER & BACKDROP
     ========================================================================= -->
<div id="drawerBackdrop" class="offcanvas-backdrop"></div>

<aside id="mobileDrawer" class="mobile-offcanvas-drawer" aria-label="Categories & Navigation Sheet">
    <!-- Pull handle for app-like bottom sheet feel -->
    <div class="drawer-handle-bar">
        <div class="drawer-handle-indicator"></div>
    </div>

    <div class="drawer-header">
        <div class="drawer-brand">
            <?= htmlspecialchars($site_settings['site_name'] ?? 'EduGov News') ?><span class="brand-dot">.</span>
        </div>
        <button id="closeDrawerBtn" class="drawer-close-btn" aria-label="Close Navigation">
            ✕
        </button>
    </div>

    <!-- Quick Search Input inside Sheet -->
    <div class="drawer-search-box">
        <form action="/search" method="get">
            <div class="search-input-group">
                <input type="text" name="q" id="drawerSearchInput" placeholder="Search exams, results, notifications..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit">🔍</button>
            </div>
        </form>
    </div>

    <div class="drawer-content-scroll">
        <!-- 1. Major Quick Hubs -->
        <div class="drawer-category-group">
            <div class="drawer-group-title">⚡ Primary Hubs</div>
            <div class="drawer-hubs-grid">
                <a href="/results" class="drawer-hub-card <?= str_contains($currUri, 'results') ? 'active' : '' ?>">
                    <span class="hub-icon">📋</span>
                    <span class="hub-title">Results</span>
                    <span class="hub-subtitle">Merit lists & cutoffs</span>
                </a>
                <a href="/admit-card" class="drawer-hub-card <?= str_contains($currUri, 'admit-card') ? 'active' : '' ?>">
                    <span class="hub-icon">🎫</span>
                    <span class="hub-title">Admit Card</span>
                    <span class="hub-subtitle">Hall tickets & slips</span>
                </a>
                <a href="/recruitment" class="drawer-hub-card <?= str_contains($currUri, 'recruitment') ? 'active' : '' ?>">
                    <span class="hub-icon">💼</span>
                    <span class="hub-title">Recruitment</span>
                    <span class="hub-subtitle">10,000+ Govt vacancies</span>
                </a>
                <a href="/exam" class="drawer-hub-card <?= str_contains($currUri, 'exam') ? 'active' : '' ?>">
                    <span class="hub-icon">📝</span>
                    <span class="hub-title">Exam Dates</span>
                    <span class="hub-subtitle">Schedules & calendars</span>
                </a>
            </div>
        </div>

        <!-- 2. Central & State Recruitment -->
        <div class="drawer-category-group">
            <div class="drawer-group-title">🏛️ Recruitment & Opportunities</div>
            <div class="drawer-chip-cluster">
                <a href="/recruitment" class="cluster-chip">💼 All Recruitment</a>
                <a href="/category/government-jobs" class="cluster-chip">🏛️ Government Jobs</a>
                <a href="/category/application-form" class="cluster-chip">📑 Application Forms</a>
                <a href="/category/scholarship" class="cluster-chip">🏆 Scholarships</a>
            </div>
        </div>

        <!-- 3. Examinations & Scorecards -->
        <div class="drawer-category-group">
            <div class="drawer-group-title">🎯 Exams & Admissions</div>
            <div class="drawer-chip-cluster">
                <a href="/exam" class="cluster-chip">📝 Exam Calendar</a>
                <a href="/answer-key" class="cluster-chip">🔑 Answer Keys</a>
                <a href="/category/entrance-exams" class="cluster-chip">🎯 Entrance (JEE/NEET/CUET)</a>
                <a href="/category/board-exams" class="cluster-chip">🏫 Board Exams (CBSE/State)</a>
                <a href="/category/admission" class="cluster-chip">🎓 University Admission</a>
                <a href="/category/important-updates" class="cluster-chip">⚡ Urgent Updates</a>
                <a href="/category/latest-news" class="cluster-chip">📰 Latest News</a>
            </div>
        </div>

        <!-- 4. Quick Transparency & Policy Links -->
        <div class="drawer-category-group" style="border-bottom: none; margin-bottom: 2rem;">
            <div class="drawer-group-title">🛡️ About & Policies</div>
            <div class="drawer-links-horizontal">
                <a href="/about">About</a>
                <a href="/contact">Contact</a>
                <a href="/disclaimer">Official Disclaimer</a>
                <a href="/privacy-policy">Privacy</a>
                <a href="/terms-and-conditions">Terms</a>
                <a href="/rss.xml">RSS Feed</a>
            </div>
        </div>
    </div>
</aside>

<script src="/static/js/main.js?v=3.0"></script>
</body>
</html>
