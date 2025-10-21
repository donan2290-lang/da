<?php

define('ADMIN_ACCESS', true);
require_once '../../config_modern.php';
require_once '../../includes/seo_content_template.php';
require_once '../../includes/seo_heading_helper.php';
if (!isset($_SESSION['admin_id'])) {
    die('Unauthorized access');
}
// Get parameters
$action = $_GET['action'] ?? 'scan';
$limit = (int)($_GET['limit'] ?? 10);
$offset = (int)($_GET['offset'] ?? 0);
$dryRun = isset($_GET['dry_run']) ? true : false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Update SEO Headings - DONAN22</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .post-item { border-left: 4px solid #ddd; }
        .post-item.needs-update { border-left-color: #ffc107; }
        .post-item.updated { border-left-color: #28a745; }
        .post-item.error { border-left-color: #dc3545; }
        .heading-preview { background: #f8f9fa; border-left: 3px solid #007bff; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-magic me-2"></i>Bulk Update SEO Headings</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($action === 'scan'): ?>
                            <!-- SCAN MODE -->
                            <h5>Scan Posts untuk Update</h5>
                            <p class="text-muted">Mencari posts yang belum memiliki struktur heading SEO yang proper...</p>
                            <?php
                            // Scan posts yang belum punya H2
                            $stmt = $pdo->prepare("
                                SELECT id, title, slug, content, post_type_id, category_id,
                                       LENGTH(content) as content_length,
                                       (content LIKE '%<h2%' OR content LIKE '%<H2%') as has_h2
                                FROM posts
                                WHERE status = 'published' AND deleted_at IS NULL
                                ORDER BY created_at DESC
                                LIMIT ? OFFSET ?
                            ");
                            $stmt->execute([$limit, $offset]);
                            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $needsUpdate = [];
                            $hasStructure = [];
                            foreach ($posts as $post) {
                                if (!$post['has_h2'] && $post['content_length'] > 100) {
                                    $needsUpdate[] = $post;
                                } else {
                                    $hasStructure[] = $post;
                                }
                            }
                            ?>
                            <div class="alert alert-info">
                                <strong>Scan Results:</strong><br>
                                ✅ Posts with H2 structure: <strong><?= count($hasStructure) ?></strong><br>
                                ⚠️ Posts needing update: <strong><?= count($needsUpdate) ?></strong>
                            </div>
                            <?php if (count($needsUpdate) > 0): ?>
                                <h6 class="mt-4">Posts yang Perlu Update:</h6>
                                <div class="list-group">
                                    <?php foreach ($needsUpdate as $post): ?>
                                        <div class="list-group-item post-item needs-update">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1"><?= htmlspecialchars($post['title']) ?></h6>
                                                    <small class="text-muted">
                                                        ID: <?= $post['id'] ?> |
                                                        Slug: <?= $post['slug'] ?> |
                                                        Content: <?= $post['content_length'] ?> chars
                                                    </small>
                                                </div>
                                                <span class="badge bg-warning">Needs H2/H3</span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mt-4">
                                    <a href="?action=update&limit=<?= $limit ?>&offset=<?= $offset ?>&dry_run=1" class="btn btn-warning">
                                        <i class="fas fa-eye me-2"></i>Preview Update (Dry Run)
                                    </a>
                                    <a href="?action=update&limit=<?= $limit ?>&offset=<?= $offset ?>" class="btn btn-success"
                                       onclick="return confirm('Update <?= count($needsUpdate) ?> posts dengan SEO heading structure?\n\n⚠️ Ini akan mengubah content posts!\n\nBackup sudah dilakukan?')">
                                        <i class="fas fa-magic me-2"></i>Update Now
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">
                                    ✅ All posts already have H2 structure!
                                </div>
                            <?php endif; ?>
                        <?php elseif ($action === 'update'): ?>
                            <!-- UPDATE MODE -->
                            <h5><?= $dryRun ? '🔍 Dry Run Preview' : '✅ Updating Posts' ?></h5>
                            <?php
                            // Get posts without H2
                            $stmt = $pdo->prepare("
                                SELECT p.*, pt.slug as post_type_slug, c.slug as category_slug
                                FROM posts p
                                LEFT JOIN post_types pt ON p.post_type_id = pt.id
                                LEFT JOIN categories c ON p.category_id = c.id
                                WHERE p.status = 'published'
                                  AND p.deleted_at IS NULL
                                  AND (p.content NOT LIKE '%<h2%' AND p.content NOT LIKE '%<H2%')
                                  AND LENGTH(p.content) > 100
                                ORDER BY p.created_at DESC
                                LIMIT ? OFFSET ?
                            ");
                            $stmt->execute([$limit, $offset]);
                            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $updated = 0;
                            $errors = 0;
                            foreach ($posts as $post):
                                try {
                                    // Enhance content with headings
                                    $enhancedContent = enhanceContentWithHeadings(
                                        $post['content'],
                                        $post['post_type_slug'] ?? 'software',
                                        $post['title']
                                    );
                                    // Count headings
                                    preg_match_all('/<h2/', $enhancedContent, $h2Matches);
                                    preg_match_all('/<h3/', $enhancedContent, $h3Matches);
                                    if (!$dryRun) {
                                        $updateStmt = $pdo->prepare("UPDATE posts SET content = ?, updated_at = NOW() WHERE id = ?");
                                        $updateStmt->execute([$enhancedContent, $post['id']]);
                                        $updated++;
                                    }
                                    ?>
                                    <div class="post-item <?= $dryRun ? 'needs-update' : 'updated' ?> p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($post['title']) ?></h6>
                                                <small class="text-muted">ID: <?= $post['id'] ?> | Type: <?= $post['post_type_slug'] ?></small>
                                            </div>
                                            <span class="badge <?= $dryRun ? 'bg-warning' : 'bg-success' ?>">
                                                <?= $dryRun ? 'Preview' : 'Updated' ?>
                                            </span>
                                        </div>
                                        <div class="heading-preview">
                                            <strong>Added Headings:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>H2 sections: <strong><?= count($h2Matches[0]) ?></strong></li>
                                                <li>H3 sub-sections: <strong><?= count($h3Matches[0]) ?></strong></li>
                                            </ul>
                                        </div>
                                        <?php if ($dryRun): ?>
                                            <details class="mt-2">
                                                <summary class="btn btn-sm btn-outline-primary">View Enhanced Content</summary>
                                                <div class="mt-2 p-3 bg-light border">
                                                    <pre style="max-height: 300px; overflow-y: auto;"><?= htmlspecialchars($enhancedContent) ?></pre>
                                                </div>
                                            </details>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                } catch (Exception $e) {
                                    $errors++;
                                    ?>
                                    <div class="post-item error p-3 mb-3">
                                        <h6><?= htmlspecialchars($post['title']) ?></h6>
                                        <div class="alert alert-danger mb-0">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Error: <?= $e->getMessage() ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            endforeach;
                            ?>
                            <div class="alert alert-info mt-4">
                                <strong>Results:</strong><br>
                                <?= $dryRun ? '👁️ Previewed' : '✅ Updated' ?>: <strong><?= count($posts) ?></strong> posts<br>
                                <?php if (!$dryRun): ?>
                                    ✅ Successful: <strong><?= $updated ?></strong><br>
                                    ❌ Errors: <strong><?= $errors ?></strong>
                                <?php endif; ?>
                            </div>
                            <?php if ($dryRun): ?>
                                <a href="?action=update&limit=<?= $limit ?>&offset=<?= $offset ?>" class="btn btn-success"
                                   onclick="return confirm('Proceed with actual update?')">
                                    <i class="fas fa-check me-2"></i>Confirm & Update
                                </a>
                            <?php else: ?>
                                <a href="?action=scan&offset=<?= $offset + $limit ?>&limit=<?= $limit ?>" class="btn btn-primary">
                                    <i class="fas fa-arrow-right me-2"></i>Next Batch
                                </a>
                            <?php endif; ?>
                            <a href="?action=scan" class="btn btn-secondary">
                                <i class="fas fa-sync me-2"></i>Back to Scan
                            </a>
                        <?php endif; ?>
                        <!-- Pagination -->
                        <div class="mt-4 p-3 bg-light border rounded">
                            <h6>Batch Controls:</h6>
                            <div class="btn-group">
                                <a href="?action=<?= $action ?>&limit=10&offset=0" class="btn btn-sm btn-outline-primary">10 posts</a>
                                <a href="?action=<?= $action ?>&limit=50&offset=0" class="btn btn-sm btn-outline-primary">50 posts</a>
                                <a href="?action=<?= $action ?>&limit=100&offset=0" class="btn btn-sm btn-outline-primary">100 posts</a>
                                <a href="?action=<?= $action ?>&limit=500&offset=0" class="btn btn-sm btn-outline-primary">500 posts</a>
                            </div>
                            <p class="mb-0 mt-2 text-muted small">Current: <?= $limit ?> posts, offset <?= $offset ?></p>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <a href="../posts.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>