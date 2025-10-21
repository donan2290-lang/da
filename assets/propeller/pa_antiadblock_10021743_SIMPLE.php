<?php

// Configuration
$zoneId = '10021743';
$token = '6053b6f12ec4c350a3ce163622e076daa5650cbb';
$apiUrl = "https://go.transferzenad.com/v3/getTag?token={$token}&zoneId={$zoneId}&version=1";
$cacheFile = sys_get_temp_dir() . '/antiadblock_' . $zoneId . '.cache';
$cacheTTL = 30 * 60; // 30 minutes

if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTTL) {
    // Use cached response
    $response = file_get_contents($cacheFile);
} else {
    // Fetch from API
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    // Debug: log if error occurred
    if (!empty($error)) {
        error_log("AntiAdBlock API Error for zone {$zoneId}: " . $error);
    }
    
    // Save to cache
    if (!empty($response)) {
        file_put_contents($cacheFile, $response, LOCK_EX);
    }
}

// Parse response
if (!empty($response)) {
    $hash = substr($response, 0, 32);
    $dataRaw = substr($response, 32);
    
    // Validate MD5
    if (md5($dataRaw) === strtolower($hash)) {
        // Unserialize
        $data = @unserialize($dataRaw, ['allowed_classes' => false]);
        if (is_array($data) && isset($data['tag'])) {
            // Return the tag
            return $data['tag'];
        }
    }
}

// Fallback: return empty
return '';
