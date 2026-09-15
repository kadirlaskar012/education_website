<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Automation & Ingestion Metrics</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Real-time automated scraper status, Gemini 3.6 Flash generator & article inventory</p>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="/admin/articles" class="admin-btn admin-btn-secondary">📰 Articles</a>
        <a href="/admin/translator" class="admin-btn admin-btn-secondary" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd;">🌐 AI Translator</a>
        <a href="/admin/sources" class="admin-btn admin-btn-secondary">⚙️ Manage Sources</a>
        <a href="/admin/settings" class="admin-btn admin-btn-secondary">⚙️ AI & Settings</a>
        <button id="runPipelineBtn" class="admin-btn admin-btn-primary" onclick="startSequentialPipeline()">
            ⚡ Fetch & Scrape All Sources
        </button>
    </div>
</div>

<!-- Interactive Live Scraper Terminal & Progress Box -->
<div id="pipelineProgressCard" class="admin-card" style="display: none; margin-bottom: 1.5rem; background-color: #0f172a; color: #f8fafc; border: 1px solid #1e293b;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <h3 style="color: #38bdf8; font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <span class="live-pulse-dot" style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span>
                Live AI Scraping Engine Active
            </h3>
            <div id="pipelineStatusSubtitle" style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.2rem;">
                Initializing sequential ingestion queue...
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <span id="pipelineProgressPercent" style="font-weight: 800; color: #38bdf8; font-size: 1.1rem;">0%</span>
            <button id="pipelineStopBtn" class="admin-btn" style="background: #ef4444; color: #fff; padding: 0.25rem 0.6rem; font-size: 0.75rem;" onclick="stopPipeline()">
                ✕ Stop Queue
            </button>
        </div>
    </div>

    <!-- Progress Bar -->
    <div style="width: 100%; height: 8px; background: #1e293b; border-radius: 4px; overflow: hidden; margin-bottom: 1rem;">
        <div id="pipelineProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #2563eb, #38bdf8, #22c55e); transition: width 0.3s ease;"></div>
    </div>

    <!-- Live Terminal Stream Logs -->
    <div id="pipelineTerminalLogs" style="background: #020617; border: 1px solid #1e293b; border-radius: 6px; padding: 0.75rem 1rem; font-family: monospace; font-size: 0.8rem; line-height: 1.6; max-height: 220px; overflow-y: auto; color: #cbd5e1;">
        <div style="color: #64748b;">[Ready] Click 'Fetch & Scrape All Sources' or fetch individual boards on Manage Sources page.</div>
    </div>
</div>

<!-- Metrics Cards Grid -->
<div class="admin-metrics-grid">
    <div class="metric-card border-blue">
        <div class="metric-label">Total Articles</div>
        <div class="metric-value" id="statTotal"><?= (int)($stats['total'] ?? 0) ?></div>
    </div>
    <div class="metric-card border-green">
        <div class="metric-label">Published</div>
        <div class="metric-value" id="statPublished"><?= (int)($stats['published'] ?? 0) ?></div>
    </div>
    <div class="metric-card border-amber">
        <div class="metric-label">Pending Review</div>
        <div class="metric-value"><a href="/admin/articles?status=review" style="color: #d97706; text-decoration: underline;"><?= (int)($stats['review'] ?? 0) ?></a></div>
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
        In your cPanel or server Cron Jobs tab, set the following single-line command to run automatically every 15 minutes:
    </p>
    <code style="display: block; background-color: #1e293b; color: #38bdf8; padding: 0.65rem 1rem; border-radius: 4px; font-size: 0.8125rem; word-break: break-all;">
        */15 * * * * php /home/yourusername/public_html/cron/run_all.php >/dev/null 2>&1
    </code>
</div>

<!-- Navigation Links & Recent Articles -->
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 0.5rem;">
        <h2 style="font-size: 1.125rem; color: #0a192f;">Recent Articles</h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="/admin/articles" class="admin-btn admin-btn-secondary">View All Articles</a>
            <a href="/admin/sources" class="admin-btn admin-btn-secondary">Manage Sources</a>
            <a href="/admin/settings" class="admin-btn admin-btn-secondary">Settings</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Headline</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th>Views</th>
                    <th>Published At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="recentArticlesTbody">
                <?php if (!empty($recent_articles)): ?>
                    <?php foreach ($recent_articles as $art): ?>
                    <tr>
                        <td>
                            <strong><a href="/news/<?= htmlspecialchars($art['slug']) ?>" target="_blank"><?= htmlspecialchars($art['title']) ?></a></strong>
                        </td>
                        <td><span class="cat-badge"><?= htmlspecialchars($art['category_name']) ?></span></td>
                        <td><span class="status-badge status-<?= htmlspecialchars($art['status']) ?>"><?= strtoupper(htmlspecialchars($art['status'])) ?></span></td>
                        <td><span style="font-weight: 700; color: <?= ($art['quality_score'] ?? 100) >= 80 ? '#16a34a' : '#d97706' ?>;"><?= (int)($art['quality_score'] ?? 100) ?>%</span></td>
                        <td><?= (int)$art['views_count'] ?></td>
                        <td><?= date('M j, Y — g:i A', strtotime($art['published_at'])) ?></td>
                        <td>
                            <a href="/admin/articles/edit/<?= $art['id'] ?>" class="admin-btn-sm">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align: center; color: #64748b;">No articles found. Use the Fetch button above to generate fresh articles.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Security & Audit Activity Trail Widget -->
<div class="admin-card" style="margin-top: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.125rem; color: #0a192f; display: flex; align-items: center; gap: 0.4rem;">
            🛡️ Security Audit & Administrator Activity Trail
        </h2>
        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Real-Time Protection Active</span>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Action</th>
                    <th>User</th>
                    <th>Details</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($audit_logs)): ?>
                    <?php foreach ($audit_logs as $log): ?>
                    <tr>
                        <td style="white-space: nowrap; font-size: 0.75rem; color: #64748b;">
                            <?= date('M j, Y — g:i:s A', strtotime($log['created_at'])) ?>
                        </td>
                        <td>
                            <span class="status-badge" style="background: #eff6ff; color: #1e40af;">
                                <?= htmlspecialchars($log['action']) ?>
                            </span>
                        </td>
                        <td><strong><?= htmlspecialchars($log['username'] ?? 'System') ?></strong></td>
                        <td style="font-size: 0.8rem;"><?= htmlspecialchars($log['details'] ?? '—') ?></td>
                        <td style="font-family: monospace; font-size: 0.75rem; color: #475569;">
                            <?= htmlspecialchars($log['ip_address'] ?? '127.0.0.1') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; color: #64748b;">No recent security events recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const CSRF_TOKEN = "<?= \App\Core\Auth::csrfToken() ?>";
let statusPoller = null;

function appendLog(msg, color = '#cbd5e1') {
    const term = document.getElementById('pipelineTerminalLogs');
    const line = document.createElement('div');
    line.style.color = color;
    line.innerHTML = `[${new Date().toLocaleTimeString()}] ${msg}`;
    term.appendChild(line);
    term.scrollTop = term.scrollHeight;
}

async function stopPipeline() {
    try {
        const formData = new FormData();
        formData.append('csrf_token', CSRF_TOKEN);
        await fetch('/admin/pipeline/stop', { method: 'POST', body: formData });
        appendLog('⚠️ Stop signal sent to background worker...', '#f59e0b');
    } catch (err) {
        console.error(err);
    }
}

async function startSequentialPipeline() {
    const runBtn = document.getElementById('runPipelineBtn');
    const card = document.getElementById('pipelineProgressCard');
    const subtitle = document.getElementById('pipelineStatusSubtitle');
    const pBar = document.getElementById('pipelineProgressBar');
    const pPercent = document.getElementById('pipelineProgressPercent');
    const term = document.getElementById('pipelineTerminalLogs');

    runBtn.disabled = true;
    runBtn.innerText = '⚡ Background AI Ingestion Active';
    card.style.display = 'block';
    term.innerHTML = '';
    appendLog('🚀 Spawning detached background AI worker (Zero website slowdown)...', '#38bdf8');

    try {
        const formData = new FormData();
        formData.append('csrf_token', CSRF_TOKEN);

        // Instant non-blocking trigger (<10ms)
        const res = await fetch('/admin/pipeline/start-all', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            appendLog('✓ Background worker launched successfully! Monitoring live progress...', '#22c55e');
            startStatusPolling();
        } else {
            appendLog('❌ Failed to launch worker: ' + data.message, '#ef4444');
            runBtn.disabled = false;
            runBtn.innerText = '⚡ Fetch & Scrape All Sources';
        }
    } catch (err) {
        appendLog('❌ Network error: ' + err.message, '#ef4444');
        runBtn.disabled = false;
        runBtn.innerText = '⚡ Fetch & Scrape All Sources';
    }
}

function startStatusPolling() {
    if (statusPoller) clearInterval(statusPoller);

    const card = document.getElementById('pipelineProgressCard');
    const subtitle = document.getElementById('pipelineStatusSubtitle');
    const pBar = document.getElementById('pipelineProgressBar');
    const pPercent = document.getElementById('pipelineProgressPercent');
    const runBtn = document.getElementById('runPipelineBtn');
    const term = document.getElementById('pipelineTerminalLogs');

    let lastLogCount = 0;

    statusPoller = setInterval(async () => {
        try {
            const res = await fetch('/admin/pipeline/status');
            const data = await res.json();

            if (data.status === 'running') {
                const pct = data.percent || 0;
                pBar.style.width = `${pct}%`;
                pPercent.innerText = `${pct}%`;
                subtitle.innerText = data.current_source || 'Ingesting sources...';

                if (Array.isArray(data.logs) && data.logs.length > lastLogCount) {
                    for (let i = lastLogCount; i < data.logs.length; i++) {
                        appendLog(data.logs[i], '#93c5fd');
                    }
                    lastLogCount = data.logs.length;
                }
            } else if (data.status === 'completed' || data.status === 'idle') {
                clearInterval(statusPoller);
                statusPoller = null;

                pBar.style.width = '100%';
                pPercent.innerText = '100%';
                subtitle.innerText = `✅ Ingestion completed! Total Articles: ${data.created_total || 0}.`;
                appendLog(`🎉 <strong>All Sources Completed!</strong> Created: ${data.created_total || 0} articles.`, '#38bdf8');

                runBtn.disabled = false;
                runBtn.innerText = '⚡ Fetch & Scrape All Sources';

                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        } catch (e) {
            console.warn('Status poll warning:', e);
        }
    }, 1500);
}

// Auto-check if background worker is currently running on page load
window.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('/admin/pipeline/status');
        const data = await res.json();
        if (data.status === 'running') {
            document.getElementById('pipelineProgressCard').style.display = 'block';
            document.getElementById('runPipelineBtn').disabled = true;
            document.getElementById('runPipelineBtn').innerText = '⚡ Background AI Ingestion Active';
            startStatusPolling();
        }
    } catch (e) {}
});
</script>
