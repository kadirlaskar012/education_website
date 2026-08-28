<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="separator">/</span>
    <span><?= htmlspecialchars($category['name']) ?></span>
</nav>

<!-- Category Header Box (Fully Responsive) -->
<header class="category-header-banner">
    <div class="cat-header-top">
        <h1 class="cat-header-title">
            <span class="cat-title-icon"><?= htmlspecialchars($category['icon'] ?? '📰') ?></span>
            <span><?= htmlspecialchars($category['name']) ?></span>
        </h1>
        <span class="cat-header-count">
            <?= (int)$total_items ?> Updates
        </span>
    </div>
    <?php if (!empty($category['description'])): ?>
    <p class="cat-header-desc">
        <?= htmlspecialchars($category['description']) ?>
    </p>
    <?php endif; ?>

    <!-- Dynamic State-Wise Filter Pills (Only states with active posts appear) -->
    <?php if (!empty($available_states)): ?>
    <div class="category-state-filter-wrap">
        <div class="filter-label-row">
            <span>🗺️ Filter by State:</span>
        </div>
        <div class="state-chips-scroll-track">
            <a href="/category/<?= htmlspecialchars($category['slug']) ?>" 
               class="smart-tab-pill <?= empty($selected_state) ? 'active' : '' ?>">
                All Regions
            </a>
            <?php foreach ($available_states as $st): ?>
            <a href="/category/<?= htmlspecialchars($category['slug']) ?>?state=<?= urlencode($st['state_code']) ?>" 
               class="smart-tab-pill <?= ($selected_state === $st['state_code']) ? 'active' : '' ?>">
                <?= htmlspecialchars($st['state_name']) ?> <span class="pill-count">(<?= (int)$st['count'] ?>)</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</header>

<!-- Main Feed & Sidebar Grid -->
<div class="feed-layout-grid">
    <!-- Main Articles Column -->
    <main class="feed-main-col">
        <?php if (!empty($articles)): ?>
            <div class="articles-stack">
                <?php foreach ($articles as $art): ?>
                <article class="article-card">
                    <div class="card-meta">
                        <span class="cat-badge"><?= htmlspecialchars($art['category_name']) ?></span>
                        <?php if (!empty($art['official_source_name'])): ?>
                        <span class="official-verified-badge">
                            🏛️ <?= htmlspecialchars($art['official_source_name']) ?>
                        </span>
                        <?php endif; ?>
                        <time datetime="<?= $art['published_at'] ?>" class="card-time">
                            <?= date('M j, Y — g:i A', strtotime($art['published_at'])) ?>
                        </time>
                    </div>

                    <h2 class="card-title">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                            <?= htmlspecialchars($art['title']) ?>
                        </a>
                    </h2>

                    <p class="card-excerpt">
                        <?= htmlspecialchars($art['excerpt'] ?? mb_substr(strip_tags($art['content_html']), 0, 160) . '...') ?>
                    </p>

                    <div class="card-footer">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="read-more-btn">
                            Read Full Notice & Direct Links »
                        </a>
                        <span class="badge-verified-small">✓ Verified</span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination-container">
                <?php 
                $stateParam = !empty($selected_state) ? '&state=' . urlencode($selected_state) : '';
                for ($i = 1; $i <= $total_pages; $i++): 
                ?>
                <a href="?page=<?= $i ?><?= $stateParam ?>" class="pagination-item <?= $i === $current_page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state-box">
                <div class="empty-icon">📭</div>
                <h3>No notices found for this selection</h3>
                <p>New recruitment and exam notifications are synchronized daily.</p>
                <a href="/category/<?= htmlspecialchars($category['slug']) ?>" class="admin-btn admin-btn-primary" style="margin-top: 1rem; display: inline-block;">View All States</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Sidebar Quick Links -->
    <aside class="feed-sidebar-col">
        <div class="sidebar-card" style="margin-bottom: 1.5rem;">
            <h3 class="sidebar-title">⚡ Quick Categories</h3>
            <ul class="sidebar-links-list">
                <li><a href="/results">📋 Results & Merit Lists</a></li>
                <li><a href="/admit-card">🎫 Admit Cards & Slips</a></li>
                <li><a href="/recruitment">💼 Government Recruitment</a></li>
                <li><a href="/exam">📝 Exam Calendar</a></li>
                <li><a href="/answer-key">🔑 Answer Keys</a></li>
                <li><a href="/category/scholarship">🏆 Scholarships</a></li>
            </ul>
        </div>

        <div class="sidebar-card">
            <h3 class="sidebar-title">🗺️ State Portals</h3>
            <ul class="sidebar-links-list">
                <li><a href="/state/central-govt">🏛️ Central Government</a></li>
                <li><a href="/state/west-bengal">🌊 West Bengal (WBPSC)</a></li>
                <li><a href="/state/uttar-pradesh">🌾 Uttar Pradesh (UPPSC)</a></li>
                <li><a href="/state/bihar">🚩 Bihar (BPSC)</a></li>
                <li><a href="/state/rajasthan">🏰 Rajasthan (RPSC)</a></li>
                <li><a href="/state/madhya-pradesh">🌲 Madhya Pradesh (MPPSC)</a></li>
                <li><a href="/state/maharashtra">🏙️ Maharashtra (MPSC)</a></li>
            </ul>
        </div>
    </aside>
</div>
