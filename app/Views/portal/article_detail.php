<!-- ==========================================================================
     EDUGOV NEWS — ULTRA-PREMIUM ARTICLE EDITORIAL ARCHITECTURE
     Features: 2-Column Responsive Layout with Sticky Sidebar, In-Text Autolinks,
     "Also Read" Editorial Callout, High-Visibility Prev/Next Topic Navigation,
     Related Category Stream, Same Authority Grid, and Rich Schema.org Graph
     ========================================================================== -->

<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <a href="/">🏠 <?= htmlspecialchars(__('nav_home')) ?></a>
    <span class="separator">/</span>
    <a href="/category/<?= htmlspecialchars($article['category_slug']) ?>"><?= htmlspecialchars($article['category_name']) ?></a>
    <?php if (!empty($article['state_name']) && $article['state_name'] !== 'All India'): ?>
    <span class="separator">/</span>
    <a href="/state/<?= htmlspecialchars(strtolower($article['state_code'])) ?>"><?= htmlspecialchars($article['state_name']) ?></a>
    <?php endif; ?>
    <span class="separator">/</span>
    <span><?= htmlspecialchars(mb_substr($article['title'], 0, 45)) ?>...</span>
</nav>

<?php
$wordCount = str_word_count(strip_tags($article['content_html'] ?? ''));
if ($wordCount < 50) {
    $wordCount = (int)ceil(mb_strlen(strip_tags($article['content_html'] ?? '')) / 5);
}
$readingTime = max(1, (int)ceil($wordCount / 180));
$locale = \App\Core\I18n::getLocale();
?>

