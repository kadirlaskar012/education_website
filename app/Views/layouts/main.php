<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= htmlspecialchars($page_title ?? 'EduGov News — Official Education & Jobs Portal') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Instant & verified official educational notifications, exam dates, admit cards, results, and government job vacancy alerts.') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical_url ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">
    <link rel="alternate" type="application/rss+xml" title="EduGov News RSS Feed" href="/rss.xml">

    <!-- OpenGraph & Twitter Meta Tags -->
    <meta property="og:title" content="<?= htmlspecialchars($page_title ?? 'EduGov News') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? 'Official education news and updates.') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($canonical_url ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($site_settings['site_name'] ?? 'EduGov News') ?>">

    <!-- Google Fonts & Main CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/static/css/main.css?v=<?= time() ?>">
</head>
<body class="site-body">
    <!-- Top Verified Trust Header (Desktop only) -->
    <div class="top-trust-bar">
        <div class="site-container trust-bar-inner">
            <div class="trust-left">
                <span class="trust-badge">🇮🇳 National Education & Recruitment Ingestion Network</span>
                <span class="trust-meta">Automated Official Synchronization Active</span>
            </div>
            <div class="trust-right">
                <button class="theme-toggle-btn js-theme-toggle" aria-label="Toggle Dark/Light Mode">
                    <span class="theme-icon-slot">
                        <svg class="sun-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg class="moon-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </span>
                    <span class="theme-label">Theme</span>
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
                            <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="32" height="32" rx="8" fill="url(#logo-grad)"/>
                                <path d="M16 7L6 12L16 17L26 12L16 7Z" fill="#FFFFFF"/>
                                <path d="M10 14.5V19.5C10 22.5 16 25 16 25C16 25 22 22.5 22 19.5V14.5L16 17.5L10 14.5Z" fill="#93C5FD" fill-opacity="0.9"/>
                                <circle cx="26" cy="16" r="2.5" fill="#EF4444"/>
                                <defs>
                                    <linearGradient id="logo-grad" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#1E3A8A"/>
                                        <stop offset="1" stop-color="#2563EB"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="brand-text-stack">
                            <div class="brand-title">
                                <span class="logo-accent">EduGov</span><span class="logo-sub">News<span class="logo-dot">.</span></span>
                            </div>
                            <span class="logo-tagline"><span class="live-dot"></span> OFFICIAL PORTAL</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Header Quick Search Bar (Desktop) -->
            <div class="header-search-box">
                <form action="/search" method="get" class="search-form">
                    <input type="text" name="q" placeholder="Search exams, results, admit cards..." aria-label="Search notifications" required>
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
                <a href="/recruitment" class="btn-gov-jobs">🏛️ Latest Jobs</a>
            </div>
        </div>

        <!-- 1. Desktop Traditional Navigation Bar -->
        <nav class="desktop-main-nav">
            <div class="site-container nav-items-row">
                <a href="/" class="nav-item <?= ($current_category ?? '') === '' && $_SERVER['REQUEST_URI'] === '/' ? 'active' : '' ?>">🏠 Home</a>
                <a href="/results" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'results') ? 'active' : '' ?>">📋 Results</a>
                <a href="/admit-card" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'admit-card') ? 'active' : '' ?>">🎫 Admit Card</a>
                <a href="/recruitment" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'recruitment') ? 'active' : '' ?>">💼 Recruitment</a>
                <a href="/exam" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'exam') ? 'active' : '' ?>">📝 Exam Dates</a>
                <a href="/answer-key" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'answer-key') ? 'active' : '' ?>">🔑 Answer Key</a>
                <a href="/category/scholarship" class="nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'scholarship') ? 'active' : '' ?>">🏆 Scholarship</a>
                
                <!-- Desktop Dropdown -->
                <div class="nav-dropdown">
                    <button class="nav-dropdown-btn" id="moreCategoriesBtn">More Categories ▾</button>
                    <div class="nav-dropdown-menu">
                        <a href="/category/admission">🎓 Admission & Counseling</a>
                        <a href="/category/application-form">📑 Application Forms</a>
                        <a href="/category/board-exams">🏫 Board Exams (CBSE/ICSE)</a>
                        <a href="/category/entrance-exams">🎯 Entrance Exams (JEE/NEET)</a>
                        <a href="/category/government-jobs">🏛️ All Government Jobs</a>
                        <a href="/category/important-updates">⚡ Important Updates</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- 2. Mobile Smart Category Swipeable Bar (Sticky Top Pills) -->
        <div class="mobile-smart-tabs-bar">
            <div class="smart-tabs-scroll-track" id="categoryScrollTrack">
                <a href="/" class="smart-tab-pill <?= ($_SERVER['REQUEST_URI'] === '/') ? 'active' : '' ?>">
                    <span>🏠</span> All
                </a>
                <a href="/results" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'results') ? 'active' : '' ?>">
                    <span>📋</span> Results
                </a>
                <a href="/admit-card" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'admit-card') ? 'active' : '' ?>">
                    <span>🎫</span> Admit Cards
                </a>
                <a href="/recruitment" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'recruitment') ? 'active' : '' ?>">
                    <span>💼</span> Recruitment
                </a>
                <a href="/exam" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'exam') ? 'active' : '' ?>">
                    <span>📝</span> Exams
                </a>
                <a href="/answer-key" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'answer-key') ? 'active' : '' ?>">
                    <span>🔑</span> Answer Key
                </a>
                <a href="/category/scholarship" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'scholarship') ? 'active' : '' ?>">
                    <span>🏆</span> Scholarships
                </a>
                <a href="/category/entrance-exams" class="smart-tab-pill <?= str_contains($_SERVER['REQUEST_URI'], 'entrance-exams') ? 'active' : '' ?>">
                    <span>🎯</span> JEE / NEET
                </a>
            </div>
        </div>
    </header>

    <!-- Breaking News Marquee Ticker (Clickable Articles!) -->
    <div class="breaking-ticker-bar">
        <div class="site-container ticker-inner">
            <span class="ticker-badge">⚡ BREAKING</span>
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
                        <span class="ticker-link">• National Education & Recruitment Ingestion Network Active</span>
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
                <span class="nav-text">Home</span>
            </a>
            <a href="/results" class="bottom-nav-item nav-item-results <?= str_contains($_SERVER['REQUEST_URI'], 'results') ? 'active' : '' ?>">
                <span class="nav-icon icon-results">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="#d1fae5" fill-opacity="0.6"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </span>
                <span class="nav-text">Results</span>
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
                <span class="nav-text">Admit Card</span>
            </a>
            <a href="/recruitment" class="bottom-nav-item nav-item-jobs <?= str_contains($_SERVER['REQUEST_URI'], 'recruitment') ? 'active' : '' ?>">
                <span class="nav-icon icon-jobs">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" fill="#fef3c7" fill-opacity="0.6"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </span>
                <span class="nav-text">Jobs</span>
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
                <span class="nav-text">Explore</span>
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
                    <a href="/search?q=SSC" class="search-tag-pill">SSC</a>
                    <a href="/search?q=UPSC" class="search-tag-pill">UPSC</a>
                    <a href="/search?q=Railway" class="search-tag-pill">Railway</a>
                    <a href="/search?q=Admit+Card" class="search-tag-pill">Admit Card</a>
                    <a href="/search?q=Results" class="search-tag-pill">Results</a>
                    <a href="/search?q=Rajasthan" class="search-tag-pill">Rajasthan</a>
                    <a href="/search?q=West+Bengal" class="search-tag-pill">West Bengal</a>
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
        </div>

        <div class="drawer-body-content">
            <!-- Primary Hubs (2x2 Big Touch Cards) -->
            <div class="drawer-section-title">⚡ PRIMARY HUBS</div>
            <div class="drawer-hubs-grid">
                <a href="/results" class="drawer-hub-card">
                    <div class="hub-icon">📋</div>
                    <div class="hub-label">Results</div>
                    <div class="hub-sub">Merit lists & cutoffs</div>
                </a>
                <a href="/admit-card" class="drawer-hub-card">
                    <div class="hub-icon">🎫</div>
                    <div class="hub-label">Admit Card</div>
                    <div class="hub-sub">Hall tickets & slips</div>
                </a>
                <a href="/recruitment" class="drawer-hub-card">
                    <div class="hub-icon">💼</div>
                    <div class="hub-label">Recruitment</div>
                    <div class="hub-sub">10,000+ Govt vacancies</div>
                </a>
                <a href="/exam" class="drawer-hub-card">
                    <div class="hub-icon">📝</div>
                    <div class="hub-label">Exam Dates</div>
                    <div class="hub-sub">Schedules & calendars</div>
                </a>
            </div>

            <!-- State Matrix in Drawer -->
            <div class="drawer-section-title">🗺️ STATE-WISE RECRUITMENT</div>
            <div class="drawer-chip-cluster">
                <a href="/state/central-govt" class="chip-item">🏛️ Central Govt</a>
                <a href="/state/west-bengal" class="chip-item">🌊 West Bengal</a>
                <a href="/state/uttar-pradesh" class="chip-item">🌾 Uttar Pradesh</a>
                <a href="/state/bihar" class="chip-item">🚩 Bihar</a>
                <a href="/state/rajasthan" class="chip-item">🏰 Rajasthan</a>
                <a href="/state/madhya-pradesh" class="chip-item">🌲 Madhya Pradesh</a>
                <a href="/state/maharashtra" class="chip-item">🏙️ Maharashtra</a>
            </div>

            <!-- Recruitment & Opportunities -->
            <div class="drawer-section-title">🏛️ RECRUITMENT & OPPORTUNITIES</div>
            <div class="drawer-chip-cluster">
                <a href="/recruitment" class="chip-item">💼 All Recruitment</a>
                <a href="/category/government-jobs" class="chip-item">🏛️ Government Jobs</a>
                <a href="/category/application-form" class="chip-item">📑 Application Forms</a>
                <a href="/category/scholarship" class="chip-item">🏆 Scholarships</a>
            </div>

            <!-- Exams & Admissions -->
            <div class="drawer-section-title">🎯 EXAMS & ADMISSIONS</div>
            <div class="drawer-chip-cluster">
                <a href="/exam" class="chip-item">📝 Exam Calendar</a>
                <a href="/answer-key" class="chip-item">🔑 Answer Keys</a>
                <a href="/category/entrance-exams" class="chip-item">🎯 JEE / NEET / CUET</a>
                <a href="/category/board-exams" class="chip-item">🏫 CBSE & ICSE Boards</a>
                <a href="/category/admission" class="chip-item">🎓 Admission & Counseling</a>
            </div>

            <!-- Policies & Legal -->
            <div class="drawer-section-title">🛡️ ABOUT & POLICIES</div>
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
                <div class="brand-logo-wrap" style="margin-bottom: 0.75rem;">
                    <div class="brand-text-stack">
                        <div class="brand-title">
                            <span class="logo-accent" style="color: #60a5fa;">EduGov</span><span class="logo-sub" style="color: #ffffff;">News<span class="logo-dot">.</span></span>
                        </div>
                    </div>
                </div>
                <p class="footer-desc">
                    EduGov News is a high-speed education news dissemination platform providing direct access to verified public government announcements.
                </p>
                <div class="footer-disclaimer-badge">
                    ⚖️ <strong>Disclaimer:</strong> EduGov News is an independent news reporting portal and is NOT affiliated with any government authority. Always verify details on official government (.gov.in/.nic.in) portals.
                </div>
            </div>

            <div class="footer-col">
                <div class="footer-heading">⚡ Primary Hubs</div>
                <ul class="footer-links">
                    <li><a href="/results">Results & Merit Lists</a></li>
                    <li><a href="/admit-card">Admit Cards & Slips</a></li>
                    <li><a href="/recruitment">Government Recruitment</a></li>
                    <li><a href="/exam">Examination Dates</a></li>
                    <li><a href="/answer-key">Official Answer Keys</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <div class="footer-heading">🗺️ State Portals</div>
                <ul class="footer-links">
                    <li><a href="/state/central-govt">Central Govt Jobs</a></li>
                    <li><a href="/state/west-bengal">West Bengal (WBPSC)</a></li>
                    <li><a href="/state/uttar-pradesh">Uttar Pradesh (UPPSC)</a></li>
                    <li><a href="/state/bihar">Bihar (BPSC)</a></li>
                    <li><a href="/state/rajasthan">Rajasthan (RPSC)</a></li>
                    <li><a href="/state/madhya-pradesh">Madhya Pradesh (MPPSC)</a></li>
                    <li><a href="/state/maharashtra">Maharashtra (MPSC)</a></li>
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

    <!-- Interactive Scripts -->
    <script src="/static/js/main.js?v=<?= time() ?>"></script>
</body>
</html>
