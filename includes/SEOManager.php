<?php
class SEOManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function trackView($postId) {
        if (!isset($_SESSION['viewed_posts'])) {
            $_SESSION['viewed_posts'] = [];
        }
        if (in_array($postId, $_SESSION['viewed_posts'])) {
            return false; // Already viewed
        }
        // Increment view count
        $stmt = $this->pdo->prepare("
            UPDATE posts
            SET view_count = view_count + 1
            WHERE id = ?
        ");
        $stmt->execute([$postId]);
        // Mark as viewed
        $_SESSION['viewed_posts'][] = $postId;
        return true;
    }
    
    public function trackDownload($postId) {
        $stmt = $this->pdo->prepare("
            UPDATE posts
            SET download_count = download_count + 1
            WHERE id = ?
        ");
        $stmt->execute([$postId]);
        // Log download
        $stmt = $this->pdo->prepare("
            INSERT INTO download_logs (post_id, ip_address, user_agent, downloaded_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([
            $postId,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
        return true;
    }
    public function generateBreadcrumbSchema($breadcrumbs) {
        $items = [];
        $position = 1;
        foreach ($breadcrumbs as $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $crumb['name'],
                'item' => $crumb['url']
            ];
        }
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    public function generateImageAlt($post) {
        $alt = $post['title'];
        if (!empty($post['version'])) {
            $alt .= ' ' . $post['version'];
        }
        if (!empty($post['platform'])) {
            $alt .= ' ' . $post['platform'];
        }
        $alt .= ' - Download Gratis';
        return $alt;
    }
}