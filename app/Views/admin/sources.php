<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Scraper Sources & Adapters</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Active government portals and examination board scrapers</p>
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
                    <th>Source Name</th>
                    <th>Authority</th>
                    <th>Adapter</th>
                    <th>Base URL</th>
                    <th>Last Fetched</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sources)): ?>
                    <?php foreach ($sources as $s): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
                        <td><?= htmlspecialchars($s['authority_name']) ?></td>
                        <td><code><?= htmlspecialchars($s['adapter_class']) ?></code></td>
                        <td><a href="<?= htmlspecialchars($s['base_url']) ?>" target="_blank"><?= htmlspecialchars($s['base_url']) ?> ↗</a></td>
                        <td><?= $s['last_fetched_at'] ? date('M j, Y — g:i A', strtotime($s['last_fetched_at'])) : 'Never' ?></td>
                        <td><span class="status-badge status-published">ACTIVE</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align: center; color: #64748b;">No sources configured.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
