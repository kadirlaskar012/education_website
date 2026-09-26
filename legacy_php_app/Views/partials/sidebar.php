<!-- Standard Shared Right Sidebar -->
<aside class="feed-sidebar-col">
    <!-- 1. Latest 10 Official Notices -->
    <?php if (!empty($sidebar_latest_notices)): ?>
    <div class="sidebar-card">
        <h3 class="sidebar-title">
            <span>📢</span> <?= htmlspecialchars(__('recent_notices')) ?>
        </h3>
        <div class="sidebar-notices-list">
            <?php foreach ($sidebar_latest_notices as $sbNotice): ?>
            <a href="/news/<?= htmlspecialchars($sbNotice['slug']) ?>" class="sidebar-notice-item">
                <div class="sb-notice-badge-row">
                    <span class="sb-badge-tag"><?= htmlspecialchars($sbNotice['category_name']) ?></span>
                    <span class="sb-time-text"><?= date('M j', strtotime($sbNotice['published_at'])) ?></span>
                </div>
                <h4 class="sb-notice-title">
                    <?= htmlspecialchars($sbNotice['title']) ?>
                </h4>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- AdSense Sidebar Placement Slot -->
    <div class="adsense-slot-wrapper adsense-slot-sidebar" style="margin-top: 1.5rem;" aria-label="Sponsored Advertisement">
        <div class="ad-disclosure-bar"><span class="ad-label">ADVERTISEMENT</span></div>
        <div class="ad-banner-placeholder">
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="5566778899" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <div class="ad-demo-preview">
                <span class="ad-demo-icon">📢</span>
                <span class="ad-demo-text">Sidebar Responsive Ad Slot</span>
            </div>
        </div>
    </div>

    <!-- 2. Quick Categories -->
    <div class="sidebar-card" style="margin-top: 1.5rem;">
        <h3 class="sidebar-title">
            <span>📂</span> <?= htmlspecialchars(__('quick_categories')) ?>
        </h3>
        <ul class="sidebar-links-list">
            <li><a href="/results">📋 <?= htmlspecialchars(__('nav_results')) ?></a></li>
            <li><a href="/admit-card">🎫 <?= htmlspecialchars(__('nav_admit_card')) ?></a></li>
            <li><a href="/recruitment">💼 <?= htmlspecialchars(__('nav_recruitment')) ?></a></li>
            <li><a href="/exam">📝 <?= htmlspecialchars(__('nav_exam_dates')) ?></a></li>
            <li><a href="/answer-key">🔑 <?= htmlspecialchars(__('nav_answer_key')) ?></a></li>
            <li><a href="/category/scholarship">🏆 <?= htmlspecialchars(__('nav_scholarship')) ?></a></li>
            <li><a href="/category/admission">🎓 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'ভর্তি ও কাউন্সেলিং' : (\App\Core\I18n::getLocale() === 'hi' ? 'प्रवेश एवं काउंसलिंग' : 'Admission & Counseling')) ?></a></li>
            <li><a href="/category/board-exams">🏫 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'বোর্ড পরীক্ষা' : (\App\Core\I18n::getLocale() === 'hi' ? 'बोर्ड परीक्षाएं' : 'Board Exams (CBSE/ICSE)')) ?></a></li>
        </ul>
    </div>

    <!-- 3. State-Wise Portals -->
    <div class="sidebar-card" style="margin-top: 1.5rem;">
        <h3 class="sidebar-title">
            <span>🗺️</span> <?= htmlspecialchars(__('nav_states')) ?>
        </h3>
        <ul class="sidebar-links-list">
            <li><a href="/state/central-govt">🏛️ <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'কেন্দ্রীয় সরকার' : (\App\Core\I18n::getLocale() === 'hi' ? 'केंद्रीय सरकार' : 'Central Government')) ?></a></li>
            <li><a href="/state/west-bengal">🌊 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'পশ্চিমবঙ্গ (WBPSC)' : (\App\Core\I18n::getLocale() === 'hi' ? 'पश्चिम बंगाल (WBPSC)' : 'West Bengal (WBPSC)')) ?></a></li>
            <li><a href="/state/uttar-pradesh">🌾 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'উত্তরপ্রদেশ (UPPSC)' : (\App\Core\I18n::getLocale() === 'hi' ? 'उत्तर प्रदेश (UPPSC)' : 'Uttar Pradesh (UPPSC)')) ?></a></li>
            <li><a href="/state/bihar">🚩 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'বিহার (BPSC)' : (\App\Core\I18n::getLocale() === 'hi' ? 'बिहार (BPSC)' : 'Bihar (BPSC)')) ?></a></li>
            <li><a href="/state/rajasthan">🏰 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'রাজস্থান (RPSC)' : (\App\Core\I18n::getLocale() === 'hi' ? 'राजस्थान (RPSC)' : 'Rajasthan (RPSC)')) ?></a></li>
            <li><a href="/state/madhya-pradesh">🌲 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'মধ্যপ্রদেশ (MPPSC)' : (\App\Core\I18n::getLocale() === 'hi' ? 'मध्य प्रदेश (MPPSC)' : 'Madhya Pradesh (MPPSC)')) ?></a></li>
            <li><a href="/state/maharashtra">🏙️ <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'মহারাষ্ট্র (MPSC)' : (\App\Core\I18n::getLocale() === 'hi' ? 'महाराष्ट्र (MPSC)' : 'Maharashtra (MPSC)')) ?></a></li>
            <li><a href="/state/all-india">🇮🇳 <?= htmlspecialchars(\App\Core\I18n::getLocale() === 'bn' ? 'সর্বভারতীয় কেন্দ্রীয় চাকরি' : (\App\Core\I18n::getLocale() === 'hi' ? 'अखिल भारतीय केंद्रीय नौकरियां' : 'All India Central Jobs')) ?></a></li>
        </ul>
    </div>

    <!-- 4. Official Authenticity Notice -->
    <div class="sidebar-card" style="margin-top: 1.5rem;">
        <h3 class="sidebar-title">
            <span>🛡️</span> <?= htmlspecialchars(__('official_authenticity')) ?>
        </h3>
        <p style="font-size: 0.8125rem; color: var(--color-text-muted, #64748b); line-height: 1.55; margin-bottom: 0;">
            <?= htmlspecialchars(__('official_auth_desc')) ?>
        </p>
    </div>
</aside>
