<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Automation, AI & SEO Settings</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Configure Gemini API, Auto-publishing rules, and Quality thresholds</p>
    </div>
    <div>
        <a href="/admin" class="admin-btn admin-btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php if (!empty($message)): ?>
<div style="background-color: #f0fdf4; color: #166534; padding: 0.75rem 1rem; border-radius: 4px; font-size: 0.8125rem; margin-bottom: 1.5rem; border: 1px solid #bbf7d0;">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<?php if (!empty($error)): ?>
<div style="background-color: #fef2f2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 4px; font-size: 0.8125rem; margin-bottom: 1.5rem; border: 1px solid #fecaca;">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<!-- 1. Administrator Account & Password Management -->
<div class="admin-card" style="margin-bottom: 1.5rem; border-left: 4px solid #10b981;">
    <h3 style="font-size: 1rem; color: #0a192f; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
        🔐 Update Administrator Password
    </h3>
    <p style="font-size: 0.8125rem; color: #64748b; margin-bottom: 1.25rem;">
        Change your login password. Passwords are automatically encrypted using secure Bcrypt hashing.
    </p>

    <form action="/admin/settings" method="post">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
        <input type="hidden" name="action_type" value="update_password">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
            <div class="form-group">
                <label for="current_password" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Current Password</label>
                <input type="password" id="current_password" name="current_password" required placeholder="••••••••" style="width: 100%; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
            </div>
            <div class="form-group">
                <label for="new_password" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">New Password (min 8 chars)</label>
                <input type="password" id="new_password" name="new_password" required minlength="8" placeholder="••••••••" style="width: 100%; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
            </div>
            <div class="form-group">
                <label for="confirm_password" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8" placeholder="••••••••" style="width: 100%; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
            </div>
        </div>

        <button type="submit" class="admin-btn admin-btn-primary" style="background-color: #059669; padding: 0.6rem 1.25rem; font-size: 0.8125rem;">
            🔑 Update Password Now
        </button>
    </form>
</div>

<div class="admin-card">
    <form action="/admin/settings" method="post">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
        <h3 style="font-size: 1rem; color: #0a192f; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;">
            🤖 AI Human-Tone Rewriting Engine (Google Gemini API)
        </h3>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                <input type="checkbox" name="ai_rewrite" value="1" <?= !empty($settings['ai_rewrite']) ? 'checked' : '' ?> style="width: auto;">
                Enable AI Human-Tone Article Rewriting
            </label>
            <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; margin-left: 1.5rem;">
                When enabled, extracts verified government facts and uses Gemini to produce human-tone, professional articles with zero hallucination.
            </p>
        </div>

        <div class="form-group" style="margin-bottom: 1.25rem;">
            <label for="gemini_api_key" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">
                Gemini API Key (Optional — fallback is enabled automatically)
            </label>
            <input type="password" id="gemini_api_key" name="gemini_api_key" value="<?= htmlspecialchars($settings['gemini_api_key'] ?? '') ?>" placeholder="AIzaSy..." style="width: 100%; max-width: 500px; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem; font-family: monospace;">
            <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                Get a free API key from Google AI Studio. If left blank, the system uses high-performance structured deterministic generation.
            </p>
        </div>

        <h3 style="font-size: 1rem; color: #0a192f; margin-top: 1.5rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;">
            🛡️ Quality Control & Auto-Publishing Gatekeeper
        </h3>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                <input type="checkbox" name="auto_publish" value="1" <?= !empty($settings['auto_publish']) ? 'checked' : '' ?> style="width: auto;">
                Auto-Publish Validated Articles (Recommended)
            </label>
            <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; margin-left: 1.5rem;">
                If enabled, articles that pass all 10 pre-publish quality checks are published automatically. If disabled, new articles are held in 'review'.
            </p>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="min_quality_score" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">
                Minimum Quality Score for Auto-Publish (0 - 100)
            </label>
            <input type="number" id="min_quality_score" name="min_quality_score" value="<?= (int)($settings['min_quality_score'] ?? 80) ?>" min="50" max="100" style="width: 120px; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
            <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                Articles scoring below this score are automatically flagged for editorial review.
            </p>
        </div>

        <h3 style="font-size: 1rem; color: #0a192f; margin-top: 1.5rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;">
            🌐 Portal Branding & Global Contact
        </h3>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="site_name" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Website Name</label>
            <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? 'EduGov News') ?>" style="width: 100%; max-width: 500px; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="site_tagline" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Tagline</label>
            <input type="text" id="site_tagline" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>" style="width: 100%; max-width: 500px; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="top_breaking_announcement" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Top Bar Announcement</label>
            <input type="text" id="top_breaking_announcement" name="top_breaking_announcement" value="<?= htmlspecialchars($settings['top_breaking_announcement'] ?? '') ?>" style="width: 100%; max-width: 500px; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
        </div>

        <button type="submit" class="admin-btn admin-btn-primary" style="padding: 0.65rem 1.5rem; font-size: 0.875rem;">
            💾 Save All Settings
        </button>
    </form>
</div>
