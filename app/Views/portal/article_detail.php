<!-- ==========================================================================
     EDUGOV NEWS — ULTRA-PREMIUM ARTICLE EDITORIAL ARCHITECTURE
     Features: Quick Factsheet Widget, AI Audio Bar, Visual Event Timeline,
     Interactive TOC, AdSense Slots, In-Text Autolinks, ALSO READ Box,
     Sticky Mobile Action Dock, and Rich Structured Data (Schema.org)
     ========================================================================== -->

<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="separator">/</span>
    <a href="/category/<?= htmlspecialchars($article['category_slug']) ?>"><?= htmlspecialchars($article['category_name']) ?></a>
    <span class="separator">/</span>
    <span><?= htmlspecialchars(mb_substr($article['title'], 0, 45)) ?>...</span>
</nav>

<?php
$wordCount = str_word_count(strip_tags($article['content_html'] ?? ''));
$readingTime = max(1, (int)ceil($wordCount / 200));
?>

<article class="article-container">
    <!-- Editorial Article Header -->
    <header class="article-header">
        <div class="article-badges-cluster">
            <span class="cat-badge"><?= htmlspecialchars($article['category_name']) ?></span>
            <?php if (!empty($article['official_source_name'])): ?>
            <span class="official-verified-badge">
                ✓ Verified Source: <?= htmlspecialchars($article['source_domain'] ?? $article['official_source_name']) ?>
            </span>
            <?php endif; ?>
        </div>

        <h1 class="article-title-h1"><?= htmlspecialchars($article['title']) ?></h1>

        <div class="article-meta-bar">
            <span class="meta-pill">📅 Published: <?= date('M j, Y — g:i A', strtotime($article['published_at'])) ?></span>
            <span class="meta-pill reading-time-pill">⏱️ <?= $readingTime ?> min read (<?= number_format($wordCount) ?> words)</span>
            <?php if ($article['version_number'] > 1): ?>
            <span class="meta-pill version-pill">⚡ Updated v<?= (int)$article['version_number'] ?></span>
            <?php endif; ?>
            <span class="meta-pill">👁️ <?= number_format((int)$article['views_count']) ?> views</span>
        </div>

        <!-- Multi-Language Reader Bar -->
        <div class="article-lang-switch-box" style="margin: 0.9rem 0; padding: 0.6rem 1rem; background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); border: 1px solid #bae6fd; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: #0369a1;">
                <span style="font-size: 1.1rem;">🌐</span>
                <span><?= htmlspecialchars(__('read_in_language')) ?></span>
            </div>
            <div class="lang-pills-row" style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                <a href="/set-language/en" style="padding: 0.25rem 0.7rem; font-size: 0.8rem; font-weight: 700; border-radius: 6px; text-decoration: none; <?= ($current_locale ?? 'en') === 'en' ? 'background: #0284c7; color: #fff;' : 'background: #ffffff; color: #334155; border: 1px solid #cbd5e1;' ?>">🇬🇧 English</a>
                <a href="/set-language/bn" style="padding: 0.25rem 0.7rem; font-size: 0.8rem; font-weight: 700; border-radius: 6px; text-decoration: none; <?= ($current_locale ?? 'en') === 'bn' ? 'background: #0284c7; color: #fff;' : 'background: #ffffff; color: #334155; border: 1px solid #cbd5e1;' ?>">🇧🇩 বাংলা</a>
                <a href="/set-language/hi" style="padding: 0.25rem 0.7rem; font-size: 0.8rem; font-weight: 700; border-radius: 6px; text-decoration: none; <?= ($current_locale ?? 'en') === 'hi' ? 'background: #0284c7; color: #fff;' : 'background: #ffffff; color: #334155; border: 1px solid #cbd5e1;' ?>">🇮🇳 हिंदी</a>
            </div>
        </div>

        <!-- Social Share & Action Bar -->
        <div class="article-share-bar">
            <span class="share-label">Share Alert:</span>
            <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>" target="_blank" rel="noopener noreferrer" class="link-btn share-btn-wa">
                💬 WhatsApp
            </a>
            <a href="https://t.me/share/url?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener noreferrer" class="link-btn share-btn-tg">
                ✈️ Telegram
            </a>
            <button class="link-btn share-btn-action js-copy-link" type="button" data-url="http://<?= $_SERVER['HTTP_HOST'] ?>/news/<?= htmlspecialchars($article['slug']) ?>">
                📋 Copy Link
            </button>
            <button onclick="window.print()" class="link-btn share-btn-action" type="button">
                🖨️ Print Notice
            </button>
        </div>

        <!-- AI Audio Reader Bar -->
        <div class="audio-article-player">
            <div class="audio-left">
                <button type="button" class="audio-play-btn js-audio-play" aria-label="Listen to Audio Summary">
                    <span class="play-icon">▶</span>
                </button>
                <div class="audio-info">
                    <strong class="audio-title">🎧 Listen to this Official Update (Audio Narration)</strong>
                    <span class="audio-meta"><?= $readingTime ?> min listen • Clear Bengali Voice Summary</span>
                </div>
            </div>
            <div class="audio-right">
                <span class="audio-badge">AI AUDIO</span>
            </div>
        </div>
    </header>

    <!-- 1. Quick Highlights Factsheet Hero Widget -->
    <div class="quick-factsheet-widget">
        <div class="factsheet-header">
            <div class="factsheet-title">
                <span class="factsheet-icon">⚡</span>
                <strong><?= htmlspecialchars(__('key_highlights_title')) ?></strong>
            </div>
            <span class="factsheet-badge">✓ <?= htmlspecialchars(__('verified_badge')) ?></span>
        </div>
        <div class="factsheet-grid">
            <div class="factsheet-card">
                <span class="f-label">🏛️ <?= htmlspecialchars(__('conducting_authority')) ?></span>
                <strong class="f-value" style="color: var(--color-primary);"><?= htmlspecialchars($article['official_source_name'] ?? 'Government Authority') ?></strong>
                <span class="f-sub">Official Source</span>
            </div>
            <div class="factsheet-card">
                <span class="f-label">📂 <?= htmlspecialchars(__('notification_type')) ?></span>
                <strong class="f-value"><?= htmlspecialchars($article['category_name']) ?></strong>
                <span class="f-sub">Direct Release</span>
            </div>
            <div class="factsheet-card">
                <span class="f-label">📅 <?= htmlspecialchars(__('release_date')) ?></span>
                <strong class="f-value" style="color: var(--color-danger);"><?= date('M j, Y', strtotime($article['published_at'])) ?></strong>
                <span class="f-sub">Published Date</span>
            </div>
            <div class="factsheet-card">
                <span class="f-label">🌐 <?= htmlspecialchars(__('official_portal_link')) ?></span>
                <strong class="f-value" style="color: var(--color-primary);"><?= htmlspecialchars($article['source_domain'] ?? 'Official Portal') ?></strong>
                <span class="f-sub">Live Authority Link</span>
            </div>
        </div>
    </div>

    <!-- Official Source Verification Card -->
    <div class="source-verification-box" id="overview">
        <div class="source-icon">🏛️</div>
        <div class="source-info">
            <strong class="source-info-heading"><?= htmlspecialchars(__('verified_source_heading')) ?></strong>
            <p class="source-info-text">
                <?= htmlspecialchars(__('verified_source_desc', ['authority' => $article['official_source_name'] ?? 'Government Authority'])) ?>
            </p>
            <?php if (!empty($article['official_source_url'])): ?>
            <div class="source-link-row">
                <span class="source-link-label"><?= htmlspecialchars(__('direct_source')) ?></span> 
                <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="source-verify-link"><?= htmlspecialchars($article['official_source_url']) ?> ↗</a>
            </div>
            <?php endif; ?>
            <?php if (!empty($article['official_pdf_url'])): ?>
            <div class="source-link-row">
                <span class="source-link-label"><?= htmlspecialchars(__('official_pdf_doc')) ?></span> 
                <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="source-verify-link"><?= htmlspecialchars(__('download_pdf')) ?> ↗</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Top AdSense Responsive Slot (Above TOC) -->
    <div class="adsense-slot-wrapper adsense-slot-top" aria-label="Sponsored Advertisement">
        <div class="ad-disclosure-bar"><span class="ad-label">ADVERTISEMENT</span></div>
        <div class="ad-banner-placeholder">
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="1122334455" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <div class="ad-demo-preview">
                <span class="ad-demo-icon">📢</span>
                <span class="ad-demo-text">Top Responsive In-Article Ad Placement (High CTR)</span>
            </div>
        </div>
    </div>

    <!-- Hierarchical Table of Contents (Parent-Child H2 & H3 with 155px Header Clearance) -->
    <div class="article-toc-box" id="articleTocBox">
        <div class="toc-header" id="tocHeaderToggle">
            <div class="toc-header-left">
                <span class="toc-icon">📑</span>
                <span class="toc-title">Table of Contents</span>
                <span class="toc-count-badge" id="tocCountBadge"></span>
            </div>
            <button class="toc-toggle-btn" id="tocToggleBtn" type="button" aria-label="Toggle Table of Contents">
                <span class="toc-toggle-text">Hide</span>
                <span class="toc-toggle-arrow">▾</span>
            </button>
        </div>
        <div class="toc-content-wrap" id="tocContentWrap">
            <nav class="toc-nav" id="tocNavContainer">
                <ol class="toc-root-list" id="tocRootList">
                    <li class="toc-parent-item"><a href="#overview" class="toc-link">📌 Official Source & Authority Details</a></li>
                    <?php if (!empty($article['official_pdf_url']) || !empty($article['official_source_url'])): ?>
                    <li class="toc-parent-item"><a href="#official-links" class="toc-link">⚡ Direct PDF & Online Portal Links</a></li>
                    <?php endif; ?>
                    <li class="toc-parent-item"><a href="#faqs" class="toc-link">❓ Frequently Asked Questions (FAQs)</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Prominent Dual Action CTA Box if URLs are available -->
    <?php if (!empty($article['official_pdf_url']) || !empty($article['official_source_url'])): ?>
    <div class="article-cta-box" id="official-links">
        <div class="cta-box-title">
            ⚡ Direct Official Links & Downloads
        </div>
        <div class="cta-buttons-wrap">
            <?php if (!empty($article['official_pdf_url'])): ?>
            <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="btn-official-pdf">
                📥 Download Official PDF
            </a>
            <?php endif; ?>
            <?php if (!empty($article['official_source_url'])): ?>
            <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="btn-official-apply">
                🌐 Visit Official Portal ↗
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Rich Article Body Content (With Contextual Autolinks & ALSO READ Box) -->
    <div class="article-body-content" id="articleBodyContent">
        <?= $article['content_html'] ?>
    </div>

    <!-- Mid-Content AdSense Responsive Placement -->
    <div class="adsense-slot-wrapper adsense-slot-mid" aria-label="Sponsored Advertisement">
        <div class="ad-disclosure-bar"><span class="ad-label">ADVERTISEMENT</span></div>
        <div class="ad-banner-placeholder">
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="6677889900" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <div class="ad-demo-preview">
                <span class="ad-demo-icon">📢</span>
                <span class="ad-demo-text">Mid-Article High Viewability In-Feed Ad Unit</span>
            </div>
        </div>
    </div>

    <!-- Dynamic Topic & Qualification Tags Clustering -->
    <div class="article-tags-wrap" style="margin-top: 2rem;">
        <span class="tags-heading">🏷️ Topic Matrix:</span>
        <a href="/category/<?= htmlspecialchars($article['category_slug']) ?>" class="tag-pill">#<?= htmlspecialchars($article['category_name']) ?></a>
        <?php if (!empty($article['state_code']) && $article['state_code'] !== 'ALL'): ?>
        <a href="/state/<?= htmlspecialchars(strtolower($article['state_code'])) ?>" class="tag-pill">#<?= htmlspecialchars($article['state_code']) ?>-Govt</a>
        <?php else: ?>
        <a href="/state/central-govt" class="tag-pill">#Central-Govt</a>
        <?php endif; ?>
        <?php if (!empty($article['official_source_name'])): ?>
        <a href="/search?q=<?= urlencode($article['official_source_name']) ?>" class="tag-pill">#<?= htmlspecialchars(preg_replace('/[^a-zA-Z0-9]/', '', $article['official_source_name'])) ?></a>
        <?php endif; ?>
        <a href="/results" class="tag-pill">#Exam-Results</a>
        <a href="/admit-card" class="tag-pill">#Admit-Card</a>
    </div>

    <!-- Previous & Next Article Navigation -->
    <?php if (!empty($prev_article) || !empty($next_article)): ?>
    <nav class="adjacent-articles-nav" aria-label="Adjacent Articles Navigation">
        <?php if (!empty($prev_article)): ?>
        <a href="/news/<?= htmlspecialchars($prev_article['slug']) ?>" class="adjacent-nav-card prev-card">
            <span class="nav-direction">← PREVIOUS POST</span>
            <strong class="nav-title"><?= htmlspecialchars(mb_substr($prev_article['title'], 0, 65)) ?>...</strong>
        </a>
        <?php else: ?>
        <div class="adjacent-nav-placeholder"></div>
        <?php endif; ?>

        <?php if (!empty($next_article)): ?>
        <a href="/news/<?= htmlspecialchars($next_article['slug']) ?>" class="adjacent-nav-card next-card">
            <span class="nav-direction">NEXT POST →</span>
            <strong class="nav-title"><?= htmlspecialchars(mb_substr($next_article['title'], 0, 65)) ?>...</strong>
        </a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>
