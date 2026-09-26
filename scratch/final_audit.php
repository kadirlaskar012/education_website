<?php
$routes = [
    '/',
    '/news/national-scholarship-portal-nsp-2026-27-fresh-application-renewal-portal-opened',
    '/results',
    '/admit-card',
    '/recruitment',
    '/exam',
    '/answer-key',
    '/category/scholarship',
    '/state/west-bengal',
    '/state/delhi',
    '/search?q=ssc',
    '/about',
    '/contact',
    '/privacy-policy',
    '/terms-and-conditions',
    '/disclaimer',
    '/copyright-policy',
    '/sitemap.xml',
    '/news-sitemap.xml',
    '/rss.xml',
    '/admin',
    '/admin/login',
    '/admin/articles',
    '/admin/sources',
    '/admin/settings',
    '/admin/translator',
];

$failed = [];
foreach ($routes as $route) {
    $url = "http://127.0.0.1:8000" . $route;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 4,
    ]);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code !== 200 && $code !== 302) {
        $failed[] = "$route => HTTP $code";
    }
}

if (empty($failed)) {
    echo "ALL " . count($routes) . " AUDITED ROUTES PASSED WITH 200/302 OK!\n";
} else {
    echo "FAILED ROUTES:\n" . implode("\n", $failed) . "\n";
}
