<?php
// Prevent direct web access (only allow CLI)
if (php_sapi_name() !== 'cli' && !defined('ALLOW_WEB_CRON')) {
    // You can create a secret URL: /cron.php?secret=YOUR_SECRET_KEY
    if (!isset($_GET['secret']) || $_GET['secret'] !== 'j18bZrnYNRlLoq7xa6FW4sQzv3gy2kI0') {
        die('Access denied. This script must be run from command line or with valid secret.');
    }
    define('ALLOW_WEB_CRON', true);
}
set_time_limit(300); // 5 minutes max
// Include the sitemap hooks
require_once __DIR__ . '/includes/sitemap_hooks.php';
// Log start
$startTime = microtime(true);
echo "[" . date('Y-m-d H:i:s') . "] Starting sitemap job processor...\n";
try {
    // Process all pending sitemap jobs
    $processed = processSitemapJobs();
    $duration = round(microtime(true) - $startTime, 2);
    echo "[" . date('Y-m-d H:i:s') . "] Completed: Processed $processed job(s) in {$duration}s\n";
    // Log to file
    $logFile = __DIR__ . '/logs/cron_sitemap_jobs.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logMessage = date('Y-m-d H:i:s') . " - Processed $processed job(s) in {$duration}s\n";
    @file_put_contents($logFile, $logMessage, FILE_APPEND);
    exit(0); // Success
} catch (Exception $e) {
    echo "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n";
    error_log("Sitemap job processor failed: " . $e->getMessage());
    exit(1); // Error
}