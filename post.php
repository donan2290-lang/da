<?php

require_once 'config_modern.php';
require_once 'includes/tracking.php';
require_once 'includes/enhancements.php'; // ? NEW: Enhancement features
require_once 'includes/breadcrumb.php'; // ? NEW: Breadcrumb navigation
require_once 'includes/comments_handler.php'; // ? NEW: Comment system
require_once 'includes/MonetizationManager.php'; // ? NEW: Monetization system
require_once 'includes/seo_helpers.php'; // ? NEW: SEO Meta Tags Helper
require_once 'includes/seo_heading_helper.php'; // ? NEW: SEO Heading Structure Helper
require_once 'includes/seo_content_template.php'; // ? NEW: SEO Content Template Generator
handleCommentSubmission(); // ? NEW: Handle comment form POST


$postSlug = $_GET['slug'] ?? '';
if (empty($postSlug)) {
    header('Location: index.php');
    exit;
}
try {

    $stmt = $pdo->prepare("
        SELECT
            p.*,
            c.name as category_name,
            c.slug as category_slug,
            c2.name as secondary_category_name,
            c2.slug as secondary_category_slug,
            sd.version,
            sd.developer,
            sd.release_date,
            sd.file_size,
            sd.requirements,
            sd.language,
            sd.license,
            sd.homepage,
            sd.rating,
            sd.downloads_count,
            (SELECT COUNT(*) FROM comments WHERE post_id = p.id AND status = 'approved') as comment_count
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN categories c2 ON p.secondary_category_id = c2.id
        LEFT JOIN software_details sd ON p.id = sd.post_id
        WHERE p.slug = ? AND (p.status = 'published' OR p.status IS NULL)
    ");
    $stmt->execute([$postSlug]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$post) {
        header('HTTP/1.0 404 Not Found');
        include '404.php';
        exit;
    }

    trackPageView($post['id'], 'post');

    $post['view_count'] = getPostViewCount($post['id']);
    $post['download_count'] = getPostDownloadCount($post['id']);

    $post['views'] = $post['view_count'];

    $post['downloads_count'] = $post['download_count'];

    $stmt = $pdo->prepare("
        SELECT * FROM download_links
        WHERE post_id = ? AND (status = 'active' OR status IS NULL)
        ORDER BY is_primary DESC, id ASC
    ");
    $stmt->execute([$post['id']]);
    $downloadLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $monetization = new MonetizationManager($pdo);
    $monetizedLinks = $monetization->getLinksByPost($post['id']);

    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.slug, p.featured_image, p.excerpt,
               COALESCE(p.view_count, 0) as views,
               COALESCE(p.download_count, 0) as downloads_count,
               c.name as category_name
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.category_id = ? AND p.id != ? AND (p.status = 'published' OR p.status IS NULL)
        ORDER BY p.view_count DESC, p.created_at DESC
        LIMIT 8
    ");
    $stmt->execute([$post['category_id'], $post['id']]);
    $relatedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pageTitle = $post['meta_title'] ?: $post['title'] . ' - Download Gratis';
    $pageDescription = $post['meta_description'] ?: $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 155);

    $isSoftware = in_array(strtolower($post['post_type_name'] ?? ''), ['software', 'game', 'app', 'mobile app']);
    $isTutorial = in_array(strtolower($post['post_type_name'] ?? ''), ['tutorial', 'guide', 'tips']);
} catch (PDOException $e) {
    error_log("Database error in post.php: " . $e->getMessage());
    error_log("Query attempted for slug: " . $postSlug);
    
    // Force show error in development
    $showError = (defined('DEBUG_MODE') && DEBUG_MODE === true) || 
                 (getenv('DEBUG_MODE') === 'true') || 
                 (getenv('ENVIRONMENT') === 'development');
    
    if ($showError) {
        echo "<div style='background: #fee; border: 2px solid red; padding: 20px; margin: 20px; font-family: monospace;'>";
        echo "<h2 style='color: red;'>❌ Database Error in post.php</h2>";
        echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
        echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
        echo "<p><strong>Slug:</strong> " . htmlspecialchars($postSlug) . "</p>";
        echo "<hr>";
        echo "<h3>Stack Trace:</h3>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        echo "</div>";
        die();
    } else {
        header('HTTP/1.0 500 Internal Server Error');
        exit('Database error occurred. Please contact administrator.');
    }
}

function getPostTypeIcon($postType) {
    return match(strtolower($postType ?? '')) {
        'software' => 'fas fa-desktop',
        'game' => 'fas fa-gamepad',
        'mobile app' => 'fas fa-mobile-alt',
        'tutorial' => 'fas fa-graduation-cap',
        'guide' => 'fas fa-book',
        default => 'fas fa-file-alt'
    };
}
function getPostTypeColor($postType) {
    return match(strtolower($postType ?? '')) {
        'software' => '#3b82f6',
        'game' => '#ef4444',
        'mobile app' => '#10b981',
        'tutorial' => '#f59e0b',
        'guide' => '#8b5cf6',
        default => '#6b7280'
    };
}
?>
<!DOCTYPE html>
<html lang="id">
<head>

    <?php include_once __DIR__ . '/includes/propeller_ads.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/logo.png">
    <link rel="shortcut icon" type="image/png" href="<?= SITE_URL ?>/assets/images/logo.png">
    <link rel="apple-touch-icon" href="<?= SITE_URL ?>/assets/images/logo.png">

    <?php

    outputPostMetaTags($post, SITE_URL);
    ?>

    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Critical CSS - Bootstrap -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'"><noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"></noscript>

    <!-- Non-critical CSS - Load async -->
    <link href="<?= SITE_URL ?>/assets/css/responsive-scale.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="<?= SITE_URL ?>/assets/css/responsive-scale.min.css" rel="stylesheet"></noscript>

    <!-- Font Awesome - Load async -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"></noscript>

    <!-- Google Fonts - Load with display=swap -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php if ($isTutorial): ?>

    <link href="<?= SITE_URL ?>/assets/css/tutorial-style.css" rel="stylesheet">
    <?php endif; ?>
    <style>:root{--primary-color:#3b82f6;--secondary-color:#1e40af;--accent-color:#f59e0b;--success-color:#10b981;--warning-color:#f59e0b;--danger-color:#ef4444;--dark-color:#1f2937;--light-color:#f8fafc;--border-color:#e5e7eb;}body{font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background-color:var(--light-color);line-height:1.6;}.main-header{background:linear-gradient(135deg,#4f6bebff 0%,#7a4aaaff 100%);box-shadow:0 4px 20px rgba(102,126,234,0.4);position:sticky;top:0;z-index:1000;}.navbar-brand{font-weight:700;color:#ffffff !important;text-shadow:0 2px 4px rgba(0,0,0,0.1);font-size:1.75rem;letter-spacing:-0.5px;display:flex;align-items:center;gap:0.75rem;}.navbar-brand img{height:45px;width:auto;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));}.navbar-brand .brand-icon{font-size:2.5rem;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;filter:drop-shadow(0 2px 4px rgba(251,191,36,0.3));}.navbar-brand .brand-text{font-size:1.75rem;font-weight:800;letter-spacing:-1px;}.navbar-brand:hover{color:#f0f9ff !important;transform:scale(1.05);transition:all 0.3s ease;}.navbar-brand:hover .brand-icon{filter:drop-shadow(0 4px 8px rgba(251,191,36,0.5));transform:rotate(10deg);transition:all 0.3s ease;}.nav-link{font-weight:500;color:#ffffff !important;transition:all 0.3s ease;padding:0.5rem 1rem !important;border-radius:6px;position:relative;}.nav-link:hover{background:rgba(255,255,255,0.15);color:#ffffff !important;transform:translateY(-2px);}.nav-link.active{background:rgba(255,255,255,0.2);font-weight:600;}.navbar-toggler{border-color:rgba(255,255,255,0.3);}.navbar-toggler-icon{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255,255,255,1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");}.live-search-container{max-width:400px;}.live-search-results{position:absolute;top:100%;left:0;right:0;background:white;border-radius:12px;box-shadow:0 15px 35px rgba(0,0,0,0.15);z-index:1000;max-height:400px;overflow-y:auto;display:none;border:1px solid #e5e7eb;}.live-search-results.show{display:block;animation:slideDown 0.3s ease;}@keyframes slideDown{from{opacity:0;transform:translateY(-10px);}to{opacity:1;transform:translateY(0);}}.live-search-item{padding:12px 15px;border-bottom:1px solid #f1f5f9;transition:all 0.2s ease;cursor:pointer;text-decoration:none;color:inherit;display:flex;align-items:center;}.live-search-item:hover{background:#f8fafc;color:inherit;transform:translateX(3px);}.live-search-item:last-child{border-bottom:none;}.live-search-icon{width:35px;height:35px;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px;font-size:1rem;}.live-search-content h6{margin:0 0 3px 0;font-size:0.9rem;font-weight:600;color:var(--dark-color);}.live-search-content p{margin:0;font-size:0.75rem;color:var(--muted-color);}.loading-spinner{display:inline-block;width:16px;height:16px;border:2px solid #f3f3f3;border-top:2px solid var(--primary-color);border-radius:50%;animation:spin 1s linear infinite;}@keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}.featured-image-wrapper{position:relative;z-index:2;animation:fadeIn 0.5s ease-in;}.featured-image{display:block !important;visibility:visible !important;opacity:1 !important;border:3px solid rgba(255,255,255,0.3);box-shadow:0 10px 30px rgba(0,0,0,0.3);transition:transform 0.3s ease;}.featured-image:hover{transform:scale(1.02);}@keyframes fadeIn{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}.post-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 50%,#f093fb 100%);color:white;padding:clamp(2.25rem,5vw,3rem) 0;position:relative;overflow:hidden;}.post-header::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:linear-gradient(45deg,rgba(59,130,246,0.1) 0%,rgba(147,51,234,0.1) 100%);z-index:1;}.post-header-content{position:relative;z-index:2;}.post-title{font-size:clamp(2rem,4vw,2.4rem);font-weight:700;margin-bottom:clamp(0.75rem,2vw,1rem);text-shadow:0 2px 4px rgba(0,0,0,0.1);}.post-meta{display:flex;flex-wrap:wrap;gap:clamp(0.9rem,3vw,1.35rem);align-items:center;margin-bottom:clamp(1rem,3vw,1.4rem);}.meta-item{display:flex;align-items:center;gap:clamp(0.35rem,2vw,0.5rem);padding:clamp(0.4rem,2vw,0.6rem) clamp(0.75rem,3vw,1rem);background:rgba(255,255,255,0.15);border-radius:25px;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.2);}.breadcrumb{background:white;padding:clamp(0.6rem,2vw,0.9rem) 0;margin:0;}.breadcrumb-item + .breadcrumb-item::before{content:"�";color:#6b7280;}.post-content-wrapper{background:white;border-radius:16px;padding:clamp(1.8rem,4.5vw,2.6rem);margin-top:clamp(1.4rem,4vw,2rem);box-shadow:0 4px 16px rgba(0,0,0,0.1);}.post-content{font-size:clamp(1rem,2.5vw,1.08rem);line-height:1.8;color:#374151;}.post-content img{max-width:100%;height:auto;border-radius:12px;margin:clamp(1.3rem,4vw,1.8rem) auto;display:block;box-shadow:0 4px 12px rgba(0,0,0,0.1);}.post-content h1,.post-content h2,.post-content h3,.post-content h4,.post-content h5,.post-content h6{color:var(--dark-color);margin-top:clamp(1.5rem,4vw,1.9rem);margin-bottom:clamp(0.75rem,2.5vw,1rem);font-weight:600;}.post-content p{margin-bottom:clamp(1rem,3vw,1.35rem);}.post-content ul,.post-content ol{margin-bottom:clamp(1rem,3vw,1.35rem);padding-left:clamp(1.5rem,4vw,1.85rem);}.post-content li{margin-bottom:0.5rem;}.download-section{background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:clamp(1.6rem,4vw,2.2rem);border-radius:16px;margin:clamp(1.4rem,4vw,1.9rem) 0;}.download-section h3{color:white;margin-bottom:1rem;}.download-section p{color:rgba(255,255,255,0.9);}.download-item{background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.1);}.download-item h5{color:#1f2937;font-size:1.1rem;}.download-item .text-muted{color:#6b7280 !important;}.download-item .text-danger{color:#dc2626 !important;}.download-item code{background:#fee2e2;color:#dc2626;padding:2px 8px;border-radius:4px;font-weight:600;}.download-btn{background:white;color:var(--success-color);padding:clamp(0.75rem,3vw,0.95rem) clamp(1.25rem,5vw,1.8rem);border-radius:50px;text-decoration:none;font-weight:600;font-size:clamp(1rem,2.5vw,1.05rem);display:inline-flex;align-items:center;gap:clamp(0.6rem,2.5vw,0.75rem);transition:all 0.3s ease;border:none;margin:clamp(0.35rem,2vw,0.5rem);}.download-btn:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,0.2);color:var(--success-color);}.software-specs{background:#f8fafc;border-radius:12px;padding:clamp(1.4rem,4vw,1.9rem);margin:clamp(1.3rem,4vw,1.8rem) 0;}.spec-item{display:flex;justify-content:space-between;padding:clamp(0.55rem,2vw,0.75rem) 0;border-bottom:1px solid #e5e7eb;}.spec-item:last-child{border-bottom:none;}.spec-label{font-weight:600;color:var(--dark-color);}.spec-value{color:#6b7280;}.tutorial-navigation{background:#fef3c7;border-left:4px solid var(--warning-color);padding:clamp(1.1rem,3vw,1.4rem);margin:clamp(1.3rem,4vw,1.8rem) 0;border-radius:0 12px 12px 0;}.related-posts{background:white;border-radius:16px;padding:clamp(1.5rem,4vw,2rem);margin-top:clamp(1.4rem,4vw,1.9rem);}.related-posts h4{margin-bottom:clamp(1rem,3vw,1.35rem);color:var(--dark-color);font-weight:600;}.related-posts-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;}@media (max-width:991px){.related-posts-grid{grid-template-columns:repeat(3,1fr);}}@media (max-width:768px){.related-posts-grid{grid-template-columns:repeat(2,1fr);}}.related-post-card{display:flex;flex-direction:column;border-radius:8px;text-decoration:none;color:inherit;transition:all 0.3s ease;overflow:hidden;background:white;border:1px solid #e5e7eb;box-shadow:0 1px 4px rgba(0,0,0,0.05);}.related-post-card:hover{transform:translateY(-4px);box-shadow:0 8px 20px rgba(0,0,0,0.12);color:inherit;border-color:var(--primary-color);}.related-post-image{width:100%;height:100px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;color:white;font-size:1.5rem;overflow:hidden;position:relative;}.related-post-image img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:2;}.related-post-image i{font-size:1.5rem;opacity:0.9;z-index:1;}.related-post-content{padding:0.5rem;flex:1;display:flex;flex-direction:column;}.related-post-title{font-weight:600;margin-bottom:0;font-size:0.75rem;line-height:1.3;display:-webkit-box;line-clamp:2;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;color:var(--dark-color);}.related-post-meta{font-size:0.65rem;color:#6b7280;margin-top:auto;padding-top:0.3rem;border-top:1px solid #f3f4f6;}.sidebar{background:white;border-radius:16px;padding:clamp(1.4rem,4vw,1.9rem);margin-bottom:clamp(1.3rem,4vw,1.8rem);box-shadow:0 4px 16px rgba(0,0,0,0.1);}.sidebar h5{color:var(--dark-color);font-weight:600;margin-bottom:clamp(1rem,3vw,1.35rem);padding-bottom:clamp(0.6rem,2vw,0.75rem);border-bottom:2px solid var(--primary-color);}.stats-item{display:flex;align-items:center;justify-content:space-between;padding:clamp(0.6rem,2.5vw,0.8rem) clamp(0.75rem,3vw,1rem);background:#f8fafc;border-radius:8px;margin-bottom:clamp(0.55rem,2vw,0.75rem);}.stats-icon{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:1rem;}@media (max-width:991px){.navbar-brand{font-size:1.5rem;}.navbar-brand img{height:38px;}.navbar-brand .brand-icon{font-size:2rem;}.nav-link{padding:0.75rem 1rem !important;}.navbar-collapse{background:rgba(79,107,235,0.95);margin-top:15px;padding:15px;border-radius:12px;}}@media (max-width:575px){.navbar-brand{font-size:1.3rem;}.navbar-brand img{height:32px;}.navbar-brand .brand-text{font-size:1.3rem;}}@media (max-width:991px){.post-title{font-size:1.9rem;}.post-content-wrapper{padding:2rem 1.5rem;}.sidebar{margin-top:2rem;}}@media (max-width:767px){.post-title{font-size:1.6rem;line-height:1.3;}.post-content-wrapper{padding:1.5rem;border-radius:12px;}.post-meta{flex-direction:column;align-items:flex-start;gap:8px;}.download-section{padding:1.5rem;border-radius:12px;}}@media (max-width:575px){.post-title{font-size:1.4rem;}.post-content-wrapper{padding:1rem;margin-bottom:1rem;}.download-section{padding:1rem;}.related-post-image{height:100px;}.related-post-title{font-size:0.8rem;}.related-post-meta{font-size:0.6rem;}.sidebar{padding:1rem;margin-bottom:1rem;}.stats-item{padding:0.6rem 0.75rem;}.stats-icon{width:35px;height:35px;margin-right:0.75rem;}}.post-content table{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;}@media (max-width:767px){.post-content table{font-size:0.85rem;}.post-content table th,.post-content table td{padding:0.5rem !important;white-space:nowrap;}}.post-content img{max-width:100%;height:auto;border-radius:8px;}@media (max-width:575px){.post-content img{border-radius:6px;}}@media (max-width:575px){.btn-download{width:100%;padding:0.75rem 1rem;font-size:0.9rem;}.download-section .btn{margin-bottom:0.5rem;}}@media (max-width:767px){.post-content h2{font-size:1.4rem;}.post-content h3{font-size:1.2rem;}.post-content h4{font-size:1.1rem;}.post-content{font-size:0.95rem;line-height:1.7;}}@media (max-width:575px){.post-content h2{font-size:1.25rem;}.post-content h3{font-size:1.1rem;}.post-content h4{font-size:1rem;}.post-content{font-size:0.9rem;}.post-content p{margin-bottom:1rem;}.post-content ul,.post-content ol{padding-left:1.25rem;}}@media (max-width:575px){.container{padding-left:10px;padding-right:10px;}main{padding-top:1rem !important;padding-bottom:1rem !important;}}@media (max-width:575px){.breadcrumb{font-size:0.8rem;padding:0.5rem 0;}.breadcrumb-item + .breadcrumb-item::before{font-size:0.75rem;}}</style>

    <?php renderEnhancementStyles(); ?>
</head>
<body>

    <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <a class="navbar-brand" href="<?= SITE_URL ?>/">



                    <i class="fas fa-rocket brand-icon"></i>
                    <span class="brand-text">DONAN22</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/"><i class="fas fa-home me-1"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/category/software"><i class="fas fa-download me-1"></i> Software</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/category/blog"><i class="fas fa-graduation-cap me-1"></i> Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/category/mobile-apps"><i class="fas fa-mobile-alt me-1"></i> Mobile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/category/windows-software"><i class="fab fa-windows me-1"></i> Windows</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/category/mac-software"><i class="fab fa-apple me-1"></i> Mac</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/categories.php"><i class="fas fa-th-large me-1"></i> Kategori</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container py-2">
            <form action="<?= SITE_URL ?>/search.php" method="GET" id="searchForm" class="live-search-container position-relative" style="max-width: 400px; margin: 0 auto;">
                <input
                    type="search"
                    name="q"
                    class="form-control form-control-sm"
                    id="live-search-input"
                    placeholder="Cari software..."
                    autocomplete="off"
                    style="padding-right: 35px; border-radius: 20px;"
                >
                <button class="btn btn-sm position-absolute" type="submit" id="searchButton" style="right: 5px; top: 50%; transform: translateY(-50%); border: none; background: transparent;">
                    <i class="fas fa-search text-primary"></i>
                </button>

                <div class="live-search-results" id="live-search-results"></div>
            </form>
        </div>
    </header>

    <div class="container">
        <?php
        echo breadcrumbStyles();
        echo renderBreadcrumb(getPostBreadcrumb($post));
        ?>
    </div>

    <section class="post-header">
        <div class="container">
            <div class="post-header-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">

                        <h1 class="post-title">
                            <?= generateSEOH1($post['title'], $post['post_type_slug'] ?? 'software', $post['version'] ?? null) ?>
                        </h1>
                        <div class="post-meta">
                            <div class="meta-item">
                                <i class="<?= getPostTypeIcon($post['post_type_name'] ?? '') ?>"></i>
                                <span><?= htmlspecialchars($post['post_type_name'] ?? 'Post') ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span><?= date('d F Y', strtotime($post['created_at'])) ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-eye"></i>
                                <span><?= number_format($post['views']) ?> views</span>
                            </div>
                            <?php if ($post['downloads_count']): ?>
                            <div class="meta-item">
                                <i class="fas fa-download"></i>
                                <span><?= number_format($post['downloads_count']) ?> downloads</span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php
                        $tagsStmt = $pdo->prepare("SELECT t.* FROM tags t INNER JOIN post_tags pt ON t.id = pt.tag_id WHERE pt.post_id = ? ORDER BY t.name");
                        $tagsStmt->execute([$post['id']]);
                        $postTags = $tagsStmt->fetchAll();
                        if (!empty($postTags)):
                        ?>
                        <div class="post-tags mt-3 mb-2">
                            <i class="fas fa-tags me-2" style="color: #6b7280;"></i>
                            <?php foreach ($postTags as $tag): ?>
                            <a href="<?= SITE_URL ?>/tag/<?= urlencode($tag['slug']) ?>" class="badge bg-light text-dark me-1" style="text-decoration: none; font-weight: 500; padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb;">
                                <?= htmlspecialchars($tag['name']) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($post['excerpt']): ?>
                        <p class="lead" style="opacity: 0.9; font-size: 1.2rem;">
                            <?= htmlspecialchars($post['excerpt']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($post['featured_image'])): ?>
                    <div class="col-lg-4">
                        <?php

                        $imagePath = $post['featured_image'];

                        if (!preg_match('/^(https?:\/\/|\/)/', $imagePath)) {
                            $imagePath = SITE_URL . '/' . $imagePath;
                        }
                        ?>
                        <div class="featured-image-wrapper">
                            <img loading="lazy" decoding="async" src="<?= htmlspecialchars($imagePath) ?>"
                                 alt="<?= htmlspecialchars($post['title']) ?>"
                                 class="img-fluid rounded shadow-lg featured-image"
                                 style="width: 100%; height: auto; max-height: 400px; object-fit: cover;"
                                 onerror="console.error('Failed to load image:', this.src); this.onerror=null; this.style.opacity='0.3'; this.alt='Image not found';">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <?php if ($isTutorial): ?>

                <div class="col-lg-9">

                    <div class="tutorial-content">
                        <?php

                        $content = $post['content'];
                        $content = preg_replace_callback(
                            '/<img loading="lazy" decoding="async"([^>]+)src=[\'"]([^\'"]+)[\'"]([^>]*)>/i',
                            function($matches) {
                                $beforeSrc = $matches[1];
                                $src = $matches[2];
                                $afterSrc = $matches[3];

                                if (!preg_match('/^(https?:\/\/|\/)/', $src)) {

                                    $src = preg_replace('/^\.\.\//', '', $src);
                                    $src = SITE_URL . '/' . $src;
                                }
                                return '<img loading="lazy" decoding="async"' . $beforeSrc . 'src="' . $src . '"' . $afterSrc . '>';
                            },
                            $content
                        );

                        preg_match_all('/<h([2-4])([^>]*)>(.*?)<\/h[2-4]>/i', $content, $matches, PREG_SET_ORDER);
                        $tocIndex = 0;
                        foreach ($matches as $match) {
                            $level = $match[1];
                            $attributes = $match[2];
                            $text = $match[3];
                            $id = 'heading-' . $tocIndex;
                            if (strpos($attributes, 'id=') === false) {
                                $oldHeading = $match[0];
                                $newHeading = '<h' . $level . $attributes . ' id="' . $id . '">' . $text . '</h' . $level . '>';
                                $content = str_replace($oldHeading, $newHeading, $content);
                            }
                            $tocIndex++;
                        }
                        echo $content;
                        ?>
                    </div>
                </div>
                <div class="col-lg-3">

                    <div class="table-of-contents">
                        <div class="toc-title">
                            <i class="fas fa-list"></i>
                            CONTENTS
                        </div>

                    </div>

                    <div class="sidebar mt-3">
                        <h5><i class="fas fa-chart-bar me-2"></i>Statistik</h5>
                        <div class="stats-item">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <span>Views</span>
                            </div>
                            <strong><?= number_format($post['views']) ?></strong>
                        </div>
                        <div class="stats-item">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <span>Comments</span>
                            </div>
                            <strong><?= $post['comment_count'] ?></strong>
                        </div>
                    </div>
                </div>
            <?php else: ?>

            <div class="col-lg-8">

                <div class="post-content-wrapper">

                    <div class="post-content">
                        <?php

                        $content = $post['content'];

                        $content = preg_replace_callback(
                            '/<img loading="lazy" decoding="async"([^>]+)src=[\'"]([^\'"]+)[\'"]([^>]*)>/i',
                            function($matches) {
                                $beforeSrc = $matches[1];
                                $src = $matches[2];
                                $afterSrc = $matches[3];

                                if (!preg_match('/^(https?:\/\/|\/)/', $src)) {

                                    $src = preg_replace('/^\.\.\//', '', $src);
                                    $src = SITE_URL . '/' . $src;
                                }
                                return '<img loading="lazy" decoding="async"' . $beforeSrc . 'src="' . $src . '"' . $afterSrc . '>';
                            },
                            $content
                        );

                        preg_match_all('/<h([2-4])([^>]*)>(.*?)<\/h[2-4]>/i', $content, $matches, PREG_SET_ORDER);
                        $tocIndex = 0;
                        foreach ($matches as $match) {
                            $level = $match[1];
                            $attributes = $match[2];
                            $text = $match[3];
                            $id = 'heading-' . $tocIndex;
                            if (strpos($attributes, 'id=') === false) {
                                $oldHeading = $match[0];
                                $newHeading = '<h' . $level . $attributes . ' id="' . $id . '">' . $text . '</h' . $level . '>';
                                $content = str_replace($oldHeading, $newHeading, $content);
                            }
                            $tocIndex++;
                        }

                        $paragraphs = preg_split('/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
                        if (count($paragraphs) > 2) {
                            echo $paragraphs[0] . $paragraphs[1]; // First <p>...</p>

                            // In-content ad after first paragraph
                            if (function_exists('renderInContentAd')) {
                                echo renderInContentAd(1);
                            }

                            if (isset($post['post_type_slug']) && $post['post_type_slug'] !== 'blog') {
                            ?>
                            <a href="#download-section" class="fake-download-section my-4 p-4 text-decoration-none d-block" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.25)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)';" onclick="return handleFakeDownloadClick(event)">
                                <div class="text-center text-white">
                                    <div class="mb-3">
                                        <i class="fas fa-download" style="font-size: 3rem; animation: bounce 2s infinite;"></i>
                                    </div>
                                    <h4 class="fw-bold mb-2">
                                        <i class="fas fa-gift me-2"></i>Download Gratis Tersedia!
                                    </h4>
                                    <p class="mb-3" style="font-size: 1.1rem;">
                                        Klik tombol di bawah untuk mulai mengunduh file
                                    </p>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                        <span class="btn btn-light btn-lg px-5 py-3" style="border-radius: 50px; font-weight: bold; box-shadow: 0 4px 15px rgba(255,255,255,0.3);">
                                            <i class="fas fa-download me-2"></i>DOWNLOAD SEKARANG
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </span>
                                    </div>
                                    <small class="d-block mt-3" style="opacity: 0.9;">
                                        <i class="fas fa-shield-alt me-1"></i>100% Aman & Gratis
                                        <span class="mx-2">�</span>
                                        <i class="fas fa-bolt me-1"></i>Download Cepat
                                    </small>
                                </div>
                            </a>
                            <style>@keyframes bounce{0%,100%{transform:translateY(0);}50%{transform:translateY(-10px);}}.fake-download-section:active{transform:scale(0.98) !important;}</style>
                            <script>
                                let clickCount = 0;
                                let lastClickTime = 0;
                                function handleFakeDownloadClick(event) {
                                    clickCount++;
                                    const now = Date.now();
                                    console.log('?? Fake Download Button Clicked (#' + clickCount + ')');
                                    console.log('?? Time since last click: ' + (now - lastClickTime) + 'ms');

                                    const btn = event.target.closest('.fake-download-section');
                                    if (btn) {
                                        btn.style.transition = 'all 0.15s ease';
                                        btn.style.opacity = '0.7';
                                        btn.style.transform = 'scale(0.95)';

                                        setTimeout(() => {
                                            btn.style.opacity = '1';
                                            btn.style.transform = 'scale(1)';
                                        }, 150);
                                    }

                                    console.log('?? Triggering OnClick Popunder...');

                                    const externalScripts = document.querySelectorAll('script[src*="5gvci"], script[src*="ueuee"], script[src*="gizokraijaw"]');
                                    const allScripts = document.querySelectorAll('script[data-cfasync="false"]');
                                    console.log('?? External Monetag scripts:', externalScripts.length);
                                    console.log('?? Inline Monetag scripts:', allScripts.length);
                                    if (externalScripts.length > 0 || allScripts.length > 0) {
                                        console.log('? Monetag OnClick active - popunder will trigger on this click');
                                    } else {
                                        console.warn('?? No Monetag scripts detected - popunder may not work');
                                    }





                                    console.log('? OnClick Popunder triggered - ad will open in new tab');
                                    console.log('? User stays at current position (no auto-scroll)');
                                    console.log('?? User can scroll down manually for real download links');


                                    lastClickTime = now;
                                    event.preventDefault();
                                    return false;
                                }
                            </script>
                            <?php
                            } // End if post_type_slug check

                            for ($i = 2; $i < count($paragraphs); $i++) {
                                echo $paragraphs[$i];
                            }
                        } else {

                            echo $content;
                        }
                        ?>
                    </div>

                    <div class="my-4">
                        <?php
                        $currentUrl = SITE_URL . '/post/' . $post['slug'];
                        renderShareButtons(
                            $currentUrl,
                            $post['title'],
                            $post['excerpt'] ?? substr(strip_tags($post['content']), 0, 155)
                        );
                        ?>
                    </div>
                    <?php if (!empty($downloadLinks) || !empty($monetizedLinks)): ?>

                        <div id="download-section" class="download-section">
                            <h3><i class="fas fa-download me-2"></i>Download Gratis</h3>

                            <div class="mb-3">
                                <?php if (!empty($post['category_name'])): ?>
                                <span class="badge bg-primary me-2" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                    <i class="fas fa-folder me-1"></i>
                                    <?= htmlspecialchars($post['category_name']) ?>
                                </span>
                                <?php endif; ?>
                                <?php if (!empty($post['secondary_category_name'])): ?>
                                <span class="badge bg-info me-2" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                    <i class="fas fa-folder-plus me-1"></i>
                                    <?= htmlspecialchars($post['secondary_category_name']) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-3">Klik tombol download di bawah untuk mendapatkan file:</p>
                            <?php

                            if (!empty($monetizedLinks)):
                                foreach ($monetizedLinks as $link):
                            ?>
                                <div class="download-item mb-3 p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1">
                                                <i class="fas fa-file-archive text-primary"></i>
                                                <?= htmlspecialchars($link['download_title'] ?: 'Download File') ?>
                                            </h5>
                                            <?php if ($link['file_size']): ?>
                                            <small class="text-muted">
                                                <i class="fas fa-hdd"></i> Size: <?= htmlspecialchars($link['file_size']) ?>
                                            </small>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-mouse-pointer"></i> <?= number_format($link['total_clicks']) ?> clicks
                                                    <span class="ms-2">
                                                        <i class="fas fa-download"></i> <?= number_format($link['total_downloads']) ?> downloads
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <a href="<?= SITE_URL ?>/go/<?= htmlspecialchars($link['short_code']) ?>"
                                               class="btn btn-success btn-lg px-4"
                                               target="_blank">
                                                <i class="fas fa-download"></i> Download Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                endforeach;
                            endif;

                            if (empty($monetizedLinks) && !empty($downloadLinks)):
                                foreach ($downloadLinks as $link):
                            ?>
                                <a href="<?= htmlspecialchars($link['url']) ?>"
                                   class="download-btn"
                                   target="_blank"
                                   rel="nofollow">
                                    <i class="fas fa-download"></i>
                                    <?= htmlspecialchars($link['title'] ?: 'Download Now') ?>
                                </a>
                            <?php
                                endforeach;
                            endif;
                            ?>

                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i>
                                <strong>Note:</strong> Anda akan dialihkan ke halaman perantara sebelum download dimulai.
                                Mohon nonaktifkan ad-blocker untuk pengalaman terbaik.
                            </div>
                        </div>

                        <div class="row justify-content-center my-4">
                            <div class="col-lg-8 col-md-10">
                                <div class="card border-warning shadow-sm">
                                    <div class="card-header text-center" style="background-color: #fff3cd; border-bottom: 2px solid #ffc107; padding: 0.75rem;">
                                        <h6 class="mb-0" style="color: #dc3545; font-weight: 600;"><i class="fas fa-exclamation-triangle me-2"></i>Attention!</h6>
                                    </div>
                                    <div class="card-body text-center" style="background-color: #fffbf0; padding: 1.5rem;">
                                        <p class="mb-0" style="color: #333; font-size: 0.95rem; line-height: 1.6;">All software and games here are only for research or test base, not permanent use. If you like the software or game please support the developer. <strong>BUY IT!</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($isSoftware && ($post['version'] || $post['developer'] || $post['file_size'])): ?>

                        <div class="software-specs">
                            <h4><i class="fas fa-info-circle me-2"></i>Informasi Software</h4>
                            <?php if ($post['developer']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Developer</span>
                                <span class="spec-value"><?= htmlspecialchars($post['developer']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($post['version']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Version</span>
                                <span class="spec-value"><?= htmlspecialchars($post['version']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($post['file_size']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Size</span>
                                <span class="spec-value"><?= htmlspecialchars($post['file_size']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($post['requirements']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Requirements</span>
                                <span class="spec-value"><?= htmlspecialchars($post['requirements']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($post['language']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Language</span>
                                <span class="spec-value"><?= htmlspecialchars($post['language']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($post['license']): ?>
                            <div class="spec-item">
                                <span class="spec-label">License</span>
                                <span class="spec-value"><?= htmlspecialchars($post['license']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($isTutorial): ?>

                        <div class="tutorial-navigation">
                            <h5><i class="fas fa-lightbulb me-2"></i>Tutorial Guide</h5>
                            <p class="mb-0">Ikuti langkah-langkah di atas dengan cermat untuk hasil yang optimal. Jika mengalami kesulitan, silakan tinggalkan komentar di bawah.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($relatedPosts)): ?>
                <div class="related-posts">
                    <h4><i class="fas fa-layer-group me-2"></i>Artikel Terkait</h4>
                    <div class="related-posts-grid">
                    <?php foreach ($relatedPosts as $relatedPost):

                        $imageUrl = '';
                        if (!empty($relatedPost['featured_image'])) {
                            $imagePath = $relatedPost['featured_image'];

                            if (strpos($imagePath, 'http') === 0) {
                                $imageUrl = $imagePath;
                            } elseif (strpos($imagePath, '/uploads/') === 0) {
                                $imageUrl = SITE_URL . $imagePath;
                            } elseif (strpos($imagePath, 'uploads/') === 0) {
                                $imageUrl = SITE_URL . '/' . $imagePath;
                            } else {

                                $imageUrl = SITE_URL . '/uploads/' . basename($imagePath);
                            }
                        }

                        $categoryColors = [
                            'software' => '#667eea',
                            'tutorial' => '#764ba2',
                            'mobile' => '#f093fb',
                            'windows' => '#4facfe',
                            'mac' => '#43e97b',
                            'game' => '#fa709a'
                        ];
                        $categorySlug = strtolower($relatedPost['category_name'] ?? 'default');
                        $bgColor = $categoryColors[$categorySlug] ?? '#667eea';
                    ?>
                        <a href="<?= SITE_URL ?>/post/<?= urlencode($relatedPost['slug']) ?>" class="related-post-card" title="<?= htmlspecialchars($relatedPost['title']) ?>">
                            <div class="related-post-image" style="background: linear-gradient(135deg, <?= $bgColor ?> 0%, #764ba2 100%);">
                                <?php if ($imageUrl): ?>
                                    <img loading="lazy" decoding="async" src="<?= htmlspecialchars($imageUrl) ?>"
                                         alt="<?= htmlspecialchars($relatedPost['title']) ?>"
                                         loading="lazy"
                                         onerror="this.style.display='none';">
                                <?php endif; ?>

                                <i class="fas fa-file-alt" style="<?= $imageUrl ? 'display:none;' : '' ?>"></i>
                            </div>
                            <div class="related-post-content">
                                <div class="related-post-title">
                                    <?= htmlspecialchars($relatedPost['title']) ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">

                <div class="sidebar">
                    <h5>Statistik Post</h5>
                    <div class="stats-item">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary text-white">
                                <i class="fas fa-eye"></i>
                            </div>
                            <span>Views</span>
                        </div>
                        <strong><?= number_format($post['views']) ?></strong>
                    </div>
                    <?php if ($post['downloads_count']): ?>
                    <div class="stats-item">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-success text-white">
                                <i class="fas fa-download"></i>
                            </div>
                            <span>Downloads</span>
                        </div>
                        <strong><?= number_format($post['downloads_count']) ?></strong>
                    </div>
                    <?php endif; ?>
                    <div class="stats-item">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-warning text-white">
                                <i class="fas fa-comments"></i>
                            </div>
                            <span>Comments</span>
                        </div>
                        <strong><?= number_format($post['comment_count']) ?></strong>
                    </div>
                    <div class="stats-item">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-info text-white">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <span>Published</span>
                        </div>
                        <strong><?= date('M d, Y', strtotime($post['created_at'])) ?></strong>
                    </div>
                </div>

                <?php
                $avgRating = getAverageRating($post['id']);
                renderDownloadStats(
                    $post['views'],
                    $post['download_count'] ?? 0,
                    $avgRating
                );
                ?>

                <div class="sidebar">
                    <h5>Kategori</h5>
                    <a href="<?= SITE_URL ?>/category/<?= urlencode($post['category_slug']) ?>"
                       class="btn btn-outline-primary w-100">
                        <i class="fas fa-folder me-2"></i>
                        <?= htmlspecialchars($post['category_name']) ?>
                    </a>
                </div>

                <div class="sidebar">
                    <h5>Share</h5>
                    <div class="d-grid gap-2">
                        <a aria-label="Share on facebook" href="https://facebook.com
                           target="_blank" class="btn btn-primary btn-sm"">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                        <a aria-label="Share on twitter" href="https://twitter.com
                           target="_blank" class="btn btn-info btn-sm"">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                        <a href="https://wa.me/?text=<?= urlencode($post['title'] . ' ' . SITE_URL . '/post/' . $post['slug']) ?>"
                           target="_blank" class="btn btn-success btn-sm">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; // End Tutorial vs Regular layout ?>
        </div>
    </div>

    <div id="comments" class="container my-4">
        <div class="row">
            <div class="col-lg-<?= $isTutorial ? '9' : '8' ?>">
                <h4 class="mb-3">
                    <i class="fas fa-comments me-2"></i>
                    Komentar & Rating
                </h4>
                <?php
                $ratingData = getAverageRating($post['id']);
                $distribution = getRatingDistribution($post['id']);
                ?>

                <?php if ($ratingData['count'] > 0): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <h3 class="mb-0"><?= number_format($ratingData['average'], 1) ?></h3>
                                <?= renderStarRating($ratingData['average'], 5, false) ?>
                                <p class="text-muted mt-1 mb-0">
                                    <small style="font-size: 12px;"><?= $ratingData['count'] ?> rating<?= $ratingData['count'] > 1 ? 's' : '' ?></small>
                                </p>
                            </div>
                            <div class="col-md-9">
                                <h6 class="mb-2" style="font-size: 14px;">Rating Distribution</h6>
                                <?php for ($i = 5; $i >= 1; $i--):
                                    $count = $distribution[$i] ?? 0;
                                    $percentage = $ratingData['count'] > 0 ? ($count / $ratingData['count'] * 100) : 0;
                                ?>
                                <div class="mb-1">
                                    <div class="d-flex align-items-center" style="font-size: 13px;">
                                        <span class="me-2" style="min-width: 50px;"><?= $i ?> <i class="fas fa-star text-warning" style="font-size: 0.7em;"></i></span>
                                        <div class="progress flex-grow-1" style="height: 16px;">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                 style="width: <?= $percentage ?>%"
                                                 aria-valuenow="<?= $percentage ?>"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="ms-2 text-muted" style="min-width: 35px; font-size: 12px;"><?= $count ?></span>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card mb-3 shadow-sm">
                    <div class="card-body p-3">
                        <?= renderCommentForm($post['id']) ?>
                    </div>
                </div>

                <div class="comments-list">
                    <?= renderCommentsList($post['id']) ?>
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if (document.querySelector('.glightbox')) {
                const lightbox = GLightbox({
                    touchNavigation: true,
                    loop: true,
                    autoplayVideos: true
                });
            }

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            document.querySelectorAll('.copy-link').forEach(button => {
                button.addEventListener('click', function() {
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        this.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-link me-2"></i>Copy Link';
                        }, 2000);
                    });
                });
            });


            const allDownloadButtons = document.querySelectorAll('.download-btn, a.btn-success[href*="/go/"], a.btn-success[href*="go.php"]');
            allDownloadButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const finalUrl = this.href;
                    console.log('?? Download Now button clicked!');
                    console.log('?? Opening in new tab:', finalUrl);

                    <?php if (isset($post['id'])): ?>
                    fetch('../track_download.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            post_id: <?= $post['id'] ?>,
                            link_url: finalUrl
                        })
                    }).catch(err => console.log('Tracking failed'));
                    <?php endif; ?>
                    console.log('? Download page will open in new tab');

                });
            });
            console.log('? Download tracking configured for', allDownloadButtons.length, 'download buttons');
        });
    </script>

    <script defer src="<?= SITE_URL ?>/assets/js/live-search.js"></script>
    <?php if ($isTutorial): ?>

    <script defer src="<?= SITE_URL ?>/assets/js/tutorial.js"></script>
    <?php endif; ?>

    <?php renderEnhancementScripts(); ?>
</body>
</html>