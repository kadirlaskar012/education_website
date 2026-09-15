<!-- ==========================================================================
     EDUGOV NEWS — ULTRA-PREMIUM HOMEPAGE ARCHITECTURE
     Features: Hero Featured Showcase, Live Top 10 Stream, Smart Filter Hub,
     State Quick Matrix, Categorized News Feed & Interactive Community Banners
     ========================================================================== -->

<!-- Top Hero Section: Featured Breaking Notice + Live Top 10 Ticker Stream -->
<section class="premium-hero-section">
    <div class="hero-grid-container">
        <!-- 1. Featured Top Story (65% width) -->
        <?php 
        $featuredStory = $top10_notices[0] ?? null;
        if ($featuredStory): 
            $featWordCount = str_word_count(strip_tags($featuredStory['excerpt'] ?? ''));
            $featReadTime = max(1, (int)ceil($featWordCount / 200));
        ?>
        <div class="hero-featured-card">
            <div class="featured-card-top">
                <div class="badge-cluster">
                    <span class="pulse-live-badge"><span class="pulsing-dot"></span> TOP ANNOUNCEMENT</span>
                    <span class="cat-pill"><?= htmlspecialchars($featuredStory['category_name']) ?></span>
                    <?php if (!empty($featuredStory['official_source_name'])): ?>
                    <span class="source-pill">🏛️ <?= htmlspecialchars($featuredStory['official_source_name']) ?></span>
                    <?php endif; ?>
                </div>
                <time class="featured-time" datetime="<?= $featuredStory['published_at'] ?>">
                    📅 <?= date('M j, Y • g:i A', strtotime($featuredStory['published_at'])) ?>
                </time>
            </div>

            <h1 class="featured-headline">
                <a href="/news/<?= htmlspecialchars($featuredStory['slug']) ?>">
                    <?= htmlspecialchars($featuredStory['title']) ?>
                </a>
            </h1>

            <p class="featured-excerpt">
                <?= htmlspecialchars(mb_substr($featuredStory['excerpt'] ?? strip_tags($featuredStory['content_html'] ?? ''), 0, 195)) ?>...
            </p>

            <div class="featured-footer">
                <div class="featured-tags-row">
                    <span class="ft-tag">🎓 All Eligible</span>
                    <span class="ft-tag">⚡ Direct Apply Active</span>
                </div>
                <a href="/news/<?= htmlspecialchars($featuredStory['slug']) ?>" class="btn-hero-action">
                    Read Full Notification <span class="arrow-icon">→</span>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- 2. Live Top 10 Updates Stream (35% width) -->
        <div class="hero-stream-card">
            <div class="stream-card-header">
                <div class="stream-title-wrap">
                    <span class="stream-icon">⚡</span>
                    <h2 class="stream-title">Trending Live Notices</h2>
                </div>
                <span class="stream-live-tag">LIVE UPDATES</span>
            </div>

            <div class="stream-items-scroll">
                <?php 
                $rank = 1;
                foreach (array_slice($top10_notices, 1, 6) as $notice): 
                    $rankPadded = str_pad((string)$rank, 2, '0', STR_PAD_LEFT);
                    $rankClass = ($rank <= 3) ? 'top-rank' : '';
                    $rank++;
                ?>
                <a href="/news/<?= htmlspecialchars($notice['slug']) ?>" class="stream-notice-row">
                    <div class="rank-pill <?= $rankClass ?>">#<?= $rankPadded ?></div>
                    <div class="stream-notice-info">
                        <div class="stream-meta">
                            <span class="stream-cat"><?= htmlspecialchars($notice['category_name']) ?></span>
                            <span class="stream-dot">•</span>
                            <span class="stream-time"><?= date('M j', strtotime($notice['published_at'])) ?></span>
                        </div>
                        <h3 class="stream-headline"><?= htmlspecialchars($notice['title']) ?></h3>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="stream-card-footer">
                <a href="/recruitment" class="stream-view-more">Browse All Active Government Notices »</a>
            </div>
        </div>
    </div>
</section>

