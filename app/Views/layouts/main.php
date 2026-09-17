<!DOCTYPE html>
<html lang="<?= htmlspecialchars($current_locale ?? 'en') ?>" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= htmlspecialchars($page_title ?? (__('site_name') . ' — ' . __('site_tagline'))) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Instant & verified official educational notifications, exam dates, admit cards, results, and government job vacancy alerts.') ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <?php if (!empty($site_settings['google_site_verification'])): ?>
    <meta name="google-site-verification" content="<?= htmlspecialchars($site_settings['google_site_verification']) ?>">
    <?php endif; ?>
    <?php
    $currentHost = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $host = $currentHost;
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $baseDomain = "{$scheme}://{$host}";
    $reqUri = $_SERVER['REQUEST_URI'] ?? '/';
    $pathOnly = parse_url($reqUri, PHP_URL_PATH) ?? '/';

    // Strip language prefix to compute base path
    $cleanPath = preg_replace('#^/(?:bn|hi)(/|$)#', '$1', $pathOnly);
    if (empty($cleanPath) || $cleanPath === '') {
        $cleanPath = '/';
    }
    if ($cleanPath !== '/' && !str_starts_with($cleanPath, '/')) {
        $cleanPath = '/' . $cleanPath;
    }

    $urlEn = $baseDomain . ($cleanPath === '/' ? '/' : $cleanPath);
    $urlBn = $baseDomain . '/bn' . ($cleanPath === '/' ? '' : $cleanPath);
    $urlHi = $baseDomain . '/hi' . ($cleanPath === '/' ? '' : $cleanPath);

    $activeLocale = $current_locale ?? \App\Core\I18n::getLocale();
    $ogLocale = match($activeLocale) {
        'bn' => 'bn_IN',
        'hi' => 'hi_IN',
        default => 'en_US',
    };
    $altLocales = array_diff(['en_US', 'bn_IN', 'hi_IN'], [$ogLocale]);

    $calculatedCanonical = match($activeLocale) {
        'bn' => $urlBn,
        'hi' => $urlHi,
        default => $urlEn,
    };
    $finalCanonical = $canonical_url ?? $calculatedCanonical;
    ?>

    <link rel="canonical" href="<?= htmlspecialchars($finalCanonical) ?>">
    <!-- Multilingual Hreflang Canonical Annotations for Googlebot -->
    <link rel="alternate" hreflang="en" href="<?= htmlspecialchars($urlEn) ?>">
    <link rel="alternate" hreflang="bn" href="<?= htmlspecialchars($urlBn) ?>">
    <link rel="alternate" hreflang="hi" href="<?= htmlspecialchars($urlHi) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($urlEn) ?>">

    <link rel="alternate" type="application/rss+xml" title="EduGov News RSS Feed" href="/rss.xml">
    <link rel="sitemap" type="application/xml" title="Google News Sitemap" href="/news-sitemap.xml">

    <?php if (!empty($site_settings['google_site_verification'])): ?>
    <meta name="google-site-verification" content="<?= htmlspecialchars($site_settings['google_site_verification']) ?>">
    <?php endif; ?>

    <?php if (!empty($site_settings['ga4_measurement_id'])): ?>
    <!-- Google Analytics (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($site_settings['ga4_measurement_id']) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= htmlspecialchars($site_settings['ga4_measurement_id']) ?>');
    </script>
    <?php endif; ?>

    <!-- Performance & DNS Pre-fetching -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Progressive Web App (PWA) & Tab Favicons -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/static/img/icon-192.svg">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1d4ed8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="EduGov">

    <!-- OpenGraph & Twitter Meta Tags with Dynamic Social OG Banner -->
    <?php 
    $ogImg = !empty($article['slug']) 
             ? "{$baseDomain}/og-image/{$article['slug']}" 
             : "{$baseDomain}/static/img/og-default.png";
    ?>
    <meta property="og:title" content="<?= htmlspecialchars($page_title ?? 'EduGov News') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? 'Official education news, exam updates, results, and verified government recruitment alerts.') ?>">
    <meta property="og:type" content="<?= !empty($article) ? 'article' : 'website' ?>">
    <meta property="og:url" content="<?= htmlspecialchars($finalCanonical) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($ogImg) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="<?= htmlspecialchars($site_settings['site_name'] ?? 'EduGov News') ?>">
    <meta property="og:locale" content="<?= $ogLocale ?>">
    <?php foreach ($altLocales as $altLoc): ?>
    <meta property="og:locale:alternate" content="<?= $altLoc ?>">
    <?php endforeach; ?>

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title ?? 'EduGov News') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($meta_description ?? 'Instant official educational notifications and recruitment alerts.') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($ogImg) ?>">

    <!-- Google Fonts & Main CSS Design System -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/static/css/main.css?v=<?= time() ?>">

    <!-- Schema.org Global WebSite & SearchAction Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebSite",
          "name": <?= json_encode($site_settings['site_name'] ?? 'EduGov News') ?>,
          "url": "http://<?= $_SERVER['HTTP_HOST'] ?>/",
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "http://<?= $_SERVER['HTTP_HOST'] ?>/search?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }
        },
        {
          "@type": "Organization",
          "name": <?= json_encode($site_settings['site_name'] ?? 'EduGov News') ?>,
          "url": "http://<?= $_SERVER['HTTP_HOST'] ?>/",
          "description": "National Education & Recruitment News Portal"
        }
      ]
    }
    </script>
