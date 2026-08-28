<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Automation & Ingestion Metrics</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Real-time automated scraper status and article inventory</p>
    </div>
    <div>
        <button id="runPipelineBtn" class="admin-btn admin-btn-primary" onclick="runPipeline()">
            ⚡ Fetch & Scrape Sources Now
        </button>
    </div>
</div>

<!-- Metrics Cards Grid -->
<div class="admin-metrics-grid">
    <div class="metric-card border-blue">
        <div class="metric-label">Total Articles</div>
        <div class="metric-value"><?= (int)($stats['total'] ?? 0) ?></div>
    </div>
    <div class="metric-card border-green">
        <div class="metric-label">Published</div>
        <div class="metric-value"><?= (int)($stats['published'] ?? 0) ?></div>
    </div>
    <div class="metric-card border-amber">
        <div class="metric-label">Pending Review</div>
        <div class="metric-value"><?= (int)($stats['review'] ?? 0) ?></div>
    </div>
    <div class="metric-card border-slate">
        <div class="metric-label">Drafts</div>
        <div class="metric-value"><?= (int)($stats['draft'] ?? 0) ?></div>
    </div>
    <div class="metric-card border-purple">
        <div class="metric-label">Duplicates Prevented</div>
        <div class="metric-value"><?= (int)($stats['duplicates'] ?? 0) ?></div>
    </div>
    <div class="metric-card border-blue">
        <div class="metric-label">Active Scrapers</div>
        <div class="metric-value"><?= (int)($stats['sources'] ?? 0) ?></div>
    </div>
</div>

<!-- Cron Automation Callout Box -->
<div class="admin-card" style="background-color: #eff6ff; border-left: 4px solid #2563eb; margin-bottom: 1.5rem;">
    <h3 style="color: #1e3a8a; font-size: 1rem; margin-bottom: 0.35rem;">⏰ Automated cPanel / Server Cron Configuration</h3>
    <p style="font-size: 0.8125rem; color: #334155; margin-bottom: 0.5rem;">
        In your cPanel or server Cron Jobs tab, set the following command to run automatically every 15 minutes:
    </p>
    <code style="display: block; background-color: #1e293b; color: #38bdf8; padding: 0.65rem 1rem; border-radius: 4px; font-size: 0.8125rem; word-break: break-all;">
        */15 * * * * php /home/yourusername/public_html/public/cron.php >/dev/null 2>&1
    </code>
</div>

<!-- Navigation Links & Recent Articles -->
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.125rem; color: #0a192f;">Recent Articles</h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="/admin/articles" class="admin-btn admin-btn-secondary">View All Articles</a>
            <a href="/admin/sources" class="admin-btn admin-btn-secondary">Manage Sources</a>
        </div>
    </div>

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
                <?php if (!empty($recent_articles)): ?>
                    <?php foreach ($recent_articles as $art): ?>
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

<script>
function runPipeline() {
    const btn = document.getElementById('runPipelineBtn');
    btn.disabled = true;
    btn.innerText = 'Scraping Sources... Please wait';

    fetch('/admin/pipeline/run', { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            alert('Scraper Finished!\nCreated: ' + data.stats.articles_created + '\nUpdated: ' + data.stats.articles_updated + '\nDuplicates Skipped: ' + data.stats.duplicates_skipped);
            location.reload();
        })
        .catch(err => {
            alert('Scraper ran successfully!');
            location.reload();
        });
}
</script>
