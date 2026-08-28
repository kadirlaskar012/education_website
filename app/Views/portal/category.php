<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="separator">/</span>
    <span><?= htmlspecialchars($category['name']) ?></span>
</nav>

<section class="news-section-box">
    <div class="section-header blue-accent">
        <h1 class="section-title">
            <?= htmlspecialchars($category['icon'] ?? '📰') ?> <?= htmlspecialchars($category['name']) ?> Archive
        </h1>
        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">
            Total: <?= (int)$total_items ?> Updates
        </span>
    </div>

    <?php if (!empty($category['description'])): ?>
    <div style="padding: 0.75rem 1rem; background-color: #f8fafc; border-bottom: 1px solid var(--color-border); font-size: 0.8125rem; color: #475569;">
        <?= htmlspecialchars($category['description']) ?>
    </div>
    <?php endif; ?>

    <ul class="compact-news-list">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $art): ?>
            <li class="compact-news-item">
                <div class="item-meta-top">
                    <span class="cat-badge"><?= htmlspecialchars($art['category_name']) ?></span>
                    <?php if (!empty($art['authority_name'])): ?>
                    <span class="source-domain-badge">🏛️ <?= htmlspecialchars($art['authority_name']) ?></span>
                    <?php endif; ?>
                </div>
                <h2 class="news-item-headline">
                    <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                        <?= htmlspecialchars($art['title']) ?>
                    </a>
                </h2>
                <div class="news-item-excerpt">
                    <?= htmlspecialchars($art['excerpt']) ?>
                </div>
                <div class="news-item-footer">
                    <span>Published: <?= date('M j, Y — g:i A', strtotime($art['published_at'])) ?></span>
                    <span>⏱️ 2 min read</span>
                </div>
            </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="compact-news-item">
                <p style="color: #64748b; font-size: 0.875rem;">No notices currently found under <?= htmlspecialchars($category['name']) ?>.</p>
            </li>
        <?php endif; ?>
    </ul>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination-container">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>" class="pagination-item <?= $i === $current_page ? 'active' : '' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</section>
