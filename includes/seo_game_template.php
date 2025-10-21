<?php

function generateGameContentTemplate($data) {
    // Extract data with defaults
    $title = $data['title'] ?? 'Game Title';
    $genre = $data['genre'] ?? 'Action';
    $platform = $data['platform'] ?? 'PC';
    $developer = $data['developer'] ?? 'Unknown Developer';
    $publisher = $data['publisher'] ?? 'Unknown Publisher';
    $releaseDate = $data['release_date'] ?? date('Y');
    $fileSize = $data['file_size'] ?? '2 GB';
    $version = $data['version'] ?? '1.0';
    $language = $data['language'] ?? 'English, Indonesian';
    $mode = $data['mode'] ?? 'Single Player, Multiplayer';
    // Clean title for keyword
    $cleanTitle = preg_replace('/(download|game|free|gratis|full|version)/i', '', $title);
    $cleanTitle = trim($cleanTitle);
    $content = <<<HTML
<div class="seo-game-content">
<h2>🎮 Tentang {$cleanTitle}</h2>
<p><strong>{$title}</strong> adalah game {$genre} yang dikembangkan oleh <strong>{$developer}</strong> dan diterbitkan oleh <strong>{$publisher}</strong>. Game ini dirilis pada tahun <strong>{$releaseDate}</strong> dan tersedia untuk platform <strong>{$platform}</strong>.</p>
<p>Dengan grafis yang memukau dan gameplay yang seru, {$cleanTitle} menawarkan pengalaman bermain yang tak terlupakan. Game ini mendukung mode <strong>{$mode}</strong>, sehingga Anda bisa bermain sendiri atau bersama teman-teman.</p>
<div class="alert alert-info mt-4 mb-4">
    <i class="fas fa-info-circle"></i> <strong>Informasi Penting:</strong>
    Download {$title} hanya untuk tujuan evaluasi. Jika Anda menyukai game ini, dukung developer dengan membeli versi resminya.
</div>
<h2>📋 Spesifikasi Game {$cleanTitle}</h2>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <td><i class="fas fa-gamepad text-primary"></i> <strong>Nama Game</strong></td>
            <td>{$title}</td>
        </tr>
        <tr>
            <td><i class="fas fa-tag text-primary"></i> <strong>Genre</strong></td>
            <td>{$genre}</td>
        </tr>
        <tr>
            <td><i class="fas fa-desktop text-primary"></i> <strong>Platform</strong></td>
            <td>{$platform}</td>
        </tr>
        <tr>
            <td><i class="fas fa-code text-primary"></i> <strong>Developer</strong></td>
            <td>{$developer}</td>
        </tr>
        <tr>
            <td><i class="fas fa-building text-primary"></i> <strong>Publisher</strong></td>
            <td>{$publisher}</td>
        </tr>
        <tr>
            <td><i class="fas fa-calendar text-primary"></i> <strong>Release Date</strong></td>
            <td>{$releaseDate}</td>
        </tr>
        <tr>
            <td><i class="fas fa-hdd text-primary"></i> <strong>File Size</strong></td>
            <td>{$fileSize}</td>
        </tr>
        <tr>
            <td><i class="fas fa-tag text-primary"></i> <strong>Version</strong></td>
            <td>{$version}</td>
        </tr>
        <tr>
            <td><i class="fas fa-language text-primary"></i> <strong>Language</strong></td>
            <td>{$language}</td>
        </tr>
        <tr>
            <td><i class="fas fa-users text-primary"></i> <strong>Mode</strong></td>
            <td>{$mode}</td>
        </tr>
    </tbody>
</table>
</div>
<h2>⭐ Fitur Utama {$cleanTitle}</h2>
<div class="row">
    <div class="col-md-6">
        <div class="card border-primary mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-star text-warning"></i> Grafis Berkualitas Tinggi</h5>
                <p class="card-text">Nikmati visual yang memukau dengan grafis berkualitas tinggi yang membuat pengalaman bermain lebih realistis dan menarik.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-success mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-gamepad text-danger"></i> Gameplay Seru</h5>
                <p class="card-text">Gameplay yang menantang dan seru dengan berbagai misi, quest, dan tantangan yang membuat Anda ketagihan bermain.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-info mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users text-primary"></i> Mode Multiplayer</h5>
                <p class="card-text">Bermain bersama teman atau pemain lain dari seluruh dunia dalam mode multiplayer yang seru dan kompetitif.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-warning mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-trophy text-success"></i> Achievement System</h5>
                <p class="card-text">Kumpulkan berbagai achievement dan unlock konten eksklusif dengan menyelesaikan misi dan tantangan tertentu.</p>
            </div>
        </div>
    </div>
</div>
<h2>💻 System Requirements {$cleanTitle}</h2>
<div class="row">
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header bg-success text-white"><i class="fas fa-check-circle"></i> Minimum Requirements</div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>OS:</strong> Windows 7/8/10/11 (64-bit)</li>
                    <li><strong>Processor:</strong> Intel Core i3 / AMD Ryzen 3</li>
                    <li><strong>Memory:</strong> 4 GB RAM</li>
                    <li><strong>Graphics:</strong> NVIDIA GTX 660 / AMD Radeon HD 7850</li>
                    <li><strong>DirectX:</strong> Version 11</li>
                    <li><strong>Storage:</strong> {$fileSize} available space</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header bg-primary text-white"><i class="fas fa-star"></i> Recommended Requirements</div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>OS:</strong> Windows 10/11 (64-bit)</li>
                    <li><strong>Processor:</strong> Intel Core i5 / AMD Ryzen 5</li>
                    <li><strong>Memory:</strong> 8 GB RAM</li>
                    <li><strong>Graphics:</strong> NVIDIA GTX 1060 / AMD RX 580</li>
                    <li><strong>DirectX:</strong> Version 12</li>
                    <li><strong>Storage:</strong> SSD with {$fileSize} available space</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<h2>📥 Cara Download {$cleanTitle}</h2>
<div class="accordion" id="downloadAccordion">
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#step1">
                <i class="fas fa-download text-primary me-2"></i> Step 1: Download File Game
            </button>
        </h3>
        <div id="step1" class="accordion-collapse collapse show" data-bs-parent="#downloadAccordion">
            <div class="accordion-body">
                <p>Klik tombol download di atas untuk mengunduh file installer {$title}. Tunggu hingga proses download selesai. Pastikan koneksi internet Anda stabil.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step2">
                <i class="fas fa-file-archive text-success me-2"></i> Step 2: Extract File
            </button>
        </h3>
        <div id="step2" class="accordion-collapse collapse" data-bs-parent="#downloadAccordion">
            <div class="accordion-body">
                <p>Setelah download selesai, extract file menggunakan WinRAR atau 7-Zip. Klik kanan pada file, pilih "Extract Here" atau "Extract to folder".</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step3">
                <i class="fas fa-cog text-warning me-2"></i> Step 3: Install Game
            </button>
        </h3>
        <div id="step3" class="accordion-collapse collapse" data-bs-parent="#downloadAccordion">
            <div class="accordion-body">
                <p>Jalankan file setup atau installer. Ikuti petunjuk instalasi. Pilih lokasi instalasi dan tunggu hingga proses instalasi selesai.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step4">
                <i class="fas fa-play text-danger me-2"></i> Step 4: Play Game
            </button>
        </h3>
        <div id="step4" class="accordion-collapse collapse" data-bs-parent="#downloadAccordion">
            <div class="accordion-body">
                <p>Setelah instalasi selesai, jalankan game dari desktop shortcut atau folder instalasi. Nikmati permainan!</p>
            </div>
        </div>
    </div>
</div>
<h2>❓ FAQ (Frequently Asked Questions)</h2>
<div class="accordion mb-4" id="faqAccordion">
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                Apakah {$cleanTitle} gratis?
            </button>
        </h3>
        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Game ini tersedia untuk evaluasi. Jika Anda menyukainya, kami sangat menyarankan untuk membeli versi resmi untuk mendukung developer.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                Berapa ukuran file {$cleanTitle}?
            </button>
        </h3>
        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Ukuran file download adalah sekitar <strong>{$fileSize}</strong>. Pastikan Anda memiliki ruang penyimpanan yang cukup.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                Apakah {$cleanTitle} support multiplayer?
            </button>
        </h3>
        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Ya, game ini mendukung mode <strong>{$mode}</strong>. Anda bisa bermain sendiri atau bersama teman.
            </div>
        </div>
    </div>
</div>
<div class="alert alert-success">
    <h3><i class="fas fa-download"></i> Download {$title} Sekarang!</h3>
    <p>Dapatkan pengalaman bermain game {$genre} terbaik dengan {$cleanTitle}. Download sekarang dan nikmati petualangan seru!</p>
</div>
<h2>🏷️ Tags & Keywords</h2>
<div class="tags-cloud">
    <span class="badge bg-primary">download {$cleanTitle}</span>
    <span class="badge bg-secondary">{$cleanTitle} gratis</span>
    <span class="badge bg-success">{$cleanTitle} {$platform}</span>
    <span class="badge bg-danger">game {$genre}</span>
    <span class="badge bg-warning text-dark">{$cleanTitle} full version</span>
    <span class="badge bg-info">{$cleanTitle} {$releaseDate}</span>
    <span class="badge bg-dark">{$cleanTitle} terbaru</span>
</div>
</div>
<style>
.seo-game-content h2 {
    color: #2c3e50;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 3px solid #3498db;
}
.seo-game-content h3 {
    color: #34495e;
    font-weight: 600;
    margin-top: 1.5rem;
}
.seo-game-content .table {
    margin: 1.5rem 0;
}
.seo-game-content .card {
    transition: transform 0.3s ease;
}
.seo-game-content .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.tags-cloud {
    margin: 1rem 0;
}
.tags-cloud .badge {
    margin: 0.25rem;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}
</style>
HTML;
    return $content;
}
// Example usage:
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $sampleData = [
        'title' => 'GTA V Premium Edition',
        'genre' => 'Action, Adventure, Open World',
        'platform' => 'PC, PlayStation, Xbox',
        'developer' => 'Rockstar North',
        'publisher' => 'Rockstar Games',
        'release_date' => '2015',
        'file_size' => '94 GB',
        'version' => 'Premium Edition',
        'language' => 'English, Indonesian, Multi-language',
        'mode' => 'Single Player, Online Multiplayer'
    ];
    echo generateGameContentTemplate($sampleData);
}