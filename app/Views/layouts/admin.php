<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Admin Panel — EduGov News') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="/static/admin/css/custom_admin.css?v=2.0">
</head>
<body class="admin-body">

<header class="admin-topbar">
    <div class="admin-container admin-topbar-inner">
        <div class="admin-brand">
            <a href="/admin">🏛️ EduGov <span>Administration</span></a>
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
