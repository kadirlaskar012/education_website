<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="separator">/</span>
    <a href="/#state-matrix">States</a>
    <span class="separator">/</span>
    <span><?= htmlspecialchars($state_name) ?></span>
</nav>

<!-- State Header Box -->
<header class="category-header-banner">
    <div class="cat-header-top">
        <h1 class="cat-header-title">
            🏛️ <?= htmlspecialchars($state_name) ?> Notifications
        </h1>
        <span class="cat-header-count">
            Total Updates: <?= count($articles) ?>
        </span>
    </div>
    <p class="cat-header-desc">
        Verified official notifications, state recruitment exams, results, admit cards, and board updates for <?= htmlspecialchars($state_name) ?>.
    </p>

    <!-- State Quick Chips -->
    <div class="state-chips-row" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
        <?php foreach ($all_states as $sSlug => $sData): ?>
        <a href="/state/<?= $sSlug ?>" class="smart-tab-pill <?= $sSlug === $state_slug ? 'active' : '' ?>" style="font-size: 0.75rem; text-decoration: none;">
            <?= htmlspecialchars($sData['name']) ?>
        </a>
        <?php endforeach; ?>
    </div>
</header>

<!-- Feed Layout -->
<div class="feed-layout-grid">
    <!-- Main Articles Stream -->
    <main class="feed-main-col">
        <?php if (!empty($articles)): ?>
            <div class="articles-stack">
                <?php foreach ($articles as $art): ?>
                <article class="article-card">
                    <div class="card-meta">
                        <span class="cat-badge"><?= htmlspecialchars($art['category_name']) ?></span>
                        <span class="official-verified-badge">
                            🏛️ <?= htmlspecialchars($art['official_source_name'] ?? $art['state_name']) ?>
                        </span>
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
        <?php else: ?>
            <div class="empty-state-box">
                <div class="empty-icon">📭</div>
                <h3>No active notices found for <?= htmlspecialchars($state_name) ?></h3>
                <p>New recruitment and exam notifications are being synchronized by the automated pipeline.</p>
                <a href="/" class="admin-btn admin-btn-primary" style="margin-top: 1rem; display: inline-block;">Return to All Updates</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Standard Reusable Right Sidebar -->
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
</div>
