<?php
define('ADMIN_ACCESS', true);
require_once '../config_modern.php';
requireLogin();
$action = $_GET['action'] ?? '';
// Handle AJAX requests
if (!empty($action)) {
    header('Content-Type: application/json');
    try {
        switch ($action) {
            case 'check':
                // Get distribution
                $stmt = $pdo->query("SELECT post_type, COUNT(*) as count FROM posts WHERE status = 'published' GROUP BY post_type");
                $distribution = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $distribution[$row['post_type'] ?: 'NULL'] = $row['count'];
                }
                // Get examples
                $stmt = $pdo->query("SELECT id, title, post_type FROM posts WHERE status = 'published' ORDER BY id DESC LIMIT 10");
                $examples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode([
                    'success' => true,
                    'distribution' => $distribution,
                    'examples' => $examples
                ]);
                exit;
            case 'auto_update':
                $stmt = $pdo->query("SELECT id, title, post_type FROM posts WHERE status = 'published'");
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $updated = 0;
                $results = [];
                foreach ($posts as $post) {
                    $title = $post['title'];
                    $oldType = $post['post_type'];
                    $newType = null;
                    // Detect by keywords
                    if (preg_match('/\b(whatsapp|telegram|facebook|instagram|tiktok|android emulator|bluestacks|ldplayer|syncios|minio|apk)\b/i', $title)) {
                        $newType = 'mobile-apps';
                    }
                    elseif (preg_match('/\b(game|cod|pubg|mobile legends|free fire|genshin|minecraft|roblox)\b/i', $title)) {
                        $newType = 'games';
                    }
                    elseif (preg_match('/\b(photoshop|premiere|illustrator|office|windows|software|aplikasi|download|pc|mac)\b/i', $title)) {
                        $newType = 'software';
                    }
                    elseif (preg_match('/\b(cara|tutorial|tips|panduan|guide|how to)\b/i', $title)) {
                        $newType = 'blog';
                    }
                    else {
                        $newType = 'software'; // default
                    }
                    if ($newType !== $oldType) {
                        $updateStmt = $pdo->prepare("UPDATE posts SET post_type = ? WHERE id = ?");
                        $updateStmt->execute([$newType, $post['id']]);
                        $updated++;
                        $results[] = [
                            'id' => $post['id'],
                            'title' => $post['title'],
                            'old_type' => $oldType,
                            'new_type' => $newType
                        ];
                    }
                }
                echo json_encode([
                    'success' => true,
                    'updated' => $updated,
                    'results' => $results
                ]);
                exit;
            case 'update_all':
                $newType = $_GET['type'] ?? 'software';
                $stmt = $pdo->prepare("UPDATE posts SET post_type = ? WHERE status = 'published'");
                $stmt->execute([$newType]);
                echo json_encode([
                    'success' => true,
                    'updated' => $stmt->rowCount()
                ]);
                exit;
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post Types - DONAN22</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding: 20px; }
        .type-badge { display: inline-block; padding: 5px 10px; margin: 2px; border-radius: 5px; }
        .type-software { background: #0d6efd; color: white; }
        .type-games { background: #dc3545; color: white; }
        .type-mobile-apps { background: #198754; color: white; }
        .type-blog { background: #ffc107; color: black; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4"><i class="fas fa-exchange-alt me-2"></i>Update Post Types</h1>
        <div class="alert alert-info">
            <h5>Aturan Migrasi:</h5>
            <ul>
                <li><span class="type-badge type-mobile-apps">Mobile Apps</span> - WhatsApp, Telegram, Android Emulator, aplikasi mobile</li>
                <li><span class="type-badge type-games">Games</span> - COD, PUBG, Mobile Legends, game-game</li>
                <li><span class="type-badge type-software">Software</span> - Photoshop, Windows, aplikasi PC/Mac</li>
                <li><span class="type-badge type-blog">Blog</span> - Tutorial, Cara, Panduan, Tips</li>
            </ul>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <button class="btn btn-success w-100" onclick="updateByKeywords()">
                            <i class="fas fa-magic me-2"></i>Auto Detect by Keywords
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info w-100" onclick="showCurrentDistribution()">
                            <i class="fas fa-chart-pie me-2"></i>Show Current Types
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-warning w-100" onclick="updateAll('software')">
                            <i class="fas fa-laptop me-2"></i>Set All → Software
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-danger w-100" onclick="updateAll('blog')">
                            <i class="fas fa-blog me-2"></i>Set All → Blog
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="results"></div>
    </div>
    <script>
    async function showCurrentDistribution() {
        const results = document.getElementById('results');
        results.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>';
        try {
            const response = await fetch('?action=check');
            const data = await response.json();
            let html = '<div class="card"><div class="card-header"><h5>Current Distribution</h5></div><div class="card-body">';
            html += '<table class="table table-striped"><thead><tr><th>Type</th><th>Count</th></tr></thead><tbody>';
            for (const [type, count] of Object.entries(data.distribution || {})) {
                const displayType = type || 'NULL';
                html += `<tr><td><span class="type-badge type-${type || 'software'}">${displayType}</span></td><td>${count}</td></tr>`;
            }
            html += '</tbody></table>';
            if (data.examples && data.examples.length > 0) {
                html += '<h6 class="mt-3">Recent Posts:</h6><ul class="list-group">';
                data.examples.forEach(post => {
                    const type = post.post_type || 'NULL';
                    html += `<li class="list-group-item"><span class="type-badge type-${post.post_type || 'software'}">${type}</span> - ${post.title}</li>`;
                });
                html += '</ul>';
            }
            html += '</div></div>';
            results.innerHTML = html;
        } catch (error) {
            results.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        }
    }
    async function updateByKeywords() {
        if (!confirm('Update all posts based on keywords in their titles?')) return;
        const results = document.getElementById('results');
        results.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i><p>Updating...</p></div>';
        try {
            const response = await fetch('?action=auto_update');
            const data = await response.json();
            if (data.success) {
                let html = `<div class="alert alert-success">
                    <h5><i class="fas fa-check-circle me-2"></i>Update Complete!</h5>
                    <p>Total Updated: ${data.updated} posts</p>
                </div>`;
                if (data.results && data.results.length > 0) {
                    html += '<div class="card"><div class="card-header"><h5>Updated Posts</h5></div><div class="card-body" style="max-height:400px;overflow-y:auto;">';
                    html += '<table class="table table-sm"><thead><tr><th>ID</th><th>Title</th><th>Old</th><th>New</th></tr></thead><tbody>';
                    data.results.forEach(post => {
                        html += `<tr>
                            <td>${post.id}</td>
                            <td><small>${post.title}</small></td>
                            <td><span class="type-badge type-${post.old_type || 'software'}">${post.old_type || 'NULL'}</span></td>
                            <td><span class="type-badge type-${post.new_type}">${post.new_type}</span></td>
                        </tr>`;
                    });
                    html += '</tbody></table></div></div>';
                }
                results.innerHTML = html;
            } else {
                results.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
            }
        } catch (error) {
            results.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        }
    }
    async function updateAll(newType) {
        if (!confirm(`Set all posts to type: ${newType}?`)) return;
        const results = document.getElementById('results');
        results.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>';
        try {
            const response = await fetch(`?action=update_all&type=${newType}`);
            const data = await response.json();
            if (data.success) {
                results.innerHTML = `<div class="alert alert-success">
                    <h5><i class="fas fa-check-circle me-2"></i>Success!</h5>
                    <p>Updated ${data.updated} posts to type: <span class="type-badge type-${newType}">${newType}</span></p>
                </div>`;
            } else {
                results.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
            }
        } catch (error) {
            results.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        }
    }
    // Auto load on page load
    window.onload = () => showCurrentDistribution();
    </script>
</body>
</html>