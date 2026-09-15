<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Admin Panel — EduGov News') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="/static/admin/css/custom_admin.css?v=2.0">
</head>
<body class="admin-body">

<header class="admin-topbar">
    <div class="admin-container admin-topbar-inner">
        <div class="admin-brand">
            <a href="/admin" style="display: flex; align-items: center; gap: 0.6rem; text-decoration: none;">
                <svg width="28" height="28" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="48" height="48" rx="12" fill="#1e40af"/>
                    <path d="M10 32 C14 30, 20 30, 24 33 C28 30, 34 30, 38 32 V38 C34 36, 28 36, 24 39 C20 36, 14 36, 10 38 Z" fill="#ffffff"/>
                    <polygon points="24,10 7,18 24,25 41,18" fill="#ffffff"/>
                    <path d="M14 21.5 V26 C14 29 24 31 24 31 C24 31 34 29 34 26 V21.5 L24 25.5 Z" fill="#93c5fd"/>
                    <circle cx="37" cy="11" r="5" fill="#f59e0b"/>
                    <circle cx="9" cy="11" r="3.5" fill="#ef4444"/>
                </svg>
                <span>EduGov <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">Admin</span></span>
            </a>
        </div>
        <div class="admin-user-tools">
            <?php if (\App\Core\Auth::check()): ?>
            <span>Welcome, <strong><?= htmlspecialchars(\App\Core\Auth::user()['username'] ?? 'admin') ?></strong></span>
            <a href="/" target="_blank">View Site ↗</a>
            <a href="/admin/logout" class="logout-link">Log out</a>
            <?php else: ?>
            <a href="/admin/login">Log in</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="admin-container admin-main-content">
    <?= $content ?>
</main>

<footer class="admin-footer">
    <div class="admin-container">
        <p>EduGov News Control Center & Scraper Automation Engine</p>
    </div>
</footer>

</body>
</html>