</article>

<!-- Related Updates from the Same Official Authority Grid -->
<?php if (!empty($related_source_articles)): ?>
<section class="related-authority-section">
    <div class="related-section-header">
        <div class="related-header-left">
            <span class="sec-icon">🏛️</span>
            <h3 class="related-section-title">
                More Updates from <?= htmlspecialchars($article['official_source_name'] ?? 'this Authority') ?>
            </h3>
        </div>
        <a href="/search?q=<?= urlencode($article['official_source_name'] ?? '') ?>" class="view-all-board-link">All <?= htmlspecialchars($article['official_source_name'] ?? '') ?> Notices →</a>
    </div>

    <div class="related-cards-grid">
        <?php foreach ($related_source_articles as $rel): ?>
        <a href="/news/<?= htmlspecialchars($rel['slug'] ?? '') ?>" class="related-post-card">
            <span class="related-card-badge"><?= htmlspecialchars($rel['category_name'] ?? 'Notification') ?></span>
            <h4 class="related-card-title"><?= htmlspecialchars($rel['title'] ?? '') ?></h4>
            <div class="related-card-meta">
                <time datetime="<?= $rel['published_at'] ?? '' ?>">📅 <?= !empty($rel['published_at']) ? date('M j, Y', strtotime($rel['published_at'])) : 'Recent' ?></time>
                <span class="related-card-arrow">Read Notice →</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Sticky Mobile Smart Action Dock -->
