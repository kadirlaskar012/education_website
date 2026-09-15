<!-- Interactive Live Smart Filter Hub (Instant Client-Side & Server Filter) -->
<section class="smart-filter-hub" id="smartFilterHub" aria-label="Interactive Notification Filters">
    <div class="filter-hub-header">
        <div class="filter-hub-title-wrap">
            <span class="filter-hub-icon">⚡</span>
            <h2 class="filter-hub-heading">Instant Smart Notice Filter</h2>
        </div>
        <div class="filter-status-wrap">
            <span class="filter-match-count" id="filterMatchCount">Showing All Updates</span>
            <button class="btn-filter-reset" id="btnFilterReset" type="button" style="display: none;">
                ✕ Clear Filter
            </button>
        </div>
    </div>

    <!-- Multi-Axis Filter Matrix -->
    <div class="filter-matrix-wrap">
        <!-- 1. Qualification Level -->
        <div class="filter-row">
            <span class="filter-row-label">🎓 Qualification:</span>
            <div class="filter-pills-track">
                <button class="smart-filter-pill active" type="button" data-group="qual" data-val="all">All</button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="10th">10th / Matric</button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="12th">12th / HS</button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="graduate">Graduate / Degree</button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="diploma">Diploma / ITI</button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="pg">Post Graduate</button>
            </div>
        </div>

        <!-- 2. Employment Sector / Board -->
        <div class="filter-row">
            <span class="filter-row-label">🏛️ Department:</span>
            <div class="filter-pills-track">
                <button class="smart-filter-pill active" type="button" data-group="sector" data-val="all">All</button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="railway">🚆 Railway (RRB)</button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="ssc">🏛️ SSC & Central</button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="police">👮 Police & Defense</button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="banking">🏦 Banking & IBPS</button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="wbpsc">🌊 West Bengal</button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="upsc">⚖️ UPSC & Civil</button>
            </div>
        </div>

        <!-- 3. Notice Category / Status -->
        <div class="filter-row">
            <span class="filter-row-label">📋 Notice Type:</span>
            <div class="filter-pills-track">
                <button class="smart-filter-pill active" type="button" data-group="type" data-val="all">All</button>
                <button class="smart-filter-pill" type="button" data-group="type" data-val="recruitment">💼 New Vacancies</button>
                <button class="smart-filter-pill" type="button" data-group="type" data-val="admit_card">🎟️ Admit Cards</button>
                <button class="smart-filter-pill" type="button" data-group="type" data-val="result">🏆 Results & Merit</button>
                <button class="smart-filter-pill" type="button" data-group="type" data-val="answer_key">📝 Answer Keys</button>
            </div>
        </div>
    </div>
</section>
