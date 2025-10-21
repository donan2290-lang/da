<?php
/**
 * Sidebar Widget: Popular Games (by Views)
 * Menampilkan games populer
 *
 * Usage: include 'includes/widgets/widget_popular_games.php';
 */
// Query untuk mendapatkan popular games
try {
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.slug, p.featured_image, p.created_at,
               p.file_size, p.version, p.platform,
               COALESCE(p.view_count, 0) as views,
               c.name as category_name, c.slug as category_slug
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE ((p.status = 'published' OR p.status IS NULL) OR p.status IS NULL)
         
          AND p.post_type = 'games'
        ORDER BY p.view_count DESC
        LIMIT 5
    ");
    $stmt->execute();
    $popularGames = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $popularGames = [];
}
// Jika ada games untuk ditampilkan
if (!empty($popularGames)):
?>
<!-- Popular Games Widget -->
<div class="sidebar-widget widget-popular-games">
    <h3 class="widget-title">Games Terpopuler</h3>
    <div class="widget-content">
        <?php foreach ($popularGames as $game): ?>
        <div class="popular-game-item">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($game['slug']) ?>">
                <img width="300" height="180" style="aspect-ratio: 5/3; object-fit: cover;" loading="lazy" decoding="async" src="<?= !empty($game['featured_image']) ? htmlspecialchars($game['featured_image']) : SITE_URL . '/assets/images/default-game.png' ?>"
                     alt="<?= htmlspecialchars($game['title']) ?>"
                     class="game-cover"
                     loading="lazy">
                <div class="game-info">
                    <h4><?= htmlspecialchars($game['title']) ?></h4>
                    <div class="game-meta">
                        <?php if (!empty($game['file_size'])): ?>
                        <span class="size-badge">[<?= htmlspecialchars($game['file_size']) ?>]</span>
                        <?php endif; ?>
                        <?php if (!empty($game['platform'])): ?>
                        <span class="platform-badge"><?= htmlspecialchars($game['platform']) ?></span>
                        <?php endif; ?>
                        <?php if ($game['views'] > 0): ?>
                        <span class="views-badge"><?= number_format($game['views']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<!-- Empty State -->
<div class="sidebar-widget widget-popular-games">
    <h3 class="widget-title">Games Terpopuler</h3>
    <div class="widget-empty">
        <div class="icon">🎮</div>
        <p>Belum ada games populer</p>
    </div>
</div>
<?php endif; ?>