<!-- 2-Column Responsive Main Grid Layout -->
<div class="article-page-grid">
    <!-- Main Editorial Article Column -->
    <main class="article-main-col">
        <article class="article-container">
            <!-- Article Header -->
            <header class="article-header">
                <div class="article-badges-cluster">
                    <span class="cat-badge"><?= htmlspecialchars($article['category_name']) ?></span>
                    <?php if (!empty($article['official_source_name'])): ?>
                    <span class="official-verified-badge">
                        ✓ <?= htmlspecialchars(__('verified_source_heading')) ?>: <?= htmlspecialchars($article['source_domain'] ?? $article['official_source_name']) ?>
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($article['state_name']) && $article['state_name'] !== 'All India'): ?>
                    <span class="state-badge">📍 <?= htmlspecialchars($article['state_name']) ?></span>
                    <?php endif; ?>
                </div>

                <h1 class="article-title-h1"><?= htmlspecialchars($article['title']) ?></h1>

                <div class="article-meta-bar">
                    <span class="meta-pill">📅 <?= date('M j, Y — g:i A', strtotime($article['published_at'])) ?></span>
                    <span class="meta-pill reading-time-pill">⏱️ <?= $readingTime ?> min read (<?= number_format($wordCount) ?> words)</span>
                    <?php if ($article['version_number'] > 1): ?>
                    <span class="meta-pill version-pill">⚡ v<?= (int)$article['version_number'] ?></span>
                    <?php endif; ?>
                    <span class="meta-pill">👁️ <?= number_format((int)$article['views_count']) ?> views</span>
                </div>

                <!-- Multi-Language Reader Bar -->
                <div class="article-lang-switch-box" style="margin: 0.9rem 0; padding: 0.6rem 1rem; background: linear-gradient(135deg, rgba(2,132,199,0.06) 0%, rgba(16,185,129,0.06) 100%); border: 1px solid var(--color-border); border-radius: 10px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: var(--color-navy-600);">
                        <span style="font-size: 1.1rem;">🌐</span>
                        <span><?= htmlspecialchars(__('read_in_language')) ?></span>
                    </div>
                    <div class="lang-pills-row" style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                        <a href="/set-language/en" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; font-weight: 700; border-radius: 6px; text-decoration: none; <?= ($current_locale ?? 'en') === 'en' ? 'background: #0284c7; color: #fff;' : 'background: var(--color-bg); color: var(--color-text-main); border: 1px solid var(--color-border);' ?>">🇬🇧 English</a>
                        <a href="/set-language/bn" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; font-weight: 700; border-radius: 6px; text-decoration: none; <?= ($current_locale ?? 'en') === 'bn' ? 'background: #0284c7; color: #fff;' : 'background: var(--color-bg); color: var(--color-text-main); border: 1px solid var(--color-border);' ?>">🇧🇩 বাংলা</a>
                        <a href="/set-language/hi" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; font-weight: 700; border-radius: 6px; text-decoration: none; <?= ($current_locale ?? 'en') === 'hi' ? 'background: #0284c7; color: #fff;' : 'background: var(--color-bg); color: var(--color-text-main); border: 1px solid var(--color-border);' ?>">🇮🇳 हिंदी</a>
                    </div>
                </div>

                <!-- Social Share & Action Bar -->
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

                <!-- AI Audio Reader Bar -->
                <div class="audio-article-player">
                    <div class="audio-left">
                        <button type="button" class="audio-play-btn js-audio-play" aria-label="Listen to Audio Summary">
                            <span class="play-icon">▶</span>
                        </button>
                        <div class="audio-info">
                            <strong class="audio-title">🎧 <?= htmlspecialchars(__('listen_audio_summary') ?? 'Listen to this Official Update') ?></strong>
                            <span class="audio-meta"><?= $readingTime ?> min listen • Smart Audio Narration</span>
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

            <!-- Hierarchical Table of Contents (Parent-Child H2 & H3 with Header Clearance) -->
            <div class="article-toc-box" id="articleTocBox">
                <div class="toc-header" id="tocHeaderToggle">
                    <div class="toc-header-left">
                        <span class="toc-icon">📑</span>
                        <span class="toc-title"><?= htmlspecialchars($locale === 'bn' ? 'সূচিপত্র (Table of Contents)' : ($locale === 'hi' ? 'विषय-सूची (Table of Contents)' : 'Table of Contents')) ?></span>
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
                            <li class="toc-parent-item"><a href="#overview" class="toc-link">📌 <?= htmlspecialchars(__('verified_source_heading')) ?></a></li>
                            <?php if (!empty($article['official_pdf_url']) || !empty($article['official_source_url'])): ?>
                            <li class="toc-parent-item"><a href="#official-links" class="toc-link">⚡ Direct PDF & Online Portal Links</a></li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Prominent Dual Action CTA Box if URLs are available -->
            <?php if (!empty($article['official_pdf_url']) || !empty($article['official_source_url'])): ?>
            <div class="article-cta-box" id="official-links">
                <div class="cta-box-title">
                    ⚡ <?= htmlspecialchars($locale === 'bn' ? 'অফিসিয়াল সরাসরি লিঙ্ক ও ডাউনলোড' : ($locale === 'hi' ? 'आधिकारिक प्रत्यक्ष लिंक एवं डाउनलोड' : 'Direct Official Links & Downloads')) ?>
                </div>
                <div class="cta-buttons-wrap">
                    <?php if (!empty($article['official_pdf_url'])): ?>
                    <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="btn-official-pdf">
                        📥 <?= htmlspecialchars(__('download_pdf')) ?>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($article['official_source_url'])): ?>
                    <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="btn-official-apply">
                        🌐 <?= htmlspecialchars(__('official_portal_link')) ?> ↗
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
                <a href="/results" class="tag-pill">#Results</a>
                <a href="/admit-card" class="tag-pill">#AdmitCard</a>
                <a href="/recruitment" class="tag-pill">#Recruitment</a>
            </div>

            <!-- Prominent Previous & Next Article Navigation -->
            <?php if (!empty($prev_article) || !empty($next_article)): ?>
            <nav class="adjacent-articles-nav" aria-label="Adjacent Articles Navigation">
                <?php if (!empty($prev_article)): ?>
                <a href="/news/<?= htmlspecialchars($prev_article['slug']) ?>" class="adjacent-nav-card prev-card">
                    <div class="nav-dir-badge">
                        <span>←</span> <?= htmlspecialchars($locale === 'bn' ? 'পূর্ববর্তী পোস্ট' : ($locale === 'hi' ? 'पिछला पोस्ट' : 'PREVIOUS TOPIC')) ?>
                    </div>
                    <strong class="nav-title"><?= htmlspecialchars($prev_article['title']) ?></strong>
                    <span class="nav-date">📅 <?= date('M j, Y', strtotime($prev_article['published_at'])) ?></span>
                </a>
                <?php else: ?>
                <div class="adjacent-nav-placeholder"></div>
                <?php endif; ?>

                <?php if (!empty($next_article)): ?>
                <a href="/news/<?= htmlspecialchars($next_article['slug']) ?>" class="adjacent-nav-card next-card">
                    <div class="nav-dir-badge">
                        <?= htmlspecialchars($locale === 'bn' ? 'পরবর্তী পোস্ট' : ($locale === 'hi' ? 'अगला पोस्ट' : 'NEXT TOPIC')) ?> <span>→</span>
                    </div>
                    <strong class="nav-title"><?= htmlspecialchars($next_article['title']) ?></strong>
                    <span class="nav-date">📅 <?= date('M j, Y', strtotime($next_article['published_at'])) ?></span>
                </a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>
        </article>

        <!-- Related Updates from Same Category (4-Card Rich Grid) -->
        <?php if (!empty($related_articles)): ?>
        <section class="related-authority-section" style="margin-top: 2rem;">
            <div class="related-section-header">
                <div class="related-header-left">
                    <span class="sec-icon">📂</span>
                    <h3 class="related-section-title">
                        <?= htmlspecialchars($locale === 'bn' ? 'সম্পর্কিত অন্যান্য গুরুত্বপূর্ণ নোটিশ' : ($locale === 'hi' ? 'संबंधित अन्य महत्वपूर्ण सूचनाएं' : 'Related Important Notifications')) ?>
                    </h3>
                </div>
                <a href="/category/<?= htmlspecialchars($article['category_slug']) ?>" class="view-all-board-link">
                    <?= htmlspecialchars($locale === 'bn' ? 'সব নোটিশ দেখুন' : ($locale === 'hi' ? 'सभी नोटिस देखें' : 'View All in Category')) ?> →
                </a>
            </div>

            <div class="related-cards-grid">
                <?php foreach ($related_articles as $rel): ?>
                <a href="/news/<?= htmlspecialchars($rel['slug'] ?? '') ?>" class="related-post-card">
                    <div class="rel-badge-row">
                        <span class="related-card-badge"><?= htmlspecialchars($article['category_name']) ?></span>
                        <time datetime="<?= $rel['published_at'] ?? '' ?>" class="rel-time-tag">📅 <?= !empty($rel['published_at']) ? date('M j, Y', strtotime($rel['published_at'])) : 'Recent' ?></time>
                    </div>
                    <h4 class="related-card-title"><?= htmlspecialchars($rel['title'] ?? '') ?></h4>
                    <span class="related-card-arrow">
                        <?= htmlspecialchars($locale === 'bn' ? 'বিজ্ঞপ্তি পড়ুন' : ($locale === 'hi' ? 'सूचना पढ़ें' : 'Read Notice')) ?> →
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Related Updates from the Same Official Authority Grid -->
        <?php if (!empty($related_source_articles)): ?>
        <section class="related-authority-section" style="margin-top: 2rem;">
            <div class="related-section-header">
                <div class="related-header-left">
                    <span class="sec-icon">🏛️</span>
                    <h3 class="related-section-title">
                        <?= htmlspecialchars($locale === 'bn' ? ($article['official_source_name'] ?? 'সংশ্লিষ্ট দপ্তর') . '-এর অন্যান্য আপডেট' : ($locale === 'hi' ? ($article['official_source_name'] ?? 'संबंधित विभाग') . ' के अन्य अपडेट' : 'More Updates from ' . ($article['official_source_name'] ?? 'this Authority'))) ?>
                    </h3>
                </div>
                <a href="/search?q=<?= urlencode($article['official_source_name'] ?? '') ?>" class="view-all-board-link">
                    <?= htmlspecialchars($locale === 'bn' ? 'সমস্ত নোটিশ দেখুন' : ($locale === 'hi' ? 'सभी नोटिस देखें' : 'All Notices')) ?> →
                </a>
            </div>

            <div class="related-cards-grid">
                <?php foreach ($related_source_articles as $rel): ?>
                <a href="/news/<?= htmlspecialchars($rel['slug'] ?? '') ?>" class="related-post-card">
                    <div class="rel-badge-row">
                        <span class="related-card-badge"><?= htmlspecialchars($rel['category_name'] ?? 'Notification') ?></span>
                        <time datetime="<?= $rel['published_at'] ?? '' ?>" class="rel-time-tag">📅 <?= !empty($rel['published_at']) ? date('M j, Y', strtotime($rel['published_at'])) : 'Recent' ?></time>
                    </div>
                    <h4 class="related-card-title"><?= htmlspecialchars($rel['title'] ?? '') ?></h4>
                    <span class="related-card-arrow">
                        <?= htmlspecialchars($locale === 'bn' ? 'বিজ্ঞপ্তি পড়ুন' : ($locale === 'hi' ? 'सूचना पढ़ें' : 'Read Notice')) ?> →
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- SEO Deep Cluster Hub Box (Quick Authority & Category Interlinking Mesh) -->
        <section class="seo-cluster-hub" style="margin-top: 2rem; padding: 1.5rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg);">
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--color-text-main); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>🚀</span> <?= htmlspecialchars($locale === 'bn' ? 'দ্রুত নেভিগেশন ও পোর্টাল লিঙ্ক' : ($locale === 'hi' ? 'त्वरित नेविगेशन एवं पोर्टल लिंक' : 'Quick Navigation & Portal Links')) ?>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <a href="/results" class="chip-item">📋 <?= htmlspecialchars(__('nav_results')) ?></a>
                <a href="/admit-card" class="chip-item">🎫 <?= htmlspecialchars(__('nav_admit_card')) ?></a>
                <a href="/recruitment" class="chip-item">💼 <?= htmlspecialchars(__('nav_recruitment')) ?></a>
                <a href="/exam" class="chip-item">📝 <?= htmlspecialchars(__('nav_exam_dates')) ?></a>
                <a href="/answer-key" class="chip-item">🔑 <?= htmlspecialchars(__('nav_answer_key')) ?></a>
                <a href="/category/scholarship" class="chip-item">🏆 <?= htmlspecialchars(__('nav_scholarship')) ?></a>
                <a href="/state/central-govt" class="chip-item">🏛️ Central Govt</a>
                <a href="/state/west-bengal" class="chip-item">🌊 West Bengal</a>
                <a href="/state/uttar-pradesh" class="chip-item">🌾 Uttar Pradesh</a>
                <a href="/state/bihar" class="chip-item">🚩 Bihar</a>
                <a href="/state/rajasthan" class="chip-item">🏰 Rajasthan</a>
            </div>
        </section>
    </main>

    <!-- Right Sidebar Column with Live Updates, Adverts & Hubs -->
    <?php require_once __DIR__ . '/../partials/sidebar.php'; ?>
