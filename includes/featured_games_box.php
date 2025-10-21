<?php
/**
 * Featured Games Box Component
 * Menampilkan 10 games terbaru dalam grid layout
 *
 * Usage: include 'includes/featured_games_box.php';
 */
// Query untuk mendapatkan 10 games terbaru
try {
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.slug, p.excerpt, p.created_at, p.featured_image,
               p.file_size, p.version, p.platform,
               c.name as category_name, c.slug as category_slug,
               pt.name as post_type_name, pt.slug as post_type_slug
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN post_types pt ON p.post_type_id = pt.id
        WHERE (p.status = 'published' OR p.status IS NULL)
         
          AND (
              pt.slug = 'games'
              OR c.slug LIKE '%game%'
              OR c.slug LIKE '%gaming%'
              OR p.title LIKE '%game%'
          )
        ORDER BY p.created_at DESC
        LIMIT 10
    ");
    $stmt->execute();
    $featuredGames = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featuredGames = [];
}
// Jika ada games untuk ditampilkan
if (!empty($featuredGames)):
?>
<!-- Featured Games Box -->
<section class="featured-games-box">
    <div class="container">
        <h2 class="section-title">
            New Games
        </h2>
        <div class="games-grid">
            <?php foreach ($featuredGames as $game): ?>
            <div class="game-card">
                <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($game['slug']) ?>">
                    <img width="300" height="180" style="aspect-ratio: 5/3; object-fit: cover;" decoding="async" loading="lazy" 
                         src="<?= !empty($game['featured_image']) ? htmlspecialchars($game['featured_image']) : SITE_URL . '/assets/images/default-game.png' ?>"
                         alt="<?= htmlspecialchars($game['title']) ?>"
                         width="300" height="180"
                         style="aspect-ratio: 5/3; object-fit: cover;">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($game['title']) ?></h3>
                        <div class="badges">
                            <?php if (!empty($game['file_size'])): ?>
                            <span class="badge-size">[<?= htmlspecialchars($game['file_size']) ?>]</span>
                            <?php endif; ?>
                            <?php if (!empty($game['version'])): ?>
                            <span class="badge-version"><?= htmlspecialchars($game['version']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($game['platform'])): ?>
                            <span class="badge-platform">(<?= htmlspecialchars($game['platform']) ?>)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>