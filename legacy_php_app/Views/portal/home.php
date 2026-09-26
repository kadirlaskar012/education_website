<!-- ==========================================================================
     EDUGOV NEWS — ULTRA-PREMIUM HOMEPAGE ARCHITECTURE
     Features: Hero Featured Showcase, Live Top 10 Stream, Smart Filter Hub,
     State Quick Matrix, Categorized News Feed & Interactive Community Banners
     ========================================================================== -->

<!-- Top Hero Section: Multi-Story Editorial Spotlight + Live Top Stream -->
<section class="premium-hero-section">
    <div class="hero-grid-container">
        <!-- 1. Left Column: Featured Spotlight Hub (Primary Story + 2 Sub-Featured Mini Cards) -->
        <div class="hero-left-hub">
            <?php 
            $leadStory = $top10_notices[0] ?? null;
            if ($leadStory): 
            ?>
            <!-- Primary Spotlight Lead Card -->
            <article class="hero-lead-card">
                <div class="lead-card-header">
                    <div class="badge-cluster">
                        <span class="spotlight-badge"><span class="pulsing-dot"></span> <?= htmlspecialchars(__('top_announcement')) ?></span>
                        <span class="cat-pill"><?= htmlspecialchars($leadStory['category_name']) ?></span>
                        <?php if (!empty($leadStory['official_source_name'])): ?>
                        <span class="source-pill">🏛️ <?= htmlspecialchars($leadStory['official_source_name']) ?></span>
                        <?php endif; ?>
                    </div>
                    <time class="lead-time" datetime="<?= $leadStory['published_at'] ?>">
                        📅 <?= date('M j, Y • g:i A', strtotime($leadStory['published_at'])) ?>
                    </time>
                </div>

                <h1 class="lead-headline">
                    <a href="/news/<?= htmlspecialchars($leadStory['slug']) ?>">
                        <?= htmlspecialchars($leadStory['title']) ?>
                    </a>
                </h1>

                <p class="lead-excerpt">
                    <?= htmlspecialchars(mb_substr($leadStory['excerpt'] ?? strip_tags($leadStory['content_html'] ?? ''), 0, 160)) ?>...
                </p>

                <div class="lead-footer">
                    <div class="lead-tags-cluster">
                        <span class="status-chip chip-active">⚡ <?= htmlspecialchars(__('direct_apply_active')) ?></span>
                        <span class="status-chip chip-eligible">🎓 <?= htmlspecialchars(__('all_eligible')) ?></span>
                    </div>
                    <a href="/news/<?= htmlspecialchars($leadStory['slug']) ?>" class="btn-lead-read">
                        <?= htmlspecialchars(__('read_full_notification')) ?> <span class="arrow-icon">→</span>
                    </a>
                </div>
            </article>
            <?php endif; ?>

            <!-- Secondary 2-Card Spotlight Row (Sub-Features) -->
            <?php 
            $subStory1 = $top10_notices[1] ?? null;
            $subStory2 = $top10_notices[2] ?? null;
            if ($subStory1 || $subStory2):
            ?>
            <div class="hero-sub-grid">
                <?php foreach ([$subStory1, $subStory2] as $sub): if (!$sub) continue; ?>
                <article class="hero-sub-card">
                    <div class="sub-card-top">
                        <span class="sub-cat-pill"><?= htmlspecialchars($sub['category_name']) ?></span>
                        <time class="sub-time">📅 <?= date('M j, Y', strtotime($sub['published_at'])) ?></time>
                    </div>
                    <h2 class="sub-headline">
                        <a href="/news/<?= htmlspecialchars($sub['slug']) ?>">
                            <?= htmlspecialchars(mb_substr($sub['title'], 0, 75)) ?><?= mb_strlen($sub['title']) > 75 ? '...' : '' ?>
                        </a>
                    </h2>
                    <div class="sub-card-footer">
                        <?php if (!empty($sub['official_source_name'])): ?>
                        <span class="sub-source">🏛️ <?= htmlspecialchars(mb_substr($sub['official_source_name'], 0, 22)) ?></span>
                        <?php else: ?>
                        <span class="sub-source">🏛️ Official Notice</span>
                        <?php endif; ?>
                        <a href="/news/<?= htmlspecialchars($sub['slug']) ?>" class="sub-read-link">
                            <?= htmlspecialchars(__('read_more')) ?> →
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- 2. Right Column: Live Trending Stream (Items 4 to 9) -->
        <div class="hero-stream-card">
            <div class="stream-card-header">
                <div class="stream-title-wrap">
                    <span class="stream-icon">⚡</span>
                    <h2 class="stream-title"><?= htmlspecialchars(__('trending_live_notices')) ?></h2>
                </div>
                <span class="stream-live-tag"><?= htmlspecialchars(__('live_updates')) ?></span>
            </div>

            <div class="stream-items-scroll">
                <?php 
                $rank = 1;
                $trendingList = count($top10_notices) > 3 ? array_slice($top10_notices, 3, 6) : array_slice($top10_notices, 1, 6);
                foreach ($trendingList as $notice): 
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
                <a href="/recruitment" class="stream-view-more"><?= htmlspecialchars(__('browse_all_notices')) ?></a>
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
                <strong class="community-title"><?= htmlspecialchars(__('community_bar_title')) ?></strong>
                <p class="community-sub"><?= htmlspecialchars(__('community_bar_sub')) ?></p>
            </div>
        </div>
        <div class="community-actions">
            <a href="https://t.me/edugov_news_bot" target="_blank" rel="noopener noreferrer" class="btn-community btn-tg">
                <?= htmlspecialchars(__('join_telegram')) ?>
            </a>
            <a href="https://api.whatsapp.com/send?text=Join+EduGov+News+Portal" target="_blank" rel="noopener noreferrer" class="btn-community btn-wa">
                <?= htmlspecialchars(__('join_whatsapp')) ?>
            </a>
        </div>
    </div>
