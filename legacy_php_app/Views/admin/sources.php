<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Scraper Sources & Manual Fetch Hub</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Active government portals, examination boards & on-demand AI scraping engine</p>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <button id="resetAllBtn" class="admin-btn" style="background-color: #ef4444; color: #ffffff;" onclick="resetAllArticles()">
            🗑️ Reset to 0 (Clear All Articles)
        </button>
        <button id="fetchAllBtn" class="admin-btn admin-btn-primary" onclick="fetchAllSources()">
            🚀 Fetch All Sources Now
        </button>
        <a href="/admin" class="admin-btn admin-btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<!-- Status / Live Progress Alert Box -->
<div id="fetchStatusBox" class="admin-card" style="display: none; margin-bottom: 1.5rem; background-color: #f8fafc; border-left: 4px solid #2563eb;">
    <div id="fetchStatusText" style="font-size: 0.875rem; color: #1e293b; font-weight: 600;"></div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Source Portal</th>
                    <th>Authority</th>
                    <th>Adapter</th>
                    <th>Target URL</th>
                    <th>Last Fetched</th>
                    <th>Action (Manual Fetch)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sources)): ?>
                    <?php foreach ($sources as $s): ?>
                    <tr id="source-row-<?= $s['id'] ?>">
                        <td><span style="font-weight: 700; color: #64748b;">#<?= $s['id'] ?></span></td>
                        <td>
                            <strong><?= htmlspecialchars($s['name']) ?></strong>
                            <div style="font-size: 0.75rem; color: #64748b;"><?= htmlspecialchars($s['state_name'] ?? 'All India') ?></div>
                        </td>
                        <td><?= htmlspecialchars($s['authority_name']) ?></td>
                        <td><code><?= htmlspecialchars($s['adapter_class']) ?></code></td>
                        <td><a href="<?= htmlspecialchars($s['base_url']) ?>" target="_blank" rel="noopener noreferrer" style="color: #2563eb; text-decoration: underline;"><?= htmlspecialchars($s['base_url']) ?> ↗</a></td>
                        <td><span id="last-fetched-<?= $s['id'] ?>"><?= $s['last_fetched_at'] ? date('M j, Y — g:i A', strtotime($s['last_fetched_at'])) : 'Never' ?></span></td>
                        <td>
                            <button class="admin-btn admin-btn-primary admin-btn-sm js-fetch-single-btn" data-id="<?= $s['id'] ?>" data-name="<?= htmlspecialchars($s['name']) ?>" onclick="fetchSingleSource(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['name'])) ?>', this)">
                                ⚡ Fetch Now
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align: center; color: #64748b;">No sources configured.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const CSRF_TOKEN = "<?= \App\Core\Auth::csrfToken() ?>";

function showStatus(message, isError = false, isLoading = false) {
    const box = document.getElementById('fetchStatusBox');
    const txt = document.getElementById('fetchStatusText');
    box.style.display = 'block';
    box.style.borderLeftColor = isError ? '#ef4444' : (isLoading ? '#3b82f6' : '#10b981');
    box.style.backgroundColor = isError ? '#fef2f2' : (isLoading ? '#eff6ff' : '#f0fdf4');
    txt.innerHTML = message;
}

