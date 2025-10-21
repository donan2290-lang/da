<?php
define('ADMIN_ACCESS', true);
require_once '../config_modern.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migrate Post Types - DONAN22</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Migrate Post Types</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Script ini akan memindahkan post ke type yang benar:
                            <ul class="mt-2 mb-0">
                                <li><strong>Mobile Apps</strong> → mobile-apps (WhatsApp, Telegram, Android Emulator, dll)</li>
                                <li><strong>Games</strong> → games (COD, PUBG, Mobile Legends, dll)</li>
                                <li><strong>Software</strong> → software (Photoshop, Windows, PC apps, dll)</li>
                                <li><strong>Tutorial/Tips</strong> → blog (Cara, Panduan, Tutorial, dll)</li>
                            </ul>
                        </div>
                        <button id="startMigration" class="btn btn-success btn-lg w-100 mb-3">
                            <i class="fas fa-play me-2"></i>Start Migration
                        </button>
                        <div id="progress" class="mb-3" style="display:none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div id="results" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('startMigration').addEventListener('click', async function() {
        const btn = this;
        const resultsDiv = document.getElementById('results');
        const progressDiv = document.getElementById('progress');
        const progressBar = progressDiv.querySelector('.progress-bar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Migrating...';
        progressDiv.style.display = 'block';
        resultsDiv.innerHTML = '';
        try {
            // Show progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                if (progress <= 90) {
                    progressBar.style.width = progress + '%';
                }
            }, 200);
            const response = await fetch('migrate_post_types.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            });
            clearInterval(interval);
            progressBar.style.width = '100%';
            const data = await response.json();
            if (data.success) {
                let resultsHtml = `
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle me-2"></i>Migration Complete!</h5>
                        <p class="mb-2"><strong>Total Posts:</strong> ${data.total_posts}</p>
                        <p class="mb-0"><strong>Updated:</strong> ${data.updated} posts</p>
                    </div>
                `;
                if (data.results && data.results.length > 0) {
                    resultsHtml += `
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Updated Posts</h5>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Old Type</th>
                                            <th>New Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;
                    data.results.forEach(post => {
                        const typeColors = {
                            'software': 'primary',
                            'games': 'danger',
                            'mobile-apps': 'success',
                            'blog': 'warning'
                        };
                        resultsHtml += `
                            <tr>
                                <td>${post.id}</td>
                                <td><small>${post.title}</small></td>
                                <td><span class="badge bg-secondary">${post.old_type}</span></td>
                                <td><span class="badge bg-${typeColors[post.new_type] || 'info'}">${post.new_type}</span></td>
                            </tr>
                        `;
                    });
                    resultsHtml += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                }
                resultsDiv.innerHTML = resultsHtml;
                setTimeout(() => {
                    progressDiv.style.display = 'none';
                }, 2000);
            } else {
                resultsDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error: ${data.error || 'Unknown error'}
                    </div>
                `;
            }
        } catch (error) {
            resultsDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error: ${error.message}
                </div>
            `;
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play me-2"></i>Start Migration';
        }
    });
    </script>
</body>
</html>