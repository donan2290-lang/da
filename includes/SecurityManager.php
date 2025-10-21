<?php
class SecurityManager {
    public static function generateSecureFilename($originalName) {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        return bin2hex(random_bytes(16)) . '.' . $ext;
    }
    public static function logSecurityEvent($event, $details = []) {
        $logFile = __DIR__ . '/../logs/security.log';
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0755, true);
        }
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'user_id' => $_SESSION['user_id'] ?? 'guest',
            'details' => $details
        ];
        file_put_contents($logFile, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
    }
}