<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Articles & Notifications</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Manage and edit all published and draft articles</p>
    </div>
    <div>
        <a href="/admin" class="admin-btn admin-btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Headline</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Published At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $art): ?>
                    <tr>
                        <td>
                            <strong><a href="/news/<?= htmlspecialchars($art['slug']) ?>" target="_blank"><?= htmlspecialchars($art['title']) ?></a></strong>
                        </td>
                        <td><span class="cat-badge"><?= htmlspecialchars($art['category_name']) ?></span></td>
                        <td><span class="status-badge status-<?= htmlspecialchars($art['status']) ?>"><?= strtoupper(htmlspecialchars($art['status'])) ?></span></td>
                        <td><?= (int)$art['views_count'] ?></td>
                        <td><?= date('M j, Y — g:i A', strtotime($art['published_at'])) ?></td>
                        <td>
                            <a href="/admin/articles/edit/<?= $art['id'] ?>" class="admin-btn-sm">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align: center; color: #64748b;">No articles found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
