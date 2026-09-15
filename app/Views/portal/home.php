<!-- Top 10 Latest Added Notices Hero Board (Clean Text-Based & High-Performance) -->
<?php if (!empty($top10_notices)): ?>
<section class="hero-notices-board">
    <div class="hero-notices-header">
        <div class="notices-header-left">
            <span class="live-pulse-icon">🔴</span>
            <h1 class="hero-notices-title">Latest Official Notices (Top 10 Updates)</h1>
        </div>
        <div class="notices-header-badge">
            ⚡ Real-Time Government Feed
        </div>
    </div>

    <div class="hero-notices-list">
        <?php 
        $rank = 1;
        foreach ($top10_notices as $notice): 
            $rankPadded = str_pad((string)$rank, 2, '0', STR_PAD_LEFT);
            $rankClass = ($rank === 1) ? 'rank-1' : (($rank === 2) ? 'rank-2' : (($rank === 3) ? 'rank-3' : ''));
            $rank++;
        ?>
        <div class="hero-notice-row">
            <div class="notice-num-wrap">
                <span class="notice-num <?= $rankClass ?>">#<?= $rankPadded ?></span>
            </div>
            <div class="notice-details">
                <div class="notice-meta-tags">
                    <span class="cat-badge-text"><?= htmlspecialchars($notice['category_name']) ?></span>
                    <?php if (!empty($notice['official_source_name'])): ?>
                    <span class="authority-badge-text">🏛️ <?= htmlspecialchars($notice['official_source_name']) ?></span>
                    <?php endif; ?>
                    <time class="notice-time-text" datetime="<?= $notice['published_at'] ?>">
                        📅 <?= date('M j, Y — g:i A', strtotime($notice['published_at'])) ?>
                    </time>
                </div>
                <h2 class="notice-text-headline">
                    <a href="/news/<?= htmlspecialchars($notice['slug']) ?>">
                        <?= htmlspecialchars($notice['title']) ?>
                    </a>
                </h2>
            </div>
            <div class="notice-action-col">
                <a href="/news/<?= htmlspecialchars($notice['slug']) ?>" class="btn-notice-read">
                    Read Notice →
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Instant WhatsApp & Telegram Alert Banner -->
<div class="social-alert-banner">
    <div class="social-alert-text">
        <div class="social-alert-heading">
            <span>🔔 Never Miss an Exam or Job Notice!</span>
        </div>
        <p class="social-alert-sub">
            Join 100,000+ candidates receiving instant verified official notifications directly on phone.
        </p>
    </div>
    <div class="social-alert-buttons">
        <a href="https://telegram.me" target="_blank" rel="noopener noreferrer" class="social-tg-btn">
            ✈️ Join Telegram
        </a>
        <a href="https://whatsapp.com" target="_blank" rel="noopener noreferrer" class="social-wa-btn">
            💬 Join WhatsApp
        </a>
    </div>
</div>

<!-- State-Wise Quick Filter Matrix -->
<section id="state-matrix" class="state-filter-section">
    <div class="section-header-bar">
        <h2 class="section-bar-title">
            🗺️ State & Central Government Jobs Filter
        </h2>
    </div>
    <div class="state-matrix-grid">
        <a href="/state/central-govt" class="state-card-btn">🏛️ Central Govt</a>
        <a href="/state/west-bengal" class="state-card-btn">🌊 West Bengal</a>
        <a href="/state/uttar-pradesh" class="state-card-btn">🌾 Uttar Pradesh</a>
        <a href="/state/bihar" class="state-card-btn">🚩 Bihar</a>
        <a href="/state/rajasthan" class="state-card-btn">🏰 Rajasthan</a>
        <a href="/state/madhya-pradesh" class="state-card-btn">🌲 Madhya Pradesh</a>
        <a href="/state/maharashtra" class="state-card-btn">🏙️ Maharashtra</a>
        <a href="/state/all-india" class="state-card-btn">🇮🇳 All India</a>
    </div>
</section>

<!-- Interactive Live Smart Filter Hub -->
<?php require __DIR__ . '/../partials/smart_filter_hub.php'; ?>

<!-- Top Home Feed Responsive AdSense Placement Slot -->
<div class="adsense-slot-wrapper adsense-slot-feed" aria-label="Sponsored Advertisement">
    <div class="ad-disclosure-bar"><span class="ad-label">ADVERTISEMENT</span></div>
    <div class="ad-banner-placeholder">
        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="3344556677" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <div class="ad-demo-preview">
            <span class="ad-demo-icon">📢</span>
            <span class="ad-demo-text">Homepage Top Feed Leaderboard Ad Placement</span>
        </div>
    </div>
</div>

<!-- Homepage Main Category-Wise Feed with Right Sidebar -->
<div class="feed-layout-grid">
    <!-- Main Categorized Columns -->
    <main class="feed-main-col">

        <!-- 1. 📋 Results Section (Cardless Data Stream) -->
        <?php if (!empty($results_articles)): ?>
        <section class="stream-block-container">
            <div class="stream-block-header stream-header-blue">
                <h2 class="stream-block-title">
                    <span>📋</span> Latest Results & Merit Lists
                </h2>
                <a href="/results" class="stream-view-all">View All Results »</a>
            </div>
            <div class="stream-notices-feed">
                <?php foreach ($results_articles as $art): ?>
                <article class="stream-notice-row">
                    <div class="stream-content-col">
                        <div class="stream-meta-row">
                            <span class="dept-pill"><?= htmlspecialchars($art['official_source_name'] ?? 'Result') ?></span>
                            <time class="stream-time-text" datetime="<?= $art['published_at'] ?>">
                                📅 <?= date('M j, Y', strtotime($art['published_at'])) ?>
                            </time>
                            <span class="official-verified-badge">✓ Verified</span>
                        </div>
                        <h3 class="stream-headline">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div class="stream-action-col">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="stream-action-pill">
                            Check Result →
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 2. 🎫 Admit Cards Section (Cardless Data Stream) -->
        <?php if (!empty($admit_articles)): ?>
        <section class="stream-block-container">
            <div class="stream-block-header stream-header-cyan">
                <h2 class="stream-block-title">
                    <span>🎫</span> Admit Cards & Hall Tickets
                </h2>
                <a href="/admit-card" class="stream-view-all">View All Admit Cards »</a>
            </div>
            <div class="stream-notices-feed">
                <?php foreach ($admit_articles as $art): ?>
                <article class="stream-notice-row">
                    <div class="stream-content-col">
                        <div class="stream-meta-row">
                            <span class="dept-pill"><?= htmlspecialchars($art['official_source_name'] ?? 'Admit Card') ?></span>
                            <time class="stream-time-text" datetime="<?= $art['published_at'] ?>">
                                📅 <?= date('M j, Y', strtotime($art['published_at'])) ?>
                            </time>
                            <span class="official-verified-badge">✓ Verified</span>
                        </div>
                        <h3 class="stream-headline">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div class="stream-action-col">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="stream-action-pill">
                            Download Slip →
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 3. 💼 Recruitment Section (Cardless Data Stream) -->
        <?php if (!empty($recruitment_articles)): ?>
        <section class="stream-block-container">
            <div class="stream-block-header stream-header-green">
                <h2 class="stream-block-title">
                    <span>💼</span> Government & Banking Recruitment
                </h2>
                <a href="/recruitment" class="stream-view-all">View All Jobs »</a>
            </div>
            <div class="stream-notices-feed">
                <?php foreach ($recruitment_articles as $art): ?>
                <article class="stream-notice-row">
                    <div class="stream-content-col">
                        <div class="stream-meta-row">
                            <span class="dept-pill dept-pill-green"><?= htmlspecialchars($art['official_source_name'] ?? 'Recruitment') ?></span>
                            <time class="stream-time-text" datetime="<?= $art['published_at'] ?>">
                                📅 <?= date('M j, Y', strtotime($art['published_at'])) ?>
                            </time>
                            <span class="official-verified-badge">✓ Verified</span>
                        </div>
                        <h3 class="stream-headline">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div class="stream-action-col">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="stream-action-pill">
                            Apply Online →
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 4. 📝 Exam Dates Section (Cardless Data Stream) -->
        <?php if (!empty($exam_articles)): ?>
        <section class="stream-block-container">
            <div class="stream-block-header stream-header-amber">
                <h2 class="stream-block-title">
                    <span>📝</span> Exam Schedules & Answer Keys
                </h2>
                <a href="/exam" class="stream-view-all">View All Schedules »</a>
            </div>
            <div class="stream-notices-feed">
                <?php foreach ($exam_articles as $art): ?>
                <article class="stream-notice-row">
                    <div class="stream-content-col">
                        <div class="stream-meta-row">
                            <span class="dept-pill dept-pill-amber"><?= htmlspecialchars($art['official_source_name'] ?? 'Exam') ?></span>
                            <time class="stream-time-text" datetime="<?= $art['published_at'] ?>">
                                📅 <?= date('M j, Y', strtotime($art['published_at'])) ?>
                            </time>
                            <span class="official-verified-badge">✓ Verified</span>
                        </div>
                        <h3 class="stream-headline">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div class="stream-action-col">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="stream-action-pill">
                            View Dates →
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <!-- Standard Reusable Right Sidebar (Latest 10 Notices, Categories, State Portals, Official Portals) -->
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
</div>