<!-- Community Alert Bar (WhatsApp & Telegram High-CTR Channels) -->
<section class="social-community-bar">
    <div class="community-bar-inner">
        <div class="community-info">
            <span class="community-icon">🔔</span>
            <div>
                <strong class="community-title">Get Instant Exam & Job Alerts on Mobile!</strong>
                <p class="community-sub">Join 100,000+ candidates receiving verified official notifications in 1 click.</p>
            </div>
        </div>
        <div class="community-actions">
            <a href="https://t.me/edugov_news_bot" target="_blank" rel="noopener noreferrer" class="btn-community btn-tg">
                ✈️ Join Telegram Channel
            </a>
            <a href="https://api.whatsapp.com/send?text=Join+EduGov+News+Portal" target="_blank" rel="noopener noreferrer" class="btn-community btn-wa">
                💬 Join WhatsApp Group
            </a>
        </div>
    </div>
</section>

<!-- State & Central Portals Quick Matrix -->
<section class="state-matrix-section">
    <div class="section-top-row">
        <div class="section-title-wrap">
            <span class="sec-icon">🗺️</span>
            <h2 class="sec-title">State-Wise & Central Portals</h2>
        </div>
        <span class="sec-subtitle">Direct access to state public service commissions</span>
    </div>

    <div class="state-pills-grid">
        <a href="/state/central-govt" class="state-tile">
            <span class="st-icon">🏛️</span>
            <div class="st-details">
                <strong class="st-name">Central Govt</strong>
                <span class="st-sub">SSC, UPSC, Railway, IBPS</span>
            </div>
        </a>
        <a href="/state/west-bengal" class="state-tile">
            <span class="st-icon">🌊</span>
            <div class="st-details">
                <strong class="st-name">West Bengal</strong>
                <span class="st-sub">WBPSC, WBP Police, Primary</span>
            </div>
        </a>
        <a href="/state/uttar-pradesh" class="state-tile">
            <span class="st-icon">🌾</span>
            <div class="st-details">
                <strong class="st-name">Uttar Pradesh</strong>
                <span class="st-sub">UPPSC, UPSSSC, Police</span>
            </div>
        </a>
        <a href="/state/bihar" class="state-tile">
            <span class="st-icon">🚩</span>
            <div class="st-details">
                <strong class="st-name">Bihar</strong>
                <span class="st-sub">BPSC, BSSC, Police CSBC</span>
            </div>
        </a>
        <a href="/state/rajasthan" class="state-tile">
            <span class="st-icon">🏰</span>
            <div class="st-details">
                <strong class="st-name">Rajasthan</strong>
                <span class="st-sub">RPSC, RSMSSB, Police</span>
            </div>
        </a>
        <a href="/state/madhya-pradesh" class="state-tile">
            <span class="st-icon">🌲</span>
            <div class="st-details">
                <strong class="st-name">Madhya Pradesh</strong>
                <span class="st-sub">MPPSC, MPPEB Vyapam</span>
            </div>
        </a>
        <a href="/state/maharashtra" class="state-tile">
            <span class="st-icon">🏙️</span>
            <div class="st-details">
                <strong class="st-name">Maharashtra</strong>
                <span class="st-sub">MPSC, MahaTransco, Police</span>
            </div>
        </a>
        <a href="/state/all-india" class="state-tile highlight-tile">
            <span class="st-icon">🇮🇳</span>
            <div class="st-details">
                <strong class="st-name">All India Hub</strong>
                <span class="st-sub">Nationwide Notifications</span>
            </div>
        </a>
    </div>
</section>

<!-- Interactive Live Smart Multi-Axis Filter Hub -->
<?php require __DIR__ . '/../partials/smart_filter_hub.php'; ?>

<!-- Top Home Feed Responsive AdSense Placement Slot -->
<div class="adsense-slot-wrapper adsense-slot-feed" aria-label="Sponsored Advertisement">
    <div class="ad-disclosure-bar"><span class="ad-label">ADVERTISEMENT</span></div>
    <div class="ad-banner-placeholder">
        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="3344556677" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <div class="ad-demo-preview">
            <span class="ad-demo-icon">📢</span>
            <span class="ad-demo-text">Homepage High-CTR Responsive Ad Placement</span>
        </div>
    </div>
</div>

