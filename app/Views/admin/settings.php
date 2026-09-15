<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Automation, AI, SEO & Social Settings</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Configure Gemini AI, Auto-Publishing, Social Broadcasting (Facebook, Telegram, Twitter), and Analytics</p>
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

<!-- Main Settings Form -->
<div class="admin-card">
    <form action="/admin/settings" method="post">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">
        
        <!-- Social Media Auto-Publishing Section -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: gap: 1rem;">
                <h3 style="font-size: 1.05rem; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    🚀 Automated Social Media Broadcaster (Auto-Post)
                </h3>
                <button type="button" id="btnTestSocial" class="admin-btn admin-btn-secondary" style="font-size: 0.75rem; padding: 0.4rem 0.85rem; border-color: #3b82f6; color: #1d4ed8; background: #eff6ff;">
                    ⚡ Test Social Broadcast
                </button>
            </div>
            <p style="font-size: 0.8125rem; color: #64748b; margin-bottom: 1.25rem;">
                Whenever a new notice is published by AI or admin, automatically broadcast formatted updates with links and hashtags.
            </p>

            <!-- Telegram Channel Auto-Post -->
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 1rem; margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.875rem; color: #0284c7; cursor: pointer; margin-bottom: 0.5rem;">
                    <input type="checkbox" name="telegram_auto_post" value="1" <?= !empty($settings['telegram_auto_post']) ? 'checked' : '' ?> style="width: auto;">
                    ✈️ Enable Telegram Channel / Group Auto-Posting
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 0.75rem;">
                    <div>
                        <label for="telegram_bot_token" style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem;">Telegram Bot Token (from @BotFather)</label>
                        <input type="password" id="telegram_bot_token" name="telegram_bot_token" value="<?= htmlspecialchars($settings['telegram_bot_token'] ?? '') ?>" placeholder="123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ..." style="width: 100%; padding: 0.55rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem; font-family: monospace;">
                    </div>
                    <div>
                        <label for="telegram_channel_id" style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem;">Channel Username or Chat ID</label>
                        <input type="text" id="telegram_channel_id" name="telegram_channel_id" value="<?= htmlspecialchars($settings['telegram_channel_id'] ?? '') ?>" placeholder="@your_channel_name or -100xxxxxxxxxx" style="width: 100%; padding: 0.55rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem;">
                    </div>
                </div>
            </div>

            <!-- Facebook Page Auto-Post -->
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 1rem; margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.875rem; color: #1877f2; cursor: pointer; margin-bottom: 0.5rem;">
                    <input type="checkbox" name="facebook_auto_post" value="1" <?= !empty($settings['facebook_auto_post']) ? 'checked' : '' ?> style="width: auto;">
                    👥 Enable Facebook Page Auto-Posting
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 0.75rem;">
                    <div>
                        <label for="facebook_page_id" style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem;">Facebook Page ID</label>
                        <input type="text" id="facebook_page_id" name="facebook_page_id" value="<?= htmlspecialchars($settings['facebook_page_id'] ?? '') ?>" placeholder="1029384756..." style="width: 100%; padding: 0.55rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem;">
                    </div>
                    <div>
                        <label for="facebook_access_token" style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem;">Page Access Token (Meta Graph API)</label>
                        <input type="password" id="facebook_access_token" name="facebook_access_token" value="<?= htmlspecialchars($settings['facebook_access_token'] ?? '') ?>" placeholder="EAAGm0PX4ZCBA..." style="width: 100%; padding: 0.55rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem; font-family: monospace;">
                    </div>
                </div>
            </div>

            <!-- Twitter / Multi-Platform Webhook Auto-Post -->
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.875rem; color: #0f172a; cursor: pointer; margin-bottom: 0.5rem;">
                    <input type="checkbox" name="twitter_auto_post" value="1" <?= !empty($settings['twitter_auto_post']) ? 'checked' : '' ?> style="width: auto;">
                    🐦 Enable Twitter (X) / Multi-Platform Webhook (Make.com, Zapier, IFTTT)
                </label>
                <div style="margin-top: 0.75rem;">
                    <label for="twitter_webhook_url" style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem;">Webhook URL</label>
                    <input type="url" id="twitter_webhook_url" name="twitter_webhook_url" value="<?= htmlspecialchars($settings['twitter_webhook_url'] ?? '') ?>" placeholder="https://hook.eu1.make.com/... or https://hooks.zapier.com/..." style="width: 100%; padding: 0.55rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem; font-family: monospace;">
                    <p style="font-size: 0.7125rem; color: #64748b; margin-top: 0.25rem;">Sends JSON payload with formatted tweet text, headline, and direct URL to any webhook integration.</p>
                </div>
            </div>
        </div>

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
            🔍 SEO, Search Console & Google Analytics (GA4)
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="ga4_measurement_id" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Google Analytics 4 ID (GA4)</label>
                <input type="text" id="ga4_measurement_id" name="ga4_measurement_id" value="<?= htmlspecialchars($settings['ga4_measurement_id'] ?? '') ?>" placeholder="G-XXXXXXXXXX" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem; font-family: monospace;">
                <p style="font-size: 0.7125rem; color: #64748b; margin-top: 0.25rem;">Real-time visitor analytics tracking code.</p>
            </div>
            <div class="form-group">
                <label for="google_site_verification" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Google Search Console Verification Code</label>
                <input type="text" id="google_site_verification" name="google_site_verification" value="<?= htmlspecialchars($settings['google_site_verification'] ?? '') ?>" placeholder="e.g. kA8b_9z..." style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem; font-family: monospace;">
                <p style="font-size: 0.7125rem; color: #64748b; margin-top: 0.25rem;">HTML tag verification token for Search Console.</p>
            </div>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnTest = document.getElementById('btnTestSocial');
    if (btnTest) {
        btnTest.addEventListener('click', async () => {
            btnTest.disabled = true;
            btnTest.textContent = '⏳ Sending Test Broadcast...';
            try {
                const formData = new FormData();
                formData.append('csrf_token', '<?= \App\Core\Auth::csrfToken() ?>');

                const res = await fetch('/admin/social/test', {
                    method: 'POST',
                    body: formData,
                });
                const data = await res.json();
                
                if (data.success) {
                    alert('✓ ' + data.message + '\n\nResults:\n' + JSON.stringify(data.results, null, 2));
                } else {
                    alert('❌ ' + (data.message || 'Error occurred'));
                }
            } catch (err) {
                alert('⚠️ Network error while testing social broadcast.');
            } finally {
                btnTest.disabled = false;
                btnTest.textContent = '⚡ Test Social Broadcast';
            }
        });
    }
});
</script>
