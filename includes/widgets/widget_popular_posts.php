<?php
/**
 * Sidebar Widget: Popular Posts (by Comments)
 * Menampilkan postingan populer berdasarkan jumlah komentar
 *
 * Usage: include 'includes/widgets/widget_popular_posts.php';
 */
// Query untuk mendapatkan popular posts berdasarkan comments
try {
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.slug, p.featured_image, p.created_at,
               c.name as category_name, c.slug as category_slug,
               COUNT(cm.id) as comment_count
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN comments cm ON p.id = cm.post_id AND cm.status = 'approved'
        WHERE ((p.status = 'published' OR p.status IS NULL) OR p.status IS NULL)
         
        GROUP BY p.id
        ORDER BY comment_count DESC, p.view_count DESC
        LIMIT 5
    ");
    $stmt->execute();
    $popularPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $popularPosts = [];
}
// Jika ada posts untuk ditampilkan
if (!empty($popularPosts)):
?>
<!-- Popular Posts Widget -->
<div class="sidebar-widget widget-popular-posts">
    <h3 class="widget-title">Tulisan Populer</h3>
    <div class="widget-content">
        <?php foreach ($popularPosts as $post): ?>
        <div class="popular-post-item">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>">
                <img width="300" height="180" style="aspect-ratio: 5/3; object-fit: cover;" loading="lazy" decoding="async" src="<?= !empty($post['featured_image']) ? htmlspecialchars($post['featured_image']) : SITE_URL . '/assets/images/default.png' ?>"
                     alt="<?= htmlspecialchars($post['title']) ?>"
                     class="post-thumb"
                     loading="lazy">
                <div class="post-info">
                    <h4><?= htmlspecialchars($post['title']) ?></h4>
                    <div class="post-meta">
                        <?php if (!empty($post['category_name'])): ?>
                        <span class="category"><?= htmlspecialchars($post['category_name']) ?></span>
                        <?php endif; ?>
                        <span class="date"><?= date('d M Y', strtotime($post['created_at'])) ?></span>
                        <span class="comments"><?= $post['comment_count'] ?> Komentar</span>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<!-- Empty State -->
<div class="sidebar-widget widget-popular-posts">
    <h3 class="widget-title">Tulisan Populer</h3>
    <div class="widget-empty">
        <div class="icon">📝</div>
        <p>Belum ada postingan populer</p>
    </div>
</div>
<?php endif; ?>