<?php
class SecurityScanner {
    private $issues = [];
    
    private function checkSecurityHeaders() {
        $headers = [
            'X-Frame-Options' => ['recommended' => 'DENY or SAMEORIGIN', 'severity' => 'medium'],
            'X-Content-Type-Options' => ['recommended' => 'nosniff', 'severity' => 'medium'],
            'X-XSS-Protection' => ['recommended' => '1; mode=block', 'severity' => 'low'],
            'Strict-Transport-Security' => ['recommended' => 'max-age=31536000', 'severity' => 'high'],
            'Content-Security-Policy' => ['recommended' => 'default-src \'self\'', 'severity' => 'medium']
        ];
        foreach ($headers as $header => $info) {
            $this->addIssue('info', 'Security Headers',
                "Header check: $header (recommended: {$info['recommended']})",
                "Add header to .htaccess or server config if not present."
            );
        }
    }
    private function checkSensitiveFiles() {
        $baseDir = dirname(__DIR__, 2);
        $sensitiveFiles = [
            '.env' => $baseDir . '/.env',
            'config.php' => $baseDir . '/config_modern.php',
            '.git' => $baseDir . '/.git'
        ];
        foreach ($sensitiveFiles as $name => $path) {
            if (file_exists($path)) {
                if ($name === '.env' || $name === 'config.php') {
                    $this->addIssue('medium', 'Sensitive Files',
                        "Sensitive file exists: $name - ensure it's protected by .htaccess",
                        "Add 'Deny from all' in .htaccess for this directory."
                    );
                } elseif ($name === '.git') {
                    $this->addIssue('high', 'Sensitive Files',
                        ".git directory exposed - contains sensitive repository information",
                        "Block access to .git directory via .htaccess or move outside webroot."
                    );
                }
            }
        }
    }
    private function checkPHPExtensions() {
        $required = ['pdo', 'pdo_mysql', 'mysqli', 'session', 'filter', 'json'];
        $recommended = ['openssl', 'mbstring', 'gd', 'curl', 'zip'];
        $missingRequired = [];
        $missingRecommended = [];
        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $missingRequired[] = $ext;
            }
        }
        foreach ($recommended as $ext) {
            if (!extension_loaded($ext)) {
                $missingRecommended[] = $ext;
            }
        }
        if (!empty($missingRequired)) {
            $this->addIssue('critical', 'PHP Extensions',
                "Missing required extensions: " . implode(', ', $missingRequired),
                "Install missing extensions in php.ini"
            );
        }
        if (!empty($missingRecommended)) {
            $this->addIssue('medium', 'PHP Extensions',
                "Missing recommended extensions: " . implode(', ', $missingRecommended),
                "Install recommended extensions for full functionality."
            );
        }
        if (empty($missingRequired) && empty($missingRecommended)) {
            $this->addIssue('info', 'PHP Extensions',
                "All required and recommended extensions are loaded",
                "No action required."
            );
        }
    }
    
    private function addIssue($severity, $category, $description, $recommendation) {
        $this->issues[] = [
            'severity' => $severity,
            'category' => $category,
            'description' => $description,
            'recommendation' => $recommendation,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    public function getResults() {
        // Sort by severity
        $severityOrder = ['critical' => 0, 'high' => 1, 'medium' => 2, 'low' => 3, 'info' => 4];
        usort($this->issues, function($a, $b) use ($severityOrder) {
            return $severityOrder[$a['severity']] - $severityOrder[$b['severity']];
        });
        return [
            'issues' => $this->issues,
            'summary' => $this->getSummary(),
            'score' => $this->calculateScore()
        ];
    }
    
    private function getSummary() {
        $summary = [
            'total' => count($this->issues),
            'critical' => 0,
            'high' => 0,
            'medium' => 0,
            'low' => 0,
            'info' => 0
        ];
        foreach ($this->issues as $issue) {
            $summary[$issue['severity']]++;
        }
        return $summary;
    }
    
    private function calculateScore() {
        $summary = $this->getSummary();
        $maxScore = 100;
        $deductions = [
            'critical' => 25,
            'high' => 15,
            'medium' => 10,
            'low' => 5
        ];
        $score = $maxScore;
        foreach ($deductions as $severity => $points) {
            $score -= ($summary[$severity] * $points);
        }
        return max(0, min(100, $score));
    }
}