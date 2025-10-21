<?php

require_once __DIR__ . '/config_modern.php';
require_once __DIR__ . '/includes/IndexNowSubmitter.php';
// ANSI colors for terminal output
$green = "\033[32m";
$yellow = "\033[33m";
$blue = "\033[34m";
$cyan = "\033[36m";
$red = "\033[31m";
$reset = "\033[0m";
$bold = "\033[1m";
// Parse command line arguments
$options = getopt("", ["test", "endpoint:", "help"]);
if (isset($options['help'])) {
    echo "\n";
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║         📤 BULK INDEXNOW SUBMISSION TOOL                     ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "Usage:\n";
    echo "  php submit-to-indexnow.php [options]\n";
    echo "\n";
    echo "Options:\n";
    echo "  --test              Dry run mode (don't actually submit)\n";
    echo "  --endpoint=NAME     Use specific endpoint (bing, yandex, indexnow)\n";
    echo "  --help              Show this help message\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php submit-to-indexnow.php\n";
    echo "  php submit-to-indexnow.php --test\n";
    echo "  php submit-to-indexnow.php --endpoint=yandex\n";
    echo "\n";
    exit(0);
}
$testMode = isset($options['test']);
$endpoint = $options['endpoint'] ?? 'bing';
echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║         📤 BULK SUBMIT TO INDEXNOW                           ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";
if ($testMode) {
    echo "{$yellow}🧪 TEST MODE: No actual submission will be made{$reset}\n";
    echo "\n";
}
// Step 1: Initialize IndexNow
echo "{$cyan}[STEP 1]{$reset} Initializing IndexNow submitter...\n";
$indexNow = new IndexNowSubmitter('donan22.com');
echo "   ✅ API Key: 0562378ac1cabc9e90389059b69e3765\n";
echo "   ✅ Host: donan22.com\n";
echo "   ✅ Endpoint: {$bold}{$endpoint}{$reset}\n";
echo "   ✅ Key Location: {$indexNow->getKeyLocation()}\n";
echo "\n";
// Step 2: Load sitemap
echo "{$cyan}[STEP 2]{$reset} Loading sitemap...\n";
$sitemapPath = __DIR__ . '/seo/sitemap.xml';
if (!file_exists($sitemapPath)) {
    echo "{$red}❌ Sitemap not found: {$sitemapPath}{$reset}\n";
    exit(1);
}
$sitemapSize = filesize($sitemapPath);
echo "   📄 File: {$sitemapPath}\n";
echo "   📏 Size: " . number_format($sitemapSize) . " bytes\n";
// Parse sitemap
$xml = simplexml_load_file($sitemapPath);
if (!$xml) {
    echo "{$red}❌ Failed to parse sitemap XML{$reset}\n";
    exit(1);
}
// Extract URLs
$urls = [];
foreach ($xml->url as $urlNode) {
    $url = (string) $urlNode->loc;
    if (!empty($url)) {
        $urls[] = $url;
    }
}
echo "   ✅ Extracted {$bold}" . count($urls) . "{$reset} URLs from sitemap\n";
echo "\n";
// Step 3: Show sample URLs
echo "{$cyan}[STEP 3]{$reset} Sample URLs to submit:\n";
$sampleCount = min(5, count($urls));
for ($i = 0; $i < $sampleCount; $i++) {
    echo "   {$blue}•{$reset} {$urls[$i]}\n";
}
if (count($urls) > 5) {
    echo "   {$blue}...{$reset} and " . (count($urls) - 5) . " more URLs\n";
}
echo "\n";
// Step 4: Confirm submission
if (!$testMode) {
    echo "{$yellow}⚠️  Ready to submit " . count($urls) . " URLs to IndexNow{$reset}\n";
    echo "   This will notify Bing, Yandex, and other search engines.\n";
    echo "\n";
    echo "   Continue? [Y/n]: ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    fclose($handle);
    if (strtolower($line) !== 'y' && $line !== '') {
        echo "\n{$yellow}⚠️  Submission cancelled by user{$reset}\n\n";
        exit(0);
    }
    echo "\n";
}
// Step 5: Submit to IndexNow
echo "{$cyan}[STEP 4]{$reset} Submitting to IndexNow...\n";
echo "   ⏳ Sending request to {$endpoint} endpoint...\n";
echo "\n";
if ($testMode) {
    echo "{$yellow}🧪 TEST MODE: Skipping actual submission{$reset}\n";
    echo "   Would submit " . count($urls) . " URLs to {$endpoint}\n";
    echo "\n";
    $result = [
        'success' => true,
        'message' => 'Test mode - no actual submission',
        'code' => 200
    ];
} else {
    $startTime = microtime(true);
    $result = $indexNow->submitUrls($urls, $endpoint);
    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 2);
    echo "   ⏱️  Request completed in {$duration} seconds\n";
    echo "\n";
}
// Step 6: Show results
echo "{$cyan}[STEP 5]{$reset} Submission Results:\n";
echo "\n";
if ($result['success']) {
    echo "{$green}✅ SUCCESS!{$reset}\n";
    echo "   HTTP Code: {$result['code']}\n";
    echo "   Message: {$result['message']}\n";
    echo "   URLs Submitted: " . count($urls) . "\n";
    echo "\n";
    echo "{$green}🎉 Your URLs have been submitted for instant indexing!{$reset}\n";
    echo "\n";
    echo "Expected timeline:\n";
    echo "   • Bing: Indexed within 5-30 minutes\n";
    echo "   • Yandex: Indexed within 15-60 minutes\n";
    echo "\n";
    echo "You can verify indexing with:\n";
    echo "   • Bing: site:donan22.com in Bing search\n";
    echo "   • Yandex: site:donan22.com in Yandex search\n";
    echo "\n";
} else {
    echo "{$red}❌ SUBMISSION FAILED{$reset}\n";
    echo "   HTTP Code: {$result['code']}\n";
    echo "   Message: {$result['message']}\n";
    echo "\n";
    if ($result['code'] == 403) {
        echo "{$yellow}💡 Possible fix:{$reset}\n";
        echo "   1. Verify API key file is accessible:\n";
        echo "      https://donan22.com/0562378ac1cabc9e90389059b69e3765.txt\n";
        echo "   2. Make sure file contains only the API key (no extra spaces)\n";
        echo "   3. Check file is readable (chmod 644 on Linux)\n";
        echo "\n";
    } elseif ($result['code'] == 422) {
        echo "{$yellow}💡 Note:{$reset}\n";
        echo "   URLs already submitted today. IndexNow accepts each URL once per day.\n";
        echo "   This is normal and not an error.\n";
        echo "\n";
    } elseif ($result['code'] == 429) {
        echo "{$yellow}💡 Note:{$reset}\n";
        echo "   Rate limit exceeded. Try again in a few minutes.\n";
        echo "\n";
    }
}
// Step 7: Log file location
$logFile = __DIR__ . '/logs/indexnow.log';
if (file_exists($logFile)) {
    echo "📋 Detailed logs: {$logFile}\n";
    echo "\n";
}
// Final summary
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    📊 SUMMARY                                ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";
echo "Mode:           " . ($testMode ? 'Test (Dry Run)' : 'Production') . "\n";
echo "Endpoint:       {$endpoint}\n";
echo "URLs Processed: " . count($urls) . "\n";
echo "Status:         " . ($result['success'] ? "{$green}✅ Success{$reset}" : "{$red}❌ Failed{$reset}") . "\n";
echo "HTTP Code:      {$result['code']}\n";
echo "\n";
if (!$testMode && $result['success']) {
    echo "{$green}✨ Done! Your site will be indexed faster now!{$reset}\n";
} elseif ($testMode) {
    echo "{$yellow}🧪 Test completed. Run without --test to actually submit.{$reset}\n";
}
echo "\n";
?>