<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0a192f; margin: 0;">Education Notices Management</h1>
        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem;">Filter, review, edit, select multiple posts, and audit all official & generated education notices</p>
    </div>
    <div>
        <a href="/admin" class="admin-btn admin-btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php if (!empty($message)): ?>
<div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #16a34a; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: 0.875rem; color: #166534; font-weight: 600;">
    ✓ <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<!-- Search & Filter Card -->
<div class="admin-card" style="margin-bottom: 1.5rem; background: #fff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0;">
    <form action="/admin/articles" method="get" style="display: grid; grid-template-columns: 2fr 1.2fr 1fr 1fr auto auto; gap: 0.75rem; align-items: end;">
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.35rem;">Search Title or Authority</label>
            <input type="text" name="q" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="e.g. SSC CGL, RRB NTPC, UPSC..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8125rem;">
        </div>

        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.35rem;">Category</label>
            <select name="category_id" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8125rem;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.35rem;">Status</label>
            <select name="status" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8125rem;">
                <option value="">All Statuses</option>
                <option value="published" <?= ($filters['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="in_review" <?= ($filters['status'] ?? '') === 'in_review' ? 'selected' : '' ?>>In Review</option>
                <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.35rem;">Min Score</label>
            <select name="min_score" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8125rem;">
                <option value="">Any Score</option>
                <option value="100" <?= ($filters['min_score'] ?? '') === '100' ? 'selected' : '' ?>>100% Only</option>
                <option value="90" <?= ($filters['min_score'] ?? '') === '90' ? 'selected' : '' ?>>90%+</option>
                <option value="80" <?= ($filters['min_score'] ?? '') === '80' ? 'selected' : '' ?>>80%+</option>
            </select>
        </div>

        <div>
            <button type="submit" class="admin-btn admin-btn-primary" style="padding: 0.5rem 1rem;">🔍 Filter</button>
        </div>

        <div>
            <a href="/admin/articles" class="admin-btn admin-btn-secondary" style="padding: 0.5rem 0.75rem;">Reset</a>
        </div>
    </form>
</div>

<!-- Bulk Action Form & Articles Table -->
<form action="/admin/articles/bulk" method="post" id="bulkActionForm">
    <!-- Sticky / Top Bulk Action Bar -->
    <div id="bulkActionBar" style="background: #0f172a; color: #fff; padding: 0.75rem 1.25rem; border-radius: 8px; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
            <span>☑️ Selected Articles:</span>
            <span id="selectedCountBadge" style="background: #2563eb; padding: 0.2rem 0.6rem; border-radius: 999px; font-weight: 700;">0</span>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <select name="bulk_action" id="bulkActionSelect" style="padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.8125rem; border: 1px solid #475569; background: #1e293b; color: #fff;">
                <option value="">-- Choose Bulk Action --</option>
                <option value="published">✅ Mark as Published</option>
                <option value="in_review">⚠️ Mark as In Review</option>
                <option value="draft">📝 Mark as Draft</option>
                <option value="delete">🗑️ Delete Selected</option>
            </select>
            <button type="submit" id="applyBulkBtn" class="admin-btn admin-btn-primary" style="padding: 0.4rem 0.9rem;" onclick="return confirmBulkAction();">Apply to Selected</button>
        </div>
    </div>

    <div class="admin-card" style="background: #fff; padding: 0; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">
                        <input type="checkbox" id="selectAllCheckbox" style="cursor: pointer; width: 16px; height: 16px;">
                    </th>
                    <th style="width: 35%;">Title</th>
                    <th>Category</th>
                    <th>State</th>
                    <th>Status</th>
                    <th>Quality</th>
                    <th>Ver</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $art): ?>
                    <tr class="article-row">
                        <td style="text-align: center;">
                            <input type="checkbox" name="article_ids[]" value="<?= (int)$art['id'] ?>" class="article-checkbox" style="cursor: pointer; width: 16px; height: 16px;">
                        </td>
                        <td>
                            <a href="/news/<?= htmlspecialchars($art['slug']) ?>" target="_blank" style="font-weight: 700; color: #1e3a8a; text-decoration: none;">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                            <div style="font-size: 0.72rem; color: #64748b; margin-top: 0.15rem;">
                                Authority: <?= htmlspecialchars($art['official_source_name'] ?? 'Government Portal') ?>
                            </div>
                        </td>
                        <td>
                            <span class="cat-badge" style="font-size: 0.7rem;"><?= htmlspecialchars($art['category_name']) ?></span>
                        </td>
                        <td style="font-size: 0.75rem; font-weight: 600; color: #475569;">
                            <?= htmlspecialchars($art['state_code'] ?? 'ALL') ?>
                        </td>
                        <td>
                            <span class="status-badge status-<?= htmlspecialchars($art['status']) ?>">
                                <?= strtoupper($art['status']) ?>
                            </span>
                        </td>
                        <td style="font-weight: 700; color: <?= $art['quality_score'] >= 90 ? '#16a34a' : '#d97706' ?>;">
                            <?= (int)$art['quality_score'] ?>%
                        </td>
                        <td style="color: #64748b; font-size: 0.75rem;">
                            v<?= (int)$art['version_number'] ?>
                        </td>
                        <td style="font-size: 0.75rem; color: #64748b;">
                            <?= date('M j, Y', strtotime($art['published_at'])) ?>
                        </td>
                        <td>
                            <a href="/admin/articles/edit/<?= (int)$art['id'] ?>" class="admin-btn admin-btn-sm">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 2rem; color: #64748b;">
                            No articles match your search filter. Try clearing filters.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.article-checkbox');
    const badge = document.getElementById('selectedCountBadge');
    const bulkActionSelect = document.getElementById('bulkActionSelect');

    function updateCount() {
        const checked = document.querySelectorAll('.article-checkbox:checked');
        const count = checked.length;
        badge.innerText = count;
        
        // Highlight selected rows
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            if (cb.checked) {
                row.style.backgroundColor = '#eff6ff';
            } else {
                row.style.backgroundColor = '';
            }
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => { cb.checked = selectAll.checked; });
            updateCount();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!cb.checked && selectAll) {
                selectAll.checked = false;
            }
            updateCount();
        });
    });

    window.confirmBulkAction = function() {
        const checked = document.querySelectorAll('.article-checkbox:checked');
        if (checked.length === 0) {
            alert('Please select at least one article by ticking the checkboxes.');
            return false;
        }
        const action = bulkActionSelect.value;
        if (!action) {
            alert('Please choose a bulk action (e.g. Published, Draft, or Delete).');
            return false;
        }
        if (action === 'delete') {
            return confirm(`⚠️ Are you sure you want to permanently delete ${checked.length} selected articles? This action cannot be undone.`);
        }
        return confirm(`Confirm applying "${action}" to ${checked.length} selected articles?`);
    };
});
</script>