<!-- Homepage Main Categorized Feeds with Right Sidebar -->
<div class="feed-layout-grid">
    <!-- Main Stream Column -->
    <main class="feed-main-col">

        <!-- 1. 📋 Results Section -->
        <?php if (!empty($results_articles)): ?>
        <section class="stream-block-container">
            <div class="stream-block-header header-results">
                <div class="header-left-title">
                    <span class="header-icon">📋</span>
                    <h2 class="stream-block-title">Results & Merit Lists</h2>
                </div>
                <a href="/results" class="stream-view-all">View All Results →</a>
            </div>

            <div class="news-cards-flow">
                <?php foreach ($results_articles as $art): ?>
                <article class="feed-news-card">
                    <div class="card-meta-row">
                        <span class="card-badge badge-result">Official Result</span>
                        <?php if (!empty($art['official_source_name'])): ?>
                        <span class="card-authority">🏛️ <?= htmlspecialchars($art['official_source_name']) ?></span>
                        <?php endif; ?>
                        <time class="card-date" datetime="<?= $art['published_at'] ?>">📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></time>
                    </div>

                    <h3 class="card-headline">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                            <?= htmlspecialchars($art['title']) ?>
                        </a>
                    </h3>

                    <p class="card-excerpt">
                        <?= htmlspecialchars(mb_substr($art['excerpt'] ?? strip_tags($art['content_html']), 0, 140)) ?>...
                    </p>

                    <div class="card-footer-row">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="card-read-link">
                            Check Score & Merit List <span class="arrow">↗</span>
                        </a>
                        <span class="card-views">👁️ <?= (int)$art['views_count'] ?> views</span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 2. 🎫 Admit Cards Section -->
        <?php if (!empty($admit_card_articles)): ?>
        <section class="stream-block-container" style="margin-top: 2rem;">
            <div class="stream-block-header header-admit">
                <div class="header-left-title">
                    <span class="header-icon">🎫</span>
                    <h2 class="stream-block-title">Admit Cards & Exam City Slips</h2>
                </div>
                <a href="/admit-card" class="stream-view-all">View All Admit Cards →</a>
            </div>

            <div class="news-cards-flow">
                <?php foreach ($admit_card_articles as $art): ?>
                <article class="feed-news-card">
                    <div class="card-meta-row">
                        <span class="card-badge badge-admit">Hall Ticket</span>
                        <?php if (!empty($art['official_source_name'])): ?>
                        <span class="card-authority">🏛️ <?= htmlspecialchars($art['official_source_name']) ?></span>
                        <?php endif; ?>
                        <time class="card-date" datetime="<?= $art['published_at'] ?>">📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></time>
                    </div>

                    <h3 class="card-headline">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                            <?= htmlspecialchars($art['title']) ?>
                        </a>
                    </h3>

                    <p class="card-excerpt">
                        <?= htmlspecialchars(mb_substr($art['excerpt'] ?? strip_tags($art['content_html']), 0, 140)) ?>...
                    </p>

                    <div class="card-footer-row">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="card-read-link">
                            Download Admit Card <span class="arrow">↗</span>
                        </a>
                        <span class="card-views">👁️ <?= (int)$art['views_count'] ?> views</span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 3. 💼 Government Recruitment Section -->
        <?php if (!empty($recruitment_articles)): ?>
        <section class="stream-block-container" style="margin-top: 2rem;">
            <div class="stream-block-header header-jobs">
                <div class="header-left-title">
                    <span class="header-icon">💼</span>
                    <h2 class="stream-block-title">Government Recruitment & Vacancies</h2>
                </div>
                <a href="/recruitment" class="stream-view-all">View All Recruitment →</a>
            </div>

            <div class="news-cards-flow">
                <?php foreach ($recruitment_articles as $art): ?>
                <article class="feed-news-card">
                    <div class="card-meta-row">
                        <span class="card-badge badge-jobs">New Vacancy</span>
                        <?php if (!empty($art['official_source_name'])): ?>
                        <span class="card-authority">🏛️ <?= htmlspecialchars($art['official_source_name']) ?></span>
                        <?php endif; ?>
                        <time class="card-date" datetime="<?= $art['published_at'] ?>">📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></time>
                    </div>

                    <h3 class="card-headline">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                            <?= htmlspecialchars($art['title']) ?>
                        </a>
                    </h3>

                    <p class="card-excerpt">
                        <?= htmlspecialchars(mb_substr($art['excerpt'] ?? strip_tags($art['content_html']), 0, 140)) ?>...
                    </p>

                    <div class="card-footer-row">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="card-read-link">
                            Read Eligibility & Apply <span class="arrow">↗</span>
                        </a>
                        <span class="card-views">👁️ <?= (int)$art['views_count'] ?> views</span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <!-- Shared Right Sidebar -->
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
</div>
