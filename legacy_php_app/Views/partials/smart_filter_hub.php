<!-- Interactive Live Smart Filter Hub (Instant Client-Side & Server Filter) -->
<section class="smart-filter-hub" id="smartFilterHub" aria-label="Interactive Notification Filters">
    <div class="filter-hub-header">
        <div class="filter-hub-title-wrap">
            <span class="filter-hub-icon">⚡</span>
            <h2 class="filter-hub-heading"><?= htmlspecialchars(__('smart_filter_title')) ?></h2>
        </div>
        <div class="filter-status-wrap">
            <span class="filter-match-count" id="filterMatchCount"><?= htmlspecialchars(__('showing_all_updates')) ?></span>
            <button class="btn-filter-reset" id="btnFilterReset" type="button" style="display: none;">
                <?= htmlspecialchars(__('clear_filter')) ?>
            </button>
        </div>
    </div>

    <!-- Multi-Axis Filter Matrix -->
    <div class="filter-matrix-wrap">
        <!-- 1. Qualification Level -->
        <div class="filter-row">
            <span class="filter-row-label"><?= htmlspecialchars(__('qualification_label')) ?></span>
            <div class="filter-pills-track">
                <button class="smart-filter-pill active" type="button" data-group="qual" data-val="all"><?= htmlspecialchars(__('qual_all')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="10th"><?= htmlspecialchars(__('qual_10th')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="12th"><?= htmlspecialchars(__('qual_12th')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="graduate"><?= htmlspecialchars(__('qual_graduate')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="diploma"><?= htmlspecialchars(__('qual_diploma')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="qual" data-val="pg"><?= htmlspecialchars(__('qual_pg')) ?></button>
            </div>
        </div>

        <!-- 2. Employment Sector / Board -->
        <div class="filter-row">
            <span class="filter-row-label"><?= htmlspecialchars(__('department_label')) ?></span>
            <div class="filter-pills-track">
                <button class="smart-filter-pill active" type="button" data-group="sector" data-val="all"><?= htmlspecialchars(__('qual_all')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="railway"><?= htmlspecialchars(__('sec_railway')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="ssc"><?= htmlspecialchars(__('sec_ssc')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="police"><?= htmlspecialchars(__('sec_police')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="banking"><?= htmlspecialchars(__('sec_banking')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="wbpsc"><?= htmlspecialchars(__('sec_wbpsc')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="sector" data-val="upsc"><?= htmlspecialchars(__('sec_upsc')) ?></button>
            </div>
        </div>

        <!-- 3. Notice Category / Status -->
        <div class="filter-row">
            <span class="filter-row-label"><?= htmlspecialchars(__('notice_type_label')) ?></span>
            <div class="filter-pills-track">
                <button class="smart-filter-pill active" type="button" data-group="type" data-val="all"><?= htmlspecialchars(__('qual_all')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="type" data-val="recruitment"><?= htmlspecialchars(__('type_vacancies')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="type" data-val="admit_card"><?= htmlspecialchars(__('type_admits')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="type" data-val="result"><?= htmlspecialchars(__('type_results')) ?></button>
                <button class="smart-filter-pill" type="button" data-group="type" data-val="answer_key"><?= htmlspecialchars(__('type_answer_keys')) ?></button>
            </div>
        </div>
    </div>
</section>
