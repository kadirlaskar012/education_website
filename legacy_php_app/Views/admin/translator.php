<?php
/**
 * Admin AI Translation & Fact-Checker Studio
 */
?>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">🌐 AI Translation & Fact-Checker Studio</h1>
        <p class="admin-page-subtitle">Translate official education notices into verified, newspaper-grade Bengali & Hindi with real-time fact integrity audit.</p>
    </div>
    <div>
        <a href="/admin/articles" class="btn btn-secondary">← Back to Articles</a>
    </div>
</div>

<div class="grid grid-2" style="grid-template-columns: 1.1fr 0.9fr; gap: 24px; align-items: start;">
    <!-- Left Column: Input Sandbox -->
    <div class="card shadow-sm">
        <div class="card-header flex justify-between items-center">
            <h2 class="card-title text-base font-bold">📝 Input Official Notice (English)</h2>
            <div class="flex gap-2">
                <select id="quickSampleSelect" class="form-control" style="padding: 4px 10px; font-size: 13px;" onchange="loadSampleNotice(this.value)">
                    <option value="">⚡ Load Sample Notice...</option>
                    <option value="wbprb">WB Police Constable (4,500 Posts)</option>
                    <option value="rrb">RRB NTPC (11,558 Vacancies)</option>
                    <option value="ssc">SSC GD Constable 2026</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <form id="translatorForm" onsubmit="runTranslationTest(event)">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
                
                <div class="form-group mb-3">
                    <label class="form-label font-bold">Target Language</label>
                    <div class="flex gap-3 mt-1">
                        <label class="flex items-center gap-2 p-2 border rounded cursor-pointer" style="flex: 1; background: var(--bg-surface, #fff);">
                            <input type="radio" name="target_lang" value="bn" checked>
                            <span class="font-bold">🇧🇩 বাংলা (Bengali)</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 border rounded cursor-pointer" style="flex: 1; background: var(--bg-surface, #fff);">
                            <input type="radio" name="target_lang" value="hi">
                            <span class="font-bold">🇮🇳 हिंदी (Hindi)</span>
                        </label>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label font-bold">Official English Notice / Text</label>
                    <textarea id="noticeTextInput" name="notice_text" rows="8" class="form-control" style="width: 100%; font-family: monospace; font-size: 13px;" placeholder="Paste government notification text, exam announcement, eligibility details, dates, or official website links here..." required></textarea>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs text-muted" id="charCounter">0 characters</span>
                    <button type="submit" id="translateBtn" class="btn btn-primary font-bold flex items-center gap-2">
                        <span>✨ Generate Translation & Fact Audit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Live Audit & Output -->
    <div class="card shadow-sm">
        <div class="card-header flex justify-between items-center">
            <h2 class="card-title text-base font-bold">🔍 Fact Audit & Verified Output</h2>
            <span id="statusBadge" class="badge badge-neutral">Ready</span>
        </div>
        <div class="card-body">
            <!-- Loading Indicator -->
            <div id="loadingIndicator" style="display: none; text-align: center; padding: 40px 20px;">
                <div class="spinner" style="margin: 0 auto 16px; width: 40px; height: 40px; border: 4px solid rgba(0,0,0,0.1); border-top-color: #0284c7; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                <h3 class="font-bold text-base">Translating & Verifying Fact Integrity...</h3>
                <p class="text-sm text-muted">Auditing dates, numbers, vacancies, and journalistic tone.</p>
            </div>

            <!-- Empty State -->
            <div id="emptyState" style="text-align: center; padding: 60px 20px; color: var(--text-muted, #64748b);">
                <div style="font-size: 48px; margin-bottom: 12px;">🌐</div>
                <h3 class="font-bold text-base">No Translation Generated Yet</h3>
                <p class="text-sm">Select a sample notice on the left or paste your own notice, then click Generate.</p>
            </div>

            <!-- Result Box -->
            <div id="resultBox" style="display: none;">
                <!-- Fact Integrity Badges -->
                <div class="p-3 mb-4 rounded border" style="background: #f8fafc;">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-muted">Fact Integrity Verification</span>
                            <span id="engineBadge" class="badge badge-neutral text-xs ml-2" style="font-size: 11px;">Indic Engine</span>
                        </div>
                        <span id="auditScoreBadge" class="badge badge-success font-bold text-sm">100% Match</span>
                    </div>
                    <div class="grid grid-3 gap-2 text-xs" style="grid-template-columns: repeat(3, 1fr);">
                        <div class="p-2 bg-white rounded border text-center">
                            <span class="block text-muted">📅 Dates Retained</span>
                            <strong id="auditDates" class="text-sm text-success">100%</strong>
                        </div>
                        <div class="p-2 bg-white rounded border text-center">
                            <span class="block text-muted">🔢 Vacancies / Fees</span>
                            <strong id="auditNums" class="text-sm text-success">100%</strong>
                        </div>
                        <div class="p-2 bg-white rounded border text-center">
                            <span class="block text-muted">🔗 Official URLs</span>
                            <strong id="auditUrls" class="text-sm text-success">100%</strong>
                        </div>
                    </div>
                </div>

                <!-- Translated Title -->
                <div class="mb-3">
                    <label class="text-xs font-bold uppercase text-muted block mb-1">Translated Headline</label>
                    <div id="translatedTitle" class="p-3 bg-white border rounded font-bold text-base" style="color: #0f172a; line-height: 1.4;"></div>
                </div>

                <!-- Translated Summary -->
                <div class="mb-3">
                    <label class="text-xs font-bold uppercase text-muted block mb-1">Summary (Short Excerpt)</label>
                    <div id="translatedSummary" class="p-3 bg-white border rounded text-sm text-muted" style="line-height: 1.5;"></div>
                </div>

                <!-- Translated Content Preview -->
                <div class="mb-4">
                    <label class="text-xs font-bold uppercase text-muted block mb-1">Full Verified Article Content</label>
                    <div id="translatedContent" class="p-3 bg-white border rounded text-sm" style="max-height: 260px; overflow-y: auto; white-space: pre-wrap; line-height: 1.6;"></div>
                </div>

                <!-- Action Toolbar -->
                <div class="flex gap-2">
                    <button type="button" class="btn btn-secondary text-xs flex-1" onclick="copyResultText()">📋 Copy All Text</button>
                    <button type="button" class="btn btn-primary text-xs flex-1" onclick="useAsArticle()">🚀 Create Article with This</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
const samples = {
    wbprb: "WBPRB Constable Recruitment 2026: West Bengal Police Recruitment Board has released the official notification for 4,500 Constable vacancies. Online registration starts on 20 March 2026 and closes on 18 April 2026. Eligibility: Candidates must be Madhyamik (10th) pass. Age: 18 to 30 years as on 1 January 2026. Application Fee: Rs 170 for General/OBC, Rs 20 for SC/ST. Official website: prb.wb.gov.in.",
    rrb: "Railway Recruitment Board (RRB) Centralized Employment Notice (CEN 01/2026): Applications invited for 11,558 Non-Technical Popular Categories (NTPC) Graduate & Undergraduate posts. Online application portal opens on 15 April 2026. CBT-1 tentative exam date: July 2026. Official website: rrbcdg.gov.in.",
    ssc: "Staff Selection Commission (SSC) GD Constable Notification 2026 Out. Total Vacancies: 39,481 Posts in BSF, CISF, CRPF, SSB, ITBP, AR, SSF. Educational Qualification: 10th Class Pass. Online Application window: 5 September to 14 October 2026. Official website: ssc.gov.in."
};

function loadSampleNotice(key) {
    if (samples[key]) {
        document.getElementById('noticeTextInput').value = samples[key];
        updateCharCounter();
    }
}

function updateCharCounter() {
    const len = document.getElementById('noticeTextInput').value.length;
    document.getElementById('charCounter').innerText = len + ' characters';
}

document.getElementById('noticeTextInput').addEventListener('input', updateCharCounter);

async function runTranslationTest(e) {
    e.preventDefault();
    const form = document.getElementById('translatorForm');
    const formData = new FormData(form);

    const loadingIndicator = document.getElementById('loadingIndicator');
    const emptyState = document.getElementById('emptyState');
    const resultBox = document.getElementById('resultBox');
    const statusBadge = document.getElementById('statusBadge');
    const translateBtn = document.getElementById('translateBtn');

    emptyState.style.display = 'none';
    resultBox.style.display = 'none';
    loadingIndicator.style.display = 'block';
    statusBadge.className = 'badge badge-warning';
    statusBadge.innerText = 'Translating...';
    translateBtn.disabled = true;

    try {
        const response = await fetch('/admin/translator/test', {
            method: 'POST',
            body: formData
        });
        
        let json = null;
        try {
            json = await response.json();
        } catch (parseErr) {
            throw new Error('Invalid server response format.');
        }

        loadingIndicator.style.display = 'none';
        translateBtn.disabled = false;

        if (json && json.success && json.data) {
            const data = json.data;
            const audit = data.audit || {};

            document.getElementById('translatedTitle').innerText = data.title || '';
            document.getElementById('translatedSummary').innerText = data.summary || '';
            document.getElementById('translatedContent').innerText = data.content || '';

            const score = audit.score !== undefined ? audit.score : 100;
            const scoreBadge = document.getElementById('auditScoreBadge');
            scoreBadge.innerText = score + '% Match';
            scoreBadge.className = score >= 80 ? 'badge badge-success font-bold text-sm' : 'badge badge-warning font-bold text-sm';

            const engineBadge = document.getElementById('engineBadge');
            if (engineBadge) {
                engineBadge.innerText = data.engine || 'Indic Engine';
                engineBadge.className = data.engine && data.engine.includes('Gemini') ? 'badge badge-primary text-xs ml-2' : 'badge badge-neutral text-xs ml-2';
            }

            document.getElementById('auditDates').innerText = (audit.dates_passed !== undefined ? audit.dates_passed : 0) + '/' + (audit.dates_checked !== undefined ? audit.dates_checked : 0) + ' Passed';
            document.getElementById('auditNums').innerText = (audit.numbers_passed !== undefined ? audit.numbers_passed : 0) + '/' + (audit.numbers_checked !== undefined ? audit.numbers_checked : 0) + ' Passed';
            document.getElementById('auditUrls').innerText = (audit.urls_passed !== undefined ? audit.urls_passed : 0) + '/' + (audit.urls_checked !== undefined ? audit.urls_checked : 0) + ' Passed';

            statusBadge.className = 'badge badge-success';
            statusBadge.innerText = '✓ Verified & Ready';
            resultBox.style.display = 'block';
        } else {
            statusBadge.className = 'badge badge-danger';
            statusBadge.innerText = 'Failed';
            emptyState.style.display = 'block';
            alert('Notice: ' + (json.message || 'Translation could not be completed.'));
        }
    } catch (err) {
        loadingIndicator.style.display = 'none';
        translateBtn.disabled = false;
        emptyState.style.display = 'block';
        statusBadge.className = 'badge badge-danger';
        statusBadge.innerText = 'Error';
        alert('Notice: ' + (err.message || 'Server connection error. Please refresh and try again.'));
    }
}

function copyResultText() {
    const title = document.getElementById('translatedTitle').innerText;
    const content = document.getElementById('translatedContent').innerText;
    navigator.clipboard.writeText(title + "\n\n" + content).then(() => {
        alert('✓ Translated headline & content copied to clipboard!');
    });
}

function useAsArticle() {
    const title = encodeURIComponent(document.getElementById('translatedTitle').innerText);
    window.location.href = '/admin/articles?new=1&title=' + title;
}
</script>
