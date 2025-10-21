<?php
function regenerateSitemap() {
    $sitemapGenerator = __DIR__ . '/../seo/sitemap_dynamic.php';
    if (!file_exists($sitemapGenerator)) {
        error_log('Sitemap generator not found: ' . $sitemapGenerator);
        return false;
    }
    try {
        require_once $sitemapGenerator;
        if (function_exists('generateSitemap')) {
            call_user_func('generateSitemap');
        } else {
            error_log('generateSitemap function not found after including sitemap_dynamic.php');
        }
        $logFile = __DIR__ . '/../seo/sitemap_hooks.log';
        $logMessage = date('Y-m-d H:i:s') . " - Sitemap regeneration completed\n";
        @file_put_contents($logFile, $logMessage, FILE_APPEND);
        return true;
    } catch (Exception $e) {
        error_log('Sitemap regeneration failed: ' . $e->getMessage());
        return false;
    }
}
function regenerateSitemapThrottled() {
    $lockFile = __DIR__ . '/../seo/.sitemap_lock';
    $lockTimeout = 300;
    if (file_exists($lockFile)) {
        $lockTime = filemtime($lockFile);
        if (time() - $lockTime < $lockTimeout) {
            return false;
        }
    }
    touch($lockFile);
    $result = regenerateSitemap();
    return $result;
}
function regenerateSitemapAsync() {
    $jobFile = __DIR__ . '/../cache/sitemap_jobs.json';
    $cacheDir = dirname($jobFile);
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    $job = [
        'id' => uniqid('sitemap_', true),
        'type' => 'regenerate_sitemap',
        'created_at' => time(),
        'status' => 'pending'
    ];
    $jobs = [];
    if (file_exists($jobFile)) {
        $jobs = json_decode(file_get_contents($jobFile), true) ?: [];
    }
    $jobs[] = $job;
    @file_put_contents($jobFile, json_encode($jobs, JSON_PRETTY_PRINT));
    error_log("Sitemap regeneration job queued: {$job['id']}");
    return $job['id'];
}
function processSitemapJobs() {
    $jobFile = __DIR__ . '/../cache/sitemap_jobs.json';
    if (!file_exists($jobFile)) {
        return 0;
    }
    $jobs = json_decode(file_get_contents($jobFile), true) ?: [];
    $processed = 0;
    foreach ($jobs as $key => $job) {
        if ($job['status'] === 'pending') {
            try {
                regenerateSitemap();
                $jobs[$key]['status'] = 'completed';
                $jobs[$key]['completed_at'] = time();
                $processed++;
            } catch (Exception $e) {
                $jobs[$key]['status'] = 'failed';
                $jobs[$key]['error'] = $e->getMessage();
                error_log("Job {$job['id']} failed: " . $e->getMessage());
            }
        }
    }
    $jobs = array_filter($jobs, function($job) {
        if ($job['status'] === 'pending') {
            return true;
        }
        $completedAt = $job['completed_at'] ?? $job['created_at'];
        return (time() - $completedAt) < 86400;
    });
    @file_put_contents($jobFile, json_encode(array_values($jobs), JSON_PRETTY_PRINT));
    return $processed;
}
function onPostUpdated() {
    regenerateSitemapThrottled();
}
function onCategoryUpdated() {
    regenerateSitemapThrottled();
}
function onPostDeleted() {
    regenerateSitemapThrottled();
}
if (isset($_GET['action']) && $_GET['action'] === 'regenerate_sitemap') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        $result = regenerateSitemap();
        if (php_sapi_name() !== 'cli') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Sitemap regenerated successfully' : 'Failed to regenerate sitemap',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }
        exit;
    }
}
?>