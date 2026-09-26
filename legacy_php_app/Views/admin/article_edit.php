<?php
$structured = json_decode($article['structured_data'] ?? '{}', true) ?: [];
$translations = $structured['translations'] ?? [];
$score = (int)($article['quality_score'] ?? 100);
$wordCount = str_word_count(strip_tags($article['content_html'] ?? ''));
$bnTitle = $translations['bn']['title'] ?? '';
$bnSummary = $translations['bn']['summary'] ?? '';
$bnContent = $translations['bn']['content'] ?? '';

$hiTitle = $translations['hi']['title'] ?? '';
$hiSummary = $translations['hi']['summary'] ?? '';
$hiContent = $translations['hi']['content'] ?? '';
?>
<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Edit Article & Multilingual Studio</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Review AI generated content, fact verification score, and all 3 language editions (EN, BN, HI)</p>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <button type="button" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; font-weight: bold;" onclick="expandArticleWithAi(<?= $article['id'] ?>)" id="btnAiExpand">
            ⚡ AI 1500+ Word Deep Expansion
        </button>
        <a href="/news/<?= htmlspecialchars($article['slug']) ?>" target="_blank" class="admin-btn admin-btn-secondary">👁️ View Live (EN)</a>
        <a href="/bn/news/<?= htmlspecialchars($article['slug']) ?>" target="_blank" class="admin-btn admin-btn-secondary">🇧🇩 View (BN)</a>
        <a href="/hi/news/<?= htmlspecialchars($article['slug']) ?>" target="_blank" class="admin-btn admin-btn-secondary">🇮🇳 View (HI)</a>
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
        
        <!-- Status Row -->
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label for="status" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Publication Status</label>
                <select id="status" name="status" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
                    <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>✅ Published (Live in All 3 Languages)</option>
                    <option value="review" <?= $article['status'] === 'review' ? 'selected' : '' ?>>⚠️ In Review (Pending Verification)</option>
                    <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>📝 Draft (Offline)</option>
                    <option value="updated" <?= $article['status'] === 'updated' ? 'selected' : '' ?>>⚡ Updated Notification</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Official Authority & Source</label>
                <input type="text" disabled value="<?= htmlspecialchars($article['official_source_name'] ?? 'Government Authority') ?>" style="width: 100%; padding: 0.65rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; color: #64748b;">
            </div>
        </div>

        <!-- Language Edition Tabs Navigation -->
        <div style="border-bottom: 2px solid #e2e8f0; margin-bottom: 1.25rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button type="button" class="lang-tab-btn active" id="tabBtnEn" onclick="switchLangTab('en')" style="padding: 0.6rem 1.25rem; font-weight: 700; font-size: 0.875rem; border: none; background: #0284c7; color: #fff; border-radius: 6px 6px 0 0; cursor: pointer;">
                🇬🇧 English Edition (Default)
            </button>
            <button type="button" class="lang-tab-btn" id="tabBtnBn" onclick="switchLangTab('bn')" style="padding: 0.6rem 1.25rem; font-weight: 700; font-size: 0.875rem; border: none; background: #e2e8f0; color: #334155; border-radius: 6px 6px 0 0; cursor: pointer;">
                🇧🇩 বাংলা Edition (Bengali)
            </button>
            <button type="button" class="lang-tab-btn" id="tabBtnHi" onclick="switchLangTab('hi')" style="padding: 0.6rem 1.25rem; font-weight: 700; font-size: 0.875rem; border: none; background: #e2e8f0; color: #334155; border-radius: 6px 6px 0 0; cursor: pointer;">
                🇮🇳 हिंदी Edition (Hindi)
            </button>
        </div>

        <!-- 1. English Tab Pane -->
        <div id="tabPaneEn" class="lang-pane" style="display: block;">
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="title" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Article Headline (English)</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']) ?>" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="summary" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">English Summary & SEO Meta Description</label>
                <textarea id="summary" name="summary" rows="3" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"><?= htmlspecialchars($article['summary']) ?></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <label for="content_html" style="font-size: 0.8125rem; font-weight: 600;">English Content (HTML / 1500+ Words)</label>
                    <span style="font-size: 0.75rem; color: #64748b;">Structured 10-section format with tables & FAQs</span>
                </div>
                <textarea id="content_html" name="content_html" rows="16" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8125rem; font-family: monospace; line-height: 1.5;"><?= htmlspecialchars($article['content_html']) ?></textarea>
            </div>
        </div>

        <!-- 2. Bengali Tab Pane -->
        <div id="tabPaneBn" class="lang-pane" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding: 0.75rem 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px;">
                <div>
                    <strong style="color: #166534; font-size: 0.875rem;">🇧🇩 Bengali Edition Studio</strong>
                    <p style="margin: 0.2rem 0 0; font-size: 0.75rem; color: #15803d;">Shown on <code>/bn/news/<?= htmlspecialchars($article['slug']) ?></code></p>
                </div>
                <button type="button" class="admin-btn admin-btn-primary" style="background: #0284c7; padding: 0.4rem 0.8rem; font-size: 0.8rem;" onclick="generateIndicLanguage('bn')">
                    ⚡ Auto-Generate / Refresh Bengali with AI
                </button>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="bn_title" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Bengali Title (বাংলা শিরোনাম)</label>
                <input type="text" id="bn_title" name="bn_title" value="<?= htmlspecialchars($bnTitle) ?>" placeholder="বাংলায় শিরোনাম লিখুন..." style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="bn_summary" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Bengali Summary & Meta Description (বাংলা সারাংশ)</label>
                <textarea id="bn_summary" name="bn_summary" rows="3" placeholder="বাংলায় সারাংশ লিখুন..." style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"><?= htmlspecialchars($bnSummary) ?></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="bn_content" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Bengali Content (Markdown / HTML)</label>
                <textarea id="bn_content" name="bn_content" rows="14" placeholder="বাংলায় সম্পূর্ণ বিবরণ ও বিস্তারিত তথ্য..." style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8125rem; font-family: monospace; line-height: 1.5;"><?= htmlspecialchars($bnContent) ?></textarea>
            </div>
        </div>

        <!-- 3. Hindi Tab Pane -->
        <div id="tabPaneHi" class="lang-pane" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding: 0.75rem 1rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px;">
                <div>
                    <strong style="color: #92400e; font-size: 0.875rem;">🇮🇳 Hindi Edition Studio</strong>
                    <p style="margin: 0.2rem 0 0; font-size: 0.75rem; color: #b45309;">Shown on <code>/hi/news/<?= htmlspecialchars($article['slug']) ?></code></p>
                </div>
                <button type="button" class="admin-btn admin-btn-primary" style="background: #ea580c; padding: 0.4rem 0.8rem; font-size: 0.8rem;" onclick="generateIndicLanguage('hi')">
                    ⚡ Auto-Generate / Refresh Hindi with AI
                </button>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="hi_title" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Hindi Title (हिंदी शीर्षक)</label>
                <input type="text" id="hi_title" name="hi_title" value="<?= htmlspecialchars($hiTitle) ?>" placeholder="हिंदी में शीर्षक लिखें..." style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="hi_summary" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Hindi Summary & Meta Description (हिंदी सारांश)</label>
                <textarea id="hi_summary" name="hi_summary" rows="3" placeholder="हिंदी में सारांश लिखें..." style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"><?= htmlspecialchars($hiSummary) ?></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="hi_content" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Hindi Content (Markdown / HTML)</label>
                <textarea id="hi_content" name="hi_content" rows="14" placeholder="हिंदी में संपूर्ण विवरण और विस्तृत जानकारी..." style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8125rem; font-family: monospace; line-height: 1.5;"><?= htmlspecialchars($hiContent) ?></textarea>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 1.25rem;">
            <button type="submit" class="admin-btn admin-btn-primary" style="font-size: 1rem; font-weight: bold; padding: 0.75rem 2rem;">
                💾 Save All Changes (All 3 Languages)
            </button>
            <span style="font-size: 0.8125rem; color: #64748b;">All updates sync immediately across Google Sitemaps, Hreflang tags & Live URLs.</span>
        </div>
    </form>
