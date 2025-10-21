<?php
class IndexNowSubmitter {
    private $apiKey = '0562378ac1cabc9e90389059b69e3765';
    private $host = 'donan22.com';
    private $endpoints = [
        'bing'      => 'https://api.indexnow.org/indexnow',  // Bing (recommended)
        'yandex'    => 'https://yandex.com/indexnow',        // Yandex
        'indexnow'  => 'https://www.indexnow.org/indexnow',  // IndexNow.org
    ];
    private $logFile;
    /**
     * Enable/disable logging
     */
    private $loggingEnabled = true;
    public function __construct($host = null, $apiKey = null) {
        if ($host) {
            $this->host = $host;
        }
        if ($apiKey) {
            $this->apiKey = $apiKey;
        }
        $this->logFile = __DIR__ . '/../logs/indexnow.log';
        $logsDir = dirname($this->logFile);
        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }
    }
    /**
     * Submit single URL to IndexNow
     *
     * @param string $url Full URL to submit (e.g., https://donan22.com/post/adobe-photoshop)
     * @param string $endpoint Which endpoint to use ('bing', 'yandex', 'indexnow')
     * @return array Response with status and message
     */
    public function submitUrl($url, $endpoint = 'bing') {
        return $this->submitUrls([$url], $endpoint);
    }
    public function submitUrls($urls, $endpoint = 'bing') {
        // Validate URLs
        if (empty($urls) || !is_array($urls)) {
            return [
                'success' => false,
                'message' => 'No URLs provided',
                'code' => 400
            ];
        }
        // Limit to 10,000 URLs (IndexNow protocol limit)
        if (count($urls) > 10000) {
            $urls = array_slice($urls, 0, 10000);
        }
        // Prepare request payload
        $payload = [
            'host' => $this->host,
            'key' => $this->apiKey,
            'urlList' => $urls
        ];
        // Get endpoint URL
        $apiUrl = $this->endpoints[$endpoint] ?? $this->endpoints['bing'];
        // Log request
        $this->log("Submitting " . count($urls) . " URL(s) to $endpoint endpoint");
        // Send request
        try {
            $response = $this->sendRequest($apiUrl, $payload);
            // Log response
            $this->log("Response: HTTP {$response['code']} - {$response['message']}");
            return $response;
        } catch (Exception $e) {
            $errorMsg = "IndexNow submission failed: " . $e->getMessage();
            $this->log($errorMsg, 'ERROR');
            return [
                'success' => false,
                'message' => $errorMsg,
                'code' => 500
            ];
        }
    }
    public function submitSitemap($sitemapPath = null, $endpoint = 'bing') {
        if (!$sitemapPath) {
            $sitemapPath = __DIR__ . '/../seo/sitemap.xml';
        }
        if (!file_exists($sitemapPath)) {
            return [
                'success' => false,
                'message' => 'Sitemap file not found: ' . $sitemapPath,
                'code' => 404
            ];
        }
        // Parse sitemap XML
        $xml = simplexml_load_file($sitemapPath);
        if (!$xml) {
            return [
                'success' => false,
                'message' => 'Failed to parse sitemap XML',
                'code' => 500
            ];
        }
        // Extract URLs from sitemap
        $urls = [];
        foreach ($xml->url as $urlNode) {
            $url = (string) $urlNode->loc;
            if (!empty($url)) {
                $urls[] = $url;
            }
        }
        $this->log("Extracted " . count($urls) . " URLs from sitemap");
        // Submit URLs
        return $this->submitUrls($urls, $endpoint);
    }
    private function sendRequest($url, $data) {
        $jsonData = json_encode($data);
        // Use cURL for best performance
        if (function_exists('curl_init')) {
            return $this->sendCurlRequest($url, $jsonData);
        }
        // Fallback to file_get_contents
        return $this->sendFileGetContentsRequest($url, $jsonData);
    }
    private function sendCurlRequest($url, $jsonData) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'DONAN22-IndexNow/1.0'
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        // Interpret response code
        return $this->interpretResponse($httpCode, $response, $error);
    }
    private function sendFileGetContentsRequest($url, $jsonData) {
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n" .
                           "User-Agent: DONAN22-IndexNow/1.0\r\n",
                'content' => $jsonData,
                'timeout' => 30
            ]
        ];
        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
        // Extract HTTP response code
        $httpCode = 500;
        if (isset($http_response_header[0])) {
            preg_match('/\d{3}/', $http_response_header[0], $matches);
            $httpCode = isset($matches[0]) ? (int)$matches[0] : 500;
        }
        $error = $response === false ? 'Request failed' : null;
        return $this->interpretResponse($httpCode, $response, $error);
    }
    private function interpretResponse($code, $body, $error = null) {
        // Success codes
        if ($code == 200) {
            return [
                'success' => true,
                'message' => 'URLs submitted successfully',
                'code' => 200
            ];
        }
        if ($code == 202) {
            return [
                'success' => true,
                'message' => 'URLs accepted for processing',
                'code' => 202
            ];
        }
        // Error codes
        $errorMessages = [
            400 => 'Bad Request - Invalid format',
            403 => 'Forbidden - Invalid API key or key location',
            422 => 'Unprocessable Entity - URLs already submitted today',
            429 => 'Too Many Requests - Rate limit exceeded',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable'
        ];
        $message = $errorMessages[$code] ?? 'Unknown error';
        if ($error) {
            $message .= ": $error";
        }
        return [
            'success' => false,
            'message' => $message,
            'code' => $code,
            'body' => $body
        ];
    }
    private function log($message, $level = 'INFO') {
        if (!$this->loggingEnabled) {
            return;
        }
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}\n";
        @file_put_contents($this->logFile, $logEntry, FILE_APPEND);
        // Also log to PHP error log
        error_log("IndexNow: $message");
    }
    /**
     * Enable/disable logging
     */
    public function setLogging($enabled) {
        $this->loggingEnabled = (bool) $enabled;
    }
    public function getKeyLocation() {
        return "https://{$this->host}/{$this->apiKey}.txt";
    }
    public function verifyKeyFile() {
        $keyUrl = $this->getKeyLocation();
        $ch = curl_init($keyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode == 200;
    }
}
?>