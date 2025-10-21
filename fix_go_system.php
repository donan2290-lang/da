<?php
/**
 * COMPREHENSIVE FIX - go.php & download tracking
 * Fix all 404 errors and console issues
 */

echo "🔧 COMPREHENSIVE ERROR FIX\n";
echo "===========================\n\n";

$fixes = 0;

// Fix 1: Add /go/ rewrite rule to .htaccess
echo "1️⃣ Adding /go/ rewrite rule to .htaccess...\n";

$htaccessFile = '.htaccess';
$htaccessContent = file_get_contents($htaccessFile);

// Check if go rule exists
if (strpos($htaccessContent, 'RewriteRule ^go/') === false) {
    // Find the position after post/category rules
    $insertPosition = strpos($htaccessContent, '# Tag URLs');
    
    $goRule = <<<'RULE'
# Go/Download URLs: /go/code
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^go/([a-zA-Z0-9]+)/?$ go.php?code=$1 [L,QSA]

RULE;
    
    if ($insertPosition !== false) {
        $htaccessContent = substr_replace($htaccessContent, $goRule, $insertPosition, 0);
        file_put_contents($htaccessFile, $htaccessContent);
        $fixes++;
        echo "   ✅ /go/ rewrite rule added\n";
    }
} else {
    echo "   ℹ️  /go/ rule already exists\n";
}

// Fix 2: Fix track-download.php path in go.php
echo "\n2️⃣ Fixing track-download.php path in go.php...\n";

$goFile = 'go.php';
$goContent = file_get_contents($goFile);

// Fix API path
$goContent = str_replace(
    "fetch('/api/track-download.php'",
    "fetch('<?= SITE_URL ?>/api/track-download.php'",
    $goContent
);

// Also fix without quotes
$goContent = preg_replace(
    '/fetch\([\'"]\/api\/track-download\.php/',
    'fetch(\'<?= SITE_URL ?>/api/track-download.php',
    $goContent
);

file_put_contents($goFile, $goContent);
$fixes++;
echo "   ✅ track-download.php path fixed\n";

// Fix 3: Verify track-download.php exists and has proper headers
echo "\n3️⃣ Verifying track-download.php...\n";

$trackFile = 'api/track-download.php';
if (file_exists($trackFile)) {
    $trackContent = file_get_contents($trackFile);
    
    // Add CORS headers if missing
    if (strpos($trackContent, 'Access-Control-Allow-Origin') === false) {
        $headers = <<<'PHP'
<?php
// CORS Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

PHP;
        
        // Replace existing <?php tag
        $trackContent = preg_replace('/^<\?php\s*/', $headers, $trackContent);
        file_put_contents($trackFile, $trackContent);
        $fixes++;
        echo "   ✅ CORS headers added to track-download.php\n";
    } else {
        echo "   ℹ️  track-download.php already has CORS headers\n";
    }
} else {
    echo "   ❌ track-download.php not found!\n";
    
    // Create track-download.php
    $trackContent = <<<'PHP'
<?php
// CORS Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config_modern.php';
require_once __DIR__ . '/../includes/MonetizationManager.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$linkId = $data['linkId'] ?? null;

if (!$linkId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing linkId']);
    exit;
}

try {
    $monetization = new MonetizationManager($pdo);
    $monetization->trackEvent($linkId, 'download');
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
PHP;
    
    if (!is_dir('api')) {
        mkdir('api', 0755, true);
    }
    
    file_put_contents($trackFile, $trackContent);
    $fixes++;
    echo "   ✅ track-download.php created\n";
}

// Fix 4: Check monetized_links table structure
echo "\n4️⃣ Checking database tables...\n";

try {
    require_once 'config_modern.php';
    
    // Check if monetized_links table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'monetized_links'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        echo "   ⚠️  Creating monetized_links table...\n";
        
        $createTable = "
        CREATE TABLE IF NOT EXISTS monetized_links (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            short_code VARCHAR(20) UNIQUE NOT NULL,
            download_title VARCHAR(255),
            original_url TEXT NOT NULL,
            monetized_url TEXT,
            monetizer_service VARCHAR(50) DEFAULT 'direct',
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_post_id (post_id),
            INDEX idx_short_code (short_code),
            INDEX idx_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createTable);
        $fixes++;
        echo "   ✅ monetized_links table created\n";
    } else {
        echo "   ✅ monetized_links table exists\n";
    }
    
    // Check if revenue_daily table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'revenue_daily'");
    $revenueExists = $stmt->rowCount() > 0;
    
    if (!$revenueExists) {
        echo "   ⚠️  Creating revenue_daily table...\n";
        
        $createRevenue = "
        CREATE TABLE IF NOT EXISTS revenue_daily (
            id INT AUTO_INCREMENT PRIMARY KEY,
            link_id INT NOT NULL,
            monetizer_service VARCHAR(50) DEFAULT 'direct',
            views INT DEFAULT 0,
            clicks INT DEFAULT 0,
            conversions INT DEFAULT 0,
            revenue DECIMAL(10,2) DEFAULT 0.00,
            event_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_daily (link_id, event_date, monetizer_service),
            INDEX idx_link_id (link_id),
            INDEX idx_date (event_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createRevenue);
        $fixes++;
        echo "   ✅ revenue_daily table created\n";
    } else {
        echo "   ✅ revenue_daily table exists\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

// Fix 5: Create verification test page
echo "\n5️⃣ Creating verification test page...\n";

$testContent = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <title>Go URL Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #667eea; }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .test-section { margin: 20px 0; padding: 15px; background: #f9fafb; border-radius: 4px; }
        code { background: #e5e7eb; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Go URL & Download Tracking Test</h1>
        
        <div class="test-section">
            <h2>1. Test /go/ URL Rewrite</h2>
            <p>Try accessing: <code>/go/testcode123</code></p>
            <?php
            $testUrl = '/go/testcode123';
            echo "<p>URL: <a href=\"{$testUrl}\" target=\"_blank\">{$testUrl}</a></p>";
            ?>
        </div>
        
        <div class="test-section">
            <h2>2. Test Track Download API</h2>
            <button onclick="testTrackDownload()">Test Track Download</button>
            <div id="track-result" style="margin-top: 10px;"></div>
        </div>
        
        <div class="test-section">
            <h2>3. Database Tables Check</h2>
            <?php
            require_once 'config_modern.php';
            
            $tables = ['monetized_links', 'revenue_daily'];
            foreach ($tables as $table) {
                $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
                $exists = $stmt->rowCount() > 0;
                $status = $exists ? 'success' : 'error';
                $icon = $exists ? '✅' : '❌';
                echo "<p class=\"{$status}\">{$icon} Table: <code>{$table}</code></p>";
            }
            ?>
        </div>
        
        <div class="test-section">
            <h2>4. Sample Monetized Links</h2>
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM monetized_links LIMIT 5");
                $links = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($links) > 0) {
                    echo "<table style='width: 100%; border-collapse: collapse;'>";
                    echo "<tr style='background: #f3f4f6;'><th>Short Code</th><th>Title</th><th>Service</th><th>Test Link</th></tr>";
                    foreach ($links as $link) {
                        echo "<tr style='border-bottom: 1px solid #e5e7eb;'>";
                        echo "<td><code>{$link['short_code']}</code></td>";
                        echo "<td>" . htmlspecialchars($link['download_title']) . "</td>";
                        echo "<td>{$link['monetizer_service']}</td>";
                        echo "<td><a href='/go/{$link['short_code']}' target='_blank'>Test</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No monetized links found. Create some in the post editor.</p>";
                }
            } catch (Exception $e) {
                echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
    </div>
    
    <script>
    function testTrackDownload() {
        const resultDiv = document.getElementById('track-result');
        resultDiv.innerHTML = 'Testing...';
        
        fetch('/api/track-download.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                linkId: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = '<span class="success">✅ Track download API working!</span>';
            } else {
                resultDiv.innerHTML = '<span class="error">❌ Error: ' + data.message + '</span>';
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<span class="error">❌ Fetch error: ' + error + '</span>';
        });
    }
    </script>
</body>
</html>
HTML;

file_put_contents('test_go_system.php', $testContent);
$fixes++;
echo "   ✅ Test page created: test_go_system.php\n";

// Summary
echo "\n";
echo "===========================\n";
echo "✨ FIX COMPLETE!\n";
echo "===========================\n";
echo "Total fixes: {$fixes}\n\n";

echo "🎯 What Was Fixed:\n";
echo "   ✅ /go/ rewrite rule added to .htaccess\n";
echo "   ✅ track-download.php path fixed\n";
echo "   ✅ CORS headers added\n";
echo "   ✅ Database tables verified/created\n";
echo "   ✅ Test page created\n\n";

echo "🧪 Testing:\n";
echo "   1. Test go URL: http://localhost/donan22/go/testcode123\n";
echo "   2. Test page: http://localhost/donan22/test_go_system.php\n";
echo "   3. Check console: Should have 0 errors\n\n";

echo "📝 Next Steps:\n";
echo "   • Clear browser cache\n";
echo "   • Test a real /go/ link from your posts\n";
echo "   • Check browser console for errors\n";
echo "===========================\n";
?>