</head>
<body class="site-body">
    <!-- Top Reading Progress Indicator -->
    <div id="readingProgressBar" class="reading-progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div>

    <!-- Top Verified Trust Header (Desktop only) -->
    <div class="top-trust-bar">
        <div class="site-container trust-bar-inner">
            <div class="trust-left">
                <span class="trust-badge">🇮🇳 National Education & Recruitment Ingestion Network</span>
                <span class="trust-meta">Automated Official Synchronization Active</span>
            </div>
            <div class="trust-right">
                <!-- Language Switcher Pill -->
                <div class="header-lang-pills" style="display: inline-flex; align-items: center; gap: 4px; background: rgba(255,255,255,0.12); padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.15);">
                    <span style="opacity: 0.8;">🌐</span>
                    <a href="/set-language/en" style="color: <?= ($current_locale ?? 'en') === 'en' ? '#fde047; font-weight: 800;' : '#e2e8f0;' ?> text-decoration: none; padding: 2px 4px;">English</a>
                    <span style="opacity: 0.4;">|</span>
                    <a href="/set-language/bn" style="color: <?= ($current_locale ?? 'en') === 'bn' ? '#fde047; font-weight: 800;' : '#e2e8f0;' ?> text-decoration: none; padding: 2px 4px;">বাংলা</a>
                    <span style="opacity: 0.4;">|</span>
                    <a href="/set-language/hi" style="color: <?= ($current_locale ?? 'en') === 'hi' ? '#fde047; font-weight: 800;' : '#e2e8f0;' ?> text-decoration: none; padding: 2px 4px;">हिंदी</a>
                </div>

                <button id="pwaInstallBtn" class="pwa-install-pill js-pwa-install" type="button" aria-label="Install EduGov App" style="display: none;">
                    <span class="pwa-icon">📲</span>
                    <span class="pwa-label">Install App</span>
                </button>
                <button class="theme-toggle-btn js-theme-toggle" aria-label="Toggle Dark/Light Mode">
                    <span class="theme-icon-slot">
                        <svg class="sun-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg class="moon-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </span>
                    <span class="theme-label">Eye Comfort</span>
                </button>
                <a href="/sitemap.xml" class="trust-link">Sitemap</a>
                <a href="/rss.xml" class="trust-link">RSS Feed</a>
                <a href="/admin" class="trust-link">Admin Access</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="site-header">
        <div class="site-container header-inner">
            <div class="header-left">
                <!-- Mobile Drawer Trigger Button -->
                <button id="mobileMenuBtn" class="mobile-menu-btn" aria-label="Open Navigation Drawer">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>

                <!-- Modern Vector SVG Brand Logo -->
                <a href="/" class="brand-logo" title="EduGov News Homepage">
                    <div class="brand-logo-wrap">
                        <div class="brand-emblem-icon">
                            <svg width="38" height="38" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="headerLogoBg" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#0a192f"/>
                                        <stop offset="50%" stop-color="#1e40af"/>
                                        <stop offset="100%" stop-color="#2563eb"/>
                                    </linearGradient>
                                    <linearGradient id="headerLogoGold" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#fef08a"/>
                                        <stop offset="50%" stop-color="#f59e0b"/>
                                        <stop offset="100%" stop-color="#d97706"/>
                                    </linearGradient>
                                    <linearGradient id="headerLogoAlert" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#ff4444"/>
                                        <stop offset="100%" stop-color="#dc2626"/>
                                    </linearGradient>
                                </defs>
                                <!-- Shield Container -->
                                <rect width="48" height="48" rx="12" fill="url(#headerLogoBg)"/>
                                <rect x="1" y="1" width="46" height="46" rx="11" fill="none" stroke="#60a5fa" stroke-width="1.2" stroke-opacity="0.4"/>
                                <!-- Open Book of Education -->
                                <path d="M10 32 C14 30, 20 30, 24 33 C28 30, 34 30, 38 32 V38 C34 36, 28 36, 24 39 C20 36, 14 36, 10 38 Z" fill="#ffffff"/>
                                <path d="M24 33.5 V39" stroke="#94a3b8" stroke-width="1" stroke-linecap="round"/>
                                <!-- Graduation Cap / Mortarboard -->
                                <polygon points="24,10 7,18 24,25 41,18" fill="#ffffff"/>
                                <path d="M14 21.5 V26 C14 29 24 31 24 31 C24 31 34 29 34 26 V21.5 L24 25.5 Z" fill="#93c5fd"/>
                                <!-- Gold Tassel -->
                                <path d="M37 19.5 C37 23, 38 25, 39 27" fill="none" stroke="url(#headerLogoGold)" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="39" cy="28" r="1.5" fill="url(#headerLogoGold)"/>
                                <!-- Result Badge & Star -->
                                <circle cx="37" cy="11" r="5" fill="url(#headerLogoGold)"/>
                                <path d="M35 11 L36.5 12.5 L39.5 9.5" fill="none" stroke="#0a192f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <!-- Announcement Alert Pulse -->
                                <circle cx="9" cy="11" r="3.5" fill="url(#headerLogoAlert)"/>
                            </svg>
                        </div>
                        <div class="brand-text-stack">
                            <div class="brand-title">
                                <span class="logo-accent">EduGov</span><span class="logo-sub">News<span class="logo-dot">.</span></span>
                            </div>
                            <span class="logo-tagline"><span class="live-dot"></span> EXAM & RESULT NETWORK</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Header Quick Search Bar (Desktop) -->
            <div class="header-search-box">
                <form action="/search" method="get" class="search-form">
                    <input type="text" name="q" placeholder="<?= htmlspecialchars(__('search_placeholder')) ?>" aria-label="Search notifications" required>
                    <button type="submit" aria-label="Search">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Header Right Actions -->
            <div class="header-right-actions">
                <!-- Mobile / Tablet Language Selector -->
                <div class="mobile-lang-switch-wrap">
                    <a href="/set-language/en" class="mobile-lang-btn <?= ($current_locale ?? 'en') === 'en' ? 'active' : '' ?>">EN</a>
                    <a href="/set-language/bn" class="mobile-lang-btn <?= ($current_locale ?? 'en') === 'bn' ? 'active' : '' ?>">বাংলা</a>
                    <a href="/set-language/hi" class="mobile-lang-btn <?= ($current_locale ?? 'en') === 'hi' ? 'active' : '' ?>">हिंदी</a>
                </div>

                <button class="mobile-icon-btn js-theme-toggle" aria-label="Toggle Theme">
                    <span class="theme-icon-slot">
                        <svg class="sun-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg class="moon-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </span>
                </button>
                <button id="mobileSearchTrigger" class="mobile-icon-btn" aria-label="Search">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <a href="/recruitment" class="btn-gov-jobs desktop-only-inline">🏛️ <?= htmlspecialchars(__('nav_recruitment')) ?></a>
            </div>
        </div>

        <!-- 1. Desktop Traditional Navigation Bar -->
        <nav class="desktop-main-nav">
            <div class="site-container nav-items-row">
                <a href="/" class="nav-item <?= empty($current_category) && $_SERVER['REQUEST_URI'] === '/' ? 'active' : '' ?>">🏠 <?= htmlspecialchars(__('nav_home')) ?></a>
                <a href="/results" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'results') ? 'active' : '' ?>">📋 <?= htmlspecialchars(__('nav_results')) ?></a>
                <a href="/admit-card" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'admit-card') ? 'active' : '' ?>">🎫 <?= htmlspecialchars(__('nav_admit_card')) ?></a>
                <a href="/recruitment" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'recruitment') ? 'active' : '' ?>">💼 <?= htmlspecialchars(__('nav_recruitment')) ?></a>
                <a href="/exam" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'exam') ? 'active' : '' ?>">📝 <?= htmlspecialchars(__('nav_exam_dates')) ?></a>
                <a href="/answer-key" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'answer-key') ? 'active' : '' ?>">🔑 <?= htmlspecialchars(__('nav_answer_key')) ?></a>
                <a href="/category/scholarship" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'scholarship') ? 'active' : '' ?>">🏆 <?= htmlspecialchars(__('nav_scholarship')) ?></a>
                
                <!-- Desktop Dropdown -->
                <div class="nav-dropdown">
                    <button class="nav-dropdown-btn" id="moreCategoriesBtn"><?= htmlspecialchars(__('nav_categories')) ?> ▾</button>
                    <div class="nav-dropdown-menu">
                        <a href="/category/admission">🎓 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'ভর্তি ও কাউন্সেলিং' : (\App\Core\I18n::getLocale() === 'hi' ? 'प्रवेश एवं काउंसलिंग' : 'Admission & Counseling')) ?></a>
                        <a href="/category/board-exams">🏫 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'বোর্ড পরীক্ষা' : (\App\Core\I18n::getLocale() === 'hi' ? 'बोर्ड परीक्षाएं' : 'Board Exams (CBSE/ICSE)')) ?></a>
                        <a href="/category/scholarship">🏆 <?= htmlspecialchars(__('nav_scholarship')) ?></a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- 2. Mobile Smart Category Swipeable Bar (Sticky Top Pills) -->
        <div class="mobile-smart-tabs-bar">
            <div class="smart-tabs-scroll-track" id="categoryScrollTrack">
                <a href="/" class="smart-tab-pill <?= ($_SERVER['REQUEST_URI'] === '/') ? 'active' : '' ?>">
                    <span>🏠</span> <?= htmlspecialchars(__('qual_all')) ?>
                </a>
                <a href="/results" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'results') ? 'active' : '' ?>">
                    <span>📋</span> <?= htmlspecialchars(__('nav_results')) ?>
                </a>
                <a href="/admit-card" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'admit-card') ? 'active' : '' ?>">
                    <span>🎫</span> <?= htmlspecialchars(__('nav_admit_card')) ?>
                </a>
                <a href="/recruitment" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'recruitment') ? 'active' : '' ?>">
                    <span>💼</span> <?= htmlspecialchars(__('nav_recruitment')) ?>
                </a>
                <a href="/exam" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'exam') ? 'active' : '' ?>">
                    <span>📝</span> <?= htmlspecialchars(__('nav_exam_dates')) ?>
                </a>
                <a href="/answer-key" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'answer-key') ? 'active' : '' ?>">
                    <span>🔑</span> <?= htmlspecialchars(__('nav_answer_key')) ?>
                </a>
                <a href="/category/scholarship" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'scholarship') ? 'active' : '' ?>">
                    <span>🏆</span> <?= htmlspecialchars(__('nav_scholarship')) ?>
                </a>
            </div>
        </div>
    </header>

    <!-- Breaking News Marquee Ticker (Clickable Articles!) -->
    <div class="breaking-ticker-bar">
        <div class="site-container ticker-inner">
            <span class="ticker-badge">⚡ <?= htmlspecialchars(__('breaking_label')) ?></span>
            <div class="ticker-marquee">
                <div class="ticker-items">
                    <?php if (!empty($breaking_articles)): ?>
                        <?php foreach ($breaking_articles as $b): ?>
                            <a href="/news/<?= htmlspecialchars($b['slug']) ?>" class="ticker-link" title="<?= htmlspecialchars($b['title']) ?>">
                                • <?= htmlspecialchars($b['title']) ?>
                            </a>
                        <?php endforeach; ?>
                        <!-- Duplicate set for seamless continuous marquee loop -->
                        <?php foreach ($breaking_articles as $b): ?>
                            <a href="/news/<?= htmlspecialchars($b['slug']) ?>" class="ticker-link" title="<?= htmlspecialchars($b['title']) ?>">
                                • <?= htmlspecialchars($b['title']) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="ticker-link">• <?= htmlspecialchars(__('latest_updates')) ?> — 24/7 Official Monitoring</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Yield -->
    <div class="site-container main-wrapper">
        <?= $content ?>
    </div>

    <!-- 3. Mobile App-Style Fixed Bottom Dock with Vibrant Colorful Icons -->
    <nav class="mobile-bottom-nav">
        <div class="bottom-nav-inner">
            <a href="/" class="bottom-nav-item nav-item-home <?= ($_SERVER['REQUEST_URI'] === '/') ? 'active' : '' ?>">
                <span class="nav-icon icon-home">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" fill="#dbeafe" fill-opacity="0.6"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </span>
                <span class="nav-text"><?= htmlspecialchars(__('nav_home')) ?></span>
            </a>
            <a href="/results" class="bottom-nav-item nav-item-results <?= str_contains($_SERVER['REQUEST_URI'], 'results') ? 'active' : '' ?>">
                <span class="nav-icon icon-results">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="#d1fae5" fill-opacity="0.6"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </span>
                <span class="nav-text"><?= htmlspecialchars(__('nav_results')) ?></span>
            </a>
            <a href="/admit-card" class="bottom-nav-item nav-item-admit <?= str_contains($_SERVER['REQUEST_URI'], 'admit-card') ? 'active' : '' ?>">
                <span class="nav-icon icon-admit">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2" fill="#e0f2fe" fill-opacity="0.6"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                        <circle cx="6" cy="15" r="1.5" fill="#0284c7"></circle>
                        <circle cx="10" cy="15" r="1.5" fill="#0284c7"></circle>
                    </svg>
                </span>
                <span class="nav-text"><?= htmlspecialchars(__('nav_admit_card')) ?></span>
            </a>
            <a href="/recruitment" class="bottom-nav-item nav-item-jobs <?= str_contains($_SERVER['REQUEST_URI'], 'recruitment') ? 'active' : '' ?>">
                <span class="nav-icon icon-jobs">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" fill="#fef3c7" fill-opacity="0.6"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </span>
                <span class="nav-text"><?= htmlspecialchars(__('nav_recruitment')) ?></span>
            </a>
            <button type="button" class="bottom-nav-item nav-item-explore" id="bottomMenuTrigger" aria-label="Open Full Category Explorer">
                <span class="nav-icon icon-explore">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" fill="#ede9fe"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5" fill="#ede9fe"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5" fill="#ede9fe"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5" fill="#ede9fe"></rect>
                    </svg>
                </span>
                <span class="nav-text"><?= htmlspecialchars(__('nav_categories')) ?></span>
            </button>
        </div>
    </nav>

    <!-- 4. Dedicated Quick Search Modal / Overlay (Direct Search Trigger) -->
    <div class="search-modal-backdrop" id="searchModalBackdrop"></div>
    <div class="quick-search-modal" id="quickSearchModal" role="dialog" aria-modal="true" aria-label="Quick Search">
        <div class="search-modal-header">
            <div class="search-modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <span>Search Official Notices</span>
            </div>
            <button type="button" class="btn-close-search-modal" id="closeSearchModalBtn" aria-label="Close search">✕</button>
        </div>
        <div class="search-modal-body">
            <form action="/search" method="get" class="search-modal-form">
                <div class="search-modal-input-wrap">
                    <input type="text" name="q" id="quickSearchModalInput" placeholder="Search exams, results, admit cards, notices..." required autocomplete="off">
                    <button type="submit" class="btn-search-modal-submit">Search</button>
                </div>
            </form>
            <div class="search-modal-quick-tags">
                <span class="quick-tag-label">Popular Searches:</span>
                <div class="quick-tag-pills">
                    <a href="/search?q=SSC" class="search-tag-pill">🏛️ SSC</a>
                    <a href="/search?q=UPSC" class="search-tag-pill">🏛️ UPSC</a>
                    <a href="/search?q=Railway" class="search-tag-pill">🚂 Railway (RRB)</a>
                    <a href="/search?q=Admit+Card" class="search-tag-pill">🎫 Admit Card</a>
                    <a href="/search?q=Results" class="search-tag-pill">📋 Results</a>
                    <a href="/search?q=West+Bengal" class="search-tag-pill">📍 West Bengal</a>
                    <a href="/search?q=Rajasthan" class="search-tag-pill">📍 Rajasthan</a>
                    <a href="/search?q=Bihar" class="search-tag-pill">📍 Bihar</a>
                    <a href="/search?q=Uttar+Pradesh" class="search-tag-pill">📍 UPPSC</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Categorized Bottom Sheet Modal (Drawer) -->
    <div class="drawer-backdrop" id="drawerBackdrop"></div>
    <div class="mobile-offcanvas-drawer" id="mobileDrawer">
        <div class="drawer-handle-bar">
            <div class="handle-pill"></div>
        </div>

        <div class="drawer-header">
            <div class="drawer-title">
                <span class="logo-accent">EduGov</span><span class="logo-sub">News<span class="logo-dot">.</span></span>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <button class="theme-toggle-btn js-theme-toggle" style="padding: 0.3rem 0.6rem;">
                    <span class="theme-icon-slot">
                        <svg class="sun-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg class="moon-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </span>
                    <span class="theme-label">Theme</span>
                </button>
                <button class="drawer-close-btn" id="closeDrawerBtn" aria-label="Close menu">✕</button>
            </div>
        </div>

        <!-- In-Drawer Quick Search -->
        <div class="drawer-search-wrap">
            <form action="/search" method="get" class="drawer-search-form">
                <input type="text" name="q" id="drawerSearchInput" placeholder="Search exams, results, notifications..." required>
                <button type="submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
        <!-- In-Drawer Language Switcher Card -->
        <div class="drawer-lang-card">
            <div class="drawer-lang-card-header">
                <span>🌐</span> <span><?= htmlspecialchars(__('language')) ?> / Select Language:</span>
            </div>
            <div class="drawer-lang-grid">
                <a href="/set-language/en" class="drawer-lang-opt <?= ($current_locale ?? 'en') === 'en' ? 'active' : '' ?>">
                    <span class="d-flag">🇬🇧</span>
                    <span class="d-label">English</span>
                    <?php if (($current_locale ?? 'en') === 'en'): ?><span class="d-check">✓</span><?php endif; ?>
                </a>
                <a href="/set-language/bn" class="drawer-lang-opt <?= ($current_locale ?? 'en') === 'bn' ? 'active' : '' ?>">
                    <span class="d-flag">🇧🇩</span>
                    <span class="d-label">বাংলা</span>
                    <?php if (($current_locale ?? 'en') === 'bn'): ?><span class="d-check">✓</span><?php endif; ?>
                </a>
                <a href="/set-language/hi" class="drawer-lang-opt <?= ($current_locale ?? 'en') === 'hi' ? 'active' : '' ?>">
                    <span class="d-flag">🇮🇳</span>
                    <span class="d-label">हिंदी</span>
                    <?php if (($current_locale ?? 'en') === 'hi'): ?><span class="d-check">✓</span><?php endif; ?>
                </a>
            </div>
        </div>

        <div class="drawer-body-content">
            <!-- Primary Hubs (2x2 Big Touch Cards) -->
            <div class="drawer-section-title">⚡ <?= htmlspecialchars(__('quick_categories')) ?></div>
            <div class="drawer-hubs-grid">
                <a href="/results" class="drawer-hub-card">
                    <div class="hub-icon">📋</div>
                    <div class="hub-label"><?= htmlspecialchars(__('nav_results')) ?></div>
                    <div class="hub-sub"><?= htmlspecialchars(__('results_section_title')) ?></div>
                </a>
                <a href="/admit-card" class="drawer-hub-card">
                    <div class="hub-icon">🎫</div>
                    <div class="hub-label"><?= htmlspecialchars(__('nav_admit_card')) ?></div>
                    <div class="hub-sub"><?= htmlspecialchars(__('admit_cards_section_title')) ?></div>
                </a>
                <a href="/recruitment" class="drawer-hub-card">
                    <div class="hub-icon">💼</div>
                    <div class="hub-label"><?= htmlspecialchars(__('nav_recruitment')) ?></div>
                    <div class="hub-sub"><?= htmlspecialchars(__('jobs_section_title')) ?></div>
                </a>
                <a href="/exam" class="drawer-hub-card">
                    <div class="hub-icon">📝</div>
                    <div class="hub-label"><?= htmlspecialchars(__('nav_exam_dates')) ?></div>
                    <div class="hub-sub"><?= htmlspecialchars(__('nav_exam_dates')) ?></div>
                </a>
            </div>

            <!-- State Matrix in Drawer -->
            <div class="drawer-section-title">🗺️ <?= htmlspecialchars(__('state_matrix_title')) ?></div>
            <div class="drawer-chip-cluster">
                <a href="/state/central-govt" class="chip-item">🏛️ <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'কেন্দ্রীয় সরকার' : (\App\Core\I18n::getLocale() === 'hi' ? 'केंद्रीय सरकार' : 'Central Govt')) ?></a>
                <a href="/state/west-bengal" class="chip-item">🌊 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'পশ্চিমবঙ্গ' : (\App\Core\I18n::getLocale() === 'hi' ? 'पश्चिम बंगाल' : 'West Bengal')) ?></a>
                <a href="/state/uttar-pradesh" class="chip-item">🌾 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'উত্তরপ্রদেশ' : (\App\Core\I18n::getLocale() === 'hi' ? 'उत्तर प्रदेश' : 'Uttar Pradesh')) ?></a>
                <a href="/state/bihar" class="chip-item">🚩 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'বিহার' : (\App\Core\I18n::getLocale() === 'hi' ? 'बिहार' : 'Bihar')) ?></a>
                <a href="/state/rajasthan" class="chip-item">🏰 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'রাজস্থান' : (\App\Core\I18n::getLocale() === 'hi' ? 'राजस्थान' : 'Rajasthan')) ?></a>
                <a href="/state/madhya-pradesh" class="chip-item">🌲 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'মধ্যপ্রদেশ' : (\App\Core\I18n::getLocale() === 'hi' ? 'मध्य प्रदेश' : 'Madhya Pradesh')) ?></a>
                <a href="/state/maharashtra" class="chip-item">🏙️ <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'মহারাষ্ট্র' : (\App\Core\I18n::getLocale() === 'hi' ? 'महाराष्ट्र' : 'Maharashtra')) ?></a>
            </div>

            <!-- Recruitment & Opportunities -->
            <div class="drawer-section-title">🏛️ <?= htmlspecialchars(__('jobs_section_title')) ?></div>
            <div class="drawer-chip-cluster">
                <a href="/recruitment" class="chip-item">💼 <?= htmlspecialchars(__('nav_recruitment')) ?></a>
                <a href="/answer-key" class="chip-item">🔑 <?= htmlspecialchars(__('nav_answer_key')) ?></a>
                <a href="/category/scholarship" class="chip-item">🏆 <?= htmlspecialchars(__('nav_scholarship')) ?></a>
            </div>

            <!-- Policies & Legal -->
            <div class="drawer-section-title">🛡️ <?= htmlspecialchars(__('footer_disclaimer')) ?></div>
            <div class="drawer-chip-cluster">
                <a href="/about" class="chip-item">ℹ️ About Us</a>
                <a href="/disclaimer" class="chip-item">⚖️ Disclaimer</a>
                <a href="/privacy-policy" class="chip-item">🔒 Privacy Policy</a>
                <a href="/contact" class="chip-item">📬 Contact</a>
                <a href="/rss.xml" class="chip-item">📡 RSS Feed</a>
            </div>
        </div>
    </div>

    <!-- Official Portal Footer -->
    <footer class="site-footer">
        <div class="site-container footer-content">
            <div class="footer-col brand-col">
                <div class="brand-logo-wrap" style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;">
                    <div class="brand-emblem-icon">
                        <svg width="34" height="34" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" rx="12" fill="#1e40af"/>
                            <path d="M10 32 C14 30, 20 30, 24 33 C28 30, 34 30, 38 32 V38 C34 36, 28 36, 24 39 C20 36, 14 36, 10 38 Z" fill="#ffffff"/>
                            <polygon points="24,10 7,18 24,25 41,18" fill="#ffffff"/>
                            <path d="M14 21.5 V26 C14 29 24 31 24 31 C24 31 34 29 34 26 V21.5 L24 25.5 Z" fill="#93c5fd"/>
                            <circle cx="37" cy="11" r="5" fill="#f59e0b"/>
                            <circle cx="9" cy="11" r="3.5" fill="#ef4444"/>
                        </svg>
                    </div>
                    <div class="brand-text-stack">
                        <div class="brand-title">
                            <span class="logo-accent" style="color: #60a5fa;">EduGov</span><span class="logo-sub" style="color: #ffffff;">News<span class="logo-dot">.</span></span>
                        </div>
                        <span class="logo-tagline" style="color: #94a3b8; font-size: 0.6875rem;">OFFICIAL EDUCATION & EXAM PORTAL</span>
                    </div>
                </div>
                <p class="footer-desc">
                    <?= htmlspecialchars(__('footer_disclaimer')) ?>
                </p>
                <div class="footer-disclaimer-badge">
                    ⚖️ <strong><?= htmlspecialchars(__('official_authenticity')) ?>:</strong> <?= htmlspecialchars(__('official_auth_desc')) ?>
                </div>
            </div>

            <div class="footer-col">
                <div class="footer-heading">⚡ <?= htmlspecialchars(__('quick_categories')) ?></div>
                <ul class="footer-links">
                    <li><a href="/results">📋 <?= htmlspecialchars(__('nav_results')) ?></a></li>
                    <li><a href="/admit-card">🎫 <?= htmlspecialchars(__('nav_admit_card')) ?></a></li>
                    <li><a href="/recruitment">💼 <?= htmlspecialchars(__('nav_recruitment')) ?></a></li>
                    <li><a href="/exam">📝 <?= htmlspecialchars(__('nav_exam_dates')) ?></a></li>
                    <li><a href="/answer-key">🔑 <?= htmlspecialchars(__('nav_answer_key')) ?></a></li>
                    <li><a href="/category/scholarship">🏆 <?= htmlspecialchars(__('nav_scholarship')) ?></a></li>
                </ul>
            </div>

            <div class="footer-col">
                <div class="footer-heading">🗺️ <?= htmlspecialchars(__('nav_states')) ?></div>
                <ul class="footer-links">
                    <li><a href="/state/central-govt">🏛️ Central Govt Jobs</a></li>
                    <li><a href="/state/west-bengal">🌊 West Bengal (WBPSC)</a></li>
                    <li><a href="/state/uttar-pradesh">🌾 Uttar Pradesh (UPPSC)</a></li>
                    <li><a href="/state/bihar">🚩 Bihar (BPSC)</a></li>
                    <li><a href="/state/rajasthan">🏰 Rajasthan (RPSC)</a></li>
                    <li><a href="/state/madhya-pradesh">🌲 Madhya Pradesh (MPPSC)</a></li>
                    <li><a href="/state/maharashtra">🏙️ Maharashtra (MPSC)</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <div class="footer-heading">🛡️ Legal & Compliance</div>
                <ul class="footer-links">
                    <li><a href="/about">About Us</a></li>
                    <li><a href="/contact">Contact Support</a></li>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/terms-and-conditions">Terms & Conditions</a></li>
                    <li><a href="/disclaimer">Official Disclaimer</a></li>
                    <li><a href="/copyright-policy">Copyright Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="site-container footer-bottom">
            <p>© <?= date('Y') ?> EduGov News Portal. All rights reserved. Ingested from verified official education & public recruitment portals.</p>
            <p style="font-size: 0.6875rem; color: #64748b;">Powered by High-Performance Native Plain PHP & MySQL Engine.</p>
        </div>
    </footer>

    <!-- Floating Back to Top Button -->
    <button id="backToTopBtn" class="back-to-top-btn" aria-label="Scroll back to top" title="Go to top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>

    <!-- Toast Notification Container -->
    <div id="toastNotification" class="toast-notification" role="status" aria-live="polite"></div>

    <!-- Interactive Scripts -->
    <script src="/static/js/main.js?v=<?= time() ?>"></script>
</body>
</html>
