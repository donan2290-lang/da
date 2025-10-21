<?php

if (!defined('ADMIN_ACCESS') && !function_exists('getSettings')) {
    if (!file_exists(__DIR__ . '/../config_modern.php')) {
        die('Unauthorized access');
    }
}

if (!function_exists('getPropellerPageType')) {
    function getPropellerPageType() {
        $currentFile = basename($_SERVER['PHP_SELF'], '.php');
        $pageTypes = [
            'index' => ['index', 'test_propeller', 'test_monetag_complete', 'test_direct_antiadblock', 'test_antiadblock', 'test_fixed_antiadblock'],
            'post' => ['post', 'download'],
            'go' => ['go'],
            'category' => ['category', 'categories', 'search'],
        ];
        foreach ($pageTypes as $type => $files) {
            if (in_array($currentFile, $files)) {
                return $type;
            }
        }
        return 'other';
    }
}

if (!function_exists('getMontagZones')) {
    function getMontagZones() {
    return [
        // OnClick Popunder - AKTIF di Post/Download saja
        'onclick_post' => [
            'id' => '10021730',
            'name' => 'OnClick Popunder Anti-AdBlock - Post',
            'type' => 'onclick',
            'domain' => '5gvci.com',
            'pages' => ['post', 'go'] // Aktif di post & download
        ],
        // Push Notifications - All pages (lightweight)
        'push_notification' => [
            'id' => '10021743',
            'name' => 'Push Notifications',
            'type' => 'push',
            'domain' => '3nbf4.com',
            'pages' => ['index', 'post', 'category', 'go', 'search', 'about', 'other']
        ],
        // In-Page Push - AKTIF di Post/Download saja (untuk revenue)
        'inpage_push' => [
            'id' => '10021746',
            'name' => 'In-Page Push Anti-AdBlock',
            'type' => 'inpage',
            'domain' => 'ueuee.com',
            'pages' => ['post', 'go'] // Hanya di post/download
        ],
        // Vignette Banner - AKTIF di Post saja (lazy loaded)
        'vignette' => [
            'id' => '10021755',
            'name' => 'Vignette Banner',
            'type' => 'vignette',
            'domain' => 'gizokraijaw.net',
            'pages' => ['post', 'go'] // Hanya di post/download
        ],
    ];
    }
}

if (!function_exists('renderMontagZone')) {
    function renderMontagZone($zone) {
    $zoneId = $zone['id'];
    $domain = $zone['domain'];
    $type = $zone['type'];
    $name = $zone['name'];
    
    echo "\n<!-- Monetag: {$name} (Zone {$zoneId}) -->\n";
    
    switch ($type) {
        case 'onclick':
            // OnClick with Anti-AdBlock - Load immediately for better detection
            $antiAdblockFile = __DIR__ . '/../assets/propeller/pa_antiadblock_' . $zoneId . '_SIMPLE.php';
            if (file_exists($antiAdblockFile)) {
                $scriptTag = include_once($antiAdblockFile);
                if (!empty($scriptTag)) {
                    echo $scriptTag . "\n";
                } else {
                    // Fallback - Load immediately
                    echo "<script async type='text/javascript' src='https://{$domain}/pfe/current/tag.min.js?z={$zoneId}' data-cfasync='false'></script>\n";
                }
            } else {
                // Direct load - no delay for onclick
                echo "<script async type='text/javascript' src='https://{$domain}/pfe/current/tag.min.js?z={$zoneId}' data-cfasync='false'></script>\n";
            }
            break;
            
        case 'inpage':
            // In-Page Push - Load after 500ms (faster than before)
            echo "<script>\n";
            echo "setTimeout(function() {\n";
            echo "  (function(d,z,s){s.src='https://'+d+'/400/'+z;try{(document.body||document.documentElement).appendChild(s)}catch(e){}})";
            echo "('{$domain}',{$zoneId},document.createElement('script'));\n";
            echo "}, 500);\n";
            echo "</script>\n";
            break;
            
        case 'push':
            // Push Notification - Load with requestIdleCallback
            echo "<script>\n";
            echo "if ('requestIdleCallback' in window) {\n";
            echo "  requestIdleCallback(function() {\n";
            echo "    (function(s,u,z,p){s.src=u,s.setAttribute('data-zone',z),p.appendChild(s);})";
            echo "(document.createElement('script'),'https://{$domain}/act/files/tag.min.js?z={$zoneId}',{$zoneId},document.body||document.documentElement);\n";
            echo "  });\n";
            echo "} else {\n";
            echo "  setTimeout(function() {\n";
            echo "    (function(s,u,z,p){s.src=u,s.setAttribute('data-zone',z),p.appendChild(s);})";
            echo "(document.createElement('script'),'https://{$domain}/act/files/tag.min.js?z={$zoneId}',{$zoneId},document.body||document.documentElement);\n";
            echo "  }, 500);\n";
            echo "}\n";
            echo "</script>\n";
            break;
            
        case 'vignette':
            // Vignette - Load after 2 seconds or first scroll
            echo "<script>\n";
            echo "var vignetteLoaded = false;\n";
            echo "function loadVignette() {\n";
            echo "  if (!vignetteLoaded) {\n";
            echo "    vignetteLoaded = true;\n";
            echo "    var s = document.createElement('script');\n";
            echo "    s.dataset.zone = '{$zoneId}';\n";
            echo "    s.src = 'https://{$domain}/vignette.min.js';\n";
            echo "    s.type = 'text/javascript';\n";
            echo "    (document.body || document.documentElement).appendChild(s);\n";
            echo "  }\n";
            echo "}\n";
            echo "window.addEventListener('scroll', loadVignette, {once: true, passive: true});\n";
            echo "setTimeout(loadVignette, 2000);\n";
            echo "</script>\n";
            break;
    }
    }
}

if (!function_exists('loadPropellerAds')) {
    function loadPropellerAds($pageType = null) {
    if ($pageType === null) {
        $pageType = getPropellerPageType();
    }
    $zones = getMontagZones();
    
    // Load zones for current page
    foreach ($zones as $key => $zone) {
        if (!empty($zone['pages']) && in_array($pageType, $zone['pages'])) {
            renderMontagZone($zone);
        }
    }
    }
}

// Function to render in-content ad (after paragraph)
if (!function_exists('renderInContentAd')) {
    function renderInContentAd($position = 1) {
        $pageType = getPropellerPageType();
        
        // Only show in-content ads on post/download pages
        if (!in_array($pageType, ['post', 'go'])) {
            return '';
        }
        
        // Native banner after paragraph
        $adHtml = "\n<!-- In-Content Ad (After Paragraph {$position}) -->\n";
        $adHtml .= "<div class='in-content-ad' style='margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px; text-align: center;'>\n";
        $adHtml .= "  <script async='async' data-cfasync='false' src='https://dd133.com/a/display.php?r=10021755'></script>\n";
        $adHtml .= "</div>\n";
        
        return $adHtml;
    }
}

if (!function_exists('propeller_ads_loaded')) {
    function propeller_ads_loaded() {
        return true;
    }
}

// Auto-load ads if not in admin area
if (!defined('ADMIN_ACCESS')) {
    loadPropellerAds();
}