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
    <link rel="stylesheet" href="/static/css/main.css">
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
                <button class="theme-toggle-btn js-theme-toggle" aria-label="Toggle Eye Comfort Dark Mode">
                    🌓 <span class="theme-label">Eye Comfort</span>
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

                <a href="/" class="brand-logo" title="EduGov News Homepage">
                    <span class="logo-accent">EduGov</span><span class="logo-sub">News<span class="logo-dot">.</span></span>
                    <span class="logo-tagline"><span class="live-dot"></span> Verified Official Education Updates</span>
                </a>
            </div>

            <!-- Header Quick Search Bar (Desktop) -->
            <div class="header-search-box">
                <form action="/search" method="get" class="search-form">
                    <input type="text" name="q" placeholder="Search exams, results, admit cards..." aria-label="Search notifications" required>
                    <button type="submit" aria-label="Search">🔍</button>
                </form>
            </div>

            <div class="header-right-actions">
                <button class="mobile-icon-btn js-theme-toggle" aria-label="Toggle Dark Mode">🌓</button>
                <button id="mobileSearchTrigger" class="mobile-icon-btn" aria-label="Search">🔍</button>
                <button id="mobileExploreTrigger" class="mobile-icon-btn" aria-label="Explore Categories">⚡</button>
                <a href="/recruitment" class="btn-gov-jobs">🏛️ Latest Jobs</a>
            </div>
        </div>

        <!-- 1. Desktop Traditional Navigation Bar -->
        <nav class="desktop-main-nav">
            <div class="site-container nav-items-row">
                <a href="/" class="nav-item <?= ($current_category ?? '') === '' && $_SERVER['REQUEST_URI'] === '/' ? 'active' : '' ?>">🏠 Home</a>
                <a href="/results" class="nav-item <?= ($current_category ?? '') === 'results' ? 'active' : '' ?>">📋 Results</a>
                <a href="/admit-card" class="nav-item <?= ($current_category ?? '') === 'admit-card' ? 'active' : '' ?>">🎫 Admit Card</a>
                <a href="/recruitment" class="nav-item <?= ($current_category ?? '') === 'recruitment' ? 'active' : '' ?>">💼 Recruitment</a>
                <a href="/exam" class="nav-item <?= ($current_category ?? '') === 'exam' ? 'active' : '' ?>">📝 Exam Dates</a>
                <a href="/answer-key" class="nav-item <?= ($current_category ?? '') === 'answer-key' ? 'active' : '' ?>">🔑 Answer Key</a>
                <a href="/category/scholarship" class="nav-item <?= ($current_category ?? '') === 'scholarship' ? 'active' : '' ?>">🏆 Scholarship</a>
                
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

    <!-- Breaking News Marquee Ticker -->
    <div class="breaking-ticker-bar">
        <div class="site-container ticker-inner">
            <span class="ticker-badge">⚡ BREAKING</span>
            <div class="ticker-marquee">
                <div class="ticker-items">
                    <span class="ticker-item">• SSC CGL 2026 Tier-1 Examination Result and Cutoff Marks Declared</span>
                    <span class="ticker-item">• [Admit Card] SSC CHSL 2026 Tier-1 Admit Card & Application Status Released</span>
                    <span class="ticker-item">• [Notification] RRB NTPC 2026 Centralized Notification for 11,558 Posts</span>
                    <span class="ticker-item">• [Exam Date] CBSE Class 10th & 12th Board Examination 2026 Date Sheet Announced</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Yield -->
    <div class="site-container main-wrapper">
        <?= $content ?>
    </div>

    <!-- 3. Mobile App-Style Fixed Bottom Dock -->
    <nav class="mobile-bottom-nav">
        <div class="bottom-nav-inner">
            <a href="/" class="bottom-nav-item <?= ($_SERVER['REQUEST_URI'] === '/') ? 'active' : '' ?>">
                <span class="nav-icon">🏠</span>
                <span class="nav-text">Home</span>
            </a>
            <a href="/results" class="bottom-nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'results') ? 'active' : '' ?>">
                <span class="nav-icon">📋</span>
                <span class="nav-text">Results</span>
            </a>
            <a href="/admit-card" class="bottom-nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'admit-card') ? 'active' : '' ?>">
                <span class="nav-icon">🎫</span>
                <span class="nav-text">Admit Card</span>
            </a>
            <a href="/recruitment" class="bottom-nav-item <?= str_contains($_SERVER['REQUEST_URI'], 'recruitment') ? 'active' : '' ?>">
                <span class="nav-icon">💼</span>
                <span class="nav-text">Jobs</span>
            </a>
            <button type="button" class="bottom-nav-item" id="bottomMenuTrigger" aria-label="Open Full Category Explorer">
                <span class="nav-icon">⚡</span>
                <span class="nav-text">Explore</span>
            </button>
        </div>
    </nav>

    <!-- 4. Categorized Bottom Sheet Modal (Drawer) -->
    <div class="drawer-backdrop" id="drawerBackdrop"></div>
    <div class="mobile-offcanvas-drawer" id="mobileDrawer">
        <div class="drawer-handle-bar">
            <div class="handle-pill"></div>
        </div>

        <div class="drawer-header">
            <div class="drawer-title">EduGov News<span class="logo-dot">.</span></div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <button class="theme-toggle-btn js-theme-toggle" style="padding: 0.3rem 0.6rem;">🌓 Theme</button>
                <button class="drawer-close-btn" id="closeDrawerBtn" aria-label="Close menu">✕</button>
            </div>
        </div>

        <!-- In-Drawer Quick Search -->
        <div class="drawer-search-wrap">
            <form action="/search" method="get" class="drawer-search-form">
                <input type="text" name="q" id="drawerSearchInput" placeholder="Search exams, results, notifications..." required>
                <button type="submit">🔍</button>
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
                <div class="footer-logo">
                    <span class="logo-accent">EduGov</span><span class="logo-sub">News.</span>
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
    <script src="/static/js/main.js"></script>
</body>
</html>
