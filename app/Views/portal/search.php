<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="separator">/</span>
    <span>Search</span>
</nav>

<!-- Search Header Banner -->
<header class="category-header-banner">
    <div class="cat-header-top">
        <h1 class="cat-header-title">
            🔍 Search: <?= !empty($query) ? htmlspecialchars($query) : 'All Notices' ?>
        </h1>
        <span class="cat-header-count">
            Found <?= (int)$total_items ?> Results
        </span>
    </div>

    <!-- Search Input Form -->
    <div style="margin-top: 1rem;">
        <form action="/search" method="get" class="search-form" style="max-width: 550px;">
            <input type="text" name="q" placeholder="Enter keywords (e.g. SSC, UPSC, Admit Card, Result)..." value="<?= htmlspecialchars($query ?? '') ?>" required>
            <button type="submit">🔍 Search</button>
        </form>
    </div>
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
                        <span class="read-time">⏱️ 2 min read</span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state-box">
                <div class="empty-icon">🔍</div>
                <h3>No matching education notices found</h3>
                <p>Try searching with different keywords like "SSC", "UPSC", "Admit Card", or "Result".</p>
                <a href="/" class="admin-btn admin-btn-primary" style="margin-top: 1rem; display: inline-block;">Return to Homepage</a>
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
            </ul>
        </div>
    </aside>
</div>
