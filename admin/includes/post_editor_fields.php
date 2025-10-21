<?php
/**
 * Admin Panel - Post Editor Enhancement
 * Add fields for: file_size, version, platform, download_count
 *
 * This file should be integrated into admin/post-editor.php
 */
// Security: Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . SITE_URL . '/admin/login.php');
    exit;
}
// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Handle form submission with security
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }
    // Input Sanitization
    $file_size = !empty($_POST['file_size']) ? htmlspecialchars(trim($_POST['file_size']), ENT_QUOTES, 'UTF-8') : null;
    $version = !empty($_POST['version']) ? htmlspecialchars(trim($_POST['version']), ENT_QUOTES, 'UTF-8') : null;
    $platform = !empty($_POST['platform']) ? htmlspecialchars(trim($_POST['platform']), ENT_QUOTES, 'UTF-8') : null;
    $download_count = !empty($_POST['download_count']) ? (int)$_POST['download_count'] : 0;
    // Validation
    $errors = [];
    // Validate file_size format (e.g., 500MB, 2GB, 1.5GB)
    if (!empty($file_size) && !preg_match('/^\d+(\.\d+)?\s?(MB|GB|KB)$/i', $file_size)) {
        $errors[] = "Format file size tidak valid. Contoh: 500MB, 2GB, 1.5GB";
    }
    // Validate version format (e.g., v1.0.0, 2024, v2024.1)
    if (!empty($version) && !preg_match('/^v?\d+(\.\d+)*$/i', $version)) {
        $errors[] = "Format version tidak valid. Contoh: v1.0.0, 2024, v2024.1";
    }
    // Validate platform
    $valid_platforms = ['Windows', 'Mac', 'Linux', 'Windows/Mac', 'Android', 'iOS', 'Web'];
    if (!empty($platform) && !in_array($platform, $valid_platforms)) {
        $errors[] = "Platform tidak valid";
    }
    // If no errors, proceed with database update
    if (empty($errors)) {
        // Your existing save logic here...
        // Add these fields to your INSERT/UPDATE query
    }
}
?>
<!-- HTML Form Fields to Add to admin/post-editor.php -->
<div class="row mb-3">
    <div class="col-md-12">
        <h5 class="border-bottom pb-2 mb-3">
            <i class="fas fa-info-circle text-info"></i> Meta Information (Optional)
        </h5>
    </div>
</div>
<!-- File Size Field -->
<div class="row mb-3">
    <div class="col-md-4">
        <label for="file_size" class="form-label">
            <i class="fas fa-hdd"></i> File Size
            <small class="text-muted">(e.g., 500MB, 2GB)</small>
        </label>
        <div class="input-group">
            <input type="text"
                   class="form-control"
                   id="file_size"
                   name="file_size"
                   value="<?= htmlspecialchars($post['file_size'] ?? '') ?>"
                   placeholder="500MB"
                   pattern="^\d+(\.\d+)?\s?(MB|GB|KB)$"
                   title="Format: 500MB, 2GB, 1.5GB">
            <span class="input-group-text">
                <i class="fas fa-question-circle" data-bs-toggle="tooltip" title="Contoh: 500MB, 2GB, 1.5GB"></i>
            </span>
        </div>
        <small class="text-muted">Kosongkan jika tidak ada</small>
    </div>
    <!-- Version Field -->
    <div class="col-md-4">
        <label for="version" class="form-label">
            <i class="fas fa-code-branch"></i> Version
            <small class="text-muted">(e.g., v1.0.0, 2024)</small>
        </label>
        <div class="input-group">
            <input type="text"
                   class="form-control"
                   id="version"
                   name="version"
                   value="<?= htmlspecialchars($post['version'] ?? '') ?>"
                   placeholder="v1.0.0"
                   pattern="^v?\d+(\.\d+)*$"
                   title="Format: v1.0.0, 2024, v2024.1">
            <span class="input-group-text">
                <i class="fas fa-question-circle" data-bs-toggle="tooltip" title="Contoh: v1.0.0, 2024"></i>
            </span>
        </div>
        <small class="text-muted">Kosongkan jika tidak ada</small>
    </div>
    <!-- Platform Field -->
    <div class="col-md-4">
        <label for="platform" class="form-label">
            <i class="fas fa-laptop"></i> Platform
        </label>
        <select class="form-select" id="platform" name="platform">
            <option value="">-- Select Platform --</option>
            <option value="Windows" <?= ($post['platform'] ?? '') == 'Windows' ? 'selected' : '' ?>>Windows</option>
            <option value="Mac" <?= ($post['platform'] ?? '') == 'Mac' ? 'selected' : '' ?>>Mac</option>
            <option value="Linux" <?= ($post['platform'] ?? '') == 'Linux' ? 'selected' : '' ?>>Linux</option>
            <option value="Windows/Mac" <?= ($post['platform'] ?? '') == 'Windows/Mac' ? 'selected' : '' ?>>Windows/Mac</option>
            <option value="Android" <?= ($post['platform'] ?? '') == 'Android' ? 'selected' : '' ?>>Android</option>
            <option value="iOS" <?= ($post['platform'] ?? '') == 'iOS' ? 'selected' : '' ?>>iOS</option>
            <option value="Web" <?= ($post['platform'] ?? '') == 'Web' ? 'selected' : '' ?>>Web</option>
        </select>
        <small class="text-muted">Pilih platform yang didukung</small>
    </div>
</div>
<!-- Download Count Field (Admin Only) -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="download_count" class="form-label">
            <i class="fas fa-download"></i> Download Count
            <small class="text-muted">(Admin only)</small>
        </label>
        <input type="number"
               class="form-control"
               id="download_count"
               name="download_count"
               value="<?= htmlspecialchars($post['download_count'] ?? 0) ?>"
               min="0"
               step="1">
        <small class="text-muted">Jumlah download (otomatis di-track)</small>
    </div>
</div>
<?php endif; ?>
<!-- CSRF Token -->
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<!-- JavaScript Validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    // File Size Validation
    const fileSizeInput = document.getElementById('file_size');
    if (fileSizeInput) {
        fileSizeInput.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && !value.match(/^\d+(\.\d+)?\s?(MB|GB|KB)$/i)) {
                this.classList.add('is-invalid');
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Format tidak valid. Contoh: 500MB, 2GB';
                    this.parentNode.appendChild(feedback);
                }
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    // Version Validation
    const versionInput = document.getElementById('version');
    if (versionInput) {
        versionInput.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && !value.match(/^v?\d+(\.\d+)*$/i)) {
                this.classList.add('is-invalid');
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Format tidak valid. Contoh: v1.0.0, 2024';
                    this.parentNode.appendChild(feedback);
                }
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    // Auto-format file size
    fileSizeInput?.addEventListener('input', function() {
        let value = this.value.toUpperCase();
        // Auto-add space before unit if not present
        value = value.replace(/(\d)(MB|GB|KB)/i, '$1 $2');
        this.value = value;
    });
});
</script>
<style>
/* Admin Panel Enhancement Styles */
.meta-info-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border: 1px solid #dee2e6;
}
.input-group-text {
    cursor: help;
}
.form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
.invalid-feedback {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}
</style>