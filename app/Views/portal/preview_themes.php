<!-- Interactive Live Color & UI Showcase -->
<div class="theme-showcase-control-bar">
    <div class="site-container showcase-control-inner">
        <div class="showcase-title-col">
            <span class="showcase-badge">🎨 LIVE THEME & UI LAB</span>
            <strong class="showcase-heading">Choose a Color Palette & UI Concept to Preview:</strong>
        </div>
        <div class="theme-switcher-pills">
            <button type="button" class="theme-pill-btn active js-set-theme" data-theme="sapphire-gold">
                <span class="color-dot" style="background: linear-gradient(135deg, #0b192c, #0066ff, #f59e0b);"></span>
                <span class="pill-title">Theme 1: Sapphire Gold</span>
                <span class="pill-tag">Royal & Merit</span>
            </button>
            <button type="button" class="theme-pill-btn js-set-theme" data-theme="emerald-forest">
                <span class="color-dot" style="background: linear-gradient(135deg, #064e3b, #059669, #d97706);"></span>
                <span class="pill-title">Theme 2: Emerald Forest</span>
                <span class="pill-tag">Focus & Growth</span>
            </button>
            <button type="button" class="theme-pill-btn js-set-theme" data-theme="cyber-indigo">
                <span class="color-dot" style="background: linear-gradient(135deg, #0f172a, #6366f1, #ff5722);"></span>
                <span class="pill-title">Theme 3: Cyber Indigo</span>
                <span class="pill-tag">Ultra Modern</span>
            </button>
        </div>
    </div>
</div>

