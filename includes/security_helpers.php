<?php
/**
 * Security Helper Functions
 * 
 * Collection of security-related helper functions for the application
 * 
 * @package Donan22
 * @version 1.0.0
 * @author Security Team
 * @date 2025-10-16
 */

// Prevent direct access
if (!defined('ADMIN_ACCESS') && !function_exists('getSettings')) {
    if (!file_exists(__DIR__ . '/../config_modern.php')) {
        die('Unauthorized access');
    }
}

/**
 * Safe Redirect Function
 * 
 * Prevents open redirect vulnerabilities by validating redirect URLs
 * 
 * @param string $url The URL to redirect to
 * @param bool $exit Whether to exit after redirect (default: true)
 * @return void
 */
function safeRedirect($url, $exit = true) {
    // List of allowed domains for redirect
    $allowed_domains = [
        'localhost',
        '127.0.0.1',
    ];
    
    // Add current site domain if SITE_URL is defined
    if (defined('SITE_URL')) {
        $site_host = parse_url(SITE_URL, PHP_URL_HOST);
        if ($site_host) {
            $allowed_domains[] = $site_host;
        }
    }
    
    // Check if URL is relative (safe)
    if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
        // Relative URL - safe to redirect
        header('Location: ' . $url);
        if ($exit) {
            exit;
        }
        return;
    }
    
    // Parse URL to check domain
    $parsed = parse_url($url);
    
    // If no host (relative URL), it's safe
    if (!isset($parsed['host'])) {
        header('Location: ' . $url);
        if ($exit) {
            exit;
        }
        return;
    }
    
    // Check if host is in whitelist
    if (in_array($parsed['host'], $allowed_domains)) {
        header('Location: ' . $url);
        if ($exit) {
            exit;
        }
        return;
    }
    
    // Not safe - redirect to homepage
    $fallback = defined('SITE_URL') ? SITE_URL : '/';
    header('Location: ' . $fallback);
    if ($exit) {
        exit;
    }
}

/**
 * Sanitize Filename
 * 
 * Removes dangerous characters from filenames to prevent path traversal
 * 
 * @param string $filename The filename to sanitize
 * @return string Sanitized filename
 */
function sanitizeFilename($filename) {
    // Remove any path components
    $filename = basename($filename);
    
    // Remove null bytes
    $filename = str_replace(chr(0), '', $filename);
    
    // Remove directory traversal attempts
    $filename = str_replace(['../', '..\\', './'], '', $filename);
    
    // Only allow alphanumeric, dash, underscore, and dot
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    
    return $filename;
}

/**
 * Validate File Path
 * 
 * Ensures file path is within allowed directory (prevents path traversal)
 * 
 * @param string $path The file path to validate
 * @param string $allowed_dir The allowed base directory
 * @return bool True if path is safe, false otherwise
 */
function validateFilePath($path, $allowed_dir) {
    // Get real paths
    $real_path = realpath($path);
    $real_allowed_dir = realpath($allowed_dir);
    
    // Check if paths are valid
    if ($real_path === false || $real_allowed_dir === false) {
        return false;
    }
    
    // Check if file is within allowed directory
    return strpos($real_path, $real_allowed_dir) === 0;
}

/**
 * Generate CSRF Token
 * 
 * Generates a secure CSRF token for form protection
 * 
 * @return string The generated CSRF token
 */
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Generate token if not exists or expired
    if (!isset($_SESSION['csrf_token']) || 
        !isset($_SESSION['csrf_token_time']) || 
        (time() - $_SESSION['csrf_token_time']) > 3600) { // 1 hour expiry
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 * 
 * Validates a CSRF token against the session token
 * 
 * @param string $token The token to verify
 * @return bool True if token is valid, false otherwise
 */
function verifyCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if token exists in session
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        return false;
    }
    
    // Check if token is expired (1 hour)
    if ((time() - $_SESSION['csrf_token_time']) > 3600) {
        return false;
    }
    
    // Verify token using timing-safe comparison
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize Input
 * 
 * Basic input sanitization for user data
 * 
 * @param mixed $data The data to sanitize
 * @param string $type The type of sanitization (string, int, email, url)
 * @return mixed Sanitized data
 */
function sanitizeInput($data, $type = 'string') {
    switch ($type) {
        case 'int':
            return (int) $data;
            
        case 'float':
            return (float) $data;
            
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
            
        case 'url':
            return filter_var($data, FILTER_SANITIZE_URL);
            
        case 'string':
        default:
            return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Check Rate Limit
 * 
 * Simple rate limiting for actions (e.g., form submissions, API calls)
 * 
 * @param string $action The action identifier
 * @param int $max_attempts Maximum attempts allowed
 * @param int $time_window Time window in seconds
 * @return bool True if action is allowed, false if rate limit exceeded
 */
function checkRateLimit($action, $max_attempts = 5, $time_window = 60) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $key = 'rate_limit_' . $action;
    $now = time();
    
    // Initialize if not exists
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'reset_time' => $now + $time_window
        ];
    }
    
    // Reset if time window passed
    if ($now >= $_SESSION[$key]['reset_time']) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'reset_time' => $now + $time_window
        ];
    }
    
    // Check if limit exceeded
    if ($_SESSION[$key]['attempts'] >= $max_attempts) {
        return false;
    }
    
    // Increment attempt counter
    $_SESSION[$key]['attempts']++;
    
    return true;
}

/**
 * Log Security Event
 * 
 * Logs security-related events to file
 * 
 * @param string $event_type The type of security event
 * @param string $message The log message
 * @param array $context Additional context data
 * @return void
 */
function logSecurityEvent($event_type, $message, $context = []) {
    $log_dir = __DIR__ . '/../logs';
    $log_file = $log_dir . '/security_' . date('Y-m') . '.log';
    
    // Create log directory if not exists
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    // Prepare log entry
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => $event_type,
        'message' => $message,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'context' => $context
    ];
    
    // Write to log file
    $log_line = json_encode($log_entry) . PHP_EOL;
    file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
}

/**
 * Check if User is Admin (Safe)
 * 
 * Safely checks if current user is admin without exposing sensitive info
 * 
 * @return bool True if user is admin, false otherwise
 */
function isAdminUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require Admin Access (Safe Redirect)
 * 
 * Redirects to login if user is not admin
 * 
 * @param string $redirect_to URL to redirect after login (optional)
 * @return void
 */
function requireAdminAccess($redirect_to = null) {
    if (!isAdminUser()) {
        $login_url = defined('SITE_URL') ? SITE_URL . '/admin/login.php' : '/admin/login.php';
        
        if ($redirect_to) {
            $login_url .= '?redirect=' . urlencode($redirect_to);
        }
        
        safeRedirect($login_url);
    }
}
