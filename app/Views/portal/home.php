<!-- Main Featured Breaking News Hero -->
<?php if (!empty($featured)): ?>
<section class="breaking-hero-card">
    <div class="breaking-hero-meta">
        <span class="cat-badge"><?= htmlspecialchars($featured['category_name']) ?></span>
        <span class="official-verified-badge">
            ✓ Official: <?= htmlspecialchars($featured['official_source_name'] ?? 'Government Authority') ?>
        </span>
        <span class="card-time"><?= date('M j, Y — g:i A', strtotime($featured['published_at'])) ?></span>
    </div>

    <h1 class="breaking-hero-title">
        <?= htmlspecialchars($featured['title']) ?>
    </h1>

    <p class="breaking-hero-summary">
        <?= htmlspecialchars($featured['summary'] ?? $featured['excerpt']) ?>
    </p>

    <div class="breaking-hero-actions">
        <a href="/news/<?= htmlspecialchars($featured['slug']) ?>" class="btn-hero-primary">
            Read Full Notice & Direct Links »
        </a>
    </div>
</section>
<?php endif; ?>

<!-- Instant WhatsApp & Telegram Alert Banner -->
<div class="social-alert-banner" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%); color: #fff; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="font-weight: 700; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <span>🔔 Never Miss an Exam or Job Update!</span>
        </div>
        <p style="font-size: 0.8125rem; color: #cbd5e1; margin-top: 0.25rem; margin-bottom: 0;">
            Join 100,000+ students receiving instant verified official notifications directly on phone.
        </p>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="https://telegram.me" target="_blank" rel="noopener noreferrer" class="link-btn" style="background-color: #0088cc; font-weight: 600;">
            ✈️ Join Telegram
        </a>
        <a href="https://whatsapp.com" target="_blank" rel="noopener noreferrer" class="link-btn" style="background-color: #25d366; font-weight: 600;">
            💬 Join WhatsApp
        </a>
    </div>
</div>

<!-- State-Wise Quick Filter Matrix -->
<section id="state-matrix" class="state-filter-section" style="margin-bottom: 2rem;">
    <div class="section-header" style="border-left: 4px solid #0284c7; padding-left: 0.75rem; margin-bottom: 0.75rem;">
        <h2 style="font-size: 1.125rem; font-weight: 700; color: var(--color-text-main, #0a192f);">
            🗺️ State & Central Government Jobs Filter
        </h2>
    </div>
    <div class="state-matrix-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 0.5rem;">
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

