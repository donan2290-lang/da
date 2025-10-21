<?php
/**
 * URL Helper Functions untuk Pretty URLs
 * File: includes/url_helpers.php
 */

/**
 * Generate URL untuk post
 * @param string $slug - Post slug
 * @return string - Full URL
 */
function getPostUrl($slug) {
    return SITE_URL . '/post/' . $slug;
}

/**
 * Generate URL untuk category
 * @param string $slug - Category slug
 * @return string - Full URL
 */
function getCategoryUrl($slug) {
    return SITE_URL . '/category/' . $slug;
}

/**
 * Generate URL untuk download
 * @param int $postId - Post ID
 * @param int $fileId - File ID
 * @param string $token - Download token (optional)
 * @return string - Full URL
 */
function getDownloadUrl($postId, $fileId, $token = '') {
    $url = SITE_URL . '/download.php?post=' . $postId . '&file=' . $fileId;
    if ($token) {
        $url .= '&token=' . $token;
    }
    return $url;
}

/**
 * Convert old URL format to new pretty URL
 * @param string $url - Old URL with query string
 * @return string - New pretty URL
 */
function convertToPrettyUrl($url) {
    // Convert post.php?slug=xxx to /post/xxx
    if (preg_match('/post\.php\?slug=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return str_replace($matches[0], 'post/' . $matches[1], $url);
    }
    
    // Convert category.php?slug=xxx to /category/xxx
    if (preg_match('/category\.php\?slug=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return str_replace($matches[0], 'category/' . $matches[1], $url);
    }
    
    return $url;
}

/**
 * Get current page URL (pretty format)
 * @return string - Current URL
 */
function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Generate canonical URL untuk SEO
 * @param string $slug - Post/Category slug
 * @param string $type - 'post' atau 'category'
 * @return string - Canonical URL
 */
function getCanonicalUrl($slug, $type = 'post') {
    if ($type === 'category') {
        return getCategoryUrl($slug);
    }
    return getPostUrl($slug);
}

/**
 * Generate breadcrumb URL
 * @param array $items - Array of breadcrumb items [['title' => 'Home', 'url' => '/'], ...]
 * @return string - HTML breadcrumb
 */
function generateBreadcrumb($items) {
    $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    $count = count($items);
    
    foreach ($items as $index => $item) {
        $isLast = ($index === $count - 1);
        $html .= '<li class="breadcrumb-item' . ($isLast ? ' active' : '') . '">';
        
        if (!$isLast && isset($item['url'])) {
            $html .= '<a href="' . htmlspecialchars($item['url']) . '">' . htmlspecialchars($item['title']) . '</a>';
        } else {
            $html .= htmlspecialchars($item['title']);
        }
        
        $html .= '</li>';
    }
    
    $html .= '</ol></nav>';
    return $html;
}

/**
 * Sanitize URL slug
 * @param string $text - Text to convert to slug
 * @return string - Clean slug
 */
function sanitizeSlug($text) {
    // Convert to lowercase
    $text = strtolower($text);
    
    // Replace spaces with hyphens
    $text = str_replace(' ', '-', $text);
    
    // Remove special characters
    $text = preg_replace('/[^a-z0-9\-]/', '', $text);
    
    // Remove duplicate hyphens
    $text = preg_replace('/-+/', '-', $text);
    
    // Trim hyphens from ends
    $text = trim($text, '-');
    
    return $text;
}
?>
