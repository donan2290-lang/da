<?php

function generateMobileAppsContentTemplate($data) {
    // Extract data with defaults
    $title = $data['title'] ?? 'App Title';
    $appName = $data['app_name'] ?? preg_replace('/(download|apk|gratis|free|latest|terbaru)/i', '', $title);
    $appName = trim($appName);
    $version = $data['version'] ?? 'Latest Version';
    $developer = $data['developer'] ?? 'Unknown Developer';
    $fileSize = $data['file_size'] ?? '50 MB';
    $platform = $data['platform'] ?? 'Android, iOS';
    $category = $data['category'] ?? 'Social Media';
    $rating = $data['rating'] ?? '4.5';
    $downloads = $data['downloads'] ?? '100M+';
    $requiresAndroid = $data['requires_android'] ?? 'Android 5.0+';
    $requiresIos = $data['requires_ios'] ?? 'iOS 12.0+';
    $language = $data['language'] ?? 'English, Indonesian, Multi-language';
    $content = <<<HTML
<div class="seo-mobile-app-content">
<h2>📱 Tentang {$appName}</h2>
<p><strong>{$appName}</strong> adalah aplikasi mobile populer yang dikembangkan oleh <strong>{$developer}</strong> dengan rating <strong>{$rating}/5.0</strong> dari jutaan pengguna. Aplikasi ini tersedia untuk platform <strong>{$platform}</strong> dan telah diunduh lebih dari <strong>{$downloads}</strong> kali di seluruh dunia.</p>
<p>Dengan interface yang user-friendly dan fitur-fitur canggih, {$appName} menjadi pilihan utama untuk kebutuhan <strong>{$category}</strong> Anda. Download {$appName} sekarang dan nikmati pengalaman terbaik di perangkat mobile Anda!</p>
<div class="alert alert-info mt-4 mb-4">
    <i class="fas fa-info-circle"></i> <strong>Informasi Penting:</strong>
    Download {$appName} versi resmi dan terpercaya. Update rutin untuk fitur terbaru dan keamanan optimal.
</div>
<h2>📋 Informasi Aplikasi {$appName}</h2>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <td><i class="fas fa-mobile-alt text-primary"></i> <strong>Nama Aplikasi</strong></td>
            <td>{$appName}</td>
        </tr>
        <tr>
            <td><i class="fas fa-tag text-primary"></i> <strong>Versi</strong></td>
            <td>{$version}</td>
        </tr>
        <tr>
            <td><i class="fas fa-code text-primary"></i> <strong>Developer</strong></td>
            <td>{$developer}</td>
        </tr>
        <tr>
            <td><i class="fas fa-folder text-primary"></i> <strong>Kategori</strong></td>
            <td>{$category}</td>
        </tr>
        <tr>
            <td><i class="fas fa-desktop text-primary"></i> <strong>Platform</strong></td>
            <td>{$platform}</td>
        </tr>
        <tr>
            <td><i class="fas fa-hdd text-primary"></i> <strong>Ukuran File</strong></td>
            <td>{$fileSize}</td>
        </tr>
        <tr>
            <td><i class="fas fa-star text-warning"></i> <strong>Rating</strong></td>
            <td>{$rating}/5.0 ⭐⭐⭐⭐⭐</td>
        </tr>
        <tr>
            <td><i class="fas fa-download text-success"></i> <strong>Total Download</strong></td>
            <td>{$downloads}</td>
        </tr>
        <tr>
            <td><i class="fas fa-language text-primary"></i> <strong>Bahasa</strong></td>
            <td>{$language}</td>
        </tr>
        <tr>
            <td><i class="fas fa-android text-success"></i> <strong>Android</strong></td>
            <td>{$requiresAndroid}</td>
        </tr>
        <tr>
            <td><i class="fab fa-apple text-dark"></i> <strong>iOS</strong></td>
            <td>{$requiresIos}</td>
        </tr>
    </tbody>
</table>
</div>
<h2>⭐ Fitur Unggulan {$appName}</h2>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card border-primary h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-bolt text-warning"></i> Performa Cepat & Ringan</h5>
                <p class="card-text">Interface responsif dengan loading cepat. Tidak membebani RAM dan baterai smartphone Anda.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-success h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-shield-alt text-success"></i> Keamanan Terjamin</h5>
                <p class="card-text">Enkripsi end-to-end untuk melindungi data dan privasi Anda. Update security rutin dari developer.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-info h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-palette text-info"></i> Interface Modern</h5>
                <p class="card-text">Desain UI/UX yang modern, intuitif, dan mudah digunakan untuk semua kalangan.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-warning h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-sync text-primary"></i> Update Berkala</h5>
                <p class="card-text">Developer aktif merilis update dengan fitur baru, perbaikan bug, dan peningkatan performa.</p>
            </div>
        </div>
    </div>
</div>
<h2>🎯 Keunggulan {$appName}</h2>
<div class="card bg-light mb-4">
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> <strong>Gratis Download:</strong> Tersedia gratis di Play Store dan App Store</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> <strong>User-Friendly:</strong> Mudah digunakan bahkan untuk pemula</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> <strong>Multifungsi:</strong> Berbagai fitur lengkap dalam satu aplikasi</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> <strong>Offline Mode:</strong> Beberapa fitur bisa digunakan tanpa internet</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> <strong>Cloud Sync:</strong> Backup otomatis ke cloud storage</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> <strong>Cross-Platform:</strong> Sinkronisasi antar device seamless</li>
            <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> <strong>Support 24/7:</strong> Customer service responsif dan helpful</li>
        </ul>
    </div>
</div>
<h2>📱 Cara Download & Install {$appName}</h2>
<h3>Untuk Pengguna Android:</h3>
<div class="accordion mb-3" id="androidAccordion">
    <div class="accordion-item">
        <h4 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#android1">
                <i class="fab fa-google-play text-success me-2"></i> Step 1: Buka Google Play Store
            </button>
        </h4>
        <div id="android1" class="accordion-collapse collapse show" data-bs-parent="#androidAccordion">
            <div class="accordion-body">
                <p>Buka aplikasi Google Play Store di smartphone Android Anda. Pastikan Anda sudah login dengan akun Google.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h4 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#android2">
                <i class="fas fa-search text-primary me-2"></i> Step 2: Cari {$appName}
            </button>
        </h4>
        <div id="android2" class="accordion-collapse collapse" data-bs-parent="#androidAccordion">
            <div class="accordion-body">
                <p>Ketik "<strong>{$appName}</strong>" di kolom pencarian. Pilih aplikasi resmi dari developer <strong>{$developer}</strong>.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h4 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#android3">
                <i class="fas fa-download text-success me-2"></i> Step 3: Install Aplikasi
            </button>
        </h4>
        <div id="android3" class="accordion-collapse collapse" data-bs-parent="#androidAccordion">
            <div class="accordion-body">
                <p>Klik tombol "Install". Tunggu proses download dan instalasi selesai (sekitar {$fileSize}).</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h4 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#android4">
                <i class="fas fa-play text-danger me-2"></i> Step 4: Buka & Gunakan
            </button>
        </h4>
        <div id="android4" class="accordion-collapse collapse" data-bs-parent="#androidAccordion">
            <div class="accordion-body">
                <p>Setelah instalasi selesai, klik "Open" atau cari icon {$appName} di home screen. Daftar/login dan mulai gunakan!</p>
            </div>
        </div>
    </div>
</div>
<h3>Untuk Pengguna iOS (iPhone/iPad):</h3>
<div class="accordion mb-4" id="iosAccordion">
    <div class="accordion-item">
        <h4 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ios1">
                <i class="fab fa-app-store text-primary me-2"></i> Step 1: Buka App Store
            </button>
        </h4>
        <div id="ios1" class="accordion-collapse collapse show" data-bs-parent="#iosAccordion">
            <div class="accordion-body">
                <p>Buka aplikasi App Store di iPhone atau iPad Anda. Login dengan Apple ID jika belum.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h4 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ios2">
                <i class="fas fa-search text-info me-2"></i> Step 2: Cari Aplikasi
            </button>
        </h4>
        <div id="ios2" class="accordion-collapse collapse" data-bs-parent="#iosAccordion">
            <div class="accordion-body">
                <p>Tap icon search, ketik "{$appName}". Pastikan memilih aplikasi dari developer resmi.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h4 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ios3">
                <i class="fas fa-download text-success me-2"></i> Step 3: Download & Install
            </button>
        </h4>
        <div id="ios3" class="accordion-collapse collapse" data-bs-parent="#iosAccordion">
            <div class="accordion-body">
                <p>Tap tombol "GET" atau icon cloud. Verifikasi dengan Face ID/Touch ID/Password. Tunggu instalasi selesai.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h4 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ios4">
                <i class="fas fa-mobile-alt text-primary me-2"></i> Step 4: Launch App
            </button>
        </h4>
        <div id="ios4" class="accordion-collapse collapse" data-bs-parent="#iosAccordion">
            <div class="accordion-body">
                <p>Tap "Open" atau cari icon di home screen. Setup akun dan mulai explore fitur-fitur {$appName}!</p>
            </div>
        </div>
    </div>
</div>
<h2>❓ FAQ - Pertanyaan Umum</h2>
<div class="accordion mb-4" id="faqAccordion">
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                Apakah {$appName} gratis?
            </button>
        </h3>
        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Ya, {$appName} dapat didownload dan digunakan secara <strong>gratis</strong>. Namun mungkin ada fitur premium atau in-app purchase untuk fitur tambahan.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                Berapa ukuran file {$appName}?
            </button>
        </h3>
        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Ukuran file {$appName} sekitar <strong>{$fileSize}</strong>. Ukuran bisa bervariasi tergantung versi dan platform yang digunakan.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                Apakah {$appName} aman untuk digunakan?
            </button>
        </h3>
        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Ya, {$appName} dari developer resmi <strong>{$developer}</strong> sangat aman. Aplikasi ini menggunakan enkripsi dan telah lolos verifikasi Google/Apple.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                Bisa digunakan tanpa internet?
            </button>
        </h3>
        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Beberapa fitur {$appName} bisa digunakan secara offline, namun untuk fitur lengkap disarankan menggunakan koneksi internet.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                Versi minimum Android/iOS yang didukung?
            </button>
        </h3>
        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Untuk Android: <strong>{$requiresAndroid}</strong> atau lebih tinggi.<br>
                Untuk iOS: <strong>{$requiresIos}</strong> atau lebih tinggi.
            </div>
        </div>
    </div>
</div>
<h2>💡 Tips Menggunakan {$appName}</h2>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card h-100 border-info">
            <div class="card-body text-center">
                <i class="fas fa-sync-alt fa-3x text-info mb-3"></i>
                <h5 class="card-title">Update Rutin</h5>
                <p class="card-text">Selalu update ke versi terbaru untuk fitur baru dan keamanan optimal.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 border-warning">
            <div class="card-body text-center">
                <i class="fas fa-cloud fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Aktifkan Backup</h5>
                <p class="card-text">Enable cloud backup untuk menjaga data Anda tetap aman.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 border-success">
            <div class="card-body text-center">
                <i class="fas fa-cog fa-3x text-success mb-3"></i>
                <h5 class="card-title">Atur Notifikasi</h5>
                <p class="card-text">Customize notifikasi sesuai kebutuhan agar tidak mengganggu.</p>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-success mt-4">
    <h3><i class="fas fa-download"></i> Download {$appName} Sekarang!</h3>
    <p>Dapatkan aplikasi {$category} terbaik untuk smartphone Anda. Download {$appName} gratis dan nikmati fitur-fitur canggih!</p>
    <div class="d-flex gap-3 mt-3">
        <a href="https://play.google.com" target="_blank" class="btn btn-success btn-lg">
            <i class="fab fa-google-play me-2"></i> Google Play
        </a>
        <a href="https://apps.apple.com" target="_blank" class="btn btn-primary btn-lg">
            <i class="fab fa-app-store me-2"></i> App Store
        </a>
    </div>
</div>
<h2>🏷️ Tags & Keywords</h2>
<div class="tags-cloud">
    <span class="badge bg-primary">download {$appName}</span>
    <span class="badge bg-secondary">{$appName} gratis</span>
    <span class="badge bg-success">{$appName} apk</span>
    <span class="badge bg-danger">{$appName} {$platform}</span>
    <span class="badge bg-warning text-dark">{$appName} latest version</span>
    <span class="badge bg-info">{$appName} {$version}</span>
    <span class="badge bg-dark">aplikasi {$category}</span>
</div>
</div>
<style>
.seo-mobile-app-content h2 {
    color: #2c3e50;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 3px solid #3498db;
}
.seo-mobile-app-content h3, .seo-mobile-app-content h4 {
    color: #34495e;
    font-weight: 600;
    margin-top: 1.5rem;
}
.seo-mobile-app-content .table {
    margin: 1.5rem 0;
}
.seo-mobile-app-content .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.seo-mobile-app-content .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}
.tags-cloud {
    margin: 1rem 0;
}
.tags-cloud .badge {
    margin: 0.25rem;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}
.accordion-button:not(.collapsed) {
    background-color: #e7f3ff;
    color: #0066cc;
}
</style>
HTML;
    return $content;
}
// Example usage:
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
    echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>";
    echo "</head><body><div class='container my-5'>";
    $sampleData = [
        'title' => 'Download WhatsApp Business APK Latest Version',
        'app_name' => 'WhatsApp Business',
        'version' => 'v2.25.1.78',
        'developer' => 'WhatsApp LLC (Meta)',
        'file_size' => '60 MB',
        'platform' => 'Android, iOS',
        'category' => 'Communication, Business',
        'rating' => '4.3',
        'downloads' => '500M+',
        'requires_android' => 'Android 5.0+',
        'requires_ios' => 'iOS 12.0+',
        'language' => 'English, Indonesian, 60+ languages'
    ];
    echo generateMobileAppsContentTemplate($sampleData);
    echo "</div>";
    echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
    echo "</body></html>";
}
?>