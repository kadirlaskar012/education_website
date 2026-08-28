<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Articles & Notifications Management</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Filter, review, edit, and audit all scraped & generated education notices</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="/admin" class="admin-btn admin-btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<!-- Advanced Search & Filter Bar -->
<div class="admin-card" style="margin-bottom: 1.5rem; background-color: #f8fafc; border: 1px solid #e2e8f0;">
    <form action="/admin/articles" method="get" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label for="filter_q" style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">Search Title or Authority</label>
            <input type="text" id="filter_q" name="q" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="e.g. SSC CGL, RRB NTPC, UPSC..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem;">
        </div>

        <div style="width: 180px;">
            <label for="filter_cat" style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">Category</label>
            <select id="filter_cat" name="category_id" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($filters['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="width: 140px;">
            <label for="filter_status" style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">Status</label>
            <select id="filter_status" name="status" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem;">
                <option value="">All Statuses</option>
                <option value="published" <?= ($filters['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="updated" <?= ($filters['status'] ?? '') === 'updated' ? 'selected' : '' ?>>Updated</option>
                <option value="review" <?= ($filters['status'] ?? '') === 'review' ? 'selected' : '' ?>>In Review</option>
                <option value="error" <?= ($filters['status'] ?? '') === 'error' ? 'selected' : '' ?>>Error</option>
            </select>
        </div>

        <div style="width: 120px;">
            <label for="filter_score" style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">Min Score</label>
            <select id="filter_score" name="min_score" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem;">
                <option value="">Any Score</option>
                <option value="80" <?= ($filters['min_score'] ?? '') === '80' ? 'selected' : '' ?>>>= 80%</option>
                <option value="90" <?= ($filters['min_score'] ?? '') === '90' ? 'selected' : '' ?>>>= 90%</option>
                <option value="100" <?= ($filters['min_score'] ?? '') === '100' ? 'selected' : '' ?>>100% Only</option>
            </select>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="admin-btn admin-btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8125rem;">
                🔍 Filter
            </button>
            <a href="/admin/articles" class="admin-btn admin-btn-secondary" style="padding: 0.5rem 0.75rem; font-size: 0.8125rem;">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Articles Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
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
                    <tr>
                        <td style="max-width: 320px;">
                            <strong>
                                <a href="/news/<?= htmlspecialchars($art['slug']) ?>" target="_blank" style="color: #1e3a8a; text-decoration: none;">
                                    <?= htmlspecialchars($art['title']) ?>
                                </a>
                            </strong>
                            <div style="font-size: 0.6875rem; color: #64748b; margin-top: 0.2rem;">
                                Authority: <?= htmlspecialchars($art['official_source_name'] ?? 'Official') ?>
                            </div>
                        </td>
                        <td><span class="cat-badge"><?= htmlspecialchars($art['category_name']) ?></span></td>
                        <td><span style="font-size: 0.75rem; font-weight: 600; color: #475569;"><?= htmlspecialchars($art['state_code'] ?? 'ALL') ?></span></td>
                        <td>
                            <span class="status-badge status-<?= htmlspecialchars($art['status']) ?>">
                                <?= strtoupper(htmlspecialchars($art['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: <?= ($art['quality_score'] ?? 100) >= 80 ? '#16a34a' : '#d97706' ?>;">
                                <?= (int)($art['quality_score'] ?? 100) ?>%
                            </span>
                        </td>
                        <td>v<?= (int)($art['version_number'] ?? 1) ?></td>
                        <td style="font-size: 0.75rem; color: #64748b;"><?= date('M j, Y', strtotime($art['published_at'])) ?></td>
                        <td>
                            <a href="/admin/articles/edit/<?= $art['id'] ?>" class="admin-btn-sm">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #64748b; padding: 2rem;">
                            No articles match your filter criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
