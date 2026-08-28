<!-- Top 10 Latest Added Notices Text Hero Section (No Carding, Clean Text-Based & Clickable) -->
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
            $rankPadded = str_pad((string)$rank++, 2, '0', STR_PAD_LEFT);
        ?>
        <div class="hero-notice-row">
            <div class="notice-num">#<?= $rankPadded ?></div>
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

<!-- Homepage Main Category-Wise Feed with Right Sidebar -->
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

    <!-- Standard Reusable Right Sidebar (Latest 10 Notices, Categories, State Portals, Official Portals) -->
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
</div>
