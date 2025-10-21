<?php
class MonetizationManager {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    private function generateShortCode($length = 8) {
        do {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $stmt = $this->pdo->prepare("SELECT id FROM monetized_links WHERE short_code = ?");
            $stmt->execute([$code]);
        } while ($stmt->fetch());
        return $code;
    }
    public function trackEvent($link_id, $event_type = 'click', $monetizer_service = null) {
        // Get user info
        $user_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $referrer = $_SERVER['HTTP_REFERER'] ?? '';
        // Calculate revenue
        $revenue = 0;
        if ($monetizer_service) {
            $stmt = $this->pdo->prepare("
                SELECT cpm_rate FROM monetizer_config WHERE service_name = ?
            ");
            $stmt->execute([$monetizer_service]);
            $config = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($config && $event_type == 'click') {
                // CPM to per-click: $7 CPM = $0.007 per click
                $revenue = $config['cpm_rate'] / 1000;
            }
        }
        $stmt = $this->pdo->prepare("
            INSERT INTO monetization_stats
            (link_id, event_type, monetizer_service, user_ip, user_agent, referrer, revenue_earned)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $link_id,
            $event_type,
            $monetizer_service,
            $user_ip,
            $user_agent,
            $referrer,
            $revenue
        ]);
        if ($event_type == 'click') {
            $this->pdo->prepare("
                UPDATE monetized_links
                SET total_clicks = total_clicks + 1,
                    estimated_revenue = estimated_revenue + ?
                WHERE id = ?
            ")->execute([$revenue, $link_id]);
        } elseif ($event_type == 'download') {
            $this->pdo->prepare("
                UPDATE monetized_links
                SET total_downloads = total_downloads + 1
                WHERE id = ?
            ")->execute([$link_id]);
        }
        return $revenue;
    }
    
    public function getRevenueStats($period = 'today') {
        switch ($period) {
            case 'today':
                $where = "date = CURDATE()";
                break;
            case 'yesterday':
                $where = "date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                break;
            case 'week':
                $where = "date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                break;
            case 'month':
                $where = "date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                break;
            default:
                $where = "1=1";
        }
        $stmt = $this->pdo->query("
            SELECT
                SUM(total_clicks) as total_clicks,
                SUM(total_downloads) as total_downloads,
                SUM(total_revenue) as total_revenue,
                monetizer_service
            FROM revenue_daily
            WHERE {$where}
            GROUP BY monetizer_service
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLinksByPost($post_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM monetized_links 
            WHERE post_id = ? AND (is_active = 1 OR is_active IS NULL)
            ORDER BY created_at DESC
        ");
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLinkByCode($short_code) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM monetized_links 
            WHERE short_code = ? AND (is_active = 1 OR is_active IS NULL)
            LIMIT 1
        ");
        $stmt->execute([$short_code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}