<?php
/**
 * Featured Software Box Component
 * Menampilkan 10 software terbaru dalam horizontal scroll
 *
 * Usage: include 'includes/featured_software_box.php';
 */
// Query untuk mendapatkan 10 software terbaru
try {
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.slug, p.excerpt, p.created_at, p.featured_image,
               p.file_size, p.version, p.platform,
               c.name as category_name, c.slug as category_slug,
               pt.name as post_type_name, pt.slug as post_type_slug
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN post_types pt ON p.post_type_id = pt.id
        WHERE ((p.status = 'published' OR p.status IS NULL) OR p.status IS NULL)
         
          AND (
              pt.slug = 'software'
              OR c.slug IN ('windows-software', 'mac-software', 'adobe', 'microsoft-office', 'activator', 'video-editors', 'tool-utilities')
          )
        ORDER BY p.updated_at DESC
        LIMIT 10
    ");
    $stmt->execute();
    $featuredSoftware = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featuredSoftware = [];
}
// Jika ada software untuk ditampilkan
if (!empty($featuredSoftware)):
?>
<!-- Featured Software Box -->
<section class="featured-software-box">
    <div class="container">
        <h2 class="section-title">
            <span class="emoji">🔥</span> Update Software
        </h2>
        <div class="software-slider">
            <?php foreach ($featuredSoftware as $software): ?>
            <div class="software-card">
                <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($software['slug']) ?>">
                    <img width="300" height="180" style="aspect-ratio: 5/3; object-fit: cover;" decoding="async" loading="lazy" 
                         src="<?= !empty($software['featured_image']) ? htmlspecialchars($software['featured_image']) : SITE_URL . '/assets/images/default-software.png' ?>"
                         alt="<?= htmlspecialchars($software['title']) ?>"
                         width="300" height="180"
                         style="aspect-ratio: 5/3; object-fit: cover;">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($software['title']) ?></h3>
                        <div class="badges">
                            <?php if (!empty($software['version'])): ?>
                            <span class="badge-version"><?= htmlspecialchars($software['version']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($software['file_size'])): ?>
                            <span class="badge-size">[<?= htmlspecialchars($software['file_size']) ?>]</span>
                            <?php endif; ?>
                            <?php if (!empty($software['platform'])): ?>
                            <span class="badge-platform">(<?= htmlspecialchars($software['platform']) ?>)</span>
                            <?php endif; ?>
                            <?php if (!empty($software['category_name'])): ?>
                            <span class="badge-category"><?= htmlspecialchars($software['category_name']) ?></span>
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