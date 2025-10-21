<?php
/**
 * Sidebar Widget: Popular Software (by Downloads/Views)
 * Menampilkan software populer
 *
 * Usage: include 'includes/widgets/widget_popular_software.php';
 */
// Query untuk mendapatkan popular software
try {
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.slug, p.featured_image, p.created_at,
               p.file_size, p.version, p.platform,
               COALESCE(p.download_count, 0) as downloads,
               COALESCE(p.view_count, 0) as views,
               c.name as category_name, c.slug as category_slug
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE ((p.status = 'published' OR p.status IS NULL) OR p.status IS NULL)
         
          AND p.post_type = 'software'
        ORDER BY p.download_count DESC, p.view_count DESC
        LIMIT 5
    ");
    $stmt->execute();
    $popularSoftware = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $popularSoftware = [];
}
// Jika ada software untuk ditampilkan
if (!empty($popularSoftware)):
?>
<!-- Popular Software Widget -->
<div class="sidebar-widget widget-popular-software">
    <h3 class="widget-title">Software Populer</h3>
    <div class="widget-content">
        <?php foreach ($popularSoftware as $software): ?>
        <div class="popular-software-item">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($software['slug']) ?>">
                <img width="300" height="180" style="aspect-ratio: 5/3; object-fit: cover;" loading="lazy" decoding="async" src="<?= !empty($software['featured_image']) ? htmlspecialchars($software['featured_image']) : SITE_URL . '/assets/images/default-software.png' ?>"
                     alt="<?= htmlspecialchars($software['title']) ?>"
                     class="software-thumb"
                     loading="lazy">
                <div class="software-info">
                    <h4><?= htmlspecialchars($software['title']) ?></h4>
                    <div class="software-meta">
                        <?php if (!empty($software['version'])): ?>
                        <span class="version-badge"><?= htmlspecialchars($software['version']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($software['file_size'])): ?>
                        <span class="size-badge">[<?= htmlspecialchars($software['file_size']) ?>]</span>
                        <?php endif; ?>
                        <?php if ($software['downloads'] > 0): ?>
                        <span class="download-badge"><?= number_format($software['downloads']) ?></span>
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
<div class="sidebar-widget widget-popular-software">
    <h3 class="widget-title">Software Populer</h3>
    <div class="widget-empty">
        <div class="icon">💿</div>
        <p>Belum ada software populer</p>
    </div>
</div>
<?php endif; ?>