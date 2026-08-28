<!-- Standard Shared Right Sidebar -->
<aside class="feed-sidebar-col">
    <!-- 1. Latest 10 Official Notices -->
    <?php if (!empty($sidebar_latest_notices)): ?>
    <div class="sidebar-card">
        <h3 class="sidebar-title">
            <span>📢</span> Latest 10 Notices
        </h3>
        <div class="sidebar-notices-list">
            <?php 
            $noticeIndex = 1;
            foreach ($sidebar_latest_notices as $sbNotice): 
            ?>
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

    <!-- 2. Quick Categories -->
    <div class="sidebar-card" style="margin-top: 1.5rem;">
        <h3 class="sidebar-title">
            <span>📂</span> Quick Categories
        </h3>
        <ul class="sidebar-links-list">
            <li><a href="/results">📋 Results & Merit Lists</a></li>
            <li><a href="/admit-card">🎫 Admit Cards & Slips</a></li>
            <li><a href="/recruitment">💼 Government Recruitment</a></li>
            <li><a href="/exam">📝 Exam Calendar & Dates</a></li>
            <li><a href="/answer-key">🔑 Answer Keys</a></li>
            <li><a href="/category/scholarship">🏆 Scholarships & Grants</a></li>
            <li><a href="/category/admission">🎓 Admission & Counseling</a></li>
            <li><a href="/category/board-exams">🏫 Board Exams (CBSE/ICSE)</a></li>
        </ul>
    </div>

    <!-- 3. State-Wise Portals -->
    <div class="sidebar-card" style="margin-top: 1.5rem;">
        <h3 class="sidebar-title">
            <span>🗺️</span> State Portals
        </h3>
        <ul class="sidebar-links-list">
            <li><a href="/state/central-govt">🏛️ Central Government</a></li>
            <li><a href="/state/west-bengal">🌊 West Bengal (WBPSC)</a></li>
            <li><a href="/state/uttar-pradesh">🌾 Uttar Pradesh (UPPSC)</a></li>
            <li><a href="/state/bihar">🚩 Bihar (BPSC)</a></li>
            <li><a href="/state/rajasthan">🏰 Rajasthan (RPSC)</a></li>
            <li><a href="/state/madhya-pradesh">🌲 Madhya Pradesh (MPPSC)</a></li>
            <li><a href="/state/maharashtra">🏙️ Maharashtra (MPSC)</a></li>
            <li><a href="/state/all-india">🇮🇳 All India Central Jobs</a></li>
        </ul>
    </div>

    <!-- 4. Official Government Portals Monitored (Professional, No Scraped Label) -->
    <div class="sidebar-card" style="margin-top: 1.5rem;">
        <h3 class="sidebar-title">
            <span>🏛️</span> Official Portals Monitored
        </h3>
        <ul class="sidebar-links-list">
            <li><a href="/search?q=SSC">🏛️ Staff Selection Commission (SSC)</a></li>
            <li><a href="/search?q=UPSC">🏛️ Union Public Service (UPSC)</a></li>
            <li><a href="/search?q=Railway">🚂 Railway Recruitment (RRB)</a></li>
            <li><a href="/search?q=IBPS">🏦 Banking & IBPS / SBI</a></li>
            <li><a href="/search?q=NTA">🎯 National Testing Agency (NTA)</a></li>
            <li><a href="/search?q=Defense">🎖️ Indian Army / IAF / Navy</a></li>
            <li><a href="/search?q=Police">👮 State Police & PSC Boards</a></li>
        </ul>
    </div>

    <!-- 5. Official Authenticity Notice -->
    <div class="sidebar-card" style="margin-top: 1.5rem;">
        <h3 class="sidebar-title">
            <span>🛡️</span> Official Notice Authenticity
        </h3>
        <p style="font-size: 0.8125rem; color: var(--color-text-muted, #64748b); line-height: 1.55; margin-bottom: 0;">
            All articles on EduGov News are strictly generated from verified government notifications. Candidates are always provided direct official links to official .gov.in and .ac.in portals.
        </p>
    </div>
</aside>
