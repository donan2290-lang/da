<?php
/**
 * Category Icons Grid Component
 * Menampilkan grid kategori dengan icon
 *
 * Usage: include 'includes/category_icons_grid.php';
 */
// Query untuk mendapatkan semua kategori software dengan jumlah post
try {
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, c.slug, c.icon_url, c.color_code,
               COUNT(p.id) as post_count
        FROM categories c
        LEFT JOIN posts p ON c.id = p.category_id
            AND ((p.status = 'published' OR p.status IS NULL) OR p.status IS NULL)
           
        WHERE c.deleted_at IS NULL
        GROUP BY c.id
        HAVING post_count > 0
        ORDER BY post_count DESC
        LIMIT 16
    ");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}
// Jika ada kategori untuk ditampilkan
if (!empty($categories)):
?>
<!-- Category Icons Grid -->
<section class="category-icons-section">
    <div class="container">
        <h2 class="section-title">Kategori Software</h2>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="<?= SITE_URL ?>/category/<?= htmlspecialchars($cat['slug']) ?>"
               class="category-item"
               style="<?= !empty($cat['color_code']) ? 'border-color: ' . htmlspecialchars($cat['color_code']) . ';' : '' ?>">
                <?php
                // Cek apakah ada icon_url, jika tidak gunakan default
                $iconUrl = !empty($cat['icon_url']) ? htmlspecialchars($cat['icon_url']) : SITE_URL . '/assets/images/icons/default.png';
                ?>
                <img width="300" height="180" style="aspect-ratio: 5/3; object-fit: cover;" decoding="async" loading="lazy" src="<?= $iconUrl ?>"
                     alt="<?= htmlspecialchars($cat['name']) ?> Icon"
                     loading="lazy">
                <span class="cat-name"><?= htmlspecialchars($cat['name']) ?></span>
                <span class="cat-count"><?= $cat['post_count'] ?> items</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>