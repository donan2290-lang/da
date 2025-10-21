<?php
// Ensure clean output
if (ob_get_level()) ob_end_clean();
ob_start();

// Set proper encoding
mb_http_output('UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Type: application/json; charset=utf-8');

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Custom error handler to prevent HTML error pages
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Ensure session doesn't write until we're done
session_write_close();

// Set proper encoding
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

define('ADMIN_ACCESS', true);
require_once '../config_modern.php';
require_once '../includes/enhancements.php';
require_once '../includes/SEOContentGenerator.php';
requireLogin();

// Clear any BOM and set JSON header
header('Content-Type: application/json; charset=utf-8');
// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Remove BOM if present
ob_start(function($output) {
    return str_replace("\xEF\xBB\xBF", '', $output);
});

$requestMethod = $_SERVER['REQUEST_METHOD'];
// Get action from POST or GET
$action = $_REQUEST['action'] ?? '';
// Only certain actions allow GET method
$allowedGetActions = ['tags_get_post', 'tags_search'];
if ($requestMethod === 'GET' && !in_array($action, $allowedGetActions)) {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed for this action']);
    exit;
}
// Ensure we're actually getting a request
if (empty($action)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No action specified']);
    exit;
}

try {
    // Clear any existing output
    if (ob_get_length()) ob_clean();
    
    switch ($action) {
        // SEO CONTENT GENERATOR (NEW!)
        case 'generate_seo_content':
            $generator = new SEOContentGenerator($pdo);
            $data = [
                'post_type' => $_POST['post_type'] ?? 'software',
                'title' => $_POST['title'] ?? '',
                'category' => $_POST['category'] ?? '',
                'version' => $_POST['version'] ?? '',
                'platform' => $_POST['platform'] ?? '',
                'file_size' => $_POST['file_size'] ?? ''
            ];
            $result = $generator->generateContent($data);
            echo json_encode($result);
            break;
        // SEO SCORE CALCULATOR
        case 'calculate_seo':
            if (!function_exists('calculateSEOScore')) {
                throw new Exception('SEO calculation function not available');
            }
            
            // Get POST data, defaulting to empty strings
            $post = [
                'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING) ?? '',
                'content' => filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW) ?? '',
                'meta_description' => filter_input(INPUT_POST, 'meta_description', FILTER_SANITIZE_STRING) ?? '',
                'focus_keyword' => filter_input(INPUT_POST, 'focus_keyword', FILTER_SANITIZE_STRING) ?? ''
            ];
            
            // Validate required fields
            if (empty($post['title']) || empty($post['content'])) {
                throw new Exception('Title and content are required for SEO calculation');
            }
            
            // Calculate SEO score
            $result = calculateSEOScore($post);
            
            // Validate result format
            if (!is_array($result) || !isset($result['score']) || !isset($result['issues'])) {
                throw new Exception('Invalid SEO calculation result format');
            }
            
            // Clear any existing output and return fresh JSON
            if (ob_get_length()) ob_clean();
            
            echo json_encode([
                'success' => true,
                'score' => $result['score'],
                'issues' => $result['issues']
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            break;
        // GET POST TAGS (for editing)
        case 'tags_get_post':
            $post_id = (int)($_REQUEST['post_id'] ?? 0);
            if (!$post_id) {
                throw new Exception('Invalid post ID');
            }
            $stmt = $pdo->prepare("
                SELECT t.id, t.name, t.slug
                FROM tags t
                INNER JOIN post_tags pt ON t.id = pt.tag_id
                WHERE pt.post_id = ?
                ORDER BY t.name ASC
            ");
            $stmt->execute([$post_id]);
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'tags' => $tags]);
            break;
        // TAG SUGGESTIONS (Auto-complete)
        case 'tags_search':
            $query = $_REQUEST['query'] ?? $_REQUEST['q'] ?? '';
            if (strlen($query) < 2) {
                echo json_encode(['success' => true, 'tags' => []]);
                exit;
            }
            $stmt = $pdo->prepare("
                SELECT id, name, slug, COUNT(*) as usage_count
                FROM tags
                WHERE name LIKE ? OR slug LIKE ?
                GROUP BY id, name, slug
                ORDER BY usage_count DESC, name ASC
                LIMIT 10
            ");
            $stmt->execute(["%{$query}%", "%{$query}%"]);
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'tags' => $tags]);
            break;
        // SAVE POST TAGS
        case 'tags_save_post':
            $post_id = (int)($_POST['post_id'] ?? 0);
            $tag_ids = json_decode($_POST['tag_ids'] ?? '[]', true);
            if (!$post_id) {
                throw new Exception('Invalid post ID');
            }
            $stmt = $pdo->prepare("DELETE FROM post_tags WHERE post_id = ?");
            $stmt->execute([$post_id]);
            if (!empty($tag_ids)) {
                $insertStmt = $pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                foreach ($tag_ids as $tag_id) {
                    // If tag_id is a string (new tag), create it first
                    if (!is_numeric($tag_id)) {
                        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tag_id)));
                        $checkStmt = $pdo->prepare("SELECT id FROM tags WHERE slug = ?");
                        $checkStmt->execute([$slug]);
                        $existing = $checkStmt->fetch();
                        if ($existing) {
                            $tag_id = $existing['id'];
                        } else {
                            $createStmt = $pdo->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
                            $createStmt->execute([$tag_id, $slug]);
                            $tag_id = $pdo->lastInsertId();
                        }
                    }
                    $insertStmt->execute([$post_id, $tag_id]);
                }
            }
            echo json_encode(['success' => true, 'message' => 'Tags saved successfully']);
            break;
        // DEFAULT: Invalid action
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    // Clear any output that might have been generated
    if (ob_get_level()) ob_end_clean();
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'file' => basename($e->getFile()),
            'line' => $e->getLine(),
            'type' => get_class($e)
        ]
    ]);
} finally {
    // Ensure we end any remaining output buffer
    while (ob_get_level()) ob_end_clean();
}