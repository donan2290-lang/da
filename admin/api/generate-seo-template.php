<?php

define('ADMIN_ACCESS', true);
require_once '../../config_modern.php';
require_once '../../includes/seo_content_template.php';
require_once '../../includes/seo_heading_helper.php';
// Load additional templates if exist
if (file_exists('../../includes/seo_game_template.php')) {
    require_once '../../includes/seo_game_template.php';
}
if (file_exists('../../includes/seo_mobile_apps_template.php')) {
    require_once '../../includes/seo_mobile_apps_template.php';
}
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$title = $input['title'] ?? '';
$postType = $input['post_type'] ?? 'software';
$version = $input['version'] ?? '';
$developer = $input['developer'] ?? '';
$fileSize = $input['file_size'] ?? '';
$categorySlug = $input['category_slug'] ?? '';
// Validate
if (empty($title)) {
    http_response_code(400);
    echo json_encode(['error' => 'Title is required']);
    exit;
}
// Extract software/game name
$softwareName = preg_replace('/^(download|free|gratis|full|version)/i', '', $title);
$softwareName = trim($softwareName);
// Default features based on category
$defaultFeatures = [];
if (stripos($postType, 'mobile-apps') !== false || stripos($postType, 'mobile') !== false || stripos($title, 'apk') !== false) {
    // Mobile Apps features
    $defaultFeatures = [
        'Interface user-friendly dan modern',
        'Performa cepat dan ringan di semua device',
        'Keamanan data dengan enkripsi end-to-end',
        'Cloud sync untuk backup otomatis',
        'Support offline mode untuk beberapa fitur',
        'Update berkala dengan fitur baru',
        'Gratis download di Play Store & App Store'
    ];
} elseif (stripos($categorySlug, 'adobe') !== false || stripos($title, 'adobe') !== false) {
    $defaultFeatures = [
        'AI-powered Neural Filters untuk editing cerdas',
        'Cloud Document Sync - akses file dari mana saja',
        'Advanced Layer Management dengan Smart Objects',
        'Content-Aware Fill untuk hapus objek secara otomatis',
        'Camera RAW support untuk edit foto profesional',
        'Batch processing untuk edit multiple files',
        'Integration dengan Adobe Creative Cloud'
    ];
} elseif (stripos($categorySlug, 'microsoft') !== false || stripos($title, 'office') !== false || stripos($title, 'windows') !== false) {
    $defaultFeatures = [
        'Interface modern dan user-friendly',
        'Support berbagai format file (docx, xlsx, pptx)',
        'Cloud integration dengan OneDrive',
        'Real-time collaboration untuk kerja tim',
        'Template profesional siap pakai',
        'Advanced formulas dan macros',
        'Compatibility dengan semua versi Office'
    ];
} elseif (stripos($postType, 'game') !== false) {
    $defaultFeatures = [
        'Graphics HD dengan efek visual memukau',
        'Gameplay smooth dengan FPS tinggi',
        'Multiple game modes (Single/Multiplayer)',
        'Storyline menarik dan challenging',
        'Customizable characters dan weapons',
        'Regular updates dengan konten baru',
        'Support controller dan keyboard'
    ];
} else {
    // Generic software features
    $defaultFeatures = [
        'Interface yang mudah digunakan',
        'Performa cepat dan stabil',
        'Support berbagai format file',
        'Regular updates dan bug fixes',
        'Kompatibel dengan Windows 10/11',
        'Lightweight dan tidak berat',
        'Free lifetime updates'
    ];
}
// Default system requirements
$defaultRequirements = [
    'os' => 'Windows 10/11 (64-bit)',
    'processor' => 'Intel Core i3 atau AMD equivalent',
    'ram' => '4GB minimum, 8GB recommended',
    'storage' => '500MB - 2GB available space',
    'graphics' => 'DirectX 11 compatible',
    'additional' => 'Internet connection untuk aktivasi'
];
// For games, use higher requirements
if (stripos($postType, 'game') !== false) {
    $defaultRequirements = [
        'os' => 'Windows 10/11 (64-bit)',
        'processor' => 'Intel Core i5 atau AMD Ryzen 5',
        'ram' => '8GB minimum, 16GB recommended',
        'storage' => '50GB - 100GB available space (SSD recommended)',
        'graphics' => 'NVIDIA GTX 1050 Ti / AMD RX 560 (4GB VRAM)',
        'directx' => 'Version 12',
        'additional' => 'Internet connection untuk multiplayer'
    ];
}
// Default description
$defaultDescription = "**{$softwareName}** adalah software/aplikasi yang powerful dan mudah digunakan untuk membantu pekerjaan Anda lebih efisien. Dengan berbagai fitur canggih dan interface yang user-friendly, {$softwareName} menjadi pilihan terbaik untuk kebutuhan Anda.";
if (stripos($postType, 'game') !== false) {
    $defaultDescription = "**{$softwareName}** adalah game yang seru dan menantang dengan graphics HD yang memukau. Nikmati gameplay yang smooth, storyline yang menarik, dan berbagai mode permainan yang tidak akan membuat Anda bosan!";
}
// Prepare data for template
$templateData = [
    'software_name' => $softwareName,
    'title' => $title,
    'version' => $version,
    'developer' => $developer,
    'file_size' => $fileSize,
    'description' => $defaultDescription,
    'features' => $defaultFeatures,
    'requirements' => $defaultRequirements,
    'screenshots' => [] // Will be added manually
];
// Generate content based on post type
if (stripos($postType, 'blog') !== false || stripos($postType, 'tutorial') !== false || stripos($postType, 'guide') !== false) {
    // BLOG/TUTORIAL CONTENT
    // Extract topic from title
    $topic = preg_replace('/(cara|tutorial|panduan|tips|review|download|gratis|full|version|2025)/i', '', $title);
    $topic = trim($topic);
    // Determine difficulty based on title
    $difficulty = 'Pemula'; // Default
    if (stripos($title, 'advanced') !== false || stripos($title, 'professional') !== false || stripos($title, 'expert') !== false) {
        $difficulty = 'Mahir';
    } elseif (stripos($title, 'intermediate') !== false || stripos($title, 'menengah') !== false) {
        $difficulty = 'Menengah';
    }
    // Estimate duration
    $duration = '15 menit';
    if (stripos($title, 'lengkap') !== false || stripos($title, 'complete') !== false || stripos($title, 'comprehensive') !== false) {
        $duration = '25 menit';
    }
    // Prepare blog data
    $blogData = [
        'title' => $title,
        'topic' => $topic,
        'category' => stripos($postType, 'tutorial') !== false ? 'Tutorial' : (stripos($postType, 'guide') !== false ? 'Panduan' : 'Blog'),
        'difficulty' => $difficulty,
        'duration' => $duration
    ];
    $content = generateBlogContentTemplate($blogData);
} elseif (stripos($postType, 'mobile-apps') !== false || stripos($postType, 'mobile') !== false || stripos($title, 'apk') !== false) {
    // MOBILE APPS CONTENT
    // Extract app name
    $appName = preg_replace('/(download|apk|gratis|free|latest|terbaru|mod)/i', '', $title);
    $appName = trim($appName);
    // Detect category
    $appCategory = 'Social Media';
    if (stripos($title, 'whatsapp') !== false || stripos($title, 'telegram') !== false || stripos($title, 'facebook') !== false) {
        $appCategory = 'Communication';
    } elseif (stripos($title, 'instagram') !== false || stripos($title, 'tiktok') !== false || stripos($title, 'twitter') !== false) {
        $appCategory = 'Social Media';
    } elseif (stripos($title, 'shopee') !== false || stripos($title, 'tokopedia') !== false || stripos($title, 'lazada') !== false) {
        $appCategory = 'Shopping';
    } elseif (stripos($title, 'gojek') !== false || stripos($title, 'grab') !== false) {
        $appCategory = 'Transportation';
    } elseif (stripos($title, 'spotify') !== false || stripos($title, 'youtube music') !== false) {
        $appCategory = 'Music & Audio';
    } elseif (stripos($title, 'netflix') !== false || stripos($title, 'disney') !== false) {
        $appCategory = 'Entertainment';
    } elseif (stripos($title, 'canva') !== false || stripos($title, 'picsart') !== false) {
        $appCategory = 'Photo & Video';
    } elseif (stripos($title, 'zoom') !== false || stripos($title, 'teams') !== false) {
        $appCategory = 'Business';
    }
    // Detect platform
    $appPlatform = 'Android, iOS';
    if (stripos($title, 'apk') !== false && stripos($title, 'ios') === false) {
        $appPlatform = 'Android';
    } elseif (stripos($title, 'ios') !== false && stripos($title, 'android') === false) {
        $appPlatform = 'iOS';
    }
    // Estimate file size
    $estimatedSize = '50 MB';
    if (stripos($title, 'lite') !== false) {
        $estimatedSize = '20 MB';
    } elseif (stripos($title, 'game') !== false) {
        $estimatedSize = '500 MB';
    }
    $mobileAppData = [
        'title' => $title,
        'app_name' => $appName,
        'version' => $version ?: 'Latest Version',
        'developer' => $developer ?: 'Unknown Developer',
        'file_size' => $fileSize ?: $estimatedSize,
        'platform' => $appPlatform,
        'category' => $appCategory,
        'rating' => '4.5',
        'downloads' => '100M+',
        'requires_android' => 'Android 5.0+',
        'requires_ios' => 'iOS 12.0+',
        'language' => 'English, Indonesian, Multi-language'
    ];
    if (function_exists('generateMobileAppsContentTemplate')) {
        $content = generateMobileAppsContentTemplate($mobileAppData);
    } else {
        // Fallback to software template
        $content = generateSoftwareContentTemplate($templateData);
    }
} elseif (stripos($postType, 'game') !== false) {
    // GAME CONTENT
    // Extract genre from title or category
    $genre = 'Action, Adventure';
    if (stripos($title, 'rpg') !== false) $genre = 'RPG, Role-Playing';
    elseif (stripos($title, 'racing') !== false) $genre = 'Racing, Simulation';
    elseif (stripos($title, 'strategy') !== false) $genre = 'Strategy, Tactics';
    elseif (stripos($title, 'sport') !== false) $genre = 'Sports, Simulation';
    elseif (stripos($title, 'shooter') !== false || stripos($title, 'fps') !== false) $genre = 'FPS, Shooter';
    elseif (stripos($title, 'horror') !== false) $genre = 'Horror, Survival';
    // Determine platform
    $platform = 'PC';
    if (stripos($title, 'android') !== false || stripos($categorySlug, 'android') !== false) $platform = 'Android';
    elseif (stripos($title, 'ios') !== false) $platform = 'iOS';
    elseif (stripos($title, 'ps4') !== false || stripos($title, 'playstation') !== false) $platform = 'PlayStation 4/5';
    elseif (stripos($title, 'xbox') !== false) $platform = 'Xbox One/Series';
    $gameData = [
        'title' => $title,
        'genre' => $genre,
        'platform' => $platform,
        'developer' => $developer ?: 'Various Developers',
        'publisher' => $developer ?: 'Various Publishers',
        'release_date' => date('Y'),
        'file_size' => $fileSize ?: '5 GB',
        'version' => $version ?: 'Latest',
        'language' => 'English, Indonesian, Multi-language',
        'mode' => 'Single Player, Multiplayer'
    ];
    if (function_exists('generateGameContentTemplate')) {
        $content = generateGameContentTemplate($gameData);
    } else {
        // Fallback to software template if game template not found
        $content = generateSoftwareContentTemplate($templateData);
    }
} else {
    // SOFTWARE CONTENT (default)
    $content = generateSoftwareContentTemplate($templateData);
}
// Generate SEO slug
$seoSlug = generateSEOSlug($title);
// Validate slug
$slugValidation = validateSEOSlug($seoSlug);
// Generate H1
$seoH1 = generateSEOH1($title, $postType, $version);
// Count headings
preg_match_all('/<h2/', $content, $h2Matches);
preg_match_all('/<h3/', $content, $h3Matches);
// Response
$response = [
    'success' => true,
    'content' => $content,
    'seo_h1' => $seoH1,
    'suggested_slug' => $seoSlug,
    'slug_validation' => $slugValidation,
    'stats' => [
        'h2_count' => count($h2Matches[0]),
        'h3_count' => count($h3Matches[0]),
        'word_count' => str_word_count(strip_tags($content)),
        'char_count' => strlen(strip_tags($content))
    ],
    'message' => 'SEO template generated successfully!',
    'tips' => [
        'Upload featured image (1200x630px recommended)',
        'Add real screenshots di section "Screenshot / Preview"',
        'Update system requirements sesuai software',
        'Add more features if needed',
        'Proofread dan customize content'
    ]
];
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);