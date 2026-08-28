<!-- Top 5 Latest & Trending News Hero Showcase -->
<?php if (!empty($hero_main)): ?>
<section class="hero-showcase-section">
    <div class="hero-showcase-grid">
        <!-- 1. Main Featured Large Card (Item #1) -->
        <div class="hero-main-card">
            <div class="hero-badge-row">
                <span class="hero-tag-pill">🔥 Top Story #1</span>
                <span class="cat-badge"><?= htmlspecialchars($hero_main['category_name']) ?></span>
                <?php if (!empty($hero_main['official_source_name'])): ?>
                <span class="official-verified-badge">
                    ✓ <?= htmlspecialchars($hero_main['official_source_name']) ?>
                </span>
                <?php endif; ?>
                <span class="card-time"><?= date('M j, Y — g:i A', strtotime($hero_main['published_at'])) ?></span>
            </div>

            <h1 class="hero-main-title">
                <a href="/news/<?= htmlspecialchars($hero_main['slug']) ?>">
                    <?= htmlspecialchars($hero_main['title']) ?>
                </a>
            </h1>

            <p class="hero-main-summary">
                <?= htmlspecialchars($hero_main['summary'] ?? $hero_main['excerpt'] ?? mb_substr(strip_tags($hero_main['content_html']), 0, 220) . '...') ?>
            </p>

            <div class="hero-main-footer">
                <a href="/news/<?= htmlspecialchars($hero_main['slug']) ?>" class="btn-hero-primary">
                    Read Full Notice & Direct Links »
                </a>
                <span class="badge-verified-small">✓ Official Verified Source</span>
            </div>
        </div>

        <!-- 2. Trending Top 4 List (Items #2 to #5) -->
        <div class="hero-trending-panel">
            <div class="trending-panel-header">
                <div class="trending-header-title">
                    <span>⚡</span> Top Trending Today
                </div>
                <span class="trending-badge-count">5 Top Updates</span>
            </div>

            <div class="trending-items-list">
                <?php 
                $rank = 2;
                foreach ($hero_trending as $trItem): 
                ?>
                <a href="/news/<?= htmlspecialchars($trItem['slug']) ?>" class="trending-news-row">
                    <div class="trending-rank-num">#<?= $rank++ ?></div>
                    <div class="trending-content-wrap">
                        <div class="trending-meta-small">
                            <span class="cat-badge-micro"><?= htmlspecialchars($trItem['category_name']) ?></span>
                            <?php if (!empty($trItem['official_source_name'])): ?>
                            <span class="source-micro">🏛️ <?= htmlspecialchars(mb_substr($trItem['official_source_name'], 0, 24)) ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="trending-row-headline">
                            <?= htmlspecialchars($trItem['title']) ?>
                        </h2>
                        <div class="trending-date-small">
                            📅 <?= date('M j, Y', strtotime($trItem['published_at'])) ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Instant WhatsApp & Telegram Alert Banner -->
<div class="social-alert-banner">
    <div class="social-alert-text">
        <div class="social-alert-heading">
            <span>🔔 Never Miss an Exam or Job Update!</span>
        </div>
        <p class="social-alert-sub">
            Join 100,000+ students receiving instant verified official notifications directly on phone.
        </p>
    </div>
    <div class="social-alert-buttons">
        <a href="https://telegram.me" target="_blank" rel="noopener noreferrer" class="link-btn social-tg-btn">
            ✈️ Join Telegram
        </a>
        <a href="https://whatsapp.com" target="_blank" rel="noopener noreferrer" class="link-btn social-wa-btn">
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

<!-- Homepage Main Category-Wise Feed -->
<div class="feed-layout-grid">
    <!-- Main Categorized Columns -->
    <main class="feed-main-col">

        <!-- 1. 📋 Results Section -->
        <?php if (!empty($results_articles)): ?>
        <section class="category-block-card">
            <div class="block-header block-header-blue">
                <h2 class="block-title">
                    <span>📋</span> Latest Results & Merit Lists
                </h2>
                <a href="/results" class="view-all-link">View All »</a>
            </div>
            <div class="category-items-grid">
                <?php foreach ($results_articles as $art): ?>
                <article class="compact-card">
                    <div>
                        <span class="official-verified-badge">✓ <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?></span>
                        <h3 class="compact-card-title">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div class="compact-card-footer">
                        <span>📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></span>
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="card-action-link">Check Result »</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 2. 🎫 Admit Cards Section -->
        <?php if (!empty($admit_articles)): ?>
        <section class="category-block-card">
            <div class="block-header block-header-cyan">
                <h2 class="block-title">
                    <span>🎫</span> Admit Cards & Hall Tickets
                </h2>
                <a href="/admit-card" class="view-all-link">View All »</a>
            </div>
            <div class="category-items-grid">
                <?php foreach ($admit_articles as $art): ?>
                <article class="compact-card">
                    <div>
                        <span class="official-verified-badge">✓ <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?></span>
                        <h3 class="compact-card-title">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div class="compact-card-footer">
                        <span>📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></span>
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="card-action-link">Download Slip »</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 3. 💼 Recruitment Section -->
        <?php if (!empty($recruitment_articles)): ?>
        <section class="category-block-card">
            <div class="block-header block-header-green">
                <h2 class="block-title">
                    <span>💼</span> Government & Banking Recruitment
                </h2>
                <a href="/recruitment" class="view-all-link">View All »</a>
            </div>
            <div class="category-items-grid">
                <?php foreach ($recruitment_articles as $art): ?>
                <article class="compact-card">
                    <div>
                        <span class="official-verified-badge">✓ <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?></span>
                        <h3 class="compact-card-title">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div class="compact-card-footer">
                        <span>📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></span>
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="card-action-link">Apply Online »</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 4. 📝 Exam Dates Section -->
        <?php if (!empty($exam_articles)): ?>
        <section class="category-block-card">
            <div class="block-header block-header-amber">
                <h2 class="block-title">
                    <span>📝</span> Exam Schedules & Answer Keys
                </h2>
                <a href="/exam" class="view-all-link">View All »</a>
            </div>
            <div class="category-items-grid">
                <?php foreach ($exam_articles as $art): ?>
                <article class="compact-card">
                    <div>
                        <span class="official-verified-badge">✓ <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?></span>
                        <h3 class="compact-card-title">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div class="compact-card-footer">
                        <span>📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></span>
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="card-action-link">View Dates »</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <!-- Sidebar Quick Hubs & Official Links -->
    <aside class="feed-sidebar-col">
        <div class="sidebar-card">
            <h3 class="sidebar-title">⚡ 25+ Official Portals Scraped</h3>
            <ul class="sidebar-links-list">
                <li><a href="/search?q=SSC">🏛️ Staff Selection Commission (SSC)</a></li>
                <li><a href="/search?q=UPSC">🏛️ Union Public Service (UPSC)</a></li>
                <li><a href="/search?q=Railway">🚂 Railway Recruitment (RRB)</a></li>
                <li><a href="/search?q=IBPS">🏦 Banking & IBPS / SBI</a></li>
                <li><a href="/search?q=NTA">🎯 National Testing Agency (NTA)</a></li>
                <li><a href="/search?q=Defense">🎖️ Indian Army / IAF / Navy</a></li>
                <li><a href="/search?q=Police">👮 State Police & PSC Boards</a></li>
            </ul>
        </div>

        <div class="sidebar-card" style="margin-top: 1.5rem;">
            <h3 class="sidebar-title">🛡️ Official Authenticity Notice</h3>
            <p style="font-size: 0.8125rem; color: var(--color-text-muted, #64748b); line-height: 1.55; margin-bottom: 0;">
                All articles on EduGov News are strictly generated from verified government notifications. Candidates are always provided direct official links to official .gov.in and .ac.in portals.
            </p>
        </div>
    </aside>
</div>