</section>

<!-- State & Central Portals Quick Matrix -->
<section class="state-matrix-section">
    <div class="section-top-row">
        <div class="section-title-wrap">
            <span class="sec-icon">🗺️</span>
            <h2 class="sec-title"><?= htmlspecialchars(__('state_matrix_title')) ?></h2>
        </div>
        <span class="sec-subtitle"><?= htmlspecialchars(__('state_matrix_sub')) ?></span>
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
                    <h2 class="stream-block-title"><?= htmlspecialchars(__('results_section_title')) ?></h2>
                </div>
                <a href="/results" class="stream-view-all"><?= htmlspecialchars(__('view_all_results')) ?></a>
            </div>

            <div class="news-cards-flow">
                <?php foreach ($results_articles as $art): ?>
                <article class="feed-news-card">
                    <div class="card-meta-row">
                        <span class="card-badge badge-result"><?= htmlspecialchars($art['category_name']) ?></span>
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
                            <?= htmlspecialchars(__('check_score_merit')) ?> <span class="arrow">↗</span>
                        </a>
                        <span class="card-views">👁️ <?= (int)$art['views_count'] ?> <?= htmlspecialchars(__('views_count')) ?></span>
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
                    <h2 class="stream-block-title"><?= htmlspecialchars(__('admit_cards_section_title')) ?></h2>
                </div>
                <a href="/admit-card" class="stream-view-all"><?= htmlspecialchars(__('view_all_admits')) ?></a>
            </div>

            <div class="news-cards-flow">
                <?php foreach ($admit_card_articles as $art): ?>
                <article class="feed-news-card">
                    <div class="card-meta-row">
                        <span class="card-badge badge-admit"><?= htmlspecialchars($art['category_name']) ?></span>
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
                            <?= htmlspecialchars(__('download_admit_btn')) ?> <span class="arrow">↗</span>
                        </a>
                        <span class="card-views">👁️ <?= (int)$art['views_count'] ?> <?= htmlspecialchars(__('views_count')) ?></span>
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
                    <h2 class="stream-block-title"><?= htmlspecialchars(__('jobs_section_title')) ?></h2>
                </div>
                <a href="/recruitment" class="stream-view-all"><?= htmlspecialchars(__('view_all_jobs')) ?></a>
            </div>

            <div class="news-cards-flow">
                <?php foreach ($recruitment_articles as $art): ?>
                <article class="feed-news-card">
                    <div class="card-meta-row">
                        <span class="card-badge badge-jobs"><?= htmlspecialchars($art['category_name']) ?></span>
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
                            <?= htmlspecialchars(__('read_eligibility_apply')) ?> <span class="arrow">↗</span>
                        </a>
                        <span class="card-views">👁️ <?= (int)$art['views_count'] ?> <?= htmlspecialchars(__('views_count')) ?></span>
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