<div class="site-container" style="padding-top: 1.5rem; padding-bottom: 3rem;">
    <!-- Theme Description Callout -->
    <div id="themeDescBox" class="theme-description-banner">
        <div class="theme-desc-icon" id="themeDescIcon">💎</div>
        <div class="theme-desc-content">
            <h3 id="themeDescTitle" style="margin: 0 0 0.25rem 0; font-size: 1.1rem; color: var(--color-primary);">
                Theme 1: Oxford Sapphire & Golden Amber (Royal Merit Edition)
            </h3>
            <p id="themeDescText" style="margin: 0; font-size: 0.875rem; color: var(--color-text-muted);">
                Deep Royal Navy header, vibrant cobalt accents for links, radiant amber gold for merit/cutoffs, and crisp snow canvas for zero eye fatigue.
            </p>
        </div>
    </div>

    <!-- Article Container -->
    <article class="article-container" style="margin-top: 1.5rem;">
        <!-- Header -->
        <header class="article-header">
            <!-- Breadcrumb -->
            <nav class="breadcrumb-nav" aria-label="Breadcrumb" style="margin-bottom: 0.75rem;">
                <a href="/">Home</a>
                <span class="separator">/</span>
                <a href="/recruitment"><?= htmlspecialchars($article['category_name']) ?></a>
                <span class="separator">/</span>
                <span><?= htmlspecialchars(mb_substr($article['title'], 0, 45)) ?>...</span>
            </nav>

            <h1 class="article-title-h1"><?= htmlspecialchars($article['title']) ?></h1>

            <div class="article-meta-bar">
                <span class="cat-badge"><?= htmlspecialchars($article['category_name']) ?></span>
                <span class="official-verified-badge">✓ Verified Official: <?= htmlspecialchars($article['official_source_name']) ?></span>
                <span class="meta-pill">📅 <?= date('M j, Y — g:i A', strtotime($article['published_at'])) ?></span>
                <span class="meta-pill">👁️ 14,250 views</span>
            </div>

            <!-- UI Concept 3: AI Audio Reader Bar -->
            <div class="audio-article-player">
                <div class="audio-left">
                    <button type="button" class="audio-play-btn js-audio-play" aria-label="Listen to Article">
                        <span class="play-icon">▶</span>
                    </button>
                    <div class="audio-info">
                        <strong class="audio-title">🎧 Listen to this Announcement (Audio Summary)</strong>
                        <span class="audio-meta">2 min 45 sec • AI Voice Narration</span>
                    </div>
                </div>
                <div class="audio-right">
                    <span class="audio-badge">LIVE AUDIO</span>
                </div>
            </div>
        </header>

        <!-- UI Concept 1: 3-Column Quick Factsheet Hero Widget -->
        <div class="quick-factsheet-widget">
            <div class="factsheet-header">
                <div class="factsheet-title">
                    <span class="factsheet-icon">⚡</span>
                    <strong>Key Highlights & Summary Factsheet (Quick Overview)</strong>
                </div>
                <span class="factsheet-badge">Official CEN 01/2026</span>
            </div>
            <div class="factsheet-grid">
                <div class="factsheet-card">
                    <span class="f-label">👥 Total Vacancies</span>
                    <strong class="f-value" style="color: var(--color-primary);">11,558 Posts</strong>
                    <span class="f-sub">Graduate & 12th Level</span>
                </div>
                <div class="factsheet-card">
                    <span class="f-label">📅 Last Date to Apply</span>
                    <strong class="f-value" style="color: var(--color-danger);">15 Oct, 2026</strong>
                    <span class="f-sub">11:59 PM Online Portal</span>
                </div>
                <div class="factsheet-card">
                    <span class="f-label">🎓 Eligibility</span>
                    <strong class="f-value">12th / Any Degree</strong>
                    <span class="f-sub">Recognized University</span>
                </div>
                <div class="factsheet-card">
                    <span class="f-label">💰 Pay Scale</span>
                    <strong class="f-value" style="color: var(--color-warning);">Level 2 - Level 6</strong>
                    <span class="f-sub">₹19,900 - ₹63,200/mo</span>
                </div>
                <div class="factsheet-card">
                    <span class="f-label">📝 Exam Mode</span>
                    <strong class="f-value">Computer Based (CBT)</strong>
                    <span class="f-sub">CBT-1 & CBT-2</span>
                </div>
                <div class="factsheet-card">
                    <span class="f-label">🏛️ Official Portal</span>
                    <strong class="f-value">rrbapply.gov.in</strong>
                    <span class="f-sub">All 21 RRB Zones</span>
                </div>
            </div>
        </div>

        <!-- UI Concept 2: Visual Event Timeline Widget -->
        <div class="visual-event-timeline-box">
            <div class="timeline-title">
                <span>🗓️</span> <strong>Important Schedule & Timeline</strong>
            </div>
            <div class="timeline-steps-track">
                <div class="timeline-step completed">
                    <div class="step-circle">✓</div>
                    <div class="step-details">
                        <span class="step-date">Sep 15, 2026</span>
                        <strong class="step-title">Notification Released</strong>
                    </div>
                </div>
                <div class="timeline-step active">
                    <div class="step-circle">⚡</div>
                    <div class="step-details">
                        <span class="step-date">Sep 16 - Oct 15</span>
                        <strong class="step-title">Online Application Active</strong>
                    </div>
                </div>
                <div class="timeline-step upcoming">
                    <div class="step-circle">🎫</div>
                    <div class="step-details">
                        <span class="step-date">Nov 2026</span>
                        <strong class="step-title">Admit Card Download</strong>
                    </div>
                </div>
                <div class="timeline-step upcoming">
                    <div class="step-circle">📝</div>
                    <div class="step-details">
                        <span class="step-date">Dec 2026</span>
                        <strong class="step-title">CBT-1 Examination</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top AdSense Responsive Slot -->
        <div class="adsense-slot-wrapper adsense-slot-top" aria-label="Sponsored Advertisement">
            <div class="ad-disclosure-bar"><span class="ad-label">ADVERTISEMENT</span></div>
            <div class="ad-banner-placeholder">
                <div class="ad-demo-preview">
                    <span class="ad-demo-icon">📢</span>
                    <span class="ad-demo-text">High CTR Top Responsive Ad Slot (Policy Compliant)</span>
                </div>
            </div>
        </div>

        <!-- Table of Contents -->
        <div class="article-toc-box">
            <div class="toc-header">
                <div class="toc-header-left">
                    <span class="toc-icon">📑</span>
                    <span class="toc-title">Table of Contents</span>
                </div>
            </div>
            <div class="toc-body">
                <ul class="toc-list">
                    <li><a href="#overview">1. RRB NTPC 2026 Recruitment Overview</a></li>
                    <li><a href="#eligibility">2. Post-Wise Vacancies & Eligibility</a></li>
                    <li><a href="#exam-pattern">3. CBT Exam Pattern & Syllabus</a></li>
                    <li><a href="#how-to-apply">4. Step-by-Step Online Application Process</a></li>
                </ul>
            </div>
        </div>

        <!-- Article Rich Content with In-Text Autolinks & ALSO READ Box -->
        <div class="article-body-content">
            <h2 id="overview">1. RRB NTPC 2026 Recruitment Overview</h2>
            <p>
                The Railway Recruitment Boards have issued the centralized vacancy notice for 11,558 positions. Much like the prestigious <a href="#" class="internal-topic-link" title="SSC CGL 2026 Official Updates">SSC CGL</a> and <a href="#" class="internal-topic-link" title="WBPSC Civil Service Updates">WBPSC</a> examinations, this recruitment is among the most sought-after career opportunities for graduates and higher secondary aspirants across India.
            </p>

            <!-- Automated ALSO READ Editorial Callout Box -->
            <div class="also-read-callout-box">
                <div class="also-read-badge">📌 ALSO READ</div>
                <div class="also-read-content">
                    <a href="#" class="also-read-link">
                        SSC CHSL 2026 Application Form Released — Check 3,712 Vacancies & Direct Apply Link <span class="also-read-arrow">↗</span>
                    </a>
                </div>
            </div>

            <h2 id="eligibility">2. Post-Wise Vacancies & Eligibility</h2>
            <p>
                Candidates must possess a Bachelor's degree from any recognized university for Graduate level posts (Commercial Apprentice, Station Master, Goods Guard) or Higher Secondary (10+2) for Junior Clerk cum Typist positions. The age relaxation for SC/ST is 5 years and OBC (Non-Creamy Layer) is 3 years according to central government norms.
            </p>

            <!-- Official Action Hub Buttons -->
            <div class="official-action-hub" style="margin: 2rem 0; display: flex; flex-wrap: wrap; gap: 1rem;">
                <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" class="link-btn link-btn-primary" style="padding: 0.85rem 1.5rem; font-size: 0.95rem;">
                    🔗 Apply Online on Official Portal ↗
                </a>
                <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" class="link-btn link-btn-secondary" style="padding: 0.85rem 1.5rem; font-size: 0.95rem;">
                    📥 Download Official Notification PDF ↗
                </a>
            </div>
        </div>

        <!-- Tag Clusters -->
        <div class="article-tags-wrap" style="margin-top: 2rem;">
            <span class="tags-heading">🏷️ Topic Matrix:</span>
            <a href="/recruitment" class="tag-pill">#Recruitment</a>
            <a href="/state/central-govt" class="tag-pill">#Central-Govt</a>
            <a href="/search?q=RRB" class="tag-pill">#Railway</a>
            <a href="/search?q=Graduate" class="tag-pill">#Graduate-Jobs</a>
            <a href="/results" class="tag-pill">#CutOff-Marks</a>
        </div>
    </article>