<!-- Homepage Main Layout -->
<div class="feed-layout-grid">
    <!-- Category-Wise Main Content Feed -->
    <main class="feed-main-col">

        <!-- 1. 📋 Results Section -->
        <?php if (!empty($results_articles)): ?>
        <section class="category-block-card mb-8" style="margin-bottom: 2rem;">
            <div class="block-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #2563eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-main, #0a192f); display: flex; align-items: center; gap: 0.5rem;">
                    <span>📋</span> Latest Results & Merit Lists
                </h2>
                <a href="/results" class="view-all-link" style="font-size: 0.8125rem; font-weight: 600; color: #2563eb;">View All »</a>
            </div>
            <div class="category-items-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                <?php foreach ($results_articles as $art): ?>
                <article class="compact-card" style="background: var(--color-card-bg, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; padding: 0.875rem; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <span class="official-verified-badge" style="font-size: 0.6875rem;">✓ <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?></span>
                        <h3 style="font-size: 0.9375rem; font-weight: 600; margin-top: 0.5rem; margin-bottom: 0.5rem; line-height: 1.35;">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>" style="color: var(--color-text-main, #0a192f); text-decoration: none;">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #64748b; margin-top: 0.5rem; border-top: 1px dashed var(--color-border, #f1f5f9); padding-top: 0.5rem;">
                        <span>📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></span>
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" style="color: #2563eb; font-weight: 600;">Check Result »</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 2. 🎫 Admit Cards Section -->
        <?php if (!empty($admit_articles)): ?>
        <section class="category-block-card mb-8" style="margin-bottom: 2rem;">
            <div class="block-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0284c7; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-main, #0a192f); display: flex; align-items: center; gap: 0.5rem;">
                    <span>🎫</span> Admit Cards & Hall Tickets
                </h2>
                <a href="/admit-card" class="view-all-link" style="font-size: 0.8125rem; font-weight: 600; color: #0284c7;">View All »</a>
            </div>
            <div class="category-items-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                <?php foreach ($admit_articles as $art): ?>
                <article class="compact-card" style="background: var(--color-card-bg, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; padding: 0.875rem; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <span class="official-verified-badge" style="font-size: 0.6875rem;">✓ <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?></span>
                        <h3 style="font-size: 0.9375rem; font-weight: 600; margin-top: 0.5rem; margin-bottom: 0.5rem; line-height: 1.35;">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>" style="color: var(--color-text-main, #0a192f); text-decoration: none;">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #64748b; margin-top: 0.5rem; border-top: 1px dashed var(--color-border, #f1f5f9); padding-top: 0.5rem;">
                        <span>📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></span>
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" style="color: #0284c7; font-weight: 600;">Download Slip »</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 3. 💼 Recruitment & Government Jobs Section -->
        <?php if (!empty($recruitment_articles)): ?>
        <section class="category-block-card mb-8" style="margin-bottom: 2rem;">
            <div class="block-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #16a34a; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-main, #0a192f); display: flex; align-items: center; gap: 0.5rem;">
                    <span>💼</span> Government & Banking Recruitment
                </h2>
                <a href="/recruitment" class="view-all-link" style="font-size: 0.8125rem; font-weight: 600; color: #16a34a;">View All »</a>
            </div>
            <div class="category-items-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                <?php foreach ($recruitment_articles as $art): ?>
                <article class="compact-card" style="background: var(--color-card-bg, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; padding: 0.875rem; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <span class="official-verified-badge" style="font-size: 0.6875rem;">✓ <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?></span>
                        <h3 style="font-size: 0.9375rem; font-weight: 600; margin-top: 0.5rem; margin-bottom: 0.5rem; line-height: 1.35;">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>" style="color: var(--color-text-main, #0a192f); text-decoration: none;">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #64748b; margin-top: 0.5rem; border-top: 1px dashed var(--color-border, #f1f5f9); padding-top: 0.5rem;">
                        <span>📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></span>
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" style="color: #16a34a; font-weight: 600;">Apply Online »</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 4. 📝 Upcoming Exams & Answer Keys -->
        <?php if (!empty($exam_articles)): ?>
        <section class="category-block-card mb-8" style="margin-bottom: 2rem;">
            <div class="block-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #d97706; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-main, #0a192f); display: flex; align-items: center; gap: 0.5rem;">
                    <span>📝</span> Exam Schedules & Answer Keys
                </h2>
                <a href="/exam" class="view-all-link" style="font-size: 0.8125rem; font-weight: 600; color: #d97706;">View All »</a>
            </div>
            <div class="category-items-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                <?php foreach ($exam_articles as $art): ?>
                <article class="compact-card" style="background: var(--color-card-bg, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; padding: 0.875rem; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <span class="official-verified-badge" style="font-size: 0.6875rem;">✓ <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?></span>
                        <h3 style="font-size: 0.9375rem; font-weight: 600; margin-top: 0.5rem; margin-bottom: 0.5rem; line-height: 1.35;">
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>" style="color: var(--color-text-main, #0a192f); text-decoration: none;">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </h3>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #64748b; margin-top: 0.5rem; border-top: 1px dashed var(--color-border, #f1f5f9); padding-top: 0.5rem;">
                        <span>📅 <?= date('M j, Y', strtotime($art['published_at'])) ?></span>
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" style="color: #d97706; font-weight: 600;">View Dates »</a>
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
            <p style="font-size: 0.8125rem; color: var(--color-text-muted, #64748b); line-height: 1.5; margin-bottom: 0;">
                All articles on EduGov News are strictly generated from verified government notifications. Candidates are always provided direct official links to official .gov.in and .ac.in portals.
            </p>
        </div>
    </aside>
</div>
