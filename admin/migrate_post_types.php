<?php
define('ADMIN_ACCESS', true);
require_once '../config_modern.php';
requireLogin();
header('Content-Type: application/json');
try {
    // Get all posts
    $stmt = $pdo->query("SELECT id, title, category_id, post_type FROM posts WHERE status = 'published' ORDER BY id DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $updated = 0;
    $results = [];
    foreach ($posts as $post) {
        $newType = null;
        $oldType = $post['post_type'];
        // Determine new type based on title/category
        $title = strtolower($post['title']);
        // Keywords untuk Mobile Apps → software
        if (preg_match('/\b(whatsapp|telegram|facebook|instagram|tiktok|snapchat|twitter|messenger|viber|line|wechat|android|emulator|bluestacks|ldplayer|nox|memu|gameloop|apk|mobile app|smartphone)\b/i', $post['title'])) {
            $newType = 'mobile-apps';
        }
        // Keywords untuk Games → games
        elseif (preg_match('/\b(game|gameplay|cod|pubg|mobile legends|free fire|genshin|clash|minecraft|roblox|fortnite|valorant|dota|league of legends|fifa|pes|racing|rpg|fps|moba)\b/i', $post['title'])) {
            $newType = 'games';
        }
        // Keywords untuk Software → software
        elseif (preg_match('/\b(software|download|windows|mac|pc|photoshop|premiere|after effects|illustrator|corel|autocad|office|word|excel|powerpoint|chrome|firefox|antivirus|winrar|idm|ccleaner|driver|plugin|vst|daw)\b/i', $post['title'])) {
            $newType = 'software';
        }
        // Keywords untuk Tutorial/Tips → blog
        elseif (preg_match('/\b(cara|tutorial|tips|panduan|guide|how to|langkah|step|belajar|menggunakan|membuat|install|setting|konfigurasi|solusi|fix|error|masalah)\b/i', $post['title'])) {
            $newType = 'blog';
        }
        // Default: Check category
        else {
            // Get category name
            $catStmt = $pdo->prepare("SELECT slug FROM categories WHERE id = ?");
            $catStmt->execute([$post['category_id']]);
            $category = $catStmt->fetch(PDO::FETCH_ASSOC);
            if ($category) {
                $catSlug = strtolower($category['slug']);
                if (in_array($catSlug, ['mobile-apps', 'android', 'ios', 'aplikasi-mobile'])) {
                    $newType = 'mobile-apps';
                } elseif (in_array($catSlug, ['games', 'gaming', 'game-mobile', 'game-pc'])) {
                    $newType = 'games';
                } elseif (in_array($catSlug, ['software', 'windows', 'mac', 'aplikasi-pc'])) {
                    $newType = 'software';
                } elseif (in_array($catSlug, ['tutorial', 'tips', 'panduan', 'blog'])) {
                    $newType = 'blog';
                } else {
                    // Default to software for general posts
                    $newType = 'software';
                }
            } else {
                $newType = 'software'; // Default
            }
        }
        if ($newType && $newType !== $oldType) {
            $updateStmt = $pdo->prepare("UPDATE posts SET post_type = ? WHERE id = ?");
            $updateStmt->execute([$newType, $post['id']]);
            $updated++;
            $results[] = [
                'id' => $post['id'],
                'title' => $post['title'],
                'old_type' => $oldType ?: 'NULL',
                'new_type' => $newType
            ];
        }
    }
    echo json_encode([
        'success' => true,
        'total_posts' => count($posts),
        'updated' => $updated,
        'results' => $results
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}