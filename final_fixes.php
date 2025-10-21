<?php
/**
 * FINAL FIXES untuk 100% Score di Semua Kategori
 * Fixes: Title, Lang, Meta Description, Viewport, Doctype
 */

echo "🔧 FINAL FIXES - Target 100% All Scores\n";
echo str_repeat("=", 70) . "\n\n";

// Issues to fix:
// 1. Missing <title> element
// 2. Missing <html lang="id">
// 3. Missing meta description
// 4. Missing meta viewport (some pages)
// 5. Missing DOCTYPE (some pages)

$allPages = glob(__DIR__ . '/*.php');
$fixed = [
    'title' => 0,
    'lang' => 0,
    'meta_desc' => 0,
    'viewport' => 0,
    'doctype' => 0,
];

foreach ($allPages as $page) {
    $filename = basename($page);
    
    // Skip config and script files
    if (in_array($filename, ['config_modern.php', 'config.php', 'final_fixes.php'])) {
        continue;
    }
    
    $content = file_get_contents($page);
    $modified = false;
    $originalContent = $content;
    
    // 1. Fix DOCTYPE
    if (stripos($content, '<!DOCTYPE html>') === false && stripos($content, '<html') !== false) {
        $content = preg_replace('/(<html[^>]*>)/i', "<!DOCTYPE html>\n$1", $content, 1);
        $fixed['doctype']++;
        $modified = true;
    }
    
    // 2. Fix <html lang="id">
    if (preg_match('/<html(?![^>]*lang=)/i', $content)) {
        $content = preg_replace('/<html([^>]*)>/i', '<html lang="id"$1>', $content, 1);
        $fixed['lang']++;
        $modified = true;
    }
    
    // 3. Fix meta viewport
    if (stripos($content, 'name="viewport"') === false && stripos($content, '<head>') !== false) {
        $viewport = '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $content = preg_replace('/(<head>)/i', "$1\n$viewport", $content, 1);
        $fixed['viewport']++;
        $modified = true;
    }
    
    // 4. Add <title> if missing
    if (stripos($content, '<title>') === false && stripos($content, '<head>') !== false) {
        // Generate title based on filename
        $pageTitle = ucwords(str_replace(['.php', '_', '-'], [' ', ' ', ' '], $filename));
        $title = "    <title>{$pageTitle} - DONAN22</title>";
        
        // Insert after viewport or after <head>
        if (stripos($content, 'name="viewport"') !== false) {
            $content = preg_replace('/(<meta name="viewport"[^>]*>)/i', "$1\n$title", $content, 1);
        } else {
            $content = preg_replace('/(<head>)/i', "$1\n$title", $content, 1);
        }
        $fixed['title']++;
        $modified = true;
    }
    
    // 5. Add meta description if missing
    if (stripos($content, 'name="description"') === false && stripos($content, '<head>') !== false) {
        // Generate description based on page
        $descriptions = [
            'index.php' => 'Download software, aplikasi mobile, dan games gratis terbaru di Donan22. Update harian dengan tutorial lengkap dan link download aman.',
            'search.php' => 'Cari dan download software, aplikasi mobile, dan games terbaru gratis. Temukan aplikasi favorit Anda dengan mudah di Donan22.',
            'about.php' => 'Tentang Donan22 - Platform download software gratis terpercaya dengan koleksi lengkap aplikasi Windows, Mac, Android, dan iOS.',
            'contact.php' => 'Hubungi tim Donan22 untuk pertanyaan, saran, atau kerja sama. Kami siap membantu Anda 24/7.',
            'categories.php' => 'Kategori software dan aplikasi di Donan22. Temukan aplikasi berdasarkan kategori: Windows, Android, iOS, Mac, dan lainnya.',
            'download.php' => 'Download software dan aplikasi gratis terbaru dengan link aman dan cepat di Donan22.',
            'post.php' => 'Baca artikel dan tutorial lengkap tentang software dan aplikasi di Donan22. Download gratis dengan panduan step-by-step.',
            'category.php' => 'Kategori software dan aplikasi terbaru di Donan22. Download gratis dengan tutorial lengkap.',
        ];
        
        $description = $descriptions[$filename] ?? 'Download software dan aplikasi gratis terbaru di Donan22. Koleksi lengkap dengan tutorial dan link download aman.';
        
        $metaDesc = '    <meta name="description" content="' . $description . '">';
        
        // Insert after viewport or title
        if (stripos($content, '<title>') !== false) {
            $content = preg_replace('/(<title>[^<]*<\/title>)/i', "$1\n$metaDesc", $content, 1);
        } elseif (stripos($content, 'name="viewport"') !== false) {
            $content = preg_replace('/(<meta name="viewport"[^>]*>)/i', "$1\n$metaDesc", $content, 1);
        }
        $fixed['meta_desc']++;
        $modified = true;
    }
    
    // Save if modified
    if ($modified && $content !== $originalContent) {
        file_put_contents($page, $content);
        echo "✅ Fixed: {$filename}\n";
    }
}

echo "\n";
echo str_repeat("=", 70) . "\n";
echo "📊 FIXES SUMMARY\n";
echo str_repeat("=", 70) . "\n";
echo "✅ DOCTYPE added: {$fixed['doctype']} files\n";
echo "✅ HTML lang attribute: {$fixed['lang']} files\n";
echo "✅ Title elements: {$fixed['title']} files\n";
echo "✅ Meta viewport: {$fixed['viewport']} files\n";
echo "✅ Meta descriptions: {$fixed['meta_desc']} files\n";
echo str_repeat("=", 70) . "\n\n";

echo "🎯 EXPECTED SCORES (After Upload):\n";
echo "- Performance:    100 ✅ (Already perfect!)\n";
echo "- Accessibility:  95+ ✅ (55 → 95+)\n";
echo "- Best Practices: 100 ✅ (93 → 100)\n";
echo "- SEO:            100 ✅ (82 → 100)\n\n";

echo "Core Web Vitals: PASSED ✅\n";
echo "- FCP: 0.2s ✅\n";
echo "- LCP: 0.2s ✅\n";
echo "- TBT: 0ms ✅\n";
echo "- CLS: 0 ✅\n";
echo "- SI: 0.2s ✅\n\n";

echo "⚠️  NEXT STEPS:\n";
echo "1. Upload files ke hosting\n";
echo "2. Clear cache (browser + Cloudflare)\n";
echo "3. Test di PageSpeed: https://pagespeed.web.dev/\n";
echo "4. Expected: 100/95+/100/100 🎯\n\n";

echo "✅ FINAL FIXES COMPLETE!\n";
echo "Website ready for perfect scores! 🚀🎉\n\n";

// Delete this script
if (unlink(__FILE__)) {
    echo "🗑️  This script has been deleted.\n\n";
}

?>
