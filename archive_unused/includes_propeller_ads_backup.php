<?php

// Archived copy of includes/propeller_ads_backup.php
// Moved during repository cleanup on user request. Kept for safety and rollback.

// Original content preserved below.
(function(){
// ---- BEGIN ARCHIVED CONTENT ----
?>
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
        // OnClick Popunder Anti-AdBlock - Index & Homepage (Zone 10021738 - FALLBACK)
        'onclick_index' => [
            'id' => '10021738',
            'name' => 'OnClick Popunder Anti-AdBlock - Index/Homepage',
            'type' => 'onclick',
            'domain' => 'x7i0.com',
            'pages' => ['index', 'other'] // Homepage & other pages
        ],
        // OnClick Popunder Anti-AdBlock - Categories, Search, About (Zone 10021739)
        'onclick_category' => [
            'id' => '10021739',
            'name' => 'OnClick Popunder Anti-AdBlock - Category',
            'type' => 'onclick',
            'domain' => '5gvci.com',
            'pages' => ['category', 'search', 'about']
        ],
        // OnClick Popunder Anti-AdBlock - Posts & Go pages (Zone 10021730)
        'onclick_post' => [
            'id' => '10021730',
            'name' => 'OnClick Popunder Anti-AdBlock - Post',
            'type' => 'onclick',
            'domain' => '5gvci.com',
            'pages' => ['post', 'go']
        ],
        // In-Page Push Anti-AdBlock - All pages (Zone 10021747)
        'inpage_push' => [
            'id' => '10021747',
            'name' => 'In-Page Push Anti-AdBlock',
            'type' => 'inpage',
            'domain' => 'ueuee.com',
            'pages' => ['index', 'post', 'go', 'category', 'search', 'about', 'other']
        ],
        // Push Notifications - All pages (Zone 10021743)
        'push_notification' => [
            'id' => '10021743',
            'name' => 'Push Notifications',
            'type' => 'push',
            'domain' => '5gvci.com',
            'pages' => ['index', 'post', 'category', 'go', 'search', 'about', 'other']
        ],
        // Vignette Banner - All pages (Zone 10021755 - Regular)
        'vignette' => [
            'id' => '10021755',
            'name' => 'Vignette Banner',
            'type' => 'vignette',
            'domain' => 'gizokraijaw.net',
            'pages' => ['index', 'post', 'go', 'category', 'search', 'about', 'other']
        ],
        // Vignette Banner Anti-AdBlock - All pages (Zone 10021756 - Anti-AdBlock)
        'vignette_antiadblock' => [
            'id' => '10021756',
            'name' => 'Vignette Banner Anti-AdBlock',
            'type' => 'vignette',
            'domain' => 'gizokraijaw.net',
            'pages' => ['index', 'post', 'go', 'category', 'search', 'about', 'other']
        ]
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
            // OnClick Popunder - Use Anti-AdBlock API (PHP Method - Recommended by Monetag)
            $antiAdblockFile = __DIR__ . '/../assets/propeller/pa_antiadblock_' . $zoneId . '_SIMPLE.php';
            if (file_exists($antiAdblockFile)) {
                // Load script tag from Anti-AdBlock API (FIX: use include_once directly, not echo)
                $scriptTag = include_once($antiAdblockFile);
                if (!empty($scriptTag)) {
                    echo $scriptTag . "\n";
                } else {
                    // Fallback if API returns empty
                    echo "<script defer type='text/javascript' src='https://{$domain}/pfe/current/tag.min.js?z={$zoneId}' data-cfasync='false' async></script>\n";
                }
            } else {
                // Fallback to direct script if anti-adblock file not found
                echo "<script defer type='text/javascript' src='https://{$domain}/pfe/current/tag.min.js?z={$zoneId}' data-cfasync='false' async></script>\n";
            }
            break;
        case 'inpage':
            // In-Page Push - Use Anti-AdBlock API (PHP Method - Recommended by Monetag)
            $antiAdblockFile = __DIR__ . '/../assets/propeller/pa_antiadblock_' . $zoneId . '_SIMPLE.php';
            if (file_exists($antiAdblockFile)) {
                // Load script tag from Anti-AdBlock API (FIX: use include_once directly, not echo)
                $scriptTag = include_once($antiAdblockFile);
                if (!empty($scriptTag)) {
                    echo $scriptTag . "\n";
                } else {
                    // Fallback if API returns empty
                    echo "<script>(function(d,z,s){s.src='https://'+d+'/400/'+z;try{(document.body||document.documentElement).appendChild(s)}catch(e){}})";
                    echo "('{$domain}',{$zoneId},document.createElement('script'))</script>\n";
                }
            } else {
                // Fallback to direct script if anti-adblock file not found
                echo "<script>(function(d,z,s){s.src='https://'+d+'/400/'+z;try{(document.body||document.documentElement).appendChild(s)}catch(e){}})";
                echo "('{$domain}',{$zoneId},document.createElement('script'))</script>\n";
            }
            break;
        case 'push':
            // Push Notifications - Use Anti-AdBlock API (PHP Method - Recommended by Monetag)
            $antiAdblockFile = __DIR__ . '/../assets/propeller/pa_antiadblock_' . $zoneId . '_SIMPLE.php';
            if (file_exists($antiAdblockFile)) {
                // Load script tag from Anti-AdBlock API (FIX: use include_once directly, not echo)
                $scriptTag = include_once($antiAdblockFile);
                if (!empty($scriptTag)) {
                    echo $scriptTag . "\n";
                } else {
                    // Fallback if API returns empty - Service Worker based
                    echo "<script>(function(s,u,z,p){s.src=u,s.setAttribute('data-zone',z),p.appendChild(s);})";
                    echo "(document.createElement('script'),'https://{$domain}/400/{$zoneId}',{$zoneId},document.body||document.documentElement)</script>\n";
                }
            } else {
                // Fallback to direct script - Service Worker based
                echo "<script>(function(s,u,z,p){s.src=u,s.setAttribute('data-zone',z),p.appendChild(s);})";
                echo "(document.createElement('script'),'https://{$domain}/400/{$zoneId}',{$zoneId},document.body||document.documentElement)</script>\n";
            }
            break;
        case 'vignette':
            // Vignette Banner - Use Anti-AdBlock API (PHP Method - Recommended by Monetag)
            $antiAdblockFile = __DIR__ . '/../assets/propeller/pa_antiadblock_' . $zoneId . '_SIMPLE.php';
            if (file_exists($antiAdblockFile)) {
                // Load script tag from Anti-AdBlock API (FIX: use include_once directly, not echo)
                $scriptTag = include_once($antiAdblockFile);
                if (!empty($scriptTag)) {
                    echo $scriptTag . "\n";
                } else {
                    // Fallback if API returns empty
                    echo "<script type=\"text/javascript\">\n";
                    echo "(function() {\n";
                    echo "    var s = document.createElement('script');\n";
                    echo "    s.dataset.zone = '{$zoneId}';\n";
                    echo "    s.src = 'https://{$domain}/vignette.min.js';\n";
                    echo "    s.type = 'text/javascript';\n";
                    echo "    (document.body || document.documentElement).appendChild(s);\n";
                    echo "})();\n";
                    echo "</script>\n";
                }
            } else {
                // Fallback to direct script
                echo "<script type=\"text/javascript\">\n";
                echo "(function() {\n";
                echo "    var s = document.createElement('script');\n";
                echo "    s.dataset.zone = '{$zoneId}';\n";
                echo "    s.src = 'https://{$domain}/vignette.min.js';\n";
                echo "    s.type = 'text/javascript';\n";
                echo "    (document.body || document.documentElement).appendChild(s);\n";
                echo "})();\n";
                echo "</script>\n";
            }
            break;
        case 'native':
            // Native Banner - Async display
            echo "<script async=\"async\" data-cfasync=\"false\" src=\"https://{$domain}/a/display.php?r={$zoneId}\"></script>\n";
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
    
    // List of disabled ads for specific pages (PERFORMANCE OPTIMIZATION)
    $disabledAds = [
        'index' => ['onclick_index'],  // Disable onclick on homepage for better UX
    ];
    
    // Load zones for current page
    foreach ($zones as $key => $zone) {
        if (in_array($pageType, $zone['pages'])) {
            // Skip if this ad type is disabled for current page
            if (isset($disabledAds[$pageType]) && in_array($key, $disabledAds[$pageType])) {
                echo "\n<!-- Monetag: {$zone['name']} (Zone {$zone['id']}) - DISABLED for better UX on {$pageType} page -->\n";
                continue;
            }
            renderMontagZone($zone);
        }
    }
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

// ---- END ARCHIVED CONTENT ----
})();
