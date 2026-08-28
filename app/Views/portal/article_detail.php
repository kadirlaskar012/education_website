<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="separator">/</span>
    <a href="/category/<?= htmlspecialchars($article['category_slug']) ?>"><?= htmlspecialchars($article['category_name']) ?></a>
    <span class="separator">/</span>
    <span><?= htmlspecialchars(mb_substr($article['title'], 0, 45)) ?>...</span>
</nav>

<article class="article-container">
    <header class="article-header">
        <h1 class="article-title-h1"><?= htmlspecialchars($article['title']) ?></h1>

        <div class="article-meta-bar">
            <span class="cat-badge"><?= htmlspecialchars($article['category_name']) ?></span>
            <?php if (!empty($article['official_source_name'])): ?>
            <span class="official-verified-badge">
                ✓ Verified Official: <?= htmlspecialchars($article['source_domain'] ?? $article['official_source_name']) ?>
            </span>
            <?php endif; ?>
            <span>📅 Published: <?= date('M j, Y — g:i A', strtotime($article['published_at'])) ?></span>
            <?php if ($article['version_number'] > 1): ?>
            <span style="color: #0369a1; font-weight: 600;">(Updated v<?= (int)$article['version_number'] ?>)</span>
            <?php endif; ?>
            <span>⏱️ 2 min read</span>
            <span>👁️ <?= (int)$article['views_count'] ?> views</span>
        </div>

        <!-- Social Share & Print Bar -->
        <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
            <span style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Share:</span>
            <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>" target="_blank" rel="noopener noreferrer" class="link-btn" style="background-color: #25d366;">WhatsApp</a>
            <a href="https://t.me/share/url?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener noreferrer" class="link-btn" style="background-color: #0088cc;">Telegram</a>
            <button class="link-btn js-copy-link" style="background-color: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1; cursor: pointer;">📋 Copy Link</button>
            <button onclick="window.print()" class="link-btn" style="background-color: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1; cursor: pointer;">🖨️ Print</button>
        </div>
    </header>

    <!-- Source Trust Callout Box -->
    <div class="source-verification-box">
        <div class="source-icon">🏛️</div>
        <div class="source-info">
            <strong>Official Government Source Verification</strong>
            This article is automatically generated based on the official notification released by <strong><?= htmlspecialchars($article['official_source_name'] ?? 'Government Authority') ?></strong>.
            <?php if (!empty($article['official_source_url'])): ?>
            <br>Direct Official Source: <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" style="color: #15803d; text-decoration: underline; word-break: break-all;"><?= htmlspecialchars($article['official_source_url']) ?></a>
            <?php endif; ?>
            <?php if (!empty($article['official_pdf_url'])): ?>
            <br>Official PDF Document: <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" style="color: #15803d; text-decoration: underline; word-break: break-all;">Download PDF ↗</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Structured Grounded Content Body (with injected tables and contextual links) -->
    <div class="article-main-body">
        <?= $article['content_html'] ?>
    </div>
</article>

<!-- Related Articles Section -->
<?php if (!empty($related_articles)): ?>
<section class="news-section-box" style="margin-top: 2rem;">
    <div class="section-header blue-accent">
        <h2 class="section-title">
            📌 Related <?= htmlspecialchars($article['category_name']) ?> Updates
        </h2>
    </div>
    <ul class="compact-news-list">
        <?php foreach ($related_articles as $rel): ?>
        <li class="compact-news-item">
            <h3 class="news-item-headline">
                <a href="/news/<?= htmlspecialchars($rel['slug']) ?>">
                    <?= htmlspecialchars($rel['title']) ?>
                </a>
            </h3>
            <div class="news-item-footer">
                <span>Published: <?= date('M j, Y', strtotime($rel['published_at'])) ?></span>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>

<!-- Schema.org JSON-LD Structured Data for Google News, Articles, Breadcrumbs, & FAQs -->
<?php if (!empty($article['schema_json'])): ?>
<script type="application/ld+json">
<?= $article['schema_json'] ?>
</script>
<?php else: ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": <?= json_encode($article['title']) ?>,
  "description": <?= json_encode($article['excerpt']) ?>,
  "datePublished": <?= json_encode(date('c', strtotime($article['published_at']))) ?>,
  "dateModified": <?= json_encode(date('c', strtotime($article['updated_at']))) ?>,
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": <?= json_encode('http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>
  },
  "publisher": {
    "@type": "Organization",
    "name": <?= json_encode($site_settings['site_name'] ?? 'EduGov News') ?>
  }
}
</script>
<?php endif; ?>
