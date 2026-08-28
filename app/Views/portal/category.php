<?php
/**
 * Category Archive View with Interactive Collapsible State Filter Box
 */
$currentStateObj = null;
if (!empty($selected_state) && !empty($available_states)) {
    foreach ($available_states as $st) {
        if ($st['state_code'] === $selected_state) {
            $currentStateObj = $st;
            break;
        }
    }
}
?>
<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="separator">/</span>
    <span><?= htmlspecialchars($category['name']) ?></span>
</nav>

<!-- Category Header Box -->
<header class="category-header-banner">
    <div class="cat-header-top">
        <h1 class="cat-header-title">
            <span class="cat-title-icon"><?= htmlspecialchars($category['icon'] ?? '📰') ?></span>
            <span><?= htmlspecialchars($category['name']) ?></span>
        </h1>
        <span class="cat-header-count">
            <?= (int)$total_items ?> Updates
        </span>
    </div>
    <?php if (!empty($category['description'])): ?>
    <p class="cat-header-desc">
        <?= htmlspecialchars($category['description']) ?>
    </p>
    <?php endif; ?>

    <!-- Interactive Collapsible State Filter Selector Box -->
    <?php if (!empty($available_states)): ?>
    <div class="state-selector-wrapper" id="stateSelectorWrapper">
        <div class="state-selector-bar">
            <!-- Toggle Button to Open/Close State Choice Box -->
            <button type="button" class="btn-state-dropdown-trigger" id="stateDropdownToggle" aria-expanded="false">
                <span class="trigger-icon">🗺️</span>
                <span class="trigger-text"><?= $currentStateObj ? 'Change State / Region' : 'Select State / Region' ?></span>
                <span class="trigger-caret" id="stateTriggerCaret">▾</span>
            </button>

            <!-- Selected State Active Badge & Clear Option -->
            <?php if ($currentStateObj): ?>
            <div class="selected-state-active-badge">
                <span class="badge-prefix">Active Filter:</span>
                <span class="badge-state-name">📍 <?= htmlspecialchars($currentStateObj['state_name']) ?> <span class="badge-count">(<?= (int)$currentStateObj['count'] ?>)</span></span>
                <a href="/category/<?= htmlspecialchars($category['slug']) ?>" class="btn-clear-state-filter" title="Clear State Filter">
                    ✕ Clear
                </a>
            </div>
            <?php else: ?>
            <div class="selected-state-default-badge">
                <span>Showing: All Regions (<?= (int)$total_items ?> Updates)</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Collapsible State Option Grid (Initially collapsed) -->
        <div class="state-options-collapse" id="stateOptionsCollapse" style="display: none;">
            <div class="state-options-panel">
                <div class="state-options-header">
                    <span class="options-title">📌 Select State / Region:</span>
                    <button type="button" class="btn-close-state-panel" id="closeStatePanelBtn" aria-label="Close state selection">✕</button>
                </div>
                <div class="state-options-grid">
                    <a href="/category/<?= htmlspecialchars($category['slug']) ?>" 
                       class="state-option-item <?= empty($selected_state) ? 'active' : '' ?>">
                        <span class="opt-name">🏛️ All Regions</span>
                        <span class="opt-count"><?= (int)$total_items ?></span>
                    </a>
                    <?php foreach ($available_states as $st): ?>
                    <a href="/category/<?= htmlspecialchars($category['slug']) ?>?state=<?= urlencode($st['state_code']) ?>" 
                       class="state-option-item <?= ($selected_state === $st['state_code']) ? 'active' : '' ?>">
                        <span class="opt-name">📍 <?= htmlspecialchars($st['state_name']) ?></span>
                        <span class="opt-count"><?= (int)$st['count'] ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>

<!-- Main Feed & Sidebar Grid -->
<div class="feed-layout-grid">
    <!-- Main Articles Column -->
    <main class="feed-main-col">
        <?php if (!empty($articles)): ?>
            <div class="articles-stack">
                <?php foreach ($articles as $art): ?>
                <article class="article-card">
                    <div class="card-meta">
                        <span class="cat-badge"><?= htmlspecialchars($art['category_name']) ?></span>
                        <?php if (!empty($art['official_source_name'])): ?>
                        <span class="official-verified-badge">
                            🏛️ <?= htmlspecialchars($art['official_source_name']) ?>
                        </span>
                        <?php endif; ?>
                        <time datetime="<?= $art['published_at'] ?>" class="card-time">
                            <?= date('M j, Y — g:i A', strtotime($art['published_at'])) ?>
                        </time>
                    </div>

                    <h2 class="card-title">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>">
                            <?= htmlspecialchars($art['title']) ?>
                        </a>
                    </h2>

                    <p class="card-excerpt">
                        <?= htmlspecialchars($art['excerpt'] ?? mb_substr(strip_tags($art['content_html']), 0, 160) . '...') ?>
                    </p>

                    <div class="card-footer">
                        <a href="/news/<?= htmlspecialchars($art['slug']) ?>" class="read-more-btn">
                            Read Full Notice & Direct Links »
                        </a>
                        <span class="badge-verified-small">✓ Verified</span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination-container">
                <?php 
                $stateParam = !empty($selected_state) ? '&state=' . urlencode($selected_state) : '';
                for ($i = 1; $i <= $total_pages; $i++): 
                ?>
                <a href="?page=<?= $i ?><?= $stateParam ?>" class="pagination-item <?= $i === $current_page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state-box">
                <div class="empty-icon">📭</div>
                <h3>No notices found for <?= $currentStateObj ? htmlspecialchars($currentStateObj['state_name']) : 'this selection' ?></h3>
                <p>New recruitment and exam notifications are synchronized daily.</p>
                <a href="/category/<?= htmlspecialchars($category['slug']) ?>" class="admin-btn admin-btn-primary" style="margin-top: 1rem; display: inline-block;">View All Regions</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Standard Reusable Right Sidebar -->
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('stateDropdownToggle');
    const collapsePanel = document.getElementById('stateOptionsCollapse');
    const closeBtn = document.getElementById('closeStatePanelBtn');
    const caret = document.getElementById('stateTriggerCaret');

    if (toggleBtn && collapsePanel) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = collapsePanel.style.display === 'none' || collapsePanel.style.display === '';
            if (isHidden) {
                collapsePanel.style.display = 'block';
                toggleBtn.setAttribute('aria-expanded', 'true');
                if (caret) caret.innerText = '▴';
            } else {
                collapsePanel.style.display = 'none';
                toggleBtn.setAttribute('aria-expanded', 'false');
                if (caret) caret.innerText = '▾';
            }
        });
    }

    if (closeBtn && collapsePanel) {
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            collapsePanel.style.display = 'none';
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
            if (caret) caret.innerText = '▾';
        });
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (collapsePanel && !collapsePanel.contains(e.target) && e.target !== toggleBtn) {
            collapsePanel.style.display = 'none';
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
            if (caret) caret.innerText = '▾';
        }
    });
});
</script>
