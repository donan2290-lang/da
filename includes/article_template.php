<?php

function generateSoftwareArticleTemplate($data) {
    $softwareName = $data['title'] ?? 'Software';
    $version = $data['version'] ?? 'Latest Version';
    $category = $data['category'] ?? 'Software';
    $developer = $data['developer'] ?? 'Developer';
    $fileSize = $data['file_size'] ?? '100 MB';
    $releaseDate = $data['release_date'] ?? date('Y');
    $template = <<<HTML
<article class="software-article">
    <section class="article-intro">
        <p class="lead-paragraph">
            <strong>Download {$softwareName} {$version}</strong> - Dapatkan software terbaik untuk kebutuhan Anda.
            {$softwareName} adalah aplikasi profesional yang dikembangkan oleh {$developer} dengan fitur-fitur
            canggih dan performa optimal. Download gratis full version dengan crack di DONAN22.
        </p>
    </section>
    <section class="about-section">
        <h2><i class="fas fa-info-circle"></i> Tentang {$softwareName}</h2>
        <p>
            {$softwareName} merupakan salah satu software {$category} terpopuler yang digunakan oleh jutaan pengguna
            di seluruh dunia. Dengan antarmuka yang user-friendly dan fitur-fitur lengkap, {$softwareName} menjadi
            pilihan utama baik untuk pengguna pemula maupun profesional.
        </p>
        <p>
            Versi terbaru {$version} hadir dengan berbagai peningkatan performa dan fitur baru yang memudahkan
            pekerjaan Anda. Software ini telah dioptimalkan untuk bekerja dengan lancar di Windows 10 dan Windows 11,
            serta mendukung berbagai format file yang umum digunakan.
        </p>
        <p>
            Dikembangkan oleh {$developer}, {$softwareName} terus diperbarui untuk memberikan pengalaman terbaik
            kepada penggunanya. Dengan ukuran file yang relatif ringan ({$fileSize}), software ini mudah untuk
            didownload dan diinstall di komputer Anda.
        </p>
    </section>
    <section class="features-section">
        <h2><i class="fas fa-star"></i> Fitur Utama {$softwareName}</h2>
        <div class="features-grid">
            <div class="feature-item">
                <h3><i class="fas fa-check-circle"></i> Antarmuka Modern</h3>
                <p>Desain interface yang clean dan intuitive memudahkan navigasi dan penggunaan semua fitur.</p>
            </div>
            <div class="feature-item">
                <h3><i class="fas fa-check-circle"></i> Performa Cepat</h3>
                <p>Dioptimalkan untuk kecepatan maksimal dengan penggunaan resource yang efisien.</p>
            </div>
            <div class="feature-item">
                <h3><i class="fas fa-check-circle"></i> Multi Format Support</h3>
                <p>Mendukung berbagai format file populer untuk fleksibilitas maksimal.</p>
            </div>
            <div class="feature-item">
                <h3><i class="fas fa-check-circle"></i> Tools Lengkap</h3>
                <p>Dilengkapi dengan tools dan plugin yang membantu menyelesaikan tugas dengan cepat.</p>
            </div>
            <div class="feature-item">
                <h3><i class="fas fa-check-circle"></i> Regular Updates</h3>
                <p>Update berkala untuk bug fixes dan penambahan fitur baru.</p>
            </div>
            <div class="feature-item">
                <h3><i class="fas fa-check-circle"></i> Cross-Platform</h3>
                <p>Kompatibel dengan berbagai sistem operasi Windows modern.</p>
            </div>
        </div>
    </section>
    <section class="system-requirements">
        <h2><i class="fas fa-desktop"></i> System Requirements</h2>
        <div class="requirements-grid">
            <div class="req-box">
                <h3>Minimum Requirements</h3>
                <ul>
                    <li><strong>OS:</strong> Windows 10 (64-bit)</li>
                    <li><strong>Processor:</strong> Intel Core i3 / AMD Ryzen 3</li>
                    <li><strong>RAM:</strong> 4 GB</li>
                    <li><strong>Storage:</strong> 2 GB available space</li>
                    <li><strong>Graphics:</strong> Intel HD Graphics 4000</li>
                    <li><strong>Display:</strong> 1280x720 resolution</li>
                </ul>
            </div>
            <div class="req-box recommended">
                <h3>Recommended Requirements</h3>
                <ul>
                    <li><strong>OS:</strong> Windows 11 (64-bit)</li>
                    <li><strong>Processor:</strong> Intel Core i5 / AMD Ryzen 5</li>
                    <li><strong>RAM:</strong> 8 GB or more</li>
                    <li><strong>Storage:</strong> 5 GB available space (SSD)</li>
                    <li><strong>Graphics:</strong> NVIDIA GTX 1050 / AMD RX 560</li>
                    <li><strong>Display:</strong> 1920x1080 Full HD</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="whats-new">
        <h2><i class="fas fa-rocket"></i> Apa yang Baru di Versi {$version}?</h2>
        <ul class="update-list">
            <li><i class="fas fa-plus-circle"></i> Peningkatan performa hingga 30% lebih cepat</li>
            <li><i class="fas fa-plus-circle"></i> Interface baru dengan desain modern dan fresh</li>
            <li><i class="fas fa-plus-circle"></i> Penambahan fitur AI-powered tools</li>
            <li><i class="fas fa-plus-circle"></i> Dukungan untuk format file terbaru</li>
            <li><i class="fas fa-plus-circle"></i> Bug fixes dan stability improvements</li>
            <li><i class="fas fa-plus-circle"></i> Optimasi untuk Windows 11</li>
            <li><i class="fas fa-plus-circle"></i> Security patches dan updates</li>
        </ul>
    </section>
    <section class="installation-guide">
        <h2><i class="fas fa-download"></i> Cara Download dan Install {$softwareName}</h2>
        <div class="step-box">
            <h3>Step 1: Download File Installer</h3>
            <ol>
                <li>Klik tombol download di bawah artikel ini</li>
                <li>Tunggu proses download selesai (ukuran file: {$fileSize})</li>
                <li>Simpan file di folder yang mudah diakses</li>
            </ol>
            <div class="note-box">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Pastikan koneksi internet Anda stabil untuk proses download yang lancar.
            </div>
        </div>
        <div class="step-box">
            <h3>Step 2: Extract File Archive</h3>
            <ol>
                <li>Klik kanan pada file yang sudah didownload</li>
                <li>Pilih "Extract Here" atau "Extract to folder"</li>
                <li>Tunggu proses ekstraksi selesai</li>
            </ol>
            <div class="note-box">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Gunakan WinRAR atau 7-Zip untuk extract file archive.
            </div>
        </div>
        <div class="step-box">
            <h3>Step 3: Install Software</h3>
            <ol>
                <li>Matikan antivirus sementara (sangat penting!)</li>
                <li>Jalankan file installer sebagai Administrator</li>
                <li>Ikuti wizard instalasi hingga selesai</li>
                <li>Jangan jalankan software dulu setelah install</li>
            </ol>
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Penting:</strong> Matikan antivirus dan Windows Defender untuk menghindari file crack terhapus.
            </div>
        </div>
        <div class="step-box">
            <h3>Step 4: Aktivasi / Apply Crack</h3>
            <ol>
                <li>Buka folder Crack yang sudah di-extract</li>
                <li>Copy semua file di folder Crack</li>
                <li>Paste ke folder instalasi software (C:\Program Files\{$softwareName})</li>
                <li>Pilih "Replace" jika ada konfirmasi</li>
                <li>Jalankan software dan nikmati full version gratis!</li>
            </ol>
            <div class="success-box">
                <i class="fas fa-check-circle"></i>
                <strong>Selesai:</strong> {$softwareName} sudah siap digunakan dengan full features!
            </div>
        </div>
    </section>
    <section class="tips-section">
        <h2><i class="fas fa-lightbulb"></i> Tips Menggunakan {$softwareName}</h2>
        <div class="tips-grid">
            <div class="tip-item">
                <h4>1. Pelajari Keyboard Shortcuts</h4>
                <p>Gunakan keyboard shortcuts untuk meningkatkan produktivitas dan efisiensi kerja Anda.</p>
            </div>
            <div class="tip-item">
                <h4>2. Manfaatkan Templates</h4>
                <p>Gunakan template yang sudah disediakan untuk mempercepat proses pembuatan project.</p>
            </div>
            <div class="tip-item">
                <h4>3. Backup Project Secara Berkala</h4>
                <p>Selalu backup project Anda untuk menghindari kehilangan data yang tidak diinginkan.</p>
            </div>
            <div class="tip-item">
                <h4>4. Update Secara Berkala</h4>
                <p>Check update terbaru dari DONAN22 untuk mendapatkan versi dengan fitur dan bug fixes terbaru.</p>
            </div>
            <div class="tip-item">
                <h4>5. Bergabung dengan Community</h4>
                <p>Join forum atau grup pengguna untuk sharing tips dan troubleshooting masalah.</p>
            </div>
            <div class="tip-item">
                <h4>6. Optimasi Settings</h4>
                <p>Sesuaikan settings software sesuai dengan spesifikasi komputer Anda untuk performa optimal.</p>
            </div>
        </div>
    </section>
    <section class="conclusion">
        <h2><i class="fas fa-flag-checkered"></i> Kesimpulan</h2>
        <p>
            {$softwareName} {$version} adalah pilihan tepat untuk Anda yang membutuhkan software {$category}
            berkualitas tinggi dengan fitur lengkap. Dengan antarmuka yang user-friendly dan performa yang cepat,
            software ini cocok untuk berbagai kebutuhan dari pemula hingga profesional.
        </p>
        <p>
            Download {$softwareName} full version gratis dengan crack melalui link yang tersedia di DONAN22.
            Ikuti panduan instalasi di atas dengan teliti untuk proses aktivasi yang sukses. Selamat menggunakan
            dan semoga bermanfaat!
        </p>
    </section>
    <section class="download-section">
        <div class="download-box">
            <h2><i class="fas fa-download"></i> Download {$softwareName} {$version}</h2>
            <div class="download-info">
                <div class="info-item">
                    <span class="label">Version:</span>
                    <span class="value">{$version}</span>
                </div>
                <div class="info-item">
                    <span class="label">File Size:</span>
                    <span class="value">{$fileSize}</span>
                </div>
                <div class="info-item">
                    <span class="label">Developer:</span>
                    <span class="value">{$developer}</span>
                </div>
                <div class="info-item">
                    <span class="label">Release Date:</span>
                    <span class="value">{$releaseDate}</span>
                </div>
            </div>
            <a href="#" class="download-btn">
                <i class="fas fa-cloud-download-alt"></i> Download Now
            </a>
            <p class="download-note">Password: <strong>donan22.com</strong></p>
        </div>
    </section>
</article>
<style>
.software-article {
    max-width: 900px;
    margin: 0 auto;
    line-height: 1.8;
    color: #1e293b;
}
.software-article section {
    margin: 40px 0;
}
.software-article h2 {
    color: #1e293b;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 3px solid #3b82f6;
    padding-bottom: 10px;
}
.software-article h2 i {
    color: #3b82f6;
}
.software-article h3 {
    color: #1e40af;
    font-size: 1.3rem;
    font-weight: 600;
    margin: 20px 0 15px;
}
.lead-paragraph {
    font-size: 1.15rem;
    color: #475569;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #3b82f6;
}
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.feature-item {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-left: 3px solid #3b82f6;
    transition: transform 0.3s ease;
}
.feature-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(59,130,246,0.15);
}
.feature-item h3 {
    color: #1e40af;
    font-size: 1.1rem;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.feature-item h3 i {
    color: #10b981;
}
.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.req-box {
    background: #f8fafc;
    padding: 20px;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
}
.req-box.recommended {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border-color: #10b981;
}
.req-box h3 {
    color: #1e293b;
    margin-bottom: 15px;
}
.req-box ul {
    list-style: none;
    padding: 0;
}
.req-box li {
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}
.req-box li:last-child {
    border-bottom: none;
}
.update-list {
    list-style: none;
    padding: 0;
}
.update-list li {
    padding: 12px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #e2e8f0;
}
.update-list li i {
    color: #10b981;
    font-size: 1.2rem;
}
.step-box {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 20px;
}
.step-box h3 {
    color: #1e40af;
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 1.3rem;
}
.step-box ol {
    margin: 15px 0;
    padding-left: 25px;
}
.step-box ol li {
    margin: 10px 0;
    line-height: 1.6;
}
.note-box, .warning-box, .success-box {
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
    display: flex;
    align-items: start;
    gap: 10px;
}
.note-box {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
}
.warning-box {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
}
.success-box {
    background: #d1fae5;
    border-left: 4px solid #10b981;
}
.note-box i, .warning-box i, .success-box i {
    font-size: 1.2rem;
    margin-top: 2px;
}
.note-box i {
    color: #3b82f6;
}
.warning-box i {
    color: #f59e0b;
}
.success-box i {
    color: #10b981;
}
.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.tip-item {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 20px;
    border-radius: 10px;
    border-left: 3px solid #f59e0b;
}
.tip-item h4 {
    color: #92400e;
    margin-bottom: 10px;
}
.tip-item p {
    color: #78350f;
    margin: 0;
    font-size: 0.95rem;
}
.download-box {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    color: white;
    padding: 40px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(59,130,246,0.3);
}
.download-box h2 {
    color: white;
    border: none;
    justify-content: center;
    margin-bottom: 25px;
}
.download-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 25px 0;
    background: rgba(255,255,255,0.1);
    padding: 20px;
    border-radius: 10px;
}
.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.info-item .label {
    font-size: 0.85rem;
    opacity: 0.9;
}
.info-item .value {
    font-weight: 600;
    font-size: 1.1rem;
}
.download-btn {
    display: inline-block;
    background: white;
    color: #1e40af;
    padding: 15px 40px;
    border-radius: 50px;
    font-size: 1.2rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    margin: 20px 0;
}
.download-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(255,255,255,0.3);
}
.download-note {
    margin-top: 15px;
    font-size: 0.95rem;
    opacity: 0.9;
}
@media (max-width: 768px) {
    .software-article h2 {
        font-size: 1.5rem;
    }
    .features-grid,
    .requirements-grid,
    .tips-grid {
        grid-template-columns: 1fr;
    }
    .download-box {
        padding: 25px;
    }
}
</style>
HTML;
    return $template;
}
function generateGameArticleTemplate($data) {
    $gameName = $data['title'] ?? 'Game';
    $version = $data['version'] ?? 'Latest Version';
    $developer = $data['developer'] ?? 'Developer';
    $fileSize = $data['file_size'] ?? '10 GB';
    // Similar structure to software template but customized for games
    // Implementation similar to above with game-specific sections
    return generateSoftwareArticleTemplate($data); // Simplified for this example
}
function generateBlogArticleTemplate($data) {
    $title = $data['title'] ?? 'Tutorial';
    $topic = $data['topic'] ?? 'topik ini';
    $category = $data['category'] ?? 'Tutorial';
    $difficulty = $data['difficulty'] ?? 'Pemula';
    $duration = $data['duration'] ?? '10 menit';
    $template = <<<HTML
<article class="blog-article">
    <section class="article-intro">
        <div class="article-meta">
            <span class="meta-item"><i class="fas fa-folder"></i> {$category}</span>
            <span class="meta-item"><i class="fas fa-signal"></i> {$difficulty}</span>
            <span class="meta-item"><i class="fas fa-clock"></i> {$duration}</span>
        </div>
        <p class="lead-paragraph">
            Dalam panduan lengkap ini, Anda akan mempelajari <strong>{$topic}</strong> dari dasar hingga mahir.
            Tutorial ini dirancang khusus untuk memudahkan pemahaman dengan langkah-langkah praktis dan
            contoh yang jelas. Mari kita mulai!
        </p>
    </section>
    <section class="table-of-contents">
        <h2><i class="fas fa-list"></i> Daftar Isi</h2>
        <ul class="toc-list">
            <li><a href="#pengertian">Pengertian dan Dasar</a></li>
            <li><a href="#manfaat">Manfaat dan Kegunaan</a></li>
            <li><a href="#persiapan">Persiapan yang Diperlukan</a></li>
            <li><a href="#langkah">Langkah-langkah Praktis</a></li>
            <li><a href="#tips">Tips dan Trik</a></li>
            <li><a href="#troubleshooting">Troubleshooting</a></li>
            <li><a href="#kesimpulan">Kesimpulan</a></li>
        </ul>
    </section>
    <section class="content-section" id="pengertian">
        <h2><i class="fas fa-book-open"></i> Pengertian dan Dasar</h2>
        <p>
            Sebelum kita masuk ke tutorial praktis, penting untuk memahami konsep dasar terlebih dahulu.
            {$topic} adalah metode/teknik/cara yang digunakan untuk mencapai hasil tertentu dengan
            efisien dan efektif.
        </p>
        <div class="info-box">
            <h3><i class="fas fa-lightbulb"></i> Tahukah Anda?</h3>
            <p>
                Memahami konsep dasar akan membantu Anda menguasai teknik lanjutan dengan lebih mudah
                dan cepat. Jangan skip bagian ini!
            </p>
        </div>
        <p>
            Dengan pemahaman yang solid tentang dasar-dasarnya, Anda akan lebih mudah mengikuti
            langkah-langkah selanjutnya dan menghindari kesalahan umum yang sering terjadi.
        </p>
    </section>
    <section class="content-section" id="manfaat">
        <h2><i class="fas fa-star"></i> Manfaat dan Kegunaan</h2>
        <p>
            Menguasai {$topic} memberikan banyak keuntungan dan manfaat, baik untuk keperluan
            pribadi maupun profesional. Berikut adalah beberapa manfaat utama:
        </p>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
                <h3>Efisiensi Waktu</h3>
                <p>Menghemat waktu dengan cara yang lebih cepat dan praktis</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
                <h3>Hasil Profesional</h3>
                <p>Menghasilkan output berkualitas tinggi seperti profesional</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
                <h3>Skill Baru</h3>
                <p>Menambah kemampuan dan skill yang berguna di dunia kerja</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
                <h3>Problem Solving</h3>
                <p>Menyelesaikan masalah dengan pendekatan yang tepat</p>
            </div>
        </div>
    </section>
    <section class="content-section" id="persiapan">
        <h2><i class="fas fa-tasks"></i> Persiapan yang Diperlukan</h2>
        <p>
            Sebelum memulai, pastikan Anda telah mempersiapkan hal-hal berikut untuk kelancaran
            proses pembelajaran:
        </p>
        <div class="preparation-checklist">
            <div class="checklist-item">
                <i class="fas fa-check-square"></i>
                <div class="checklist-content">
                    <h4>Tools dan Software</h4>
                    <p>Siapkan aplikasi atau tools yang diperlukan. Download dan install terlebih dahulu.</p>
                </div>
            </div>
            <div class="checklist-item">
                <i class="fas fa-check-square"></i>
                <div class="checklist-content">
                    <h4>Pengetahuan Dasar</h4>
                    <p>Pahami konsep fundamental yang akan digunakan dalam tutorial ini.</p>
                </div>
            </div>
            <div class="checklist-item">
                <i class="fas fa-check-square"></i>
                <div class="checklist-content">
                    <h4>Waktu dan Fokus</h4>
                    <p>Alokasikan waktu sekitar {$duration} untuk menyelesaikan tutorial ini tanpa gangguan.</p>
                </div>
            </div>
            <div class="checklist-item">
                <i class="fas fa-check-square"></i>
                <div class="checklist-content">
                    <h4>Material Latihan</h4>
                    <p>Siapkan file atau material yang akan digunakan untuk praktik.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="content-section" id="langkah">
        <h2><i class="fas fa-route"></i> Langkah-langkah Praktis</h2>
        <p>
            Ikuti langkah-langkah berikut dengan teliti untuk hasil yang optimal. Setiap langkah
            dilengkapi dengan penjelasan detail dan tips praktis.
        </p>
        <div class="step-container">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Langkah Pertama: Persiapan Awal</h3>
                <p>
                    Mulai dengan membuka aplikasi atau tools yang akan digunakan. Pastikan semua
                    pengaturan sudah sesuai dengan kebutuhan Anda.
                </p>
                <div class="step-tip">
                    <i class="fas fa-lightbulb"></i>
                    <strong>Tips:</strong> Simpan project Anda secara berkala untuk menghindari kehilangan data.
                </div>
            </div>
        </div>
        <div class="step-container">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Langkah Kedua: Konfigurasi Dasar</h3>
                <p>
                    Sesuaikan pengaturan dasar sesuai dengan kebutuhan project Anda. Hal ini penting
                    untuk memastikan hasil akhir sesuai dengan yang diharapkan.
                </p>
                <div class="step-example">
                    <strong>Contoh:</strong> Jika membuat desain, atur ukuran canvas, resolusi, dan color mode.
                </div>
            </div>
        </div>
        <div class="step-container">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Langkah Ketiga: Eksekusi Utama</h3>
                <p>
                    Ini adalah bagian inti dari tutorial. Lakukan proses utama dengan mengikuti
                    instruksi dengan teliti dan hati-hati.
                </p>
                <div class="step-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Jangan skip langkah ini karena sangat penting untuk hasil akhir.
                </div>
            </div>
        </div>
        <div class="step-container">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Langkah Keempat: Finalisasi dan Review</h3>
                <p>
                    Setelah proses utama selesai, lakukan pengecekan dan finalisasi. Pastikan semua
                    elemen sudah sesuai dengan yang diinginkan.
                </p>
                <div class="step-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Selesai!</strong> Anda berhasil menyelesaikan tutorial ini. Save hasil kerja Anda.
                </div>
            </div>
        </div>
    </section>
    <section class="content-section" id="tips">
        <h2><i class="fas fa-magic"></i> Tips dan Trik Pro</h2>
        <p>
            Berikut adalah tips dan trik dari para profesional yang akan membuat hasil Anda
            lebih baik dan efisien:
        </p>
        <div class="tips-grid">
            <div class="tip-card pro">
                <div class="tip-header">
                    <i class="fas fa-star"></i>
                    <h4>Tip Pro #1</h4>
                </div>
                <p>
                    Gunakan keyboard shortcuts untuk mempercepat workflow Anda hingga 50%.
                    Hafal shortcut yang sering digunakan.
                </p>
            </div>
            <div class="tip-card pro">
                <div class="tip-header">
                    <i class="fas fa-star"></i>
                    <h4>Tip Pro #2</h4>
                </div>
                <p>
                    Selalu buat backup sebelum melakukan perubahan besar. Ini akan menyelamatkan
                    Anda dari masalah tak terduga.
                </p>
            </div>
            <div class="tip-card pro">
                <div class="tip-header">
                    <i class="fas fa-star"></i>
                    <h4>Tip Pro #3</h4>
                </div>
                <p>
                    Pelajari dari kesalahan. Setiap error adalah kesempatan untuk belajar dan
                    meningkatkan skill Anda.
                </p>
            </div>
            <div class="tip-card pro">
                <div class="tip-header">
                    <i class="fas fa-star"></i>
                    <h4>Tip Pro #4</h4>
                </div>
                <p>
                    Bergabung dengan komunitas untuk sharing pengalaman dan mendapat insight
                    dari pengguna lain.
                </p>
            </div>
        </div>
    </section>
    <section class="content-section" id="troubleshooting">
        <h2><i class="fas fa-wrench"></i> Troubleshooting - Masalah Umum</h2>
        <p>
            Mengalami kendala? Berikut adalah solusi untuk masalah-masalah yang sering terjadi:
        </p>
        <div class="troubleshooting-list">
            <div class="trouble-item">
                <h4><i class="fas fa-question-circle"></i> Masalah: Hasil tidak sesuai harapan</h4>
                <div class="solution">
                    <strong>Solusi:</strong>
                    <ul>
                        <li>Periksa kembali setiap langkah yang telah dilakukan</li>
                        <li>Pastikan pengaturan awal sudah benar</li>
                        <li>Coba ulangi proses dari awal dengan lebih teliti</li>
                    </ul>
                </div>
            </div>
            <div class="trouble-item">
                <h4><i class="fas fa-question-circle"></i> Masalah: Error atau crash</h4>
                <div class="solution">
                    <strong>Solusi:</strong>
                    <ul>
                        <li>Update aplikasi ke versi terbaru</li>
                        <li>Restart aplikasi dan komputer</li>
                        <li>Check spesifikasi komputer memenuhi requirement</li>
                    </ul>
                </div>
            </div>
            <div class="trouble-item">
                <h4><i class="fas fa-question-circle"></i> Masalah: Proses terlalu lambat</h4>
                <div class="solution">
                    <strong>Solusi:</strong>
                    <ul>
                        <li>Tutup aplikasi lain yang tidak diperlukan</li>
                        <li>Optimize settings untuk performa</li>
                        <li>Upgrade RAM jika diperlukan</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="content-section" id="kesimpulan">
        <h2><i class="fas fa-flag-checkered"></i> Kesimpulan</h2>
        <p>
            Selamat! Anda telah menyelesaikan tutorial <strong>{$title}</strong> dari awal hingga akhir.
            Dengan mengikuti langkah-langkah di atas, Anda sekarang memiliki kemampuan untuk {$topic}
            dengan lebih baik dan profesional.
        </p>
        <div class="conclusion-box">
            <h3>Ringkasan Pembelajaran:</h3>
            <ul>
                <li>✅ Memahami konsep dasar dan pentingnya</li>
                <li>✅ Mengetahui manfaat dan kegunaan praktis</li>
                <li>✅ Mampu melakukan langkah-langkah dengan benar</li>
                <li>✅ Menguasai tips dan trik profesional</li>
                <li>✅ Dapat troubleshooting masalah umum</li>
            </ul>
        </div>
        <p>
            Terus berlatih dan eksplorasi lebih lanjut untuk meningkatkan kemampuan Anda.
            Jangan ragu untuk membaca tutorial lainnya di DONAN22 untuk memperluas pengetahuan Anda!
        </p>
    </section>
    <section class="next-steps">
        <h2><i class="fas fa-forward"></i> Langkah Selanjutnya</h2>
        <div class="next-steps-grid">
            <div class="next-card">
                <i class="fas fa-graduation-cap"></i>
                <h4>Tingkatkan Skill</h4>
                <p>Coba tutorial tingkat lanjut untuk menguasai teknik lebih kompleks</p>
            </div>
            <div class="next-card">
                <i class="fas fa-users"></i>
                <h4>Join Community</h4>
                <p>Bergabung dengan forum atau grup untuk sharing dan networking</p>
            </div>
            <div class="next-card">
                <i class="fas fa-project-diagram"></i>
                <h4>Praktik Project</h4>
                <p>Terapkan ilmu dalam project nyata untuk memperkuat pemahaman</p>
            </div>
        </div>
    </section>
</article>
<style>
.blog-article {
    max-width: 900px;
    margin: 0 auto;
    line-height: 1.8;
    color: #1e293b;
}
.article-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 10px;
}
.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
}
.meta-item i {
    color: #3b82f6;
}
.lead-paragraph {
    font-size: 1.15rem;
    color: #475569;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #3b82f6;
    margin-bottom: 30px;
}
.table-of-contents {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-radius: 12px;
    padding: 25px;
    margin: 30px 0;
    border-left: 4px solid #10b981;
}
.table-of-contents h2 {
    color: #065f46;
    font-size: 1.5rem;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 10px;
}
.toc-list li {
    padding: 0;
}
.toc-list a {
    display: block;
    padding: 10px 15px;
    background: white;
    border-radius: 8px;
    color: #059669;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}
.toc-list a:hover {
    background: #10b981;
    color: white;
    transform: translateX(5px);
}
.content-section {
    margin: 40px 0;
}
.content-section h2 {
    color: #1e293b;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 3px solid #3b82f6;
    padding-bottom: 10px;
}
.content-section h2 i {
    color: #3b82f6;
}
.content-section h3 {
    color: #1e40af;
    font-size: 1.3rem;
    margin: 20px 0 15px;
}
.info-box,
.conclusion-box {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-left: 4px solid #f59e0b;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}
.info-box h3,
.conclusion-box h3 {
    color: #92400e;
    font-size: 1.2rem;
    margin-top: 0;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.info-box i {
    color: #f59e0b;
}
.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.benefit-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    border-top: 3px solid #10b981;
}
.benefit-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(16,185,129,0.15);
}
.benefit-icon {
    font-size: 2.5rem;
    color: #10b981;
    margin-bottom: 15px;
}
.benefit-card h3 {
    color: #1e293b;
    font-size: 1.1rem;
    margin: 15px 0 10px;
}
.benefit-card p {
    color: #64748b;
    font-size: 0.9rem;
    margin: 0;
}
.preparation-checklist {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}
.checklist-item {
    display: flex;
    gap: 15px;
    background: #f8fafc;
    padding: 20px;
    border-radius: 10px;
    border-left: 3px solid #3b82f6;
}
.checklist-item > i {
    font-size: 1.5rem;
    color: #10b981;
    flex-shrink: 0;
}
.checklist-content h4 {
    color: #1e40af;
    margin: 0 0 8px 0;
    font-size: 1.1rem;
}
.checklist-content p {
    color: #64748b;
    margin: 0;
    font-size: 0.95rem;
}
.step-container {
    display: flex;
    gap: 20px;
    margin: 30px 0;
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border-left: 4px solid #3b82f6;
}
.step-number {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
}
.step-content h3 {
    color: #1e40af;
    margin-top: 0;
}
.step-tip,
.step-example,
.step-warning,
.step-success {
    padding: 12px 15px;
    border-radius: 8px;
    margin-top: 15px;
    display: flex;
    align-items: start;
    gap: 10px;
}
.step-tip {
    background: #eff6ff;
    border-left: 3px solid #3b82f6;
    color: #1e40af;
}
.step-example {
    background: #f0fdf4;
    border-left: 3px solid #10b981;
    color: #065f46;
}
.step-warning {
    background: #fef3c7;
    border-left: 3px solid #f59e0b;
    color: #92400e;
}
.step-success {
    background: #d1fae5;
    border-left: 3px solid #10b981;
    color: #065f46;
}
.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.tip-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}
.tip-card.pro {
    border-top: 3px solid #8b5cf6;
}
.tip-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(139,92,246,0.15);
}
.tip-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}
.tip-header i {
    color: #8b5cf6;
    font-size: 1.2rem;
}
.tip-header h4 {
    color: #6d28d9;
    margin: 0;
    font-size: 1.05rem;
}
.tip-card p {
    color: #64748b;
    margin: 0;
    font-size: 0.95rem;
    line-height: 1.6;
}
.troubleshooting-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 20px;
}
.trouble-item {
    background: #fef2f2;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid #ef4444;
}
.trouble-item h4 {
    color: #991b1b;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.trouble-item h4 i {
    color: #ef4444;
}
.solution {
    background: white;
    padding: 15px;
    border-radius: 8px;
}
.solution ul {
    margin: 10px 0 0 0;
    padding-left: 20px;
}
.solution li {
    color: #64748b;
    margin: 8px 0;
}
.conclusion-box ul {
    margin: 15px 0 0 0;
    padding-left: 0;
    list-style: none;
}
.conclusion-box li {
    padding: 8px 0;
    color: #78350f;
    font-weight: 500;
}
.next-steps {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    color: white;
    border-radius: 15px;
    padding: 40px;
    margin-top: 50px;
}
.next-steps h2 {
    color: white;
    border: none;
    justify-content: center;
    text-align: center;
    margin-bottom: 30px;
}
.next-steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}
.next-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s ease;
}
.next-card:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-5px);
}
.next-card i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    opacity: 0.9;
}
.next-card h4 {
    color: white;
    margin: 15px 0 10px;
    font-size: 1.1rem;
}
.next-card p {
    color: rgba(255,255,255,0.9);
    margin: 0;
    font-size: 0.9rem;
}
@media (max-width: 768px) {
    .blog-article {
        padding: 15px;
    }
    .content-section h2 {
        font-size: 1.5rem;
    }
    .step-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .benefits-grid,
    .tips-grid,
    .next-steps-grid {
        grid-template-columns: 1fr;
    }
    .toc-list {
        grid-template-columns: 1fr;
    }
}
</style>
HTML;
    return $template;
}

function generateTutorialArticleTemplate($data) {
    return generateBlogArticleTemplate($data);
}