// 1. Fetch Single Specific Government Source in Background
async function fetchSingleSource(sourceId, sourceName, btnEl) {
    const origText = btnEl.innerHTML;
    btnEl.disabled = true;
    btnEl.innerHTML = '⏳ Scraping in Background...';
    showStatus(`⏳ Started background AI ingestion for <strong>${sourceName}</strong> (Website running at full speed)...`, false, true);

    try {
        const formData = new FormData();
        formData.append('csrf_token', CSRF_TOKEN);

        const res = await fetch(`/admin/pipeline/start-source/${sourceId}`, {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            // Poll for completion
            let pollCount = 0;
            const poller = setInterval(async () => {
                pollCount++;
                try {
                    const sRes = await fetch('/admin/pipeline/status');
                    const sData = await sRes.json();
                    if (sData.status === 'completed' || sData.status === 'idle') {
                        clearInterval(poller);
                        showStatus(`✅ <strong>${sourceName}</strong> fetched successfully in background! Generated: <strong>${sData.created_total || 0} articles</strong>. <a href="/admin/articles" style="text-decoration: underline; font-weight: bold; margin-left: 8px;">View Articles »</a>`);
                        document.getElementById(`last-fetched-${sourceId}`).innerText = 'Just now';
                        btnEl.disabled = false;
                        btnEl.innerHTML = origText;
                    } else if (sData.status === 'error') {
                        clearInterval(poller);
                        showStatus(`⚠️ ${sourceName}: ${sData.error || 'Error'}`, true);
                        btnEl.disabled = false;
                        btnEl.innerHTML = origText;
                    }
                } catch (e) {
                    if (pollCount > 40) clearInterval(poller);
                }
            }, 1500);
        } else {
            showStatus(`❌ Failed to start: ${data.message || 'Unknown error'}`, true);
            btnEl.disabled = false;
            btnEl.innerHTML = origText;
        }
    } catch (err) {
        showStatus(`❌ Network error while fetching ${sourceName}: ${err.message}`, true);
        btnEl.disabled = false;
        btnEl.innerHTML = origText;
    }
}

// 2. Fetch All Sources in Background (Non-blocking)
async function fetchAllSources() {
    const btn = document.getElementById('fetchAllBtn');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '⚡ Ingesting in Background...';
    showStatus('🚀 Spawning background worker across all government portals (Zero lag for website visitors)...', false, true);

    try {
        const formData = new FormData();
        formData.append('csrf_token', CSRF_TOKEN);

        const res = await fetch('/admin/pipeline/start-all', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            let lastPercent = 0;
            const poller = setInterval(async () => {
                try {
                    const sRes = await fetch('/admin/pipeline/status');
                    const sData = await sRes.json();
                    if (sData.status === 'running') {
                        showStatus(`⏳ Background Ingestion [${sData.percent || 0}%]: <strong>${sData.current_source || 'Processing...'}</strong>`, false, true);
                    } else if (sData.status === 'completed' || sData.status === 'idle') {
                        clearInterval(poller);
                        showStatus(`🎉 <strong>All Sources Completed!</strong> Created: <strong>${sData.created_total || 0} articles</strong>. <a href="/admin/articles" style="text-decoration: underline; font-weight: bold; margin-left: 8px;">View Articles »</a>`);
                        btn.disabled = false;
                        btn.innerHTML = origText;
                    }
                } catch (e) {}
            }, 1500);
        } else {
            showStatus(`❌ Pipeline failed: ${data.message || 'Unknown error'}`, true);
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    } catch (err) {
        showStatus(`❌ Network error: ${err.message}`, true);
        btn.disabled = false;
        btn.innerHTML = origText;
    }
}

// 3. Reset All Articles to 0 (Zero Database Reset)
async function resetAllArticles() {
    const confirmed = confirm("⚠️ Are you sure you want to RESET ALL ARTICLES TO 0?\n\nThis will clear all seeded articles and raw notices from the database so you can start 100% fresh.\n(Sources, categories, and settings will NOT be deleted).");
    if (!confirmed) return;

    const btn = document.getElementById('resetAllBtn');
    btn.disabled = true;
    btn.innerHTML = '⏳ Resetting to 0...';
    showStatus('⏳ Wiping articles database to 0...', false, true);

    try {
        const formData = new FormData();
        formData.append('csrf_token', CSRF_TOKEN);

        const res = await fetch('/admin/articles/reset-all', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showStatus(`🗑️ <strong>${data.message}</strong> Database is now completely clean (0 articles). You can now manually fetch individual sources above!`);
        } else {
            showStatus(`❌ Reset failed: ${data.message || 'Unknown error'}`, true);
        }
    } catch (err) {
        showStatus(`❌ Reset error: ${err.message}`, true);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '🗑️ Reset to 0 (Clear All Articles)';
    }
}
</script>
