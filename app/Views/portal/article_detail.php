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
    <header class="article-header">
        <h1 class="article-title-h1"><?= htmlspecialchars($article['title']) ?></h1>

        <div class="article-meta-bar">
            <span class="cat-badge"><?= htmlspecialchars($article['category_name']) ?></span>
            <?php if (!empty($article['official_source_name'])): ?>
            <span class="official-verified-badge">
                ✓ Verified Official: <?= htmlspecialchars($article['source_domain'] ?? $article['official_source_name']) ?>
            </span>
            <?php endif; ?>
            <span class="meta-pill">📅 <?= date('M j, Y — g:i A', strtotime($article['published_at'])) ?></span>
            <span class="meta-pill reading-time-pill">⏱️ <?= $readingTime ?> min read (<?= number_format($wordCount) ?> words)</span>
            <?php if ($article['version_number'] > 1): ?>
            <span class="meta-pill version-pill">(Updated v<?= (int)$article['version_number'] ?>)</span>
            <?php endif; ?>
            <span class="meta-pill">👁️ <?= (int)$article['views_count'] ?> views</span>
        </div>

        <!-- Social Share & Print Bar -->
        <div class="article-share-bar">
            <span class="share-label">Share:</span>
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
                🖨️ Print
            </button>
        </div>
    </header>

    <!-- Source Trust Callout Box (Crystal Clear in Light & Dark Mode) -->
    <div class="source-verification-box" id="overview">
        <div class="source-icon">🏛️</div>
        <div class="source-info">
            <strong class="source-info-heading">Official Government Source Verification</strong>
            <p class="source-info-text">
                This article is automatically synchronized and verified from the official notification released by <strong><?= htmlspecialchars($article['official_source_name'] ?? 'Government Authority') ?></strong>.
            </p>
            <?php if (!empty($article['official_source_url'])): ?>
            <div class="source-link-row">
                <span class="source-link-label">Direct Official Source:</span> 
                <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="source-verify-link"><?= htmlspecialchars($article['official_source_url']) ?> ↗</a>
            </div>
            <?php endif; ?>
            <?php if (!empty($article['official_pdf_url'])): ?>
            <div class="source-link-row">
                <span class="source-link-label">Official PDF Document:</span> 
                <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="source-verify-link">Download Notification PDF ↗</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Top AdSense Responsive Slot (Above Table of Contents) -->
    <div class="adsense-slot-wrapper adsense-slot-top" aria-label="Sponsored Advertisement">
        <div class="ad-disclosure-bar"><span class="ad-label">ADVERTISEMENT</span></div>
        <div class="ad-banner-placeholder">
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="1122334455" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <div class="ad-demo-preview">
                <span class="ad-demo-icon">📢</span>
                <span class="ad-demo-text">Top Responsive Ad Placement (High CTR)</span>
            </div>
        </div>
    </div>

    <!-- Hierarchical Table of Contents (Auto-populated with Parent-Child H2 & H3) -->
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

    <!-- Structured Grounded Content Body (with injected tables and contextual links) -->
    <div class="article-main-body" id="article-body">
        <?= $article['content_html'] ?>
    </div>

    <!-- Bottom AdSense In-Article Slot (Before FAQs) -->
    <div class="adsense-slot-wrapper adsense-slot-bottom" aria-label="Sponsored Advertisement">
        <div class="ad-disclosure-bar"><span class="ad-label">ADVERTISEMENT</span></div>
        <div class="ad-banner-placeholder">
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="9988776655" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <div class="ad-demo-preview">
                <span class="ad-demo-icon">📢</span>
                <span class="ad-demo-text">In-Article Native Ad Placement</span>
            </div>
        </div>
    </div>

    <!-- Dynamic FAQs Section (Google SERP FAQ Accordion) -->
    <?php
    $authorityName = $article['official_source_name'] ?? 'Official Authority';
    $publishDateText = date('F j, Y', strtotime($article['published_at']));
    $faqsList = [
        [
            'question' => "What is the official release date and issuing authority for {$article['title']}?",
            'answer'   => "This notification was officially published by {$authorityName} on {$publishDateText}. All facts and eligibility criteria are verified from the government release.",
        ],
        [
            'question' => "Where can candidates download the official notification PDF?",
            'answer'   => !empty($article['official_pdf_url']) 
                          ? "Candidates can directly download the official PDF document via the download button provided in this article."
                          : "The official PDF link is hosted directly on the authorized portal of {$authorityName}.",
        ],
        [
            'question' => "How can candidates apply or check their result/admit card status?",
            'answer'   => "Visit the verified portal (" . ($article['official_source_url'] ?? "official portal") . "), navigate to the candidate portal section, and follow the step-by-step verification process outlined in this guide.",
        ],
        [
            'question' => "Is this update applicable across all states in India?",
            'answer'   => (!empty($article['state_name']) && $article['state_name'] !== 'All India / Central')
                          ? "This notification specifically applies to {$article['state_name']} candidates as per state government rules."
                          : "Yes, this notification applies to eligible candidates across all Indian states as per Central Government norms.",
        ],
    ];
    ?>
    <section class="article-faqs-section" id="faqs">
        <h3 class="faqs-main-heading">
            ❓ Frequently Asked Questions (FAQs)
        </h3>
        <div class="faqs-accordion-list">
            <?php foreach ($faqsList as $idx => $faq): ?>
            <details class="faq-item" <?= $idx === 0 ? 'open' : '' ?>>
                <summary class="faq-question">
                    <span><?= htmlspecialchars($faq['question']) ?></span>
                    <span class="faq-icon">▾</span>
                </summary>
                <div class="faq-answer">
                    <p><?= htmlspecialchars($faq['answer']) ?></p>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Dynamic Smart Topic Tags -->
    <?php
    $tags = [
        $article['category_name'],
        $article['official_source_name'] ?? 'Government of India',
        (!empty($article['state_name']) && $article['state_name'] !== 'All India / Central') ? $article['state_name'] : 'All India',
    ];
    if (preg_match('/(10th|12th|Graduate|Degree|Matric|Diploma|ITI)/i', $article['title'], $qm)) {
        $tags[] = $qm[1] . ' Pass';
    }
    ?>
    <div class="article-tags-wrap">
        <span class="tags-label">🏷️ Topics:</span>
        <?php foreach (array_unique($tags) as $t): ?>
        <a href="/search?q=<?= urlencode($t) ?>" class="article-tag-pill">#<?= htmlspecialchars(str_replace(' ', '_', $t)) ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Previous & Next Article Navigation Bar -->
    <?php if (!empty($prev_article) || !empty($next_article)): ?>
    <nav class="adjacent-articles-nav" aria-label="Adjacent Articles">
        <?php if (!empty($prev_article)): ?>
        <a href="/news/<?= htmlspecialchars($prev_article['slug']) ?>" class="adjacent-nav-card prev-card">
            <span class="nav-direction">← Previous Update</span>
            <span class="nav-title"><?= htmlspecialchars(mb_substr($prev_article['title'], 0, 65)) ?>...</span>
        </a>
        <?php else: ?>
        <div class="adjacent-nav-card empty-card"></div>
        <?php endif; ?>

        <?php if (!empty($next_article)): ?>
        <a href="/news/<?= htmlspecialchars($next_article['slug']) ?>" class="adjacent-nav-card next-card">
            <span class="nav-direction">Next Update →</span>
            <span class="nav-title"><?= htmlspecialchars(mb_substr($next_article['title'], 0, 65)) ?>...</span>
        </a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>