</div>

<!-- UI Concept 3: Sticky Mobile Smart Action Dock -->
<div class="smart-action-dock">
    <div class="dock-inner">
        <a href="<?= htmlspecialchars($article['official_source_url']) ?>" target="_blank" class="dock-btn dock-btn-apply">
            <span class="dock-icon">🔗</span>
            <span class="dock-text">Apply Online</span>
        </a>
        <a href="<?= htmlspecialchars($article['official_pdf_url']) ?>" target="_blank" class="dock-btn dock-btn-pdf">
            <span class="dock-icon">📥</span>
            <span class="dock-text">Download PDF</span>
        </a>
        <a href="https://api.whatsapp.com/send?text=Check+RRB+NTPC+2026+Recruitment" target="_blank" class="dock-btn dock-btn-wa">
            <span class="dock-icon">💬</span>
            <span class="dock-text">WhatsApp</span>
        </a>
    </div>
</div>

<!-- Dynamic Theme Switcher Client-Side JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const themeButtons = document.querySelectorAll('.js-set-theme');
    const descIcon = document.getElementById('themeDescIcon');
    const descTitle = document.getElementById('themeDescTitle');
    const descText = document.getElementById('themeDescText');

    const themeData = {
        'sapphire-gold': {
            icon: '💎',
            title: 'Theme 1: Oxford Sapphire & Golden Amber (Royal Merit Edition)',
            desc: 'Deep Royal Navy header (#0B192C), vibrant cobalt blue accents (#0066FF), radiant amber gold for merit/cutoffs (#F59E0B), and crisp snow canvas (#FAFBFC).'
        },
        'emerald-forest': {
            icon: '🌿',
            title: 'Theme 2: Emerald Forest & Soft Ivory (Growth & Focus Edition)',
            desc: 'Calm deep forest slate (#064E3B), fresh emerald green apply buttons (#059669), warm amber alerts (#D97706), and paper-soft canvas (#F8FAFC) for zero eye strain.'
        },
        'cyber-indigo': {
            icon: '⚡',
            title: 'Theme 3: Cyber Indigo & Sunset Tangerine (Ultra-Modern Edition)',
            desc: 'Galactic dark slate header (#0F172A), electric indigo accents (#6366F1), high-converting sunset orange CTAs (#FF5722), and modern frosted glass highlights.'
        }
    };

    themeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const theme = btn.getAttribute('data-theme');
            
            // Set active class
            themeButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Set HTML attribute
            document.documentElement.setAttribute('data-theme-style', theme);
            localStorage.setItem('edugov_preview_theme', theme);

            // Update description box
            if (themeData[theme]) {
                descIcon.textContent = themeData[theme].icon;
                descTitle.textContent = themeData[theme].title;
                descText.textContent = themeData[theme].desc;
            }
        });
    });

    // Check saved theme
    const saved = localStorage.getItem('edugov_preview_theme');
    if (saved) {
        const targetBtn = document.querySelector(`.js-set-theme[data-theme="${saved}"]`);
        if (targetBtn) targetBtn.click();
    }
});
</script>
