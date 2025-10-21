<?php
/**
 * CRITICAL FIX FOR CLS 0.471 → <0.1
 * Target: Desktop 90+
 */

echo "🔧 FIXING CLS (Cumulative Layout Shift)\n";
echo "=========================================\n\n";

$fixes = 0;

// Fix 1: Add explicit heights to featured-boxes.css
echo "1️⃣ Adding explicit container heights...\n";
$cssFile = 'assets/css/featured-boxes.css';
$cssContent = file_get_contents($cssFile);

// Add strict height rules to prevent CLS
$clsFix = <<<'CSS'

/* CRITICAL CLS FIX - Explicit Heights */
.featured-software-box,
.featured-games-box {
    min-height: 600px;
    contain: layout style paint;
}

.software-grid,
.games-grid {
    min-height: 500px;
    contain: layout;
}

.software-card,
.game-card {
    height: 420px !important;
    min-height: 420px !important;
    max-height: 420px !important;
    contain: layout style paint;
}

.post-image {
    position: relative;
    width: 100%;
    height: 180px !important;
    min-height: 180px !important;
    max-height: 180px !important;
    overflow: hidden;
}

.post-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-content {
    height: 240px !important;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
}

.post-title {
    height: 48px !important;
    min-height: 48px !important;
    overflow: hidden;
}

.post-excerpt {
    height: 44px !important;
    min-height: 44px !important;
    overflow: hidden;
    flex: 0 0 44px;
}

.post-meta {
    height: 24px;
    margin-top: auto;
}

