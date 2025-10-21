<?php

// Site Information
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'DONAN22');
}
if (!defined('SITE_URL')) {
    // Use environment variable or default to production
    $siteUrl = getenv('SITE_URL') ?: 'https://donan22.com';
    define('SITE_URL', $siteUrl);
}
// IndexNow API Configuration (for Bing/Yandex instant indexing)
// Generate your key at: https://www.indexnow.org/
define('INDEXNOW_API_KEY', 'your-indexnow-api-key-here');
// Google Analytics / Tag Manager
define('GOOGLE_ANALYTICS_ID', 'G-XXXXXXXXXX'); // Replace with your GA4 ID
define('GOOGLE_TAG_MANAGER_ID', 'GTM-XXXXXXX'); // Replace with your GTM ID
// Social Media
define('FACEBOOK_APP_ID', ''); // Optional: For Facebook social sharing
define('TWITTER_HANDLE', '@donan22'); // Your Twitter handle
// SEO Defaults
define('DEFAULT_META_DESCRIPTION', 'Download gratis software, games, dan mobile apps terbaru. Dapatkan berbagai aplikasi premium, tools, dan permainan terlengkap di DONAN22.');
define('DEFAULT_META_KEYWORDS', 'download, gratis, free, software, games, mobile apps, windows, android, terbaru, 2025');
define('DEFAULT_OG_IMAGE', SITE_URL . '/assets/images/og-default.jpg');
// Sitemap Settings
define('SITEMAP_POSTS_PER_PAGE', 1000); // Max URLs per sitemap
define('SITEMAP_AUTO_GENERATE', true); // Auto-generate on post publish
// Performance Settings
define('ENABLE_IMAGE_LAZY_LOADING', true);
define('ENABLE_CSS_MINIFICATION', false);
define('ENABLE_JS_MINIFICATION', false);
// Schema.org Settings
define('ORGANIZATION_NAME', 'DONAN22');
define('ORGANIZATION_LOGO', SITE_URL . '/assets/images/logo.png');
define('ORGANIZATION_URL', SITE_URL);
// Robots.txt Rules
define('ALLOW_SEARCH_ENGINES', true);
define('DISALLOW_ADMIN', true); // Disallow crawling /admin/
// Structured Data
define('ENABLE_BREADCRUMBS_SCHEMA', true);
define('ENABLE_ARTICLE_SCHEMA', true);
define('ENABLE_SOFTWARE_SCHEMA', true);
// Tracking
define('ENABLE_VIEW_TRACKING', true);
define('ENABLE_DOWNLOAD_TRACKING', true);
return [
    'site_name' => SITE_NAME,
    'site_url' => SITE_URL,
    'indexnow_key' => INDEXNOW_API_KEY,
    'ga_id' => GOOGLE_ANALYTICS_ID,
    'gtm_id' => GOOGLE_TAG_MANAGER_ID,
    'default_meta' => [
        'description' => DEFAULT_META_DESCRIPTION,
        'keywords' => DEFAULT_META_KEYWORDS,
        'og_image' => DEFAULT_OG_IMAGE
    ],
    'social' => [
        'facebook_app_id' => FACEBOOK_APP_ID,
        'twitter' => TWITTER_HANDLE
    ],
    'sitemap' => [
        'posts_per_page' => SITEMAP_POSTS_PER_PAGE,
        'auto_generate' => SITEMAP_AUTO_GENERATE
    ],
    'performance' => [
        'lazy_loading' => ENABLE_IMAGE_LAZY_LOADING,
        'css_minify' => ENABLE_CSS_MINIFICATION,
        'js_minify' => ENABLE_JS_MINIFICATION
    ],
    'schema' => [
        'organization_name' => ORGANIZATION_NAME,
        'organization_logo' => ORGANIZATION_LOGO,
        'organization_url' => ORGANIZATION_URL
    ],
    'robots' => [
        'allow_search_engines' => ALLOW_SEARCH_ENGINES,
        'disallow_admin' => DISALLOW_ADMIN
    ],
    'structured_data' => [
        'breadcrumbs' => ENABLE_BREADCRUMBS_SCHEMA,
        'article' => ENABLE_ARTICLE_SCHEMA,
        'software' => ENABLE_SOFTWARE_SCHEMA
    ],
    'tracking' => [
        'views' => ENABLE_VIEW_TRACKING,
        'downloads' => ENABLE_DOWNLOAD_TRACKING
    ]
];