<div class="admin-login-card">
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Control Center Login</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Sign in with your administrator credentials</p>
    </div>

    <?php if (!empty($error)): ?>
    <div style="background-color: #fef2f2; color: #b91c1c; padding: 0.75rem; border-radius: 4px; font-size: 0.8125rem; margin-bottom: 1rem; border: 1px solid #fecaca;">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form action="/admin/login" method="post">
        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="username" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Username</label>
            <input type="text" id="username" name="username" required autofocus style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
        </div>
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="password" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Password</label>
            <input type="password" id="password" name="password" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
        </div>
        <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.875rem;">
            Sign In to Control Center
        </button>
    </form>
</div>
