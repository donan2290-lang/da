<?php
// Proteksi akses langsung
if (!defined('ADMIN_ACCESS') && !isset($_SESSION)) {
    http_response_code(403);
    die('Access Denied');
}
class SecurityManager {
    private $pdo;
    private $maxLoginAttempts = 5;
    private $lockoutDuration = 900; // 15 minutes
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function verifyCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
            return false;
        }
        // Check if token is expired (1 hour)
        if (time() - $_SESSION['csrf_token_time'] > 3600) {
            unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public function logSecurityEvent($eventType, $details = '', $severity = 'medium') {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO security_logs
                (event_type, ip_address, user_agent, request_uri, post_data, user_id, severity, details, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $eventType,
                $this->getClientIP(),
                $_SERVER['HTTP_USER_AGENT'] ?? '',
                $_SERVER['REQUEST_URI'] ?? '',
                !empty($_POST) ? json_encode($_POST) : null,
                $_SESSION['admin_id'] ?? null,
                $severity,
                $details
            ]);
        } catch (Exception $e) {
            error_log("Failed to log security event: " . $e->getMessage());
        }
    }
    public function detectSQLInjection($input) {
        if (is_array($input)) {
            foreach ($input as $value) {
                if ($this->detectSQLInjection($value)) {
                    return true;
                }
            }
            return false;
        }
        $patterns = [
            '/(\bunion\b.*\bselect\b)/i',
            '/(\bselect\b.*\bfrom\b)/i',
            '/(\binsert\b.*\binto\b)/i',
            '/(\bupdate\b.*\bset\b)/i',
            '/(\bdelete\b.*\bfrom\b)/i',
            '/(\bdrop\b.*\btable\b)/i',
            '/(\balter\b.*\btable\b)/i',
            '/(\bexec\b|\bexecute\b)/i',
            '/(\bscript\b.*>)/i',
            '/(\'|\"|;|\-\-|\#)/i'
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                $this->logSecurityEvent('sql_injection', "Detected pattern: $pattern in input: " . substr($input, 0, 200), 'high');
                return true;
            }
        }
        return false;
    }
    public function detectXSS($input) {
        if (is_array($input)) {
            foreach ($input as $value) {
                if ($this->detectXSS($value)) {
                    return true;
                }
            }
            return false;
        }
        $patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
            '/onmouseover\s*=/i',
            '/<iframe\b/i',
            '/<object\b/i',
            '/<embed\b/i'
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                $this->logSecurityEvent('xss_attempt', "Detected XSS pattern in input: " . substr($input, 0, 200), 'high');
                return true;
            }
        }
        return false;
    }
    public function checkLoginAttempts($ipAddress, $username = '') {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM login_attempts
                WHERE ip_address = ? AND success = 0
                AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
            ");
            $stmt->execute([$ipAddress, $this->lockoutDuration]);
            $failedAttempts = $stmt->fetchColumn();
            if ($failedAttempts >= $this->maxLoginAttempts) {
                $this->logSecurityEvent('failed_login', "IP blocked due to too many failed attempts: $ipAddress", 'high');
                return false;
            }
            return true;
        } catch (Exception $e) {
            error_log("Error checking login attempts: " . $e->getMessage());
            return false;
        }
    }
    
    public function logLoginAttempt($username, $success, $ipAddress = null) {
        try {
            $ipAddress = $ipAddress ?? $this->getClientIP();
            $stmt = $this->pdo->prepare("
                INSERT INTO login_attempts (ip_address, username, success, user_agent, attempted_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $ipAddress,
                $username,
                $success ? 1 : 0,
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            if (!$success) {
                $this->logSecurityEvent('failed_login', "Failed login for username: $username", 'medium');
            }
        } catch (Exception $e) {
            error_log("Error logging login attempt: " . $e->getMessage());
        }
    }
    public function validateFileUpload($file, $allowedTypes = [], $maxSize = null) {
        $maxSize = $maxSize ?? (50 * 1024 * 1024); // 50MB default
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'File upload error: ' . $file['error']];
        }
        if ($file['size'] > $maxSize) {
            $this->logSecurityEvent('file_upload', "File too large: " . $file['size'] . " bytes", 'medium');
            return ['success' => false, 'error' => 'File too large'];
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
            $this->logSecurityEvent('file_upload', "Unauthorized file type: $mimeType", 'high');
            return ['success' => false, 'error' => 'File type not allowed'];
        }
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $dangerousExtensions = ['php', 'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'];
        if (in_array($extension, $dangerousExtensions)) {
            $this->logSecurityEvent('file_upload', "Dangerous file extension: $extension", 'critical');
            return ['success' => false, 'error' => 'File type not allowed'];
        }
        return ['success' => true, 'mime_type' => $mimeType];
    }
    
    public function getClientIP() {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    public function generateSecurePassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle(str_repeat($chars, ceil($length/strlen($chars)))), 0, $length);
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }
}

// Initialize security manager
global $security;
$security = new SecurityManager($pdo);

// Additional helper functions for login
function isIPBanned($pdo, $ip) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blocked_ips WHERE ip_address = ? AND (expires_at > NOW() OR expires_at IS NULL)");
        $stmt->execute([$ip]);
        return $stmt->fetchColumn() > 0;
    } catch(Exception $e) {
        return false;
    }
}

function autoUnlockExpiredBans($pdo) {
    try {
        $pdo->exec("DELETE FROM blocked_ips WHERE expires_at IS NOT NULL AND expires_at < NOW()");
    } catch(Exception $e) {}
}

function getAutoBanSettings($pdo) {
    return ['auto_ban_enabled' => false, 'max_login_attempts' => 5, 'time_window' => 15, 'ban_duration' => 30];
}

function checkAutoBan($pdo, $ip, $maxAttempts, $timeWindow) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND success = 0 AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$ip, $timeWindow]);
        $attempts = $stmt->fetchColumn();
        return ['should_ban' => $attempts >= $maxAttempts, 'attempts' => $attempts];
    } catch(Exception $e) {
        return ['should_ban' => false, 'attempts' => 0];
    }
}

function autoBanIP($pdo, $ip, $duration) {
    try {
        $stmt = $pdo->prepare("INSERT INTO blocked_ips (ip_address, reason, blocked_at, expires_at) VALUES (?, 'Auto-ban', NOW(), DATE_ADD(NOW(), INTERVAL ? MINUTE))");
        $stmt->execute([$ip, $duration]);
        return true;
    } catch(Exception $e) {
        return false;
    }
}