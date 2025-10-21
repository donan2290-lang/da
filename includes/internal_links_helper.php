<?php

function getRelatedPostsByCategory($pdo, $postId, $categoryId, $limit = 5) {
    try {
        $stmt = $pdo->prepare("
            SELECT p.id, p.title, p.slug, p.excerpt, p.featured_image,
                   c.name as category_name, c.slug as category_slug,
                   pt.slug as post_type_slug,
                   COALESCE(p.view_count, 0) as views
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN post_types pt ON p.post_type_id = pt.id
            WHERE p.category_id = :category_id
            AND p.id != :post_id
            AND ((p.status = 'published' OR p.status IS NULL) OR p.status IS NULL)
           
            ORDER BY p.view_count DESC, p.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function getRelatedPostsByKeywords($pdo, $postId, $title, $limit = 5) {
    $keywords = extractKeywords($title);
    if (empty($keywords)) {
        return [];
    }
    try {
        $keywordPattern = '%' . implode('%', $keywords) . '%';
        $stmt = $pdo->prepare("
            SELECT p.id, p.title, p.slug, p.excerpt, p.featured_image,
                   c.name as category_name, c.slug as category_slug,
                   pt.slug as post_type_slug
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN post_types pt ON p.post_type_id = pt.id
            WHERE p.id != :post_id
            AND (p.title LIKE :keywords OR p.excerpt LIKE :keywords)
            AND ((p.status = 'published' OR p.status IS NULL) OR p.status IS NULL)
           
            ORDER BY p.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':keywords', $keywordPattern, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function getPopularPosts($pdo, $limit = 10) {
    try {
        $stmt = $pdo->prepare("
            SELECT p.id, p.title, p.slug, p.featured_image,
                   c.name as category_name, c.slug as category_slug,
                   COALESCE(p.view_count, 0) as views
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE ((p.status = 'published' OR p.status IS NULL) OR p.status IS NULL)
           
            ORDER BY p.view_count DESC, p.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function generateAnchorText($post) {
    $title = $post['title'];
    $category = $post['category_name'] ?? '';
    $postType = $post['post_type_slug'] ?? '';
    
    $templates = [
        'software' => [
            'Download {title} Full Version',
            'Download {title} Gratis',
            '{title} Terbaru',
            'Cara Install {title}',
            '{title} Full Crack'
        ],
        'games' => [
            'Download Game {title}',
            '{title} Full Version',
            'Download {title} Gratis',
            '{title} PC Game',
            'Main {title} Offline'
        ],
        'tutorial' => [
            'Tutorial {title}',
            'Cara {title}',
            'Panduan {title}',
            'Belajar {title}',
            '{title} Lengkap'
        ],
        'default' => [
            '{title}',
            'Baca {title}',
            'Lihat {title}',
            'Artikel {title}'
        ]
    ];
    $templateKey = 'default';
    if (stripos($postType, 'software') !== false || stripos($category, 'software') !== false) {
        $templateKey = 'software';
    } elseif (stripos($postType, 'game') !== false || stripos($category, 'game') !== false) {
        $templateKey = 'games';
    } elseif (stripos($postType, 'tutorial') !== false || stripos($category, 'tutorial') !== false) {
        $templateKey = 'tutorial';
    }
    
    $template = $templates[$templateKey][array_rand($templates[$templateKey])];
    $shortTitle = strlen($title) > 60 ? substr($title, 0, 57) . '...' : $title;
    $anchorText = str_replace('{title}', $shortTitle, $template);
    return $anchorText;
}

function extractKeywords($title) {
    $stopWords = ['download', 'gratis', 'full', 'version', 'terbaru', 'cara', 'dan', 'untuk', 'di', 'ke', 'dari', 'yang'];
    $words = explode(' ', strtolower($title));
    $keywords = array_filter($words, function($word) use ($stopWords) {
        return strlen($word) > 3 && !in_array($word, $stopWords);
    });
    return array_slice($keywords, 0, 3);
}

function renderRelatedPosts($relatedPosts, $title = 'Artikel Terkait') {
    if (empty($relatedPosts)) {
        return '';
    }
    $html = '
<section class="related-posts-section">
    <h2 class="section-title">
        <i class="fas fa-link"></i> ' . htmlspecialchars($title) . '
    </h2>
    <div class="related-posts-grid">';
    foreach ($relatedPosts as $post) {
        $postUrl = SITE_URL . '/post/' . $post['slug'];
        $anchorText = generateAnchorText($post);
        $image = $post['featured_image'] ?? SITE_URL . '/assets/images/default-post.png';
        
        if (strpos($image, 'http') !== 0) {
            $image = SITE_URL . '/' . ltrim($image, '/');
        }
        $html .= '
        <article class="related-post-card">
            <a href="' . htmlspecialchars($postUrl) . '" class="related-post-link">
                <div class="related-post-image">
                    <img width="300" height="180" style="aspect-ratio: 5/3; object-fit: cover;" decoding="async" src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($post['title']) . '" loading="lazy">
                    <div class="related-post-overlay">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="related-post-content">
                    <span class="related-post-category">' . htmlspecialchars($post['category_name'] ?? '') . '</span>
                    <h3 class="related-post-title">' . htmlspecialchars($anchorText) . '</h3>
                    ' . (!empty($post['views']) ? '<span class="related-post-views"><i class="fas fa-eye"></i> ' . number_format($post['views']) . ' views</span>' : '') . '
                </div>
            </a>
        </article>';
    }
    $html .= '
    </div>
</section>
<style>
.related-posts-section {
    margin: 50px 0;
    padding: 30px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 15px;
    border-left: 4px solid #3b82f6;
}
.related-posts-section .section-title {
    color: #1e293b;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.related-posts-section .section-title i {
    color: #3b82f6;
}
.related-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.related-post-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}
.related-post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
}
.related-post-link {
    text-decoration: none;
    color: inherit;
    display: block;
}
.related-post-image {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
    background: #e2e8f0;
}
.related-post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.related-post-card:hover .related-post-image img {
    transform: scale(1.1);
}
.related-post-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(59, 130, 246, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.related-post-card:hover .related-post-overlay {
    opacity: 1;
}
.related-post-overlay i {
    color: white;
    font-size: 2rem;
}
.related-post-content {
    padding: 15px;
}
.related-post-category {
    display: inline-block;
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}
.related-post-title {
    color: #1e293b;
    font-size: 1.05rem;
    font-weight: 600;
    line-height: 1.4;
    margin: 10px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.related-post-card:hover .related-post-title {
    color: #3b82f6;
}
.related-post-views {
    color: #64748b;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
}
.related-post-views i {
    color: #94a3b8;
}
@media (max-width: 768px) {
    .related-posts-grid {
        grid-template-columns: 1fr;
    }
    .related-posts-section {
        padding: 20px;
    }
    .related-posts-section .section-title {
        font-size: 1.5rem;
    }
}
</style>
';
    return $html;
}

function insertContextualLinks($content, $linkSuggestions) {
    if (empty($linkSuggestions)) {
        return $content;
    }
    foreach ($linkSuggestions as $suggestion) {
        $keyword = extractMainKeyword($suggestion['title']);
        $url = SITE_URL . '/post/' . $suggestion['slug'];
        $anchorText = generateAnchorText($suggestion);
        $linkHTML = '<a href="' . htmlspecialchars($url) . '" class="contextual-link" title="' . htmlspecialchars($suggestion['title']) . '">' . htmlspecialchars($anchorText) . '</a>';
        
        $pattern = '/\b' . preg_quote($keyword, '/') . '\b/i';
        $content = preg_replace($pattern, $linkHTML, $content, 1);
    }
    return $content;
}

function extractMainKeyword($title) {
    $title = preg_replace('/^(download|cara|tutorial|panduan)\s+/i', '', $title);
    $words = explode(' ', $title);
    $keyword = implode(' ', array_slice($words, 0, 3));
    return $keyword;
}
function getHomepageLinksStructure($pdo) {
    $structure = [];
    try {
        $stmt = $pdo->prepare("
            SELECT id, name, slug,
                   (SELECT COUNT(*) FROM posts WHERE category_id = c.id AND status = 'published') as post_count
            FROM categories c
            WHERE parent_id IS NULL
            ORDER BY post_count DESC
            LIMIT 10
        ");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categories as $category) {
            $stmt = $pdo->prepare("
                SELECT id, title, slug
                FROM posts
                WHERE category_id = :category_id
                AND status = 'published'
               
                ORDER BY view_count DESC
                LIMIT 5
            ");
            $stmt->bindParam(':category_id', $category['id'], PDO::PARAM_INT);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $structure[] = [
                'category' => $category,
                'posts' => $posts
            ];
        }
    } catch (PDOException $e) {
    }
    return $structure;
}

function renderHomepageLinks($structure) {
    if (empty($structure)) {
        return '';
    }
    $html = '
<section class="homepage-links">
    <h2 class="section-title">Kategori Populer</h2>
    <div class="categories-grid">';
    foreach ($structure as $item) {
        $category = $item['category'];
        $posts = $item['posts'];
        $categoryUrl = SITE_URL . '/category/' . $category['slug'];
        $html .= '
        <div class="category-box">
            <h3 class="category-title">
                <a href="' . htmlspecialchars($categoryUrl) . '">' . htmlspecialchars($category['name']) . '</a>
                <span class="post-count">(' . $category['post_count'] . ')</span>
            </h3>
            <ul class="category-posts-list">';
        foreach ($posts as $post) {
            $postUrl = SITE_URL . '/post/' . $post['slug'];
            $html .= '
                <li><a href="' . htmlspecialchars($postUrl) . '">' . htmlspecialchars($post['title']) . '</a></li>';
        }
        $html .= '
            </ul>
            <a href="' . htmlspecialchars($categoryUrl) . '" class="view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>';
    }
    $html .= '
    </div>
</section>
<style>
.homepage-links {
    margin: 50px 0;
}
.homepage-links .section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 30px;
    text-align: center;
}
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
}
.category-box {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #3b82f6;
    transition: all 0.3s ease;
}
.category-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.15);
}
.category-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 15px;
}
.category-title a {
    color: #1e40af;
    text-decoration: none;
}
.category-title a:hover {
    color: #3b82f6;
}
.post-count {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 400;
}
.category-posts-list {
    list-style: none;
    padding: 0;
    margin: 0 0 15px 0;
}
.category-posts-list li {
    margin-bottom: 10px;
    padding-left: 20px;
    position: relative;
}
.category-posts-list li:before {
    content: "→";
    position: absolute;
    left: 0;
    color: #3b82f6;
    font-weight: bold;
}
.category-posts-list a {
    color: #475569;
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.2s ease;
}
.category-posts-list a:hover {
    color: #3b82f6;
}
.view-all {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #3b82f6;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.9rem;
}
.view-all:hover {
    color: #1e40af;
}
@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
}
</style>
';
    return $html;
}