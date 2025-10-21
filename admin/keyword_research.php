<?php

session_start();
require_once '../config_modern.php';
require_once '../includes/keyword_research.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
// Generate keyword report
$keywordReport = generateKeywordReport($pdo);
// Handle export request
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $allKeywords = array_merge(
        getHighVolumeKeywords(),
        getLongTailKeywords()
    );
    exportKeywordsToCSV($allKeywords, 'donan22_keywords_' . date('Y-m-d') . '.csv');
}
$pageTitle = 'Keyword Research Dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - DONAN22 Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
        }
        .admin-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .admin-header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        .admin-nav {
            margin-top: 15px;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            opacity: 0.9;
            transition: opacity 0.2s;
        }
        .admin-nav a:hover {
            opacity: 1;
        }
        .content-wrapper {
            max-width: 1600px;
            margin: 0 auto;
            padding: 30px;
        }
        .action-bar {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16,185,129,0.3);
        }
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .keyword-suggestions {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .keyword-suggestions h2 {
            color: #1e293b;
            font-size: 1.6rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .suggestions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .suggestion-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #3b82f6;
        }
        .suggestion-card h3 {
            color: #1e40af;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        .suggestion-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .suggestion-card li {
            padding: 8px 0;
            color: #475569;
            font-size: 0.9rem;
            border-bottom: 1px solid #bae6fd;
        }
        .suggestion-card li:last-child {
            border-bottom: none;
        }
        .tips-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 12px;
            padding: 30px;
            margin-top: 30px;
            border-left: 4px solid #f59e0b;
        }
        .tips-section h2 {
            color: #92400e;
            font-size: 1.6rem;
            margin-bottom: 20px;
        }
        .tips-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }
        .tip-box {
            background: rgba(255,255,255,0.6);
            border-radius: 10px;
            padding: 20px;
        }
        .tip-box h3 {
            color: #78350f;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        .tip-box p {
            color: #92400e;
            margin: 0;
            line-height: 1.6;
        }
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 15px;
            }
            .suggestions-grid,
            .tips-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1><i class="fas fa-chart-line"></i> <?= $pageTitle ?></h1>
        <nav class="admin-nav">
            <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="posts.php"><i class="fas fa-file-alt"></i> Posts</a>
            <a href="seo_manager.php"><i class="fas fa-search"></i> SEO Manager</a>
            <a href="keyword_research.php" class="active"><i class="fas fa-key"></i> Keywords</a>
            <a href="analytics.php"><i class="fas fa-chart-bar"></i> Analytics</a>
        </nav>
    </header>
    <div class="content-wrapper">
        <div class="action-bar">
            <div>
                <h2 style="margin: 0; color: #1e293b;">
                    <i class="fas fa-database"></i> Keyword Database
                </h2>
                <p style="margin: 5px 0 0 0; color: #64748b;">
                    Manage and track your SEO keywords
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="?export=csv" class="btn btn-success">
                    <i class="fas fa-file-export"></i> Export to CSV
                </a>
                <button class="btn btn-primary" onclick="refreshReport()">
                    <i class="fas fa-sync-alt"></i> Refresh Report
                </button>
            </div>
        </div>
        <!-- Keyword Dashboard -->
        <?= renderKeywordDashboard($keywordReport) ?>
        <!-- Keyword Suggestions by Category -->
        <div class="keyword-suggestions">
            <h2><i class="fas fa-magic"></i> Keyword Templates by Category</h2>
            <div class="suggestions-grid">
                <div class="suggestion-card">
                    <h3><i class="fas fa-laptop-code"></i> Software Downloads</h3>
                    <ul>
                        <li>download {software} full version</li>
                        <li>cara install {software}</li>
                        <li>{software} terbaru 2025</li>
                        <li>{software} full crack gratis</li>
                        <li>tutorial {software} pemula</li>
                    </ul>
                </div>
                <div class="suggestion-card">
                    <h3><i class="fas fa-gamepad"></i> Games Downloads</h3>
                    <ul>
                        <li>download game {game} gratis</li>
                        <li>{game} full version pc</li>
                        <li>cara main {game} offline</li>
                        <li>{game} crack working</li>
                        <li>spesifikasi {game}</li>
                    </ul>
                </div>
                <div class="suggestion-card">
                    <h3><i class="fas fa-book"></i> Tutorial Content</h3>
                    <ul>
                        <li>cara {action}</li>
                        <li>tutorial {action} lengkap</li>
                        <li>panduan {action} untuk pemula</li>
                        <li>belajar {action}</li>
                        <li>{action} step by step</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- SEO Tips -->
        <div class="tips-section">
            <h2><i class="fas fa-lightbulb"></i> Keyword Research Best Practices</h2>
            <div class="tips-list">
                <div class="tip-box">
                    <h3>1. Focus on Long-Tail Keywords</h3>
                    <p>Target specific, longer phrases (3-5 words) with lower competition but higher intent. Example: "download adobe photoshop 2025 full crack" instead of just "photoshop".</p>
                </div>
                <div class="tip-box">
                    <h3>2. Analyze Search Intent</h3>
                    <p>Understand what users want: informational (how-to), transactional (download), or navigational (specific software). Match your content to intent.</p>
                </div>
                <div class="tip-box">
                    <h3>3. Use Keyword Modifiers</h3>
                    <p>Add modifiers like "gratis", "terbaru", "full version", "crack", "2025" to target specific queries with less competition.</p>
                </div>
                <div class="tip-box">
                    <h3>4. Monitor Competitor Keywords</h3>
                    <p>Research what keywords your competitors rank for and find gaps you can fill with better content.</p>
                </div>
                <div class="tip-box">
                    <h3>5. Update Content Regularly</h3>
                    <p>Keep your articles fresh by updating them with latest versions, new screenshots, and current year in titles.</p>
                </div>
                <div class="tip-box">
                    <h3>6. Track Keyword Performance</h3>
                    <p>Use Google Search Console to monitor which keywords bring traffic and optimize accordingly.</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function refreshReport() {
            window.location.reload();
        }
        // Auto-refresh every 5 minutes
        setTimeout(() => {
            refreshReport();
        }, 300000);
    </script>
</body>
</html>