</div>

<!-- Sticky Mobile Smart Action Dock -->
<div class="smart-action-dock">
    <div class="dock-inner">
        <?php if (!empty($article['official_source_url'])): ?>
        <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="dock-btn dock-btn-apply">
            <span class="dock-icon">🔗</span>
            <span class="dock-text"><?= htmlspecialchars(__('official_portal_link')) ?></span>
        </a>
        <?php endif; ?>
        <?php if (!empty($article['official_pdf_url'])): ?>
        <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" rel="noopener noreferrer nofollow" class="dock-btn dock-btn-pdf">
            <span class="dock-icon">📥</span>
            <span class="dock-text"><?= htmlspecialchars(__('download_pdf')) ?></span>
        </a>
        <?php endif; ?>
        <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . '/news/' . $article['slug']) ?>" target="_blank" rel="noopener noreferrer" class="dock-btn dock-btn-wa">
            <span class="dock-icon">💬</span>
            <span class="dock-text">WhatsApp</span>
        </a>
    </div>
</div>

<!-- Schema.org JSON-LD Structured Data Engine -->
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
    "description" => $article['meta_description'] ?? ($article['excerpt'] ?? mb_substr(strip_tags($article['content_html']), 0, 160)),
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

// 3. Google JobPosting Schema
$isJobAlert = in_array($article['category_slug'], ['recruitment', 'government-jobs', 'application-form']) 
              || preg_match('/recruitment|vacancy|vacancies|jobs|bharti|posts|officer|clerk|constable/i', $article['title']);

if ($isJobAlert) {
    $schemaGraph[] = [
        "@type" => "JobPosting",
        "title" => $article['title'],
        "description" => $article['meta_description'] ?? ($article['excerpt'] ?? mb_substr(strip_tags($article['content_html']), 0, 200)),
        "datePosted" => date('c', strtotime($article['published_at'])),
        "validThrough" => date('c', strtotime($article['published_at'] . ' +45 days')),
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
