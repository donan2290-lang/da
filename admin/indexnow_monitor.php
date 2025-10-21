<?php

require_once __DIR__ . '/../config_modern.php';
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check admin authentication using isLoggedIn() function
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../includes/IndexNowSubmitter.php';
$pageTitle = "IndexNow Monitor";
$currentPage = 'indexnow_monitor';
include __DIR__ . '/includes/header.php';
// Get statistics
$logFile = __DIR__ . '/../logs/indexnow.log';
$logs = [];
$stats = [
    'total_submissions' => 0,
    'successful' => 0,
    'failed' => 0,
    'last_submission' => null
];
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $logLines = array_filter(explode("\n", $logContent));
    // Parse logs
    foreach ($logLines as $line) {
        if (preg_match('/\[(.*?)\] \[(.*?)\] (.*)/', $line, $matches)) {
            $logs[] = [
                'timestamp' => $matches[1],
                'level' => $matches[2],
                'message' => $matches[3]
            ];
        }
        // Count submissions
        if (strpos($line, 'Submitting') !== false) {
            $stats['total_submissions']++;
            $stats['last_submission'] = $matches[1] ?? null;
        }
        if (strpos($line, 'HTTP 200') !== false || strpos($line, 'HTTP 202') !== false) {
            $stats['successful']++;
        }
        if (strpos($line, '[ERROR]') !== false) {
            $stats['failed']++;
        }
    }
    // Get last 50 logs
    $logs = array_reverse(array_slice(array_reverse($logs), 0, 50));
}
// Initialize IndexNow
$indexNow = new IndexNowSubmitter('donan22.com');
$keyLocation = $indexNow->getKeyLocation();
?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">
                <i class="fas fa-rocket text-primary"></i>
                IndexNow Monitoring Dashboard
            </h1>
        </div>
    </div>
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Submissions</h5>
                    <h2 class="mb-0"><?= $stats['total_submissions'] ?></h2>
                    <small>All-time</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Successful</h5>
                    <h2 class="mb-0"><?= $stats['successful'] ?></h2>
                    <small>HTTP 200/202</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Failed</h5>
                    <h2 class="mb-0"><?= $stats['failed'] ?></h2>
                    <small>Errors</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Last Submission</h5>
                    <h6 class="mb-0"><?= $stats['last_submission'] ? date('M d, H:i', strtotime($stats['last_submission'])) : 'Never' ?></h6>
                    <small>Timestamp</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="#" onclick="submitAllUrls(); return false;" class="btn btn-success">
                            <i class="fas fa-upload"></i> Submit All URLs to IndexNow
                        </a>
                        <a href="#" onclick="testIndexNow(); return false;" class="btn btn-info">
                            <i class="fas fa-vial"></i> Test IndexNow Connection
                        </a>
                        <a href="<?= $keyLocation ?>" target="_blank" class="btn btn-warning">
                            <i class="fas fa-key"></i> Verify API Key File
                        </a>
                        <a href="#" onclick="clearLogs(); return false;" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Clear Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Configuration Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">IndexNow Configuration</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">API Key:</th>
                            <td><code>0562378ac1cabc9e90389059b69e3765</code></td>
                        </tr>
                        <tr>
                            <th>Host:</th>
                            <td><code>donan22.com</code></td>
                        </tr>
                        <tr>
                            <th>Key Location:</th>
                            <td>
                                <a href="<?= $keyLocation ?>" target="_blank">
                                    <?= $keyLocation ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Endpoints:</th>
                            <td>
                                <span class="badge bg-primary">Bing</span>
                                <span class="badge bg-info">Yandex</span>
                                <span class="badge bg-success">IndexNow.org</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Auto-Submit:</th>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Enabled (post-editor.php)
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">How IndexNow Works</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li><strong>Auto-trigger:</strong> Every post save triggers IndexNow submission</li>
                        <li><strong>Instant notification:</strong> Bing & Yandex notified in real-time</li>
                        <li><strong>Fast indexing:</strong> URLs indexed within 5-30 minutes</li>
                        <li><strong>No cost:</strong> Unlimited submissions, completely free</li>
                        <li><strong>Multiple engines:</strong> Works with Bing, Yandex, Seznam, Naver</li>
                    </ol>
                    <hr>
                    <h6>Expected Timeline:</h6>
                    <ul class="mb-0">
                        <li><strong>Bing:</strong> 5-30 minutes</li>
                        <li><strong>Yandex:</strong> 15-60 minutes</li>
                        <li><strong>Google:</strong> Not supported (use Search Console)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Logs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity (Last 50 entries)</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($logs)): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No IndexNow submissions yet.</p>
                            <p>Logs will appear here after first submission.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th width="15%">Timestamp</th>
                                        <th width="10%">Level</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody style="font-family: monospace; font-size: 12px;">
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($log['timestamp']) ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = 'secondary';
                                                if ($log['level'] === 'ERROR') $badgeClass = 'danger';
                                                elseif ($log['level'] === 'WARNING') $badgeClass = 'warning';
                                                elseif ($log['level'] === 'INFO') $badgeClass = 'info';
                                                ?>
                                                <span class="badge bg-<?= $badgeClass ?>">
                                                    <?= htmlspecialchars($log['level']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($log['message']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 id="loadingMessage">Processing...</h5>
            </div>
        </div>
    </div>
</div>
<script>
function submitAllUrls() {
    if (!confirm('Submit all URLs from sitemap to IndexNow?\n\nThis will notify Bing & Yandex for instant indexing.')) {
        return;
    }
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    document.getElementById('loadingMessage').textContent = 'Submitting URLs to IndexNow...';
    modal.show();
    // Execute CLI script via AJAX (in production, trigger background job)
    fetch('indexnow_submit_api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'submit_all'})
    })
    .then(response => response.json())
    .then(data => {
        modal.hide();
        if (data.success) {
            alert('✅ Success!\n\nSubmitted ' + data.count + ' URLs to IndexNow.\n\nExpected indexing: 5-30 minutes.');
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        modal.hide();
        alert('❌ Error: ' + error.message);
    });
}
function testIndexNow() {
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    document.getElementById('loadingMessage').textContent = 'Testing IndexNow connection...';
    modal.show();
    fetch('indexnow_submit_api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'test'})
    })
    .then(response => response.json())
    .then(data => {
        modal.hide();
        if (data.success) {
            alert('✅ IndexNow Test Successful!\n\n' +
                  'API Key: Valid\n' +
                  'Endpoint: ' + data.endpoint + '\n' +
                  'Response: ' + data.message);
        } else {
            alert('❌ IndexNow Test Failed!\n\n' + data.message);
        }
    })
    .catch(error => {
        modal.hide();
        alert('❌ Error: ' + error.message);
    });
}
function clearLogs() {
    if (!confirm('Clear all IndexNow logs?\n\nThis cannot be undone.')) {
        return;
    }
    fetch('indexnow_submit_api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'clear_logs'})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Logs cleared successfully!');
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    });
}
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>