<div class="smart-action-dock">
    <div class="dock-inner">
        <?php if (!empty($article['official_source_url'])): ?>
        <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="dock-btn dock-btn-apply">
            <span class="dock-icon">🔗</span>
            <span class="dock-text">Apply Online</span>
        </a>
        <?php endif; ?>
        <?php if (!empty($article['official_pdf_url'])): ?>
        <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="dock-btn dock-btn-pdf">
            <span class="dock-icon">📥</span>
            <span class="dock-text">Download PDF</span>
        </a>
        <?php endif; ?>
        <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>" target="_blank" rel="noopener noreferrer" class="dock-btn dock-btn-wa">
            <span class="dock-icon">💬</span>
            <span class="dock-text">WhatsApp</span>
        </a>
    </div>
</div>

<!-- Schema.org JSON-LD Structured Data Engine (Google News, JobPosting, FAQPage & Breadcrumbs) -->
<?php
$schemaGraph = [];
$currentHost = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$articleUrl = "http://{$currentHost}/news/{$article['slug']}";
$ogImageUrl = "http://{$currentHost}/og-image/{$article['slug']}";

// 1. Breadcrumbs Schema
$schemaGraph[] = [
    "@type" => "BreadcrumbList",
    "itemListElement" => [
        [
            "@type" => "ListItem",
            "position" => 1,
            "name" => "Home",
            "item" => "http://{$currentHost}/",
        ],
        [
            "@type" => "ListItem",
            "position" => 2,
            "name" => $article['category_name'],
            "item" => "http://{$currentHost}/category/" . $article['category_slug'],
        ],
        [
            "@type" => "ListItem",
            "position" => 3,
            "name" => $article['title'],
            "item" => $articleUrl,
        ],
    ],
];

