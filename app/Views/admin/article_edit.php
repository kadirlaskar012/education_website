<?php
$structured = json_decode($article['structured_data'] ?? '{}', true) ?: [];
$translations = $structured['translations'] ?? [];
$score = (int)($article['quality_score'] ?? 100);
$wordCount = str_word_count(strip_tags($article['content_html'] ?? ''));
?>
<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Edit Article & Multilingual Review</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Review AI generated content, fact verification score, and translations</p>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <button type="button" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; font-weight: bold;" onclick="expandArticleWithAi(<?= $article['id'] ?>)" id="btnAiExpand">
            ⚡ AI 1500+ Word Deep Expansion
        </button>
        <a href="/admin/translator" class="admin-btn admin-btn-secondary">🌐 Open Translation Studio</a>
        <a href="/admin/articles" class="admin-btn admin-btn-secondary">← Back to Articles</a>
    </div>
</div>

<?php if (!empty($message)): ?>
<div style="background-color: #f0fdf4; color: #166534; padding: 0.75rem; border-radius: 4px; font-size: 0.8125rem; margin-bottom: 1rem; border: 1px solid #bbf7d0;">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<!-- Fact Quality & Word Count Status Banner -->
<div class="admin-card" style="margin-bottom: 1rem; padding: 1rem; background: <?= $score >= 80 ? '#f0fdf4' : '#fffbeb' ?>; border-left: 4px solid <?= $score >= 80 ? '#22c55e' : '#f59e0b' ?>;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <strong style="font-size: 0.95rem; color: #0f172a;">
                <?= $score >= 80 ? '✓ Fact Verification Passed' : '⚠️ Pending Fact Verification Review' ?>
            </strong>
            <p style="margin: 0.25rem 0 0; font-size: 0.8125rem; color: #475569;">
                <?= htmlspecialchars($article['validation_notes'] ?: 'All extracted facts, dates, and official URLs cross-checked.') ?>
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <span id="liveWordCountBadge" style="font-weight: 700; font-size: 0.875rem; padding: 0.3rem 0.8rem; border-radius: 20px; background: #e0e7ff; color: #3730a3;">
                📖 <span id="wordCountNum"><?= $wordCount ?></span> Words
            </span>
            <span style="font-weight: 800; font-size: 0.875rem; padding: 0.3rem 0.8rem; border-radius: 20px; background: <?= $score >= 80 ? '#dcfce7; color: #15803d;' : '#fef3c7; color: #b45309;' ?>">
                Quality: <?= $score ?>%
            </span>
        </div>
    </div>
</div>

<div class="admin-card">
    <form action="/admin/articles/edit/<?= $article['id'] ?>" method="post">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
        
        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="title" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Article Title (English)</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']) ?>" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="status" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Publication Status</label>
            <select id="status" name="status" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
                <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>✅ Published (Live)</option>
                <option value="review" <?= $article['status'] === 'review' ? 'selected' : '' ?>>⚠️ Review (Pending Verification)</option>
                <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>📝 Draft (Offline)</option>
                <option value="updated" <?= $article['status'] === 'updated' ? 'selected' : '' ?>>⚡ Updated</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="summary" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">English Summary</label>
            <textarea id="summary" name="summary" rows="3" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;"><?= htmlspecialchars($article['summary']) ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                <label for="content_html" style="font-size: 0.8125rem; font-weight: 600;">English Content (HTML)</label>
                <span style="font-size: 0.75rem; color: #64748b;">Aim for 1200+ words for AdSense & SEO</span>
            </div>
            <textarea id="content_html" name="content_html" rows="14" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem; font-family: monospace;"><?= htmlspecialchars($article['content_html']) ?></textarea>
        </div>

        <!-- Bengali Translation Preview / Quick Tool -->
        <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <strong style="font-size: 0.875rem; color: #0284c7;">🇧🇩 Bengali Translation (বাংলা)</strong>
                <button type="button" class="admin-btn admin-btn-secondary" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;" onclick="generateBengaliFromArticle()">
                    ⚡ Auto-Generate Bengali with AI
                </button>
            </div>
            <div id="bnTitlePreview" style="font-size: 0.875rem; font-weight: bold; color: #0f172a; margin-bottom: 0.3rem;">
                <?= htmlspecialchars($translations['bn']['title'] ?? 'Not generated yet (will generate automatically on publish)') ?>
            </div>
            <div id="bnSummaryPreview" style="font-size: 0.8125rem; color: #64748b;">
                <?= htmlspecialchars($translations['bn']['summary'] ?? '') ?>
            </div>
        </div>

        <button type="submit" class="admin-btn admin-btn-primary" style="font-size: 0.95rem; font-weight: bold; padding: 0.7rem 1.5rem;">
            💾 Save & Apply Changes
        </button>
    </form>
</div>

<script>
function updateWordCount() {
    const text = document.getElementById('content_html').value.replace(/<[^>]*>/g, ' ');
    const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
    const countEl = document.getElementById('wordCountNum');
    if (countEl) countEl.innerText = words;
}

document.getElementById('content_html')?.addEventListener('input', updateWordCount);

async function expandArticleWithAi(id) {
    const btn = document.getElementById('btnAiExpand');
    btn.disabled = true;
    const origHtml = btn.innerHTML;
    btn.innerHTML = '⏳ Synthesizing 1500+ Words...';

    try {
        const formData = new FormData();
        formData.append('csrf_token', '<?= \App\Core\Auth::csrfToken() ?>');

        const res = await fetch('/admin/articles/ai-expand/' + id, {
            method: 'POST',
            body: formData
        });
        const json = await res.json();
        btn.disabled = false;
        btn.innerHTML = origHtml;

        if (json.success) {
            document.getElementById('title').value = json.title;
            document.getElementById('summary').value = json.summary;
            document.getElementById('content_html').value = json.content_html;
            if (json.bn_title) document.getElementById('bnTitlePreview').innerText = json.bn_title;
            if (json.bn_summary) document.getElementById('bnSummaryPreview').innerText = json.bn_summary;
            updateWordCount();
            alert(json.message);
        } else {
            alert('Expansion Error: ' + (json.message || 'Failed'));
        }
    } catch (e) {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        alert('Server connection error during expansion.');
    }
}

async function generateBengaliFromArticle() {
    const text = document.getElementById('title').value + "\n\n" + document.getElementById('content_html').value;
    const btn = event.target;
    btn.disabled = true;
    btn.innerText = 'Translating & Auditing...';

    try {
        const formData = new FormData();
        formData.append('csrf_token', '<?= \App\Core\Auth::csrfToken() ?>');
        formData.append('notice_text', text);
        formData.append('target_lang', 'bn');

        const res = await fetch('/admin/translator/test', {
            method: 'POST',
            body: formData
        });
        const json = await res.json();
        btn.disabled = false;
        btn.innerText = '⚡ Auto-Generate Bengali with AI';

        if (json.success && json.data) {
            document.getElementById('bnTitlePreview').innerText = json.data.title;
            document.getElementById('bnSummaryPreview').innerText = json.data.summary;
            alert('✓ Bengali translation generated and verified with Quality Score: ' + json.data.quality_score + '%');
        } else {
            alert('Failed: ' + (json.message || 'Error'));
        }
    } catch (e) {
        btn.disabled = false;
        btn.innerText = '⚡ Auto-Generate Bengali with AI';
        alert('Server connection error.');
    }
}
</script>