</div>

<script>
function switchLangTab(lang) {
    document.querySelectorAll('.lang-pane').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.lang-tab-btn').forEach(btn => {
        btn.style.background = '#e2e8f0';
        btn.style.color = '#334155';
    });

    if (lang === 'en') {
        document.getElementById('tabPaneEn').style.display = 'block';
        document.getElementById('tabBtnEn').style.background = '#0284c7';
        document.getElementById('tabBtnEn').style.color = '#fff';
    } else if (lang === 'bn') {
        document.getElementById('tabPaneBn').style.display = 'block';
        document.getElementById('tabBtnBn').style.background = '#10b981';
        document.getElementById('tabBtnBn').style.color = '#fff';
    } else if (lang === 'hi') {
        document.getElementById('tabPaneHi').style.display = 'block';
        document.getElementById('tabBtnHi').style.background = '#ea580c';
        document.getElementById('tabBtnHi').style.color = '#fff';
    }
}

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
    btn.innerHTML = '⏳ Synthesizing 1500+ Words with Gemini AI...';

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
            if (json.bn_title) document.getElementById('bn_title').value = json.bn_title;
            if (json.bn_summary) document.getElementById('bn_summary').value = json.bn_summary;
            if (json.hi_title) document.getElementById('hi_title').value = json.hi_title;
            if (json.hi_summary) document.getElementById('hi_summary').value = json.hi_summary;
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

async function generateIndicLanguage(lang) {
    const englishTitle = document.getElementById('title').value;
    const englishContent = document.getElementById('content_html').value;
    const text = englishTitle + "\n\n" + englishContent;
    const btn = event.target;
    btn.disabled = true;
    const origText = btn.innerText;
    btn.innerText = 'Translating & Fact-Auditing...';

    try {
        const formData = new FormData();
        formData.append('csrf_token', '<?= \App\Core\Auth::csrfToken() ?>');
        formData.append('notice_text', text);
        formData.append('target_lang', lang);

        const res = await fetch('/admin/translator/test', {
            method: 'POST',
            body: formData
        });
        const json = await res.json();
        btn.disabled = false;
        btn.innerText = origText;

        if (json.success && json.data) {
            if (lang === 'bn') {
                document.getElementById('bn_title').value = json.data.title;
                document.getElementById('bn_summary').value = json.data.summary;
                document.getElementById('bn_content').value = json.data.content;
                alert('✓ Bengali edition generated! Quality Score: ' + json.data.quality_score + '%');
            } else if (lang === 'hi') {
                document.getElementById('hi_title').value = json.data.title;
                document.getElementById('hi_summary').value = json.data.summary;
                document.getElementById('hi_content').value = json.data.content;
                alert('✓ Hindi edition generated! Quality Score: ' + json.data.quality_score + '%');
            }
        } else {
            alert('Failed: ' + (json.message || 'Error'));
        }
    } catch (e) {
        btn.disabled = false;
        btn.innerText = origText;
        alert('Server connection error.');
    }
}
</script>
