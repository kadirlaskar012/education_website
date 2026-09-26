<?php
spl_autoload_register(function (string $class) {
    if (str_starts_with($class, 'App\\')) {
        $relativeClass = substr($class, 4);
        $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    } elseif ($class === 'Database') {
        require_once __DIR__ . '/../config/database.php';
    }
});
require_once __DIR__ . '/../config/database.php';

$articleModel = new \App\Models\Article();
$art = $articleModel->getLatest(1)[0] ?? null;

if (!$art) {
    echo "No article found!\n";
    exit;
}

echo "Testing Slug: " . $art['slug'] . "\n\n";

$urls = [
    'EN Page' => 'http://127.0.0.1:8000/news/' . $art['slug'],
    'BN Page' => 'http://127.0.0.1:8000/bn/news/' . $art['slug'],
    'HI Page' => 'http://127.0.0.1:8000/hi/news/' . $art['slug'],
];

foreach ($urls as $label => $url) {
    echo "=== $label ($url) ===\n";
    $html = @file_get_contents($url);
    if (!$html) {
        echo "Failed to load $url\n";
        continue;
    }

    preg_match('/<title>(.*?)<\/title>/', $html, $mTitle);
    preg_match('/<meta name="description" content="(.*?)"/', $html, $mDesc);
    preg_match('/<link rel="canonical" href="(.*?)"/', $html, $mCanon);
    preg_match('/<meta property="og:locale" content="(.*?)"/', $html, $mLocale);
    preg_match_all('/<link rel="alternate" hreflang="([^"]+)" href="([^"]+)"/', $html, $mHrefs);

    echo "  Title: " . ($mTitle[1] ?? 'N/A') . "\n";
    echo "  Meta Desc: " . mb_substr($mDesc[1] ?? 'N/A', 0, 80) . "...\n";
    echo "  Canonical: " . ($mCanon[1] ?? 'N/A') . "\n";
    echo "  OG Locale: " . ($mLocale[1] ?? 'N/A') . "\n";
    echo "  Hreflang Alternates:\n";
    foreach ($mHrefs[1] as $idx => $hreflang) {
        echo "    - $hreflang -> " . $mHrefs[2][$idx] . "\n";
    }
    echo "\n";
}

echo "=== Sitemap Verification ===\n";
$sitemapXml = @file_get_contents('http://127.0.0.1:8000/sitemap.xml');
echo "Sitemap length: " . strlen($sitemapXml) . " bytes\n";
if (strpos($sitemapXml, 'xmlns:xhtml="http://www.w3.org/1999/xhtml"') !== false && strpos($sitemapXml, 'hreflang="bn"') !== false) {
    echo "✓ XML Sitemap contains xmlns:xhtml and hreflang='bn' / 'hi' alternates!\n";
} else {
    echo "✗ Sitemap missing xhtml namespace or hreflang tags\n";
}
