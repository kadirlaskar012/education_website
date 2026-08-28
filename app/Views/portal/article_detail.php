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
            <span>👁️ <?= (int)$article['views_count'] ?> views</span>
        </div>

        <!-- Social Share & Print Bar -->
        <div class="article-share-bar">
            <span class="share-label">Share:</span>
            <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>" target="_blank" rel="noopener noreferrer" class="link-btn share-btn-wa">WhatsApp</a>
            <a href="https://t.me/share/url?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener noreferrer" class="link-btn share-btn-tg">Telegram</a>
            <button class="link-btn share-btn-action js-copy-link" type="button">📋 Copy Link</button>
            <button onclick="window.print()" class="link-btn share-btn-action" type="button">🖨️ Print</button>
        </div>
    </header>

    <!-- Source Trust Callout Box (Crystal Clear in Light & Dark Mode) -->
    <div class="source-verification-box">
        <div class="source-icon">🏛️</div>
        <div class="source-info">
            <strong class="source-info-heading">Official Government Source Verification</strong>
            <p class="source-info-text">
                This article is automatically generated based on the official notification released by <strong><?= htmlspecialchars($article['official_source_name'] ?? 'Government Authority') ?></strong>.
            </p>
            <?php if (!empty($article['official_source_url'])): ?>
            <div class="source-link-row">
                <span class="source-link-label">Direct Official Source:</span> 
                <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="source-verify-link"><?= htmlspecialchars($article['official_source_url']) ?></a>
            </div>
            <?php endif; ?>
            <?php if (!empty($article['official_pdf_url'])): ?>
            <div class="source-link-row">
                <span class="source-link-label">Official PDF Document:</span> 
                <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="source-verify-link">Download PDF ↗</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Structured Grounded Content Body (with injected tables and contextual links) -->
    <div class="article-main-body">
        <?= $article['content_html'] ?>
    </div>
</article>

<!-- Related Articles Section (Compact Modern Cards) -->
<?php if (!empty($related_articles)): ?>
<section class="related-posts-section" style="margin-top: 2rem; margin-bottom: 2rem;">
    <h3 class="related-section-heading">
        📌 Related <?= htmlspecialchars($article['category_name']) ?> Updates
    </h3>
    <div class="related-cards-grid">
        <?php foreach ($related_articles as $rel): ?>
        <a href="/news/<?= htmlspecialchars($rel['slug']) ?>" class="related-post-card">
            <h4 class="related-card-title">
                <?= htmlspecialchars($rel['title']) ?>
            </h4>
            <div class="related-card-meta">
                <span>📅 <?= date('M j, Y', strtotime($rel['published_at'])) ?></span>
                <span class="related-card-arrow">Read Notice →</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
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