// 2. NewsArticle Schema
$schemaGraph[] = [
    "@type" => "NewsArticle",
    "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id" => $articleUrl,
    ],
    "headline" => $article['title'],
    "description" => $article['excerpt'] ?? mb_substr(strip_tags($article['content_html']), 0, 160),
    "image" => [
        "@type" => "ImageObject",
        "url"   => $ogImageUrl,
        "width" => 1200,
        "height" => 630,
    ],
    "datePublished" => date('c', strtotime($article['published_at'])),
    "dateModified" => date('c', strtotime($article['updated_at'] ?? $article['published_at'])),
    "author" => [
        "@type" => "Organization",
        "name" => $article['official_source_name'] ?? 'Government Authority',
        "url" => $article['official_source_url'] ?? ("http://{$currentHost}/"),
    ],
    "publisher" => [
        "@type" => "Organization",
        "name" => $site_settings['site_name'] ?? 'EduGov News',
        "url" => "http://{$currentHost}/",
    ],
];

// 3. Google JobPosting Schema (Active for recruitment/jobs alerts)
$isJobAlert = in_array($article['category_slug'], ['recruitment', 'government-jobs', 'application-form']) 
              || preg_match('/recruitment|vacancy|vacancies|jobs|bharti|posts|officer|clerk/i', $article['title']);

if ($isJobAlert) {
    $schemaGraph[] = [
        "@type" => "JobPosting",
        "title" => $article['title'],
        "description" => $article['excerpt'] ?? mb_substr(strip_tags($article['content_html']), 0, 200),
        "datePosted" => date('c', strtotime($article['published_at'])),
        "validThrough" => date('c', strtotime($article['published_at'] . ' +30 days')),
        "employmentType" => "FULL_TIME",
        "hiringOrganization" => [
            "@type" => "Organization",
            "name" => $article['official_source_name'] ?? 'Government Recruitment Board',
            "sameAs" => $article['official_source_url'] ?? ("http://{$currentHost}/"),
        ],
        "jobLocation" => [
            "@type" => "Place",
            "address" => [
                "@type" => "PostalAddress",
                "addressCountry" => "IN",
                "addressRegion" => !empty($article['state_code']) ? $article['state_code'] : "India",
            ],
        ],
        "applicantLocationRequirements" => [
            "@type" => "Country",
            "name" => "IN",
        ],
        "directApply" => true,
        "url" => $articleUrl,
    ];
}
?>
<script type="application/ld+json">
<?= json_encode([
    "@context" => "https://schema.org",
    "@graph"   => $schemaGraph
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