</article>

<!-- Mobile Sticky Bottom Action Dock -->
<?php if (!empty($article['official_pdf_url']) || !empty($article['official_source_url'])): ?>
<div class="mobile-action-dock" id="mobileActionDock">
    <div class="mobile-dock-inner">
        <?php if (!empty($article['official_pdf_url'])): ?>
        <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="mobile-dock-btn dock-btn-pdf">
            📥 Official PDF
        </a>
        <?php endif; ?>
        <?php if (!empty($article['official_source_url'])): ?>
        <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="mobile-dock-btn dock-btn-apply">
            🌐 Visit Portal ↗
        </a>
        <?php endif; ?>
        <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>" target="_blank" rel="noopener noreferrer" class="mobile-dock-btn dock-btn-share" title="Share on WhatsApp">
            💬
        </a>
        <button type="button" class="mobile-dock-btn dock-btn-share js-copy-link" data-url="http://<?= $_SERVER['HTTP_HOST'] ?>/news/<?= htmlspecialchars($article['slug']) ?>" title="Copy link">
            📋
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Related Updates from Same Authority / Department -->
<?php if (!empty($related_source_articles)): ?>
<section class="related-posts-section" style="margin-top: 2rem;">
    <h3 class="related-section-heading">
        🏛️ More Updates from <?= htmlspecialchars($article['official_source_name'] ?? 'This Authority') ?>
    </h3>
    <div class="related-cards-grid">
        <?php foreach ($related_source_articles as $rel): ?>
        <a href="/news/<?= htmlspecialchars($rel['slug']) ?>" class="related-post-card">
            <h4 class="related-card-title">
                <?= htmlspecialchars($rel['title']) ?>
            </h4>
            <div class="related-card-meta">
                <time datetime="<?= $rel['published_at'] ?>">📅 <?= date('M j, Y', strtotime($rel['published_at'])) ?></time>
                <span class="related-card-arrow">Read Notice →</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Related Category Updates Section -->
<?php if (!empty($related_articles)): ?>
<section class="related-posts-section" style="margin-top: 1.5rem;">
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
                <time datetime="<?= $rel['published_at'] ?>">📅 <?= date('M j, Y', strtotime($rel['published_at'])) ?></time>
                <span class="related-card-arrow">Read Notice →</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

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

// 4. Google FAQPage Schema
$faqEntities = [];
foreach ($faqsList as $faqItem) {
    $faqEntities[] = [
        "@type" => "Question",
        "name" => $faqItem['question'],
        "acceptedAnswer" => [
            "@type" => "Answer",
            "text" => $faqItem['answer'],
        ],
    ];
}
$schemaGraph[] = [
    "@type" => "FAQPage",
    "mainEntity" => $faqEntities,
];
?>
<script type="application/ld+json">
<?= json_encode([
    "@context" => "https://schema.org",
    "@graph"   => $schemaGraph
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>