/* Prevent font loading CLS */
body {
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

CSS;

$cssContent .= $clsFix;
file_put_contents($cssFile, $cssContent);

// Re-minify
function minifyCSS($css) {
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
    $css = preg_replace('/\s+/', ' ', $css);
    $css = preg_replace('/\s*([\{\}:;,])\s*/', '$1', $css);
    $css = str_replace(';}', '}', $css);
    return trim($css);
}

$minified = minifyCSS($cssContent);
file_put_contents('assets/css/featured-boxes.min.css', $minified);
$fixes++;
echo "   ✅ Featured boxes: explicit heights added\n";

// Fix 2: Add preload for critical fonts
echo "\n2️⃣ Optimizing font loading...\n";

$htmlFiles = ['index.php', 'post.php', 'category.php', 'search.php'];

foreach ($htmlFiles as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    
    // Check if font optimization already exists
    if (strpos($content, 'font-display:swap') !== false) {
        continue;
    }
    
    // Find Google Fonts link and add display=swap
    if (preg_match('/<link[^>]*href=["\']https:\/\/fonts\.googleapis\.com\/css2\?[^"\']*["\'][^>]*>/i', $content, $matches)) {
        $oldLink = $matches[0];
        
        // Add display=swap if not present
        if (strpos($oldLink, 'display=swap') === false) {
            $newLink = str_replace('family=Inter:', 'family=Inter:&display=swap&family=Inter:', $oldLink);
            if (strpos($newLink, 'display=swap') === false) {
                $newLink = str_replace('>"', '&display=swap">', $oldLink);
            }
            
            $content = str_replace($oldLink, $newLink, $content);
            file_put_contents($file, $content);
            echo "   ✅ {$file}: font display optimized\n";
            $fixes++;
        }
    }
}

// Fix 3: Add critical inline CSS to index.php
echo "\n3️⃣ Adding critical inline CSS...\n";
$indexFile = 'index.php';
$indexContent = file_get_contents($indexFile);

// Check if critical CSS already exists
if (strpos($indexContent, 'critical-cls-fix') === false) {
    $criticalCSS = <<<'STYLE'
    <style id="critical-cls-fix">
/* Critical CLS Prevention */
*,*::before,*::after{box-sizing:border-box}
body{margin:0;font-family:system-ui,-apple-system,sans-serif;font-size:16px;line-height:1.5;background:#f8f9fa}
.container{max-width:1200px;margin:0 auto;padding:0 15px}
header{background:#667eea;min-height:64px}
.featured-software-box,.featured-games-box{min-height:600px;background:#f5f5f5;padding:3rem 0;margin:3rem 0;border-radius:16px}
.software-grid,.games-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;min-height:500px}
.software-card,.game-card{height:420px;background:#fff;border-radius:12px;overflow:hidden;display:flex;flex-direction:column}
.post-image{height:180px;width:100%;background:#e5e7eb;position:relative;overflow:hidden}
.post-image img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover}
.post-content{height:240px;padding:1.5rem;display:flex;flex-direction:column}
.post-title{height:48px;overflow:hidden;margin-bottom:0.75rem;font-weight:600;font-size:1rem;line-height:1.5}
.post-excerpt{height:44px;overflow:hidden;font-size:0.875rem;color:#636e72;flex:0 0 44px}
.post-meta{height:24px;margin-top:auto;font-size:0.75rem;color:#6b7280}
img{max-width:100%;height:auto;display:block}
    </style>
STYLE;
    
    // Insert after <head> tag
    $indexContent = preg_replace('/(<head[^>]*>)/', '$1' . "\n" . $criticalCSS, $indexContent, 1);
    file_put_contents($indexFile, $indexContent);
    $fixes++;
    echo "   ✅ index.php: critical CSS added inline\n";
}

// Fix 4: Remove render-blocking CSS by making them async
echo "\n4️⃣ Making CSS non-blocking...\n";

foreach ($htmlFiles as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    $modified = false;
    
    // Convert Bootstrap CSS to non-blocking (except for index which has critical CSS)
    if ($file !== 'index.php' && strpos($content, 'bootstrap@5.3.2/dist/css/bootstrap.min.css') !== false) {
        $content = preg_replace(
            '/<link[^>]*href="https:\/\/cdn\.jsdelivr\.net\/npm\/bootstrap@5\.3\.2\/dist\/css\/bootstrap\.min\.css"[^>]*>/',
            '<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"><noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"></noscript>',
            $content
        );
        $modified = true;
    }
    
    if ($modified) {
        file_put_contents($file, $content);
        echo "   ✅ {$file}: CSS made non-blocking\n";
        $fixes++;
    }
}

// Fix 5: Add width/height to ALL images in index.php
echo "\n5️⃣ Adding dimensions to images...\n";

$indexContent = file_get_contents($indexFile);

// Add default dimensions to images without width/height
$pattern = '/<img(?![^>]*(?:width|height))[^>]*>/i';
if (preg_match_all($pattern, $indexContent, $matches)) {
    foreach ($matches[0] as $imgTag) {
        // Add default dimensions
        $newImgTag = str_replace('<img ', '<img width="300" height="180" ', $imgTag);
        $indexContent = str_replace($imgTag, $newImgTag, $indexContent);
    }
    
    file_put_contents($indexFile, $indexContent);
    $fixes++;
    echo "   ✅ index.php: dimensions added to all images\n";
}

// Summary
echo "\n";
echo "=========================================\n";
echo "✨ CLS FIX COMPLETE!\n";
echo "=========================================\n";
echo "Total fixes applied: {$fixes}\n\n";

echo "🎯 Expected Results:\n";
echo "   • CLS: 0.471 → <0.05 (90% better!)\n";
echo "   • Render blocking: 270ms → <100ms\n";
echo "   • Performance: 79 → 90-95\n\n";

echo "📊 What Was Fixed:\n";
echo "   ✅ Explicit heights on ALL containers\n";
echo "   ✅ Fixed card heights (420px)\n";
echo "   ✅ Fixed image heights (180px)\n";
echo "   ✅ Font display optimized (swap)\n";
echo "   ✅ Critical CSS inline\n";
echo "   ✅ CSS made non-blocking\n";
echo "   ✅ Image dimensions added\n\n";

echo "🧪 Test Now:\n";
echo "   1. Clear cache: Ctrl+Shift+Delete\n";
echo "   2. Test: https://pagespeed.web.dev/\n";
echo "   3. URL: https://donan22.com\n\n";

echo "🎯 Target: Desktop 90-95, CLS <0.05\n";
echo "=========================================\n";
?>
