<!-- Featured Headline Box -->
<?php if (!empty($featured_article)): ?>
<section class="featured-headline-box">
    <div class="item-meta-top">
        <span class="cat-badge"><?= htmlspecialchars($featured_article['category_name']) ?></span>
        <?php if (!empty($featured_article['authority_name'])): ?>
        <span class="source-domain-badge">🏛️ Official: <?= htmlspecialchars($featured_article['authority_name']) ?></span>
        <?php endif; ?>
        <span><?= date('M j, Y — g:i A', strtotime($featured_article['published_at'])) ?></span>
    </div>
    <h2>
        <a href="/news/<?= htmlspecialchars($featured_article['slug']) ?>">
            <?= htmlspecialchars($featured_article['title']) ?>
        </a>
    </h2>
    <p><?= htmlspecialchars($featured_article['summary'] ?: $featured_article['excerpt']) ?></p>
    <div style="margin-top: 0.85rem;">
        <a href="/news/<?= htmlspecialchars($featured_article['slug']) ?>" class="link-btn" style="padding: 0.5rem 1rem;">
            Read Full Notice & Links »
        </a>
    </div>
</section>
<?php endif; ?>

<!-- Latest Education News List -->
<section class="news-section-box">
    <div class="section-header">
        <h2 class="section-title">
            📰 Latest Education News
        </h2>
        <a href="/category/latest-news" class="section-view-all">View All »</a>
    </div>
    <ul class="compact-news-list">
        <?php if (!empty($latest_articles)): ?>
            <?php foreach ($latest_articles as $art): ?>
            <li class="compact-news-item">
                <div class="item-meta-top">
                    <span class="cat-badge"><?= htmlspecialchars($art['category_name']) ?></span>
                    <?php if (!empty($art['authority_name'])): ?>
                    <span class="source-domain-badge">🏛️ <?= htmlspecialchars($art['authority_name']) ?></span>
                    <?php endif; ?>
                </div>
                <h3 class="news-item-headline">
                    <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                        <?= htmlspecialchars($art['title']) ?>
                    </a>
                </h3>
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
                <p style="color: #64748b; font-size: 0.875rem;">No notices published yet. Run the automated scraper pipeline to fetch updates.</p>
            </li>
        <?php endif; ?>
    </ul>
</section>
