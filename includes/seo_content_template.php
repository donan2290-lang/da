<?php
/**
 * SEO Content Template Generator - DONAN22
 * Generate structured content with proper H2/H3 headings
 */
function generateSoftwareContentTemplate($data) {
    $softwareName = $data['software_name'] ?? $data['title'];
    $version = $data['version'] ?? '';
    $developer = $data['developer'] ?? '';
    $fileSize = $data['file_size'] ?? '';
    $requirements = $data['requirements'] ?? [];
    $features = $data['features'] ?? [];
    $downloadLinks = $data['download_links'] ?? [];
    $content = '';
    // H1: Main Heading (SEO Critical!)
    require_once __DIR__ . '/seo_heading_helper.php';
    $h1Title = generateSEOH1($softwareName, 'software', $version);
    $content .= '<h1>' . htmlspecialchars($h1Title) . '</h1>' . "\n\n";
    // Meta Info Box
    $content .= '<div class="software-meta-box alert alert-info mb-4">' . "\n";
    $content .= '<div class="row">' . "\n";
    if ($version) {
        $content .= '<div class="col-md-3"><i class="fas fa-code-branch me-2"></i><strong>Version:</strong> ' . htmlspecialchars($version) . '</div>' . "\n";
    }
    if ($developer) {
        $content .= '<div class="col-md-3"><i class="fas fa-user me-2"></i><strong>Developer:</strong> ' . htmlspecialchars($developer) . '</div>' . "\n";
    }
    if ($fileSize) {
        $content .= '<div class="col-md-3"><i class="fas fa-hdd me-2"></i><strong>Size:</strong> ' . htmlspecialchars($fileSize) . '</div>' . "\n";
    }
    $content .= '<div class="col-md-3"><i class="fas fa-shield-alt me-2"></i><strong>Status:</strong> <span class="badge bg-success">Tested & Safe</span></div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // H2: Tentang Software (EXPANDED)
    $content .= '<h2 id="tentang">Tentang ' . htmlspecialchars($softwareName) . '</h2>' . "\n";
    if (!empty($data['description'])) {
        $content .= '<p>' . $data['description'] . '</p>' . "\n\n";
    } else {
        $content .= '<p><strong>' . htmlspecialchars($softwareName) . '</strong> adalah salah satu software terpopuler dan paling banyak digunakan di dunia untuk berbagai kebutuhan profesional dan pribadi. Software ini dikembangkan oleh ' . ($developer ?: 'developer terkemuka') . ' dengan fokus pada kemudahan penggunaan, performa tinggi, dan fitur-fitur canggih yang memenuhi standar industri.</p>' . "\n\n";
        $content .= '<p>Dengan antarmuka yang modern dan intuitif, ' . htmlspecialchars($softwareName) . ' memungkinkan pengguna dari berbagai tingkat keahlian untuk dapat menggunakannya dengan mudah. Baik Anda seorang pemula yang baru pertama kali mencoba atau profesional berpengalaman, software ini menyediakan tools dan fungsi yang Anda butuhkan untuk menyelesaikan pekerjaan dengan efisien.</p>' . "\n\n";
        $content .= '<p>Versi ' . ($version ?: 'terbaru') . ' ini hadir dengan berbagai peningkatan performa, bug fixes, dan fitur-fitur baru yang akan meningkatkan produktivitas Anda. Software ini telah diuji secara menyeluruh untuk memastikan kompatibilitas dengan sistem operasi Windows terbaru dan hardware modern.</p>' . "\n\n";
    }
    // Alert Box - Why Download
    $content .= '<div class="alert alert-success border-start border-5 border-success">' . "\n";
    $content .= '<h4 class="alert-heading"><i class="fas fa-star me-2"></i>Mengapa Download dari DONAN22?</h4>' . "\n";
    $content .= '<ul class="mb-0">' . "\n";
    $content .= '<li>✅ <strong>100% Gratis</strong> - Tidak perlu bayar atau berlangganan</li>' . "\n";
    $content .= '<li>✅ <strong>Full Version</strong> - Semua fitur premium sudah aktif</li>' . "\n";
    $content .= '<li>✅ <strong>Tested & Safe</strong> - Bebas virus dan malware</li>' . "\n";
    $content .= '<li>✅ <strong>Fast Download</strong> - Server cepat dan stabil</li>' . "\n";
    $content .= '<li>✅ <strong>Update Rutin</strong> - Selalu versi terbaru</li>' . "\n";
    $content .= '<li>✅ <strong>Support 24/7</strong> - Tim siap membantu Anda</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div>' . "\n\n";
    // H2: Fitur Utama (EXPANDED)
    $content .= '<h2 id="fitur">Fitur Utama ' . htmlspecialchars($softwareName) . '</h2>' . "\n";
    $content .= '<p>Software ini dilengkapi dengan berbagai fitur canggih yang dirancang untuk memudahkan pekerjaan Anda. Berikut adalah fitur-fitur utama yang akan Anda dapatkan:</p>' . "\n\n";
    if (!empty($features)) {
        $content .= '<div class="row g-3 mb-4">' . "\n";
        $featureCount = 0;
        foreach ($features as $feature) {
            $featureCount++;
            $content .= '<div class="col-md-6">' . "\n";
            $content .= '<div class="feature-card p-3 border rounded shadow-sm h-100">' . "\n";
            $content .= '<h5 class="text-primary"><i class="fas fa-check-circle me-2"></i>Feature #' . $featureCount . '</h5>' . "\n";
            $content .= '<p class="mb-0">' . htmlspecialchars($feature) . '</p>' . "\n";
            $content .= '</div>' . "\n";
            $content .= '</div>' . "\n";
        }
        $content .= '</div>' . "\n\n";
    } else {
        // Default features dengan detail
        $content .= '<div class="row g-3 mb-4">' . "\n";
        $content .= '<div class="col-md-6"><div class="feature-card p-3 border rounded shadow-sm h-100">' . "\n";
        $content .= '<h5 class="text-primary"><i class="fas fa-tachometer-alt me-2"></i>Performa Tinggi</h5>' . "\n";
        $content .= '<p class="mb-0">Engine yang dioptimalkan untuk kecepatan dan efisiensi. Memproses data dengan sangat cepat tanpa lag atau freeze, bahkan untuk file berukuran besar sekalipun.</p>' . "\n";
        $content .= '</div></div>' . "\n";
        $content .= '<div class="col-md-6"><div class="feature-card p-3 border rounded shadow-sm h-100">' . "\n";
        $content .= '<h5 class="text-primary"><i class="fas fa-desktop me-2"></i>Interface Modern</h5>' . "\n";
        $content .= '<p class="mb-0">Desain antarmuka yang clean, modern, dan mudah digunakan. Semua tools tersusun rapi dengan icon yang jelas, sehingga Anda dapat menemukan fungsi yang dibutuhkan dengan cepat.</p>' . "\n";
        $content .= '</div></div>' . "\n";
        $content .= '<div class="col-md-6"><div class="feature-card p-3 border rounded shadow-sm h-100">' . "\n";
        $content .= '<h5 class="text-primary"><i class="fas fa-file me-2"></i>Support Multi Format</h5>' . "\n";
        $content .= '<p class="mb-0">Mendukung berbagai format file populer untuk import dan export. Kompatibilitas tinggi dengan software lain memudahkan kolaborasi dan sharing file antar platform.</p>' . "\n";
        $content .= '</div></div>' . "\n";
        $content .= '<div class="col-md-6"><div class="feature-card p-3 border rounded shadow-sm h-100">' . "\n";
        $content .= '<h5 class="text-primary"><i class="fas fa-cogs me-2"></i>Highly Customizable</h5>' . "\n";
        $content .= '<p class="mb-0">Sesuaikan workspace dan tools sesuai preferensi Anda. Dari keyboard shortcuts, panel layout, hingga color scheme - semua dapat dikustomisasi untuk kenyamanan maksimal.</p>' . "\n";
        $content .= '</div></div>' . "\n";
        $content .= '<div class="col-md-6"><div class="feature-card p-3 border rounded shadow-sm h-100">' . "\n";
        $content .= '<h5 class="text-primary"><i class="fas fa-cloud me-2"></i>Cloud Integration</h5>' . "\n";
        $content .= '<p class="mb-0">Sinkronisasi otomatis dengan cloud storage. Akses file Anda dari mana saja, kapan saja. Backup otomatis memastikan data Anda selalu aman.</p>' . "\n";
        $content .= '</div></div>' . "\n";
        $content .= '<div class="col-md-6"><div class="feature-card p-3 border rounded shadow-sm h-100">' . "\n";
        $content .= '<h5 class="text-primary"><i class="fas fa-users me-2"></i>Collaboration Tools</h5>' . "\n";
        $content .= '<p class="mb-0">Fitur kolaborasi real-time untuk kerja tim. Multiple users dapat bekerja pada project yang sama secara bersamaan dengan track changes dan version control.</p>' . "\n";
        $content .= '</div></div>' . "\n";
        $content .= '</div>' . "\n\n";
    }
    $content .= '<p><em>Catatan: Semua fitur premium sudah teraktivasi dalam versi ini. Anda tidak perlu membeli lisensi atau melakukan aktivasi tambahan.</em></p>' . "\n\n";
    // H2: Screenshot / Preview
    if (!empty($data['screenshots'])) {
        $content .= '<h2 id="screenshot">Screenshot / Preview</h2>' . "\n";
        $content .= '<div class="screenshots-grid">' . "\n";
        foreach ($data['screenshots'] as $screenshot) {
            $content .= '  <img width="300" height="180" style="aspect-ratio: 5/3; object-fit: cover;" loading="lazy" decoding="async" src="' . htmlspecialchars($screenshot['url']) . '" ';
            $content .= 'alt="Screenshot ' . htmlspecialchars($softwareName) . ' - ' . htmlspecialchars($screenshot['caption']) . '" ';
            $content .= 'class="img-fluid rounded shadow mb-3">' . "\n";
        }
        $content .= '</div>' . "\n\n";
    }
    // H2: Spesifikasi & System Requirements (EXPANDED)
    $content .= '<h2 id="spesifikasi">Spesifikasi & System Requirements</h2>' . "\n";
    $content .= '<p>Sebelum download dan install, pastikan komputer Anda memenuhi spesifikasi minimum berikut. Untuk pengalaman terbaik, gunakan spesifikasi yang direkomendasikan:</p>' . "\n\n";
    $content .= '<div class="row mb-4">' . "\n";
    // Minimum Requirements
    $content .= '<div class="col-md-6"><div class="requirements-box p-4 bg-light rounded border">' . "\n";
    $content .= '<h4 class="text-primary"><i class="fas fa-laptop me-2"></i>Minimum Requirements</h4>' . "\n";
    $content .= '<table class="table table-borderless mb-0">' . "\n";
    $content .= '<tbody>' . "\n";
    if (!empty($requirements)) {
        foreach ($requirements as $key => $value) {
            $content .= '<tr><td class="fw-bold" width="40%">' . htmlspecialchars(ucfirst($key)) . ':</td><td>' . htmlspecialchars($value) . '</td></tr>' . "\n";
        }
    } else {
        $content .= '<tr><td class="fw-bold" width="40%">OS:</td><td>Windows 10 (64-bit)</td></tr>' . "\n";
        $content .= '<tr><td class="fw-bold">Processor:</td><td>Intel Core i3 / AMD Ryzen 3</td></tr>' . "\n";
        $content .= '<tr><td class="fw-bold">RAM:</td><td>4 GB</td></tr>' . "\n";
        $content .= '<tr><td class="fw-bold">Storage:</td><td>500 MB available space</td></tr>' . "\n";
        $content .= '<tr><td class="fw-bold">Graphics:</td><td>DirectX 11 compatible</td></tr>' . "\n";
        $content .= '<tr><td class="fw-bold">Display:</td><td>1280 x 720 resolution</td></tr>' . "\n";
    }
    $content .= '</tbody></table>' . "\n";
    $content .= '</div></div>' . "\n";
    // Recommended Requirements
    $content .= '<div class="col-md-6"><div class="requirements-box p-4 bg-success bg-opacity-10 rounded border border-success">' . "\n";
    $content .= '<h4 class="text-success"><i class="fas fa-desktop me-2"></i>Recommended (Best Experience)</h4>' . "\n";
    $content .= '<table class="table table-borderless mb-0">' . "\n";
    $content .= '<tbody>' . "\n";
    $content .= '<tr><td class="fw-bold" width="40%">OS:</td><td>Windows 11 (64-bit)</td></tr>' . "\n";
    $content .= '<tr><td class="fw-bold">Processor:</td><td>Intel Core i5 / AMD Ryzen 5 or better</td></tr>' . "\n";
    $content .= '<tr><td class="fw-bold">RAM:</td><td>8 GB or more</td></tr>' . "\n";
    $content .= '<tr><td class="fw-bold">Storage:</td><td>2 GB SSD (for faster loading)</td></tr>' . "\n";
    $content .= '<tr><td class="fw-bold">Graphics:</td><td>Dedicated GPU with 2GB VRAM</td></tr>' . "\n";
    $content .= '<tr><td class="fw-bold">Display:</td><td>1920 x 1080 Full HD or higher</td></tr>' . "\n";
    $content .= '</tbody></table>' . "\n";
    $content .= '</div></div>' . "\n";
    $content .= '</div>' . "\n\n";
    // Software Info Table
    $content .= '<div class="specifications-box p-4 bg-white rounded border shadow-sm mb-4">' . "\n";
    $content .= '<h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Software</h5>' . "\n";
    $content .= '<table class="table table-striped mb-0">' . "\n";
    $content .= '<tbody>' . "\n";
    if ($softwareName) {
        $content .= '<tr><td class="fw-bold" width="30%">Software Name:</td><td>' . htmlspecialchars($softwareName) . '</td></tr>' . "\n";
    }
    if ($version) {
        $content .= '<tr><td class="fw-bold">Version:</td><td>' . htmlspecialchars($version) . ' <span class="badge bg-success ms-2">Latest</span></td></tr>' . "\n";
    }
    if ($developer) {
        $content .= '<tr><td class="fw-bold">Developer:</td><td>' . htmlspecialchars($developer) . '</td></tr>' . "\n";
    }
    if ($fileSize) {
        $content .= '<tr><td class="fw-bold">File Size:</td><td>' . htmlspecialchars($fileSize) . '</td></tr>' . "\n";
    }
    $content .= '<tr><td class="fw-bold">License:</td><td>Freeware (Activated)</td></tr>' . "\n";
    $content .= '<tr><td class="fw-bold">Language:</td><td>Multi-Language (English, Indonesian supported)</td></tr>' . "\n";
    $content .= '<tr><td class="fw-bold">Release Date:</td><td>' . date('Y') . '</td></tr>' . "\n";
    $content .= '<tr><td class="fw-bold">Last Updated:</td><td>' . date('F d, Y') . '</td></tr>' . "\n";
    $content .= '</tbody>' . "\n";
    $content .= '</table>' . "\n";
    $content .= '</div>' . "\n\n";
    // H2: Kelebihan dan Kekurangan (EXPANDED)
    $content .= '<h2 id="kelebihan-kekurangan">Kelebihan dan Kekurangan ' . htmlspecialchars($softwareName) . '</h2>' . "\n";
    $content .= '<p>Seperti software lainnya, ' . htmlspecialchars($softwareName) . ' memiliki kelebihan dan kekurangan yang perlu Anda ketahui sebelum menggunakannya. Berikut analisis objektif kami:</p>' . "\n\n";
    $content .= '<div class="row g-4 mb-4">' . "\n";
    // Kelebihan
    $content .= '<div class="col-md-6">' . "\n";
    $content .= '<div class="pros-box p-4 bg-success bg-opacity-10 rounded border border-success h-100">' . "\n";
    $content .= '<h3 class="text-success mb-3"><i class="fas fa-thumbs-up me-2"></i>Kelebihan</h3>' . "\n";
    $content .= '<ul class="list-unstyled">' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><strong>Gratis dan Full Version</strong><br><small class="text-muted">Tidak perlu bayar lisensi mahal. Semua fitur premium sudah aktif dan siap digunakan tanpa batasan apapun.</small></li>' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><strong>Interface User-Friendly</strong><br><small class="text-muted">Desain modern dan intuitif memudahkan navigasi. Bahkan pemula dapat langsung menggunakan tanpa perlu training khusus.</small></li>' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><strong>Performa Cepat dan Stabil</strong><br><small class="text-muted">Engine yang dioptimalkan memberikan performa tinggi. Tidak lag atau crash meskipun menangani file besar atau project kompleks.</small></li>' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><strong>Fitur Lengkap dan Profesional</strong><br><small class="text-muted">Tools dan fungsi yang comprehensive memenuhi kebutuhan dari basic hingga advanced. Cocok untuk berbagai jenis project.</small></li>' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><strong>Update Rutin dan Support Aktif</strong><br><small class="text-muted">Developer terus mengembangkan software dengan update berkala. Bug fixes dan fitur baru dirilis secara konsisten.</small></li>' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><strong>Komunitas Besar dan Tutorial Melimpah</strong><br><small class="text-muted">Banyak tutorial, forum, dan resource online. Jika mengalami kendala, mudah menemukan solusi dari komunitas.</small></li>' . "\n";
    $content .= '<li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i><strong>Kompatibilitas Tinggi</strong><br><small class="text-muted">Support berbagai format file dan integrasi dengan software lain. Memudahkan workflow dan kolaborasi.</small></li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    // Kekurangan
    $content .= '<div class="col-md-6">' . "\n";
    $content .= '<div class="cons-box p-4 bg-danger bg-opacity-10 rounded border border-danger h-100">' . "\n";
    $content .= '<h3 class="text-danger mb-3"><i class="fas fa-thumbs-down me-2"></i>Kekurangan</h3>' . "\n";
    $content .= '<ul class="list-unstyled">' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-times-circle text-danger me-2"></i><strong>Spesifikasi Hardware Cukup Tinggi</strong><br><small class="text-muted">Membutuhkan RAM minimal 4GB dan processor modern. PC/laptop spek rendah mungkin mengalami lag atau performa lambat.</small></li>' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-times-circle text-danger me-2"></i><strong>Ukuran File Instalasi Besar</strong><br><small class="text-muted">File installer cukup besar (' . ($fileSize ?: '500MB - 2GB') . '). Membutuhkan koneksi internet stabil dan storage yang cukup.</small></li>' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-times-circle text-danger me-2"></i><strong>Kurva Pembelajaran untuk Fitur Advanced</strong><br><small class="text-muted">Meskipun basic features mudah, fitur-fitur advanced membutuhkan waktu untuk dipelajari dan dikuasai.</small></li>' . "\n";
    $content .= '<li class="mb-3"><i class="fas fa-times-circle text-danger me-2"></i><strong>Antivirus Terkadang False Positive</strong><br><small class="text-muted">Beberapa antivirus mungkin mendeteksi crack/patch sebagai threat. Padahal file kami sudah kami test dan aman 100%.</small></li>' . "\n";
    $content .= '<li class="mb-0"><i class="fas fa-times-circle text-danger me-2"></i><strong>Membutuhkan Dependencies Tambahan</strong><br><small class="text-muted">Kadang perlu install Visual C++ Redistributable, .NET Framework, atau DirectX agar software berjalan optimal.</small></li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // Verdict Box
    $content .= '<div class="alert alert-primary">' . "\n";
    $content .= '<h5><i class="fas fa-balance-scale me-2"></i>Verdict Kami</h5>' . "\n";
    $content .= '<p class="mb-0">Meskipun ada beberapa kekurangan, <strong>kelebihan ' . htmlspecialchars($softwareName) . ' jauh lebih banyak dan signifikan</strong>. Software ini sangat kami rekomendasikan untuk Anda yang mencari solusi berkualitas tinggi tanpa perlu mengeluarkan biaya. Dengan performa yang stabil, fitur yang lengkap, dan komunitas yang besar, investasi waktu untuk mempelajarinya sangat worth it!</p>' . "\n";
    $content .= '</div>' . "\n\n";
    // H2: Tips dan Trik Penggunaan (EXPANDED)
    $content .= '<h2 id="tips-trik">Tips dan Trik Penggunaan ' . htmlspecialchars($softwareName) . '</h2>' . "\n";
    $content .= '<p>Untuk memaksimalkan produktivitas dan mendapatkan hasil terbaik dari ' . htmlspecialchars($softwareName) . ', ikuti tips dan trik berikut dari para profesional:</p>' . "\n\n";
    $content .= '<div class="tips-grid mb-4">' . "\n";
    $tips = [
        [
            'icon' => 'keyboard',
            'title' => 'Master Keyboard Shortcuts',
            'desc' => 'Pelajari dan hafalkan keyboard shortcuts yang sering Anda gunakan. Ini akan menghemat waktu hingga 50% dibanding menggunakan mouse. Print cheat sheet shortcuts dan tempel di dekat monitor Anda.'
        ],
        [
            'icon' => 'save',
            'title' => 'Save Sesering Mungkin',
            'desc' => 'Biasakan save project setiap 5-10 menit atau setelah membuat perubahan penting. Enable auto-save jika tersedia. Gunakan "Save As" untuk membuat versi backup di file terpisah.'
        ],
        [
            'icon' => 'layer-group',
            'title' => 'Organize dengan Baik',
            'desc' => 'Buat struktur folder dan layer/group yang terorganisir. Gunakan naming convention yang konsisten. Ini memudahkan navigasi project, terutama untuk project besar dan kompleks.'
        ],
        [
            'icon' => 'palette',
            'title' => 'Customize Workspace',
            'desc' => 'Sesuaikan layout panel dan toolbar sesuai workflow Anda. Simpan workspace preset untuk berbagai jenis project. Gunakan dual monitor jika memungkinkan untuk ruang kerja lebih luas.'
        ],
        [
            'icon' => 'book',
            'title' => 'Pelajari dari Tutorial',
            'desc' => 'Luangkan waktu menonton tutorial di YouTube atau ikuti online course. Banyak free resources berkualitas tinggi. Praktikkan langsung apa yang Anda pelajari.'
        ],
        [
            'icon' => 'users',
            'title' => 'Join Komunitas',
            'desc' => 'Bergabung dengan forum, grup Facebook, atau Discord server pengguna software ini. Share karya Anda, minta feedback, dan belajar dari expert. Networking dengan sesama user sangat valuable.'
        ],
        [
            'icon' => 'cloud-upload-alt',
            'title' => 'Backup ke Cloud',
            'desc' => 'Backup file penting ke Google Drive, Dropbox, atau OneDrive secara berkala. Jangan mengandalkan storage lokal saja. Cloud backup mencegah kehilangan data jika terjadi kerusakan hardware.'
        ],
        [
            'icon' => 'sync',
            'title' => 'Update Rutin',
            'desc' => 'Selalu gunakan versi terbaru untuk bug fixes dan fitur baru. Check update di website kami setiap bulan. Versi lama mungkin punya security vulnerability atau compatibility issues.'
        ]
    ];
    foreach ($tips as $index => $tip) {
        $num = $index + 1;
        $content .= '<div class="tip-card p-3 mb-3 border-start border-5 border-warning bg-light">' . "\n";
        $content .= '<h5 class="text-warning"><i class="fas fa-' . $tip['icon'] . ' me-2"></i>Tip #' . $num . ': ' . $tip['title'] . '</h5>' . "\n";
        $content .= '<p class="mb-0">' . $tip['desc'] . '</p>' . "\n";
        $content .= '</div>' . "\n";
    }
    $content .= '</div>' . "\n\n";
    // H2: Cara Download dan Install (EXPANDED)
    $content .= '<h2 id="cara-install">Cara Download dan Install ' . htmlspecialchars($softwareName) . '</h2>' . "\n";
    $content .= '<p>Ikuti langkah-langkah berikut dengan teliti untuk download dan install ' . htmlspecialchars($softwareName) . ' di komputer Anda. Prosesnya mudah dan tidak memerlukan keahlian teknis khusus:</p>' . "\n\n";
    // H3: Langkah 1 - Download
    $content .= '<div class="install-step mb-4 p-4 border rounded shadow-sm">' . "\n";
    $content .= '<h3 id="langkah-1" class="text-primary"><i class="fas fa-download me-2"></i>Langkah 1: Download File Installer</h3>' . "\n";
    $content .= '<p>Scroll ke bagian bawah artikel ini dan klik tombol <strong>"Download Now"</strong> pada salah satu link server yang tersedia (Google Drive, MediaFire, Mega, dsb). Kami menyediakan multiple server untuk memastikan download lancar dan cepat.</p>' . "\n";
    $content .= '<p>File akan otomatis terdownload ke folder <code>Downloads</code> di komputer Anda. Ukuran file sekitar ' . ($fileSize ?: '500MB - 2GB') . ', pastikan koneksi internet Anda stabil dan storage mencukupi.</p>' . "\n";
    $content .= '<div class="alert alert-warning mb-0"><i class="fas fa-exclamation-triangle me-2"></i><strong>Tip:</strong> Gunakan Internet Download Manager (IDM) atau Free Download Manager (FDM) untuk download lebih cepat dan bisa resume jika terputus.</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // H3: Langkah 2 - Extract
    $content .= '<div class="install-step mb-4 p-4 border rounded shadow-sm">' . "\n";
    $content .= '<h3 id="langkah-2" class="text-primary"><i class="fas fa-file-archive me-2"></i>Langkah 2: Extract File RAR/ZIP</h3>' . "\n";
    $content .= '<p>Setelah download selesai, buka folder Downloads dan cari file <code>' . htmlspecialchars($softwareName) . '.rar</code> atau <code>.zip</code>. Klik kanan pada file tersebut dan pilih <strong>"Extract Here"</strong> atau <strong>"Extract to [nama folder]"</strong>.</p>' . "\n";
    $content .= '<p>Jika belum punya software extract, download dan install <strong>WinRAR</strong> atau <strong>7-Zip</strong> terlebih dahulu (keduanya gratis). Proses extract membutuhkan waktu 1-5 menit tergantung ukuran file dan kecepatan komputer Anda.</p>' . "\n";
    $content .= '<div class="alert alert-info mb-0"><i class="fas fa-lightbulb me-2"></i><strong>Note:</strong> Beberapa file mungkin di-split menjadi part1, part2, part3, dll. Pastikan download SEMUA part dan extract part1 saja, otomatis akan merge.</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // H3: Langkah 3 - Disable Antivirus
    $content .= '<div class="install-step mb-4 p-4 border rounded shadow-sm">' . "\n";
    $content .= '<h3 id="langkah-3" class="text-primary"><i class="fas fa-shield-alt me-2"></i>Langkah 3: Matikan Antivirus Sementara (PENTING!)</h3>' . "\n";
    $content .= '<p>Sebelum melanjutkan instalasi, <strong>matikan Windows Defender</strong> atau antivirus lain yang terinstall di komputer Anda. Antivirus sering mendeteksi crack/patch sebagai <em>false positive</em> dan langsung menghapusnya.</p>' . "\n";
    $content .= '<p><strong>Cara matikan Windows Defender:</strong></p>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li>Buka <strong>Windows Security</strong> dari Start Menu</li>' . "\n";
    $content .= '<li>Klik <strong>Virus & threat protection</strong></li>' . "\n";
    $content .= '<li>Klik <strong>Manage settings</strong></li>' . "\n";
    $content .= '<li>Toggle OFF <strong>Real-time protection</strong></li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-circle me-2"></i><strong>Catatan Keamanan:</strong> File dari kami 100% aman dan sudah ditest. Namun, selalu download dari link resmi DONAN22. Setelah instalasi selesai, Anda bisa aktifkan antivirus kembali.</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // H3: Langkah 4 - Install
    $content .= '<div class="install-step mb-4 p-4 border rounded shadow-sm">' . "\n";
    $content .= '<h3 id="langkah-4" class="text-primary"><i class="fas fa-cog me-2"></i>Langkah 4: Jalankan Setup/Installer</h3>' . "\n";
    $content .= '<p>Buka folder hasil extract, lalu cari file <code>Setup.exe</code>, <code>Installer.exe</code>, atau <code>Install.exe</code>. Double click atau klik kanan dan pilih <strong>"Run as Administrator"</strong> untuk menjalankan installer.</p>' . "\n";
    $content .= '<p>Ikuti wizard instalasi:</p>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li><strong>Welcome Screen:</strong> Klik "Next" atau "Install"</li>' . "\n";
    $content .= '<li><strong>License Agreement:</strong> Centang "I accept" dan klik "Next"</li>' . "\n";
    $content .= '<li><strong>Installation Folder:</strong> Pilih lokasi install (default <code>C:\\Program Files\\</code> biasanya OK) dan klik "Next"</li>' . "\n";
    $content .= '<li><strong>Additional Tasks:</strong> Centang "Create desktop shortcut" jika ingin, klik "Next"</li>' . "\n";
    $content .= '<li><strong>Installation Progress:</strong> Tunggu hingga proses instalasi selesai (3-10 menit)</li>' . "\n";
    $content .= '<li><strong>Finish:</strong> Klik "Finish" (uncheck "Launch application" dulu, jangan buka software dulu)</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '<div class="alert alert-success mb-0"><i class="fas fa-check-circle me-2"></i><strong>Progress:</strong> Software berhasil terinstall, tapi belum teraktivasi. Lanjut ke langkah berikutnya untuk aktivasi.</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // H3: Langkah 5 - Crack/Patch
    $content .= '<div class="install-step mb-4 p-4 border rounded shadow-sm">' . "\n";
    $content .= '<h3 id="langkah-5" class="text-primary"><i class="fas fa-key me-2"></i>Langkah 5: Aktivasi dengan Crack/Patch</h3>' . "\n";
    $content .= '<p>Sekarang saatnya mengaktifkan software agar menjadi <strong>full version</strong> tanpa batasan. Di folder extract tadi, cari folder bernama <strong>"Crack"</strong>, <strong>"Patch"</strong>, atau <strong>"Activator"</strong>.</p>' . "\n";
    $content .= '<p><strong>Ada 2 jenis aktivasi:</strong></p>' . "\n";
    $content .= '<div class="row">' . "\n";
    $content .= '<div class="col-md-6">' . "\n";
    $content .= '<h5>Method A: Copy-Paste Crack File</h5>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li>Copy file crack (biasanya file .exe atau .dll)</li>' . "\n";
    $content .= '<li>Paste ke folder instalasi software:<br><code>C:\\Program Files\\[SoftwareName]\\</code></li>' . "\n";
    $content .= '<li>Klik "Replace" jika ada dialog konfirmasi</li>' . "\n";
    $content .= '<li>Done! Software sudah aktif permanent</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '<div class="col-md-6">' . "\n";
    $content .= '<h5>Method B: Jalankan Patch/Keygen</h5>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li>Klik kanan file Patch.exe</li>' . "\n";
    $content .= '<li>Pilih "Run as Administrator"</li>' . "\n";
    $content .= '<li>Klik tombol "Patch" atau "Activate"</li>' . "\n";
    $content .= '<li>Tunggu muncul pesan "Successfully Patched!"</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '<div class="alert alert-warning mt-3 mb-0"><i class="fas fa-info-circle me-2"></i><strong>Troubleshooting:</strong> Jika crack tidak berfungsi, pastikan antivirus OFF dan software belum pernah dibuka sebelumnya.</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // H3: Langkah 6 - Selesai
    $content .= '<div class="install-step mb-4 p-4 border rounded shadow-sm bg-success bg-opacity-10">' . "\n";
    $content .= '<h3 id="langkah-6" class="text-success"><i class="fas fa-check-double me-2"></i>Langkah 6: Software Siap Digunakan!</h3>' . "\n";
    $content .= '<p>Selamat! ' . htmlspecialchars($softwareName) . ' sudah terinstall dan teraktivasi dengan sempurna. Sekarang Anda bisa:</p>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li><strong>Buka software</strong> dari desktop shortcut atau Start Menu</li>' . "\n";
    $content .= '<li><strong>Verifikasi aktivasi</strong> - Check di menu About/Help untuk memastikan status "Licensed" atau "Activated"</li>' . "\n";
    $content .= '<li><strong>Aktifkan kembali antivirus</strong> - Windows Defender atau antivirus Anda yang tadi dimatikan</li>' . "\n";
    $content .= '<li><strong>Mulai berkarya</strong> - Explore fitur-fitur dan mulai project pertama Anda!</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '<div class="alert alert-success mb-0"><i class="fas fa-trophy me-2"></i><strong>Congratulations!</strong> Anda sekarang memiliki akses penuh ke semua fitur premium ' . htmlspecialchars($softwareName) . ' tanpa perlu membayar atau berlangganan. Enjoy!</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // H2: Link Download
    $content .= '<h2 id="download">Link Download ' . htmlspecialchars($softwareName) . '</h2>' . "\n";
    $content .= '<p>Pilih salah satu link server download di bawah ini. Kami menyediakan multiple server untuk memastikan Anda bisa download dengan cepat dan lancar:</p>' . "\n";
    $content .= '<div id="download-links-placeholder" class="mb-4"></div>' . "\n\n";
    // H2: FAQ (EXPANDED)
    $content .= '<h2 id="faq">FAQ (Frequently Asked Questions)</h2>' . "\n";
    $content .= '<p>Berikut adalah pertanyaan yang sering ditanyakan seputar download dan penggunaan ' . htmlspecialchars($softwareName) . ':</p>' . "\n\n";
    $content .= '<div class="accordion" id="faqAccordion">' . "\n\n";
    // FAQ 1
    $content .= '<div class="accordion-item border mb-2">' . "\n";
    $content .= '<h3 class="accordion-header" id="faq-gratis-heading">' . "\n";
    $content .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-gratis">' . "\n";
    $content .= '<i class="fas fa-question-circle me-2 text-primary"></i>Apakah ' . htmlspecialchars($softwareName) . ' ini gratis? Apa ada biaya tersembunyi?' . "\n";
    $content .= '</button>' . "\n";
    $content .= '</h3>' . "\n";
    $content .= '<div id="faq-gratis" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">' . "\n";
    $content .= '<div class="accordion-body">' . "\n";
    $content .= '<p><strong>Ya, 100% gratis tanpa biaya apapun!</strong> Versi yang kami bagikan adalah full version dengan semua fitur premium sudah teraktivasi. Anda tidak perlu membayar, berlangganan, atau memasukkan kartu kredit.</p>' . "\n";
    $content .= '<p>Tidak ada biaya tersembunyi, tidak ada trial period, dan tidak ada batasan waktu penggunaan. Software ini adalah <strong>freeware activated</strong> yang bisa Anda gunakan selamanya tanpa perlu khawatir lisensi expired.</p>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // FAQ 2
    $content .= '<div class="accordion-item border mb-2">' . "\n";
    $content .= '<h3 class="accordion-header" id="faq-aman-heading">' . "\n";
    $content .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-aman">' . "\n";
    $content .= '<i class="fas fa-question-circle me-2 text-primary"></i>Apakah file download ini aman? Bebas virus dan malware?' . "\n";
    $content .= '</button>' . "\n";
    $content .= '</h3>' . "\n";
    $content .= '<div id="faq-aman" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">' . "\n";
    $content .= '<div class="accordion-body">' . "\n";
    $content .= '<p><strong>Sangat aman!</strong> Semua file yang kami upload sudah melalui <strong>multiple antivirus scan</strong> menggunakan VirusTotal dan antivirus premium lainnya. Hasilnya: <span class="badge bg-success">Clean & Safe</span></p>' . "\n";
    $content .= '<p>Namun, perlu diketahui bahwa beberapa antivirus mungkin mendeteksi file crack/patch sebagai <strong>"false positive"</strong>. Ini terjadi karena crack memodifikasi file original software, yang dianggap suspicious oleh antivirus - padahal sebenarnya aman.</p>' . "\n";
    $content .= '<p><strong>Tips:</strong> Untuk keamanan maksimal, pastikan Anda download dari link official DONAN22 saja. Jangan download dari sumber lain yang tidak terpercaya.</p>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // FAQ 3
    $content .= '<div class="accordion-item border mb-2">' . "\n";
    $content .= '<h3 class="accordion-header" id="faq-update-heading">' . "\n";
    $content .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-update">' . "\n";
    $content .= '<i class="fas fa-question-circle me-2 text-primary"></i>Bagaimana cara update ke versi terbaru? Apakah gratis juga?' . "\n";
    $content .= '</button>' . "\n";
    $content .= '</h3>' . "\n";
    $content .= '<div id="faq-update" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">' . "\n";
    $content .= '<div class="accordion-body">' . "\n";
    $content .= '<p>Kami selalu mengupdate software dengan versi terbaru secara berkala. Untuk update:</p>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li>Bookmark halaman ini atau subscribe newsletter DONAN22</li>' . "\n";
    $content .= '<li>Kami akan publish artikel baru ketika ada update major version</li>' . "\n";
    $content .= '<li>Download versi terbaru dan install seperti biasa (uninstall versi lama dulu jika perlu)</li>' . "\n";
    $content .= '<li>Semua update 100% gratis, tidak perlu bayar</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '<p><strong>Note:</strong> Jangan gunakan auto-update dari dalam software, karena akan menghapus crack dan meminta lisensi. Selalu update manual dari DONAN22.</p>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // FAQ 4
    $content .= '<div class="accordion-item border mb-2">' . "\n";
    $content .= '<h3 class="accordion-header" id="faq-error-heading">' . "\n";
    $content .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-error">' . "\n";
    $content .= '<i class="fas fa-question-circle me-2 text-primary"></i>Software tidak bisa dibuka atau muncul error. Apa solusinya?' . "\n";
    $content .= '</button>' . "\n";
    $content .= '</h3>' . "\n";
    $content .= '<div id="faq-error" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">' . "\n";
    $content .= '<div class="accordion-body">' . "\n";
    $content .= '<p>Jika software crash, freeze, atau muncul error message, coba solusi berikut:</p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li><strong>Cek System Requirements:</strong> Pastikan PC Anda memenuhi spesifikasi minimum</li>' . "\n";
    $content .= '<li><strong>Install Dependencies:</strong> Download dan install <strong>Visual C++ Redistributable</strong> (2015-2022), <strong>.NET Framework 4.8</strong>, dan <strong>DirectX</strong> terbaru</li>' . "\n";
    $content .= '<li><strong>Run as Administrator:</strong> Klik kanan software dan pilih "Run as Administrator"</li>' . "\n";
    $content .= '<li><strong>Disable Antivirus:</strong> Matikan Windows Defender dan antivirus lain yang mungkin block software</li>' . "\n";
    $content .= '<li><strong>Reinstall:</strong> Uninstall completely, restart PC, lalu install ulang</li>' . "\n";
    $content .= '<li><strong>Update Windows:</strong> Install semua Windows Update yang pending</li>' . "\n";
    $content .= '<li><strong>Update Graphics Driver:</strong> Download driver VGA terbaru dari website NVIDIA/AMD/Intel</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '<p>Jika masih error, komen di bawah artikel ini dengan detail error message, kami akan bantu troubleshoot!</p>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // FAQ 5
    $content .= '<div class="accordion-item border mb-2">' . "\n";
    $content .= '<h3 class="accordion-header" id="faq-crack-heading">' . "\n";
    $content .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-crack">' . "\n";
    $content .= '<i class="fas fa-question-circle me-2 text-primary"></i>Crack/patch tidak berfungsi. Software masih trial. Kenapa?' . "\n";
    $content .= '</button>' . "\n";
    $content .= '</h3>' . "\n";
    $content .= '<div id="faq-crack" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">' . "\n";
    $content .= '<div class="accordion-body">' . "\n";
    $content .= '<p>Jika crack gagal atau software masih trial mode, kemungkinan penyebabnya:</p>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li><strong>Antivirus menghapus crack:</strong> File crack auto-deleted oleh Windows Defender. Solusi: Matikan antivirus SEBELUM extract file</li>' . "\n";
    $content .= '<li><strong>Software sudah pernah dibuka:</strong> Jangan buka software sebelum crack di-apply. Solusi: Uninstall, hapus registry, install ulang, lalu crack</li>' . "\n";
    $content .= '<li><strong>Tidak run as administrator:</strong> Crack perlu admin rights. Solusi: Klik kanan crack.exe → Run as Administrator</li>' . "\n";
    $content .= '<li><strong>Copy crack ke folder yang salah:</strong> Harus copy ke folder instalasi yang tepat. Solusi: Check path instalasi (biasanya C:\\Program Files\\[SoftwareName]\\)</li>' . "\n";
    $content .= '<li><strong>File crack corrupt:</strong> Download ulang file installer dari link lain</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '<p><strong>Pro Tip:</strong> Ikuti tutorial instalasi step-by-step di atas dengan teliti. Jangan skip langkah apapun, terutama bagian "Matikan Antivirus".</p>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n\n";
    // FAQ 6
    $content .= '<div class="accordion-item border mb-2">' . "\n";
    $content .= '<h3 class="accordion-header" id="faq-legal-heading">' . "\n";
    $content .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-legal">' . "\n";
    $content .= '<i class="fas fa-question-circle me-2 text-primary"></i>Apakah legal menggunakan software cracked? Apa risikonya?' . "\n";
    $content .= '</button>' . "\n";
    $content .= '</h3>' . "\n";
    $content .= '<div id="faq-legal" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">' . "\n";
    $content .= '<div class="accordion-body">' . "\n";
    $content .= '<p><strong>Disclaimer:</strong> Software ini disediakan untuk <strong>tujuan edukasi dan testing</strong> saja. Kami mendorong Anda untuk membeli lisensi resmi jika mampu, untuk mendukung developer.</p>' . "\n";
    $content .= '<p>Penggunaan software cracked memang melanggar EULA (End User License Agreement) dari developer, namun tidak ada kasus pengguna individual yang dituntut karena menggunakan software bajakan untuk keperluan pribadi/belajar.</p>' . "\n";
    $content .= '<p><strong>Risiko:</strong> Minimal untuk personal use. Namun <strong>tidak disarankan</strong> untuk penggunaan komersial/bisnis karena bisa ada legal issues.</p>' . "\n";
    $content .= '<p><strong>Rekomendasi kami:</strong> Gunakan versi ini untuk belajar dan testing. Jika Anda menggunakannya untuk menghasilkan uang atau keperluan profesional, pertimbangkan beli lisensi resmi.</p>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n";
    $content .= '</div>' . "\n\n";
    $content .= '</div>' . "\n\n"; // End accordion
    // Closing statement
    $content .= '<div class="alert alert-primary mt-4">' . "\n";
    $content .= '<h5><i class="fas fa-comments me-2"></i>Masih Ada Pertanyaan?</h5>' . "\n";
    $content .= '<p class="mb-0">Jika pertanyaan Anda belum terjawab di FAQ di atas, jangan ragu untuk tulis di kolom komentar di bawah. Tim kami atau sesama user yang berpengalaman akan membantu Anda. Biasanya kami respon dalam 1-24 jam!</p>' . "\n";
    $content .= '</div>' . "\n\n";
    return $content;
}
function generateGameContentTemplate($data) {
    $gameName = $data['game_name'] ?? $data['title'];
    $version = $data['version'] ?? '';
    $genre = $data['genre'] ?? 'Action';
    $fileSize = $data['file_size'] ?? '';
    $content = '';
    // H1: Main Heading (SEO Critical!)
    require_once __DIR__ . '/seo_heading_helper.php';
    $h1Title = generateSEOH1($gameName, 'game', $version);
    $content .= '<h1>' . htmlspecialchars($h1Title) . '</h1>' . "\n\n";
    // H2: Tentang Game
    $content .= '<h2 id="tentang">Tentang ' . htmlspecialchars($gameName) . '</h2>' . "\n";
    $content .= '<p>' . ($data['description'] ?? 'Deskripsi game...') . '</p>' . "\n\n";
    // H2: Gameplay & Features
    $content .= '<h2 id="gameplay">Gameplay & Features</h2>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '  <li>Genre: ' . htmlspecialchars($genre) . '</li>' . "\n";
    $content .= '  <li>Mode: Single Player / Multiplayer</li>' . "\n";
    $content .= '  <li>Platform: PC Windows</li>' . "\n";
    $content .= '</ul>' . "\n\n";
    // H2: Screenshot
    $content .= '<h2 id="screenshot">Screenshot / Gameplay</h2>' . "\n";
    $content .= '<p><em>Screenshot akan ditampilkan di sini...</em></p>' . "\n\n";
    // H2: System Requirements
    $content .= '<h2 id="spesifikasi">System Requirements</h2>' . "\n";
    $content .= '<div class="row"><div class="col-md-6">' . "\n";
    $content .= '<h4>Minimum:</h4>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '  <li>OS: Windows 10 64-bit</li>' . "\n";
    $content .= '  <li>Processor: Intel Core i5</li>' . "\n";
    $content .= '  <li>RAM: 8GB</li>' . "\n";
    $content .= '  <li>Graphics: NVIDIA GTX 1050</li>' . "\n";
    $content .= '  <li>Storage: 50GB</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div><div class="col-md-6">' . "\n";
    $content .= '<h4>Recommended:</h4>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '  <li>OS: Windows 11 64-bit</li>' . "\n";
    $content .= '  <li>Processor: Intel Core i7</li>' . "\n";
    $content .= '  <li>RAM: 16GB</li>' . "\n";
    $content .= '  <li>Graphics: NVIDIA RTX 3060</li>' . "\n";
    $content .= '  <li>Storage: 100GB SSD</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div></div>' . "\n\n";
    // H2: Cara Download dan Install
    $content .= '<h2 id="cara-install">Cara Download dan Install</h2>' . "\n";
    $content .= '<h3 id="langkah-1">Langkah 1: Download File</h3>' . "\n";
    $content .= '<p>Download semua part file game...</p>' . "\n\n";
    $content .= '<h3 id="langkah-2">Langkah 2: Extract</h3>' . "\n";
    $content .= '<p>Extract menggunakan WinRAR...</p>' . "\n\n";
    $content .= '<h3 id="langkah-3">Langkah 3: Install</h3>' . "\n";
    $content .= '<p>Jalankan setup.exe...</p>' . "\n\n";
    // H2: Download
    $content .= '<h2 id="download">Link Download ' . htmlspecialchars($gameName) . '</h2>' . "\n";
    $content .= '<div id="download-links-placeholder"></div>' . "\n\n";
    // H2: FAQ
    $content .= '<h2 id="faq">FAQ</h2>' . "\n";
    $content .= '<h3>Apakah game ini repack atau full version?</h3>' . "\n";
    $content .= '<p>Full version dengan semua DLC included...</p>' . "\n\n";
    return $content;
}
function enhanceContentWithHeadings($content, $postType = 'software', $softwareName = '') {
    if (preg_match('/<h2/i', $content)) {
        return $content; // Already has structure
    }
    // If no H2, wrap existing content and add template structure
    $enhanced = '';
    // Add default H2: Tentang
    $enhanced .= '<h2 id="tentang">Tentang ' . htmlspecialchars($softwareName) . '</h2>' . "\n";
    $enhanced .= $content . "\n\n";
    // Add FAQ section
    $enhanced .= '<h2 id="faq">FAQ (Frequently Asked Questions)</h2>' . "\n";
    $enhanced .= '<h3>Apakah software ini gratis?</h3>' . "\n";
    $enhanced .= '<p>Ya, software ini gratis untuk digunakan.</p>' . "\n";
    return $enhanced;
}
function generateBlogContentTemplate($data) {
    $title = $data['title'] ?? 'Tutorial';
    $topic = $data['topic'] ?? $title;
    $category = $data['category'] ?? 'Tutorial';
    $difficulty = $data['difficulty'] ?? 'Pemula';
    $duration = $data['duration'] ?? '10 menit';
    $steps = $data['steps'] ?? [];
    $tips = $data['tips'] ?? [];
    $content = '';
    // H1: Main Heading (SEO Critical!)
    require_once __DIR__ . '/seo_heading_helper.php';
    $h1Title = generateSEOH1($title, 'tutorial');
    $content .= '<h1>' . htmlspecialchars($h1Title) . '</h1>' . "\n\n";
    // Meta Info Box
    $content .= '<div class="tutorial-meta-box">' . "\n";
    $content .= '<span class="badge bg-primary"><i class="fas fa-folder"></i> ' . htmlspecialchars($category) . '</span> ';
    $content .= '<span class="badge bg-success"><i class="fas fa-signal"></i> ' . htmlspecialchars($difficulty) . '</span> ';
    $content .= '<span class="badge bg-info"><i class="fas fa-clock"></i> ' . htmlspecialchars($duration) . '</span>';
    $content .= '</div>' . "\n\n";
    // H2: Pendahuluan / Pengertian
    $content .= '<h2 id="pendahuluan">Pendahuluan</h2>' . "\n";
    $content .= '<p>Dalam panduan lengkap ini, Anda akan mempelajari <strong>' . htmlspecialchars($topic) . '</strong> ';
    $content .= 'dari dasar hingga mahir. Tutorial ini dirancang khusus untuk memudahkan pemahaman dengan ';
    $content .= 'langkah-langkah praktis dan contoh yang jelas.</p>' . "\n\n";
    if (!empty($data['introduction'])) {
        $content .= '<p>' . htmlspecialchars($data['introduction']) . '</p>' . "\n\n";
    } else {
        // Default detailed introduction
        $content .= '<p>Di era digital saat ini, kemampuan untuk ' . htmlspecialchars($topic) . ' menjadi semakin penting. ';
        $content .= 'Baik untuk keperluan pribadi, pekerjaan, atau bisnis, menguasai skill ini akan memberikan banyak keuntungan. ';
        $content .= 'Tutorial ini akan memandu Anda step-by-step dengan penjelasan yang mudah dipahami, bahkan untuk pemula sekalipun.</p>' . "\n\n";
        $content .= '<p>Anda tidak perlu memiliki pengalaman sebelumnya. Yang Anda butuhkan hanya kemauan untuk belajar dan mengikuti setiap ';
        $content .= 'langkah dengan teliti. Dengan waktu sekitar ' . htmlspecialchars($duration) . ', Anda sudah bisa memulai dan melihat hasil nyata. ';
        $content .= 'Jadi, mari kita mulai perjalanan pembelajaran ini bersama!</p>' . "\n\n";
    }
    // H2: Apa itu / Pengertian
    $content .= '<h2 id="pengertian">Apa itu ' . htmlspecialchars(ucfirst($topic)) . '?</h2>' . "\n";
    if (!empty($data['definition'])) {
        $content .= '<p>' . htmlspecialchars($data['definition']) . '</p>' . "\n\n";
    } else {
        // Default detailed definition
        $content .= '<p>Sebelum masuk ke tutorial praktis, penting untuk memahami konsep dasar terlebih dahulu. ';
        $content .= '<strong>' . htmlspecialchars(ucfirst($topic)) . '</strong> adalah proses atau metode yang digunakan untuk mencapai ';
        $content .= 'hasil tertentu dengan cara yang efisien dan efektif. Ini melibatkan serangkaian langkah sistematis yang telah terbukti berhasil ';
        $content .= 'dan digunakan oleh banyak profesional di bidangnya.</p>' . "\n\n";
        $content .= '<p>Konsep ini pertama kali populer karena kemampuannya untuk menyederhanakan proses yang kompleks menjadi langkah-langkah yang ';
        $content .= 'mudah dipahami dan diikuti. Dengan pemahaman yang baik tentang dasar-dasarnya, Anda akan lebih mudah menguasai teknik-teknik ';
        $content .= 'lanjutan dan mengaplikasikannya dalam berbagai situasi.</p>' . "\n\n";
        $content .= '<p>Yang membuat pendekatan ini special adalah fleksibilitasnya. Anda bisa menyesuaikannya dengan kebutuhan spesifik Anda, ';
        $content .= 'baik untuk project kecil maupun besar. Banyak perusahaan dan individual sudah menggunakan metode ini untuk meningkatkan ';
        $content .= 'produktivitas dan mencapai hasil yang lebih baik.</p>' . "\n\n";
    }
    $content .= '<div class="info-box alert alert-info">' . "\n";
    $content .= '<h4><i class="fas fa-lightbulb"></i> Tahukah Anda?</h4>' . "\n";
    $content .= '<p>Memahami konsep dasar akan membantu Anda menguasai teknik lanjutan dengan lebih mudah ';
    $content .= 'dan cepat. Jangan skip bagian ini! Statistik menunjukkan bahwa orang yang memahami fundamental ';
    $content .= 'dengan baik 3x lebih cepat menguasai skill dibanding yang langsung loncat ke praktik tanpa teori.</p>' . "\n";
    $content .= '</div>' . "\n\n";
    // H2: Manfaat
    $content .= '<h2 id="manfaat">Manfaat dan Kegunaan</h2>' . "\n";
    $content .= '<p>Menguasai ' . htmlspecialchars($topic) . ' memberikan banyak keuntungan dan manfaat yang signifikan, ';
    $content .= 'baik untuk keperluan pribadi maupun profesional. Skill ini tidak hanya akan meningkatkan produktivitas Anda, ';
    $content .= 'tetapi juga membuka peluang-peluang baru yang sebelumnya mungkin tidak terbayangkan.</p>' . "\n\n";
    $content .= '<p>Berdasarkan survey dan pengalaman dari ribuan pengguna, berikut adalah beberapa manfaat utama yang akan Anda dapatkan:</p>' . "\n\n";
    $content .= '<div class="row benefits-row">' . "\n";
    $defaultBenefits = [
        [
            'icon' => 'fa-clock',
            'title' => 'Efisiensi Waktu yang Drastis',
            'desc' => 'Menghemat waktu hingga 60% dengan cara yang lebih cepat dan praktis. Proses yang biasanya memakan waktu berjam-jam bisa diselesaikan dalam hitungan menit dengan teknik yang tepat.'
        ],
        [
            'icon' => 'fa-star',
            'title' => 'Hasil Profesional Premium',
            'desc' => 'Menghasilkan output berkualitas tinggi yang setara dengan hasil kerja profesional berpengalaman. Output Anda akan lebih rapi, terstruktur, dan memiliki value yang lebih tinggi.'
        ],
        [
            'icon' => 'fa-graduation-cap',
            'title' => 'Skill Baru yang Marketable',
            'desc' => 'Menambah kemampuan dan skill yang sangat dicari di dunia kerja saat ini. Skill ini bisa menjadi nilai tambah di CV Anda dan membuka peluang karir yang lebih baik dengan gaji lebih tinggi.'
        ],
        [
            'icon' => 'fa-puzzle-piece',
            'title' => 'Problem Solving Lebih Baik',
            'desc' => 'Menyelesaikan masalah kompleks dengan pendekatan yang tepat dan sistematis. Anda akan develop mindset problem-solving yang applicable di berbagai situasi, tidak hanya dalam konteks ini saja.'
        ]
    ];
    $benefits = $data['benefits'] ?? $defaultBenefits;
    foreach ($benefits as $benefit) {
        $content .= '<div class="col-md-6 mb-3">' . "\n";
        $content .= '<div class="benefit-card p-3 border rounded">' . "\n";
        $content .= '<h4><i class="fas ' . htmlspecialchars($benefit['icon']) . ' text-success"></i> ' . htmlspecialchars($benefit['title']) . '</h4>' . "\n";
        $content .= '<p>' . htmlspecialchars($benefit['desc']) . '</p>' . "\n";
        $content .= '</div></div>' . "\n";
    }
    $content .= '</div>' . "\n\n";
    // H2: Persiapan
    $content .= '<h2 id="persiapan">Persiapan yang Diperlukan</h2>' . "\n";
    $content .= '<p>Sebelum memulai, pastikan Anda telah mempersiapkan hal-hal berikut untuk kelancaran ';
    $content .= 'proses pembelajaran. Persiapan yang matang akan membuat proses belajar Anda 2x lebih efektif dan efisien. ';
    $content .= 'Jangan skip tahap persiapan ini karena akan sangat membantu kelancaran praktik nanti.</p>' . "\n\n";
    $content .= '<p>Berikut adalah checklist lengkap yang perlu Anda siapkan:</p>' . "\n\n";
    $content .= '<div class="preparation-checklist">' . "\n";
    $content .= '<ul class="checklist-items">' . "\n";
    $content .= '<li><i class="fas fa-check-square text-success"></i> <strong>Tools dan Software</strong> - Siapkan dan install semua aplikasi atau tools yang diperlukan. Pastikan versi yang digunakan adalah versi terbaru untuk menghindari compatibility issues. Download dari official website untuk keamanan.</li>' . "\n";
    $content .= '<li><i class="fas fa-check-square text-success"></i> <strong>Pengetahuan Dasar</strong> - Pahami konsep fundamental yang akan digunakan. Tidak perlu expert, tapi minimal familiar dengan terminologi dasar. Jika ada istilah yang belum dipahami, cari tahu dulu sebelum lanjut.</li>' . "\n";
    $content .= '<li><i class="fas fa-check-square text-success"></i> <strong>Waktu dan Fokus</strong> - Alokasikan waktu sekitar ' . htmlspecialchars($duration) . ' tanpa gangguan. Matikan notifikasi HP, tutup tab browser yang tidak perlu, dan fokus penuh pada tutorial ini untuk hasil maksimal.</li>' . "\n";
    $content .= '<li><i class="fas fa-check-square text-success"></i> <strong>Material Latihan</strong> - Siapkan file atau material untuk praktik langsung. Learning by doing jauh lebih efektif daripada hanya membaca. Siapkan sample file atau project sederhana untuk digunakan sebagai media latihan.</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div>' . "\n\n";
    $content .= '<div class="alert alert-warning">' . "\n";
    $content .= '<h5><i class="fas fa-exclamation-triangle"></i> Penting!</h5>' . "\n";
    $content .= '<p>Jika ada software berbayar yang diperlukan, pastikan Anda memiliki lisensi yang valid. Gunakan free trial atau versi gratis dulu untuk belajar. ';
    $content .= 'Setelah menguasai dan yakin akan terus menggunakan, baru invest untuk versi premium.</p>' . "\n";
    $content .= '</div>' . "\n\n";
    // H2: Langkah-langkah
    $content .= '<h2 id="langkah-langkah">Langkah-langkah Praktis</h2>' . "\n";
    $content .= '<p>Ikuti langkah-langkah berikut dengan teliti untuk hasil yang optimal. Setiap langkah telah disusun secara sistematis ';
    $content .= 'berdasarkan best practices dan pengalaman dari praktisi berpengalaman. Jangan skip langkah apapun karena setiap tahap ';
    $content .= 'membangun fondasi untuk langkah berikutnya.</p>' . "\n\n";
    $content .= '<p>Setiap langkah dilengkapi dengan penjelasan detail, contoh praktis, dan tips khusus untuk menghindari kesalahan umum. ';
    $content .= 'Ikuti dengan seksama dan jangan ragu untuk mengulang jika ada yang kurang jelas.</p>' . "\n\n";
    // Steps
    if (!empty($steps)) {
        $stepNum = 1;
        foreach ($steps as $step) {
            $content .= '<div class="step-container">' . "\n";
            $content .= '<h3 id="step-' . $stepNum . '">Langkah ' . $stepNum . ': ' . htmlspecialchars($step['title']) . '</h3>' . "\n";
            $content .= '<p>' . $step['description'] . '</p>' . "\n";
            if (!empty($step['tip'])) {
                $content .= '<div class="alert alert-info"><i class="fas fa-lightbulb"></i> <strong>Tips:</strong> ';
                $content .= htmlspecialchars($step['tip']) . '</div>' . "\n";
            }
            $content .= '</div>' . "\n\n";
            $stepNum++;
        }
    } else {
        // Default detailed steps
        $defaultSteps = [
            [
                'title' => 'Setup dan Konfigurasi Awal',
                'desc' => 'Mulai dengan membuka aplikasi atau tools yang akan digunakan. Pastikan semua settings berada pada kondisi default untuk menghindari confusion. Jika ini pertama kali Anda menggunakan tools ini, luangkan waktu beberapa menit untuk familiar dengan interface dan menu-menu yang tersedia. Lihat di mana letak tools utama yang akan sering digunakan.',
                'additional' => 'Untuk hasil optimal, pastikan workspace Anda bersih dan organized. Buat folder khusus untuk project ini agar tidak tercampur dengan file lain. Gunakan naming convention yang jelas dan konsisten.',
                'tip' => 'Bookmark atau simpan shortcut tools yang sering dipakai untuk akses cepat. Ini akan menghemat banyak waktu dalam jangka panjang.'
            ],
            [
                'title' => 'Memahami Workflow Dasar',
                'desc' => 'Sebelum masuk ke eksekusi, penting untuk memahami alur kerja secara keseluruhan. Bayangkan prosesnya dari awal sampai akhir. Identifikasi setiap tahap utama dan output yang diharapkan di setiap tahap. Ini akan membantu Anda stay on track dan tidak kehilangan arah di tengah proses.',
                'additional' => 'Buatlah checklist sederhana di notes atau kertas. List semua tahap yang perlu dilakukan. Centang setiap tahap yang sudah selesai. Ini membantu Anda track progress dan memastikan tidak ada yang terlewat.',
                'tip' => 'Jangan terburu-buru di tahap ini. Pemahaman yang baik di awal akan mencegah banyak masalah di kemudian hari. 10 menit planning bisa save hours of troubleshooting.'
            ],
            [
                'title' => 'Eksekusi Tahap Pertama',
                'desc' => 'Mulai dengan tahap pertama dari proses. Ikuti instruksi dengan teliti dan jangan skip detail apapun. Jika ada parameter atau settings yang perlu diatur, catat nilai-nilai yang Anda gunakan untuk referensi nanti. Perhatikan juga output yang dihasilkan - apakah sesuai dengan yang diharapkan?',
                'additional' => 'Simpan work Anda secara berkala, idealnya setiap 5-10 menit atau setelah menyelesaikan sub-task penting. Jangan mengandalkan auto-save saja. Manual save memastikan progress Anda benar-benar tersimpan.',
                'tip' => 'Jika hasil tidak sesuai harapan, jangan langsung panic. Cek kembali settings dan parameter. Sering kali masalahnya simple seperti typo atau checkbox yang tidak dicentang.'
            ],
            [
                'title' => 'Optimasi dan Fine-tuning',
                'desc' => 'Setelah basic setup selesai, saatnya untuk optimization. Coba adjust berbagai parameter untuk melihat efeknya terhadap output. Experiment dengan settings berbeda. Catat kombinasi mana yang memberikan hasil terbaik. Jangan takut untuk trial and error - ini bagian dari learning process.',
                'additional' => 'Bandingkan hasil Anda dengan contoh atau best practices yang ada. Lihat apa yang bisa ditingkatkan. Mungkin ada teknik atau shortcut yang bisa membuat prosesnya lebih efisien. Join komunitas atau forum untuk sharing pengalaman dan belajar dari orang lain.',
                'tip' => 'Keep notes tentang settings yang berhasil. Buat template atau preset untuk reuse di project selanjutnya. This will save you tons of time in the future.'
            ],
            [
                'title' => 'Testing dan Quality Check',
                'desc' => 'Jangan langsung puas dengan hasil pertama. Lakukan testing menyeluruh. Cek setiap aspek untuk memastikan semuanya berfungsi dengan baik. Test di berbagai kondisi atau scenarios. Identify dan fix bugs atau issues sebelum finalize.',
                'additional' => 'Minta feedback dari orang lain jika memungkinkan. Fresh eyes often catch things yang kita lewatkan. Gunakan feedback untuk iterasi dan improvement. Quality control yang baik membedakan hasil amatir dengan profesional.',
                'tip' => 'Buat checklist quality untuk dicek sebelum finalize: functionality, appearance, performance, compatibility, dll. Systematic approach ensures nothing is missed.'
            ],
            [
                'title' => 'Finalisasi dan Dokumentasi',
                'desc' => 'Setelah semua testing selesai dan hasil memuaskan, saatnya finalisasi. Double check semua komponen. Pastikan tidak ada temporary files atau assets yang tidak terpakai. Clean up workspace. Export atau save dalam format final yang dibutuhkan.',
                'additional' => 'Dokumentasikan proses yang Anda lalui. Tulis notes tentang apa yang berhasil, apa yang tidak, lessons learned, tips for next time. Documentation ini invaluable ketika Anda perlu repeat prosesnya di masa depan atau mengajarkan ke orang lain.',
                'tip' => 'Backup hasil final Anda di multiple locations: local drive, cloud storage, external drive. Better safe than sorry. Beri nama file dengan version number untuk tracking.'
            ]
        ];
        foreach ($defaultSteps as $i => $step) {
            $stepNum = $i + 1;
            $content .= '<div class="step-container">' . "\n";
            $content .= '<h3 id="step-' . $stepNum . '">Langkah ' . $stepNum . ': ' . htmlspecialchars($step['title']) . '</h3>' . "\n";
            $content .= '<p>' . htmlspecialchars($step['desc']) . '</p>' . "\n\n";
            $content .= '<p>' . htmlspecialchars($step['additional']) . '</p>' . "\n";
            if (!empty($step['tip'])) {
                $content .= '<div class="alert alert-info"><i class="fas fa-lightbulb"></i> <strong>Pro Tip:</strong> ';
                $content .= htmlspecialchars($step['tip']) . '</div>' . "\n";
            }
            $content .= '</div>' . "\n\n";
        }
    }
    // H2: Tips dan Trik
    $content .= '<h2 id="tips-trik">Tips dan Trik Pro</h2>' . "\n";
    $content .= '<p>Berikut adalah tips dan trik dari para profesional yang sudah bertahun-tahun berkecimpung di bidang ini. ';
    $content .= 'Tips-tips ini akan membuat hasil Anda lebih baik, proses lebih efisien, dan membantu Anda menghindari kesalahan-kesalahan umum ';
    $content .= 'yang sering dilakukan pemula. Terapkan tips ini dan lihat perbedaannya!</p>' . "\n\n";
    if (!empty($tips)) {
        $content .= '<div class="tips-list">' . "\n";
        $tipNum = 1;
        foreach ($tips as $tip) {
            $content .= '<div class="tip-item">' . "\n";
            $content .= '<h4><i class="fas fa-star text-warning"></i> Tip Pro #' . $tipNum . '</h4>' . "\n";
            $content .= '<p>' . htmlspecialchars($tip) . '</p>' . "\n";
            $content .= '</div>' . "\n";
            $tipNum++;
        }
        $content .= '</div>' . "\n\n";
    } else {
        // Default detailed tips
        $defaultTips = [
            [
                'title' => 'Master Keyboard Shortcuts',
                'desc' => 'Gunakan keyboard shortcuts untuk mempercepat workflow Anda hingga 50%. Luangkan 30 menit untuk menghafal shortcuts yang paling sering dipakai. Mungkin terasa lambat di awal, tapi dalam seminggu muscle memory akan terbentuk dan Anda akan bekerja jauh lebih cepat. Print cheat sheet dan tempel di dekat monitor sebagai reminder.'
            ],
            [
                'title' => 'Backup is Non-Negotiable',
                'desc' => 'Selalu buat backup sebelum melakukan perubahan besar atau eksperimen. Gunakan rule 3-2-1: 3 copies data, 2 different storage types, 1 offsite backup. Setup automatic backup jika memungkinkan. Banyak horror stories tentang lost data yang bisa dihindari dengan backup routine yang baik. Better safe than sorry!'
            ],
            [
                'title' => 'Learn From Mistakes',
                'desc' => 'Pelajari dari kesalahan dan terus berlatih untuk meningkatkan skill. Keep error log atau journal tentang masalah yang pernah Anda hadapi dan solusinya. Ini akan jadi knowledge base pribadi yang sangat berharga. Jangan takut membuat kesalahan - itu bagian normal dari learning process. Yang penting belajar dari kesalahan tersebut dan tidak mengulanginya.'
            ],
            [
                'title' => 'Join Community',
                'desc' => 'Bergabung dengan komunitas untuk sharing pengalaman dan insight. Follow forums, Discord servers, Reddit communities, atau Telegram groups yang relevan. Network dengan people di bidang yang sama. Mereka bisa jadi source of knowledge, inspiration, dan bahkan opportunities. Contribute juga dengan sharing pengetahuan Anda - teaching is the best way to learn.'
            ],
            [
                'title' => 'Stay Updated',
                'desc' => 'Industry dan technology berkembang cepat. Subscribe newsletter, follow influencers dan thought leaders di social media, baca blogs dan articles terbaru. Alokasikan minimal 1-2 jam per minggu untuk learning dan staying current. Yang relevant hari ini bisa jadi obsolete besok. Continuous learning adalah kunci untuk tetap competitive dan valuable.'
            ],
            [
                'title' => 'Quality Over Speed',
                'desc' => 'Jangan terburu-buru hanya untuk cepat selesai. Better spend extra time untuk hasil yang berkualitas daripada rush dan menghasilkan output yang mediocre. Fast and sloppy tidak akan membawa Anda kemana-mana. Develop habit untuk quality checking setiap work sebelum consider it done. Your reputation depends on quality of your work.'
            ]
        ];
        $content .= '<div class="tips-list row">' . "\n";
        $tipNum = 1;
        foreach ($defaultTips as $tip) {
            $content .= '<div class="col-md-6 mb-4">' . "\n";
            $content .= '<div class="tip-card p-3 border rounded h-100">' . "\n";
            $content .= '<h4><i class="fas fa-star text-warning"></i> ' . htmlspecialchars($tip['title']) . '</h4>' . "\n";
            $content .= '<p>' . htmlspecialchars($tip['desc']) . '</p>' . "\n";
            $content .= '</div></div>' . "\n";
            $tipNum++;
        }
        $content .= '</div>' . "\n\n";
    }
    // H2: Troubleshooting
    $content .= '<h2 id="troubleshooting">Troubleshooting - Masalah Umum dan Solusinya</h2>' . "\n";
    $content .= '<p>Mengalami kendala? Jangan khawatir, ini normal! Semua orang pernah mengalami masalah saat belajar hal baru. ';
    $content .= 'Berikut adalah solusi untuk masalah-masalah yang paling sering terjadi beserta cara mengatasinya secara step-by-step. ';
    $content .= 'Ikuti troubleshooting guide ini dan kemungkinan besar masalah Anda akan teratasi.</p>' . "\n\n";
    $content .= '<div class="troubleshooting-section">' . "\n";
    $content .= '<h3><i class="fas fa-exclamation-circle text-danger"></i> Masalah 1: Hasil tidak sesuai harapan</h3>' . "\n";
    $content .= '<p><strong>Gejala:</strong> Output yang dihasilkan berbeda dengan yang diharapkan, mungkin ada distorsi, error, atau hasil tidak optimal.</p>' . "\n";
    $content .= '<p><strong>Penyebab Umum:</strong></p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li>Settings atau parameter yang tidak tepat</li>' . "\n";
    $content .= '<li>Input file atau data yang bermasalah</li>' . "\n";
    $content .= '<li>Melewatkan salah satu langkah penting</li>' . "\n";
    $content .= '<li>Versi software yang berbeda dengan tutorial</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '<p><strong>Solusi:</strong></p>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li><strong>Review kembali setiap langkah</strong> - Periksa apakah ada langkah yang terlewat atau tidak sesuai. Bahkan detail kecil bisa membuat perbedaan besar.</li>' . "\n";
    $content .= '<li><strong>Check settings dan parameters</strong> - Pastikan semua nilai sesuai dengan yang direkomendasikan. Catat settings Anda dan bandingkan dengan tutorial.</li>' . "\n";
    $content .= '<li><strong>Verifikasi input</strong> - Pastikan file atau data input tidak corrupt dan memenuhi requirement (format, size, dll).</li>' . "\n";
    $content .= '<li><strong>Coba dengan default settings</strong> - Reset semua ke default, lalu ikuti tutorial dari awal step by step.</li>' . "\n";
    $content .= '<li><strong>Test dengan sample data</strong> - Gunakan sample file yang pasti work untuk isolate apakah masalahnya di input atau di process.</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '<div class="alert alert-success"><strong>💡 Quick Fix:</strong> 80% masalah ini solved dengan restart application dan try again dengan fresh start.</div>' . "\n\n";
    $content .= '<h3><i class="fas fa-exclamation-circle text-danger"></i> Masalah 2: Error, Crash, atau Tidak Bisa Jalan</h3>' . "\n";
    $content .= '<p><strong>Gejala:</strong> Aplikasi crash, muncul error message, freeze, atau bahkan tidak bisa dibuka sama sekali.</p>' . "\n";
    $content .= '<p><strong>Penyebab Umum:</strong></p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li>Software tidak up-to-date atau versi lama</li>' . "\n";
    $content .= '<li>Compatibility issue dengan system atau hardware</li>' . "\n";
    $content .= '<li>File corrupt atau installation incomplete</li>' . "\n";
    $content .= '<li>Insufficient system resources (RAM, storage, CPU)</li>' . "\n";
    $content .= '<li>Conflict dengan aplikasi atau process lain</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '<p><strong>Solusi:</strong></p>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li><strong>Update ke versi terbaru</strong> - Check for updates dan install latest version. Bug fixes dan improvements often resolve crash issues.</li>' . "\n";
    $content .= '<li><strong>Restart everything</strong> - Close aplikasi completely, restart computer. Simple tapi surprisingly effective.</li>' . "\n";
    $content .= '<li><strong>Check system requirements</strong> - Verify apakah komputer Anda meet minimum requirements (RAM, processor, GPU, OS version, dll).</li>' . "\n";
    $content .= '<li><strong>Reinstall aplikasi</strong> - Uninstall completely (termasuk registry dan leftover files), lalu install fresh dari scratch.</li>' . "\n";
    $content .= '<li><strong>Check error logs</strong> - Baca error message dengan teliti. Google error code atau message untuk find specific solutions.</li>' . "\n";
    $content .= '<li><strong>Free up resources</strong> - Close aplikasi lain yang tidak perlu, clear temporary files, free up disk space.</li>' . "\n";
    $content .= '<li><strong>Run as administrator</strong> - Right-click aplikasi dan pilih "Run as administrator" - kadang permission issue bisa cause problems.</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '<div class="alert alert-success"><strong>💡 Quick Fix:</strong> Restart computer, update aplikasi, dan run as administrator solve kebanyakan crash issues.</div>' . "\n\n";
    $content .= '<h3><i class="fas fa-exclamation-circle text-danger"></i> Masalah 3: Proses Terlalu Lambat atau Hang</h3>' . "\n";
    $content .= '<p><strong>Gejala:</strong> Proses berjalan sangat lambat, aplikasi sering not responding, atau bahkan freeze.</p>' . "\n";
    $content .= '<p><strong>Penyebab Umum:</strong></p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li>System resources maxed out (CPU, RAM, atau disk usage tinggi)</li>' . "\n";
    $content .= '<li>File terlalu besar atau complex</li>' . "\n";
    $content .= '<li>Settings tidak optimal untuk performance</li>' . "\n";
    $content .= '<li>Background processes consuming resources</li>' . "\n";
    $content .= '<li>Hardware limitation atau aging hardware</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '<p><strong>Solusi:</strong></p>' . "\n";
    $content .= '<ol>' . "\n";
    $content .= '<li><strong>Optimize settings untuk performance</strong> - Lower quality settings, reduce resolution, disable unnecessary features sementara untuk speed up process.</li>' . "\n";
    $content .= '<li><strong>Close unnecessary applications</strong> - Open Task Manager (Ctrl+Shift+Esc), close programs yang tidak dipakai, end high-CPU processes.</li>' . "\n";
    $content .= '<li><strong>Break down into smaller chunks</strong> - Instead of processing semuanya sekaligus, bagi jadi parts kecil dan process satu-satu.</li>' . "\n";
    $content .= '<li><strong>Clear cache dan temporary files</strong> - Aplikasi often accumulate cache yang memperlambat performance. Clear regular untuk maintain speed.</li>' . "\n";
    $content .= '<li><strong>Upgrade hardware jika perlu</strong> - Jika budget allows: add more RAM (paling impactful), upgrade ke SSD, atau consider better processor/GPU.</li>' . "\n";
    $content .= '<li><strong>Process during off-hours</strong> - Run heavy processes saat Anda tidak perlu use komputer, atau overnight.</li>' . "\n";
    $content .= '<li><strong>Check for malware</strong> - Virus atau malware bisa significantly slow down system. Run antivirus scan.</li>' . "\n";
    $content .= '</ol>' . "\n";
    $content .= '<div class="alert alert-success"><strong>💡 Quick Fix:</strong> Close unused apps, clear cache, lower quality settings untuk immediate performance boost.</div>' . "\n\n";
    $content .= '</div>' . "\n\n";
    $content .= '<div class="alert alert-info">' . "\n";
    $content .= '<h5><i class="fas fa-question-circle"></i> Masih Bermasalah?</h5>' . "\n";
    $content .= '<p>Jika masalah Anda tidak ada di list ini atau solusi di atas tidak berhasil:</p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li>Search di Google dengan keyword spesifik: "nama_software error_message solution"</li>' . "\n";
    $content .= '<li>Check official documentation atau knowledge base dari software yang digunakan</li>' . "\n";
    $content .= '<li>Tanya di forum atau community (Reddit, Discord, Stack Overflow)</li>' . "\n";
    $content .= '<li>Contact official support jika tersedia</li>' . "\n";
    $content .= '<li>Check YouTube untuk video tutorials troubleshooting</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '<p>Jangan menyerah! Every problem has a solution. Persistence adalah kunci.</p>' . "\n";
    $content .= '</div>' . "\n\n";
    $content .= '<h3>❓ Masalah: Proses terlalu lambat</h3>' . "\n";
    $content .= '<p><strong>Solusi:</strong></p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li>Tutup aplikasi lain yang tidak diperlukan</li>' . "\n";
    $content .= '<li>Optimize settings untuk performa</li>' . "\n";
    $content .= '<li>Upgrade hardware jika diperlukan</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div>' . "\n\n";
    // H2: Kesimpulan
    $content .= '<h2 id="kesimpulan">Kesimpulan</h2>' . "\n";
    $content .= '<p>Selamat! 🎉 Anda telah menyelesaikan tutorial <strong>' . htmlspecialchars($title) . '</strong> ';
    $content .= 'dari awal hingga akhir. Ini adalah achievement yang patut dibanggakan! Dengan mengikuti langkah-langkah di atas, ';
    $content .= 'Anda sekarang memiliki kemampuan dan pengetahuan untuk ' . htmlspecialchars($topic) . ' dengan percaya diri dan profesional.</p>' . "\n\n";
    $content .= '<p>Skill yang baru saja Anda pelajari ini bukan hanya sekedar pengetahuan teoritis, tapi practical skill yang bisa langsung ';
    $content .= 'Anda aplikasikan dalam pekerjaan atau project pribadi. Jangan underestimate value dari skill ini - banyak orang mencari ';
    $content .= 'kemampuan seperti ini dan willing to pay good money untuk services terkait.</p>' . "\n\n";
    $content .= '<div class="conclusion-box alert alert-success">' . "\n";
    $content .= '<h4><i class="fas fa-check-circle"></i> Ringkasan Pembelajaran:</h4>' . "\n";
    $content .= '<p>Setelah menyelesaikan tutorial ini, Anda telah:</p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li>✅ <strong>Memahami konsep fundamental</strong> - Anda tahu apa itu ' . htmlspecialchars($topic) . ', kenapa penting, dan bagaimana cara kerjanya</li>' . "\n";
    $content .= '<li>✅ <strong>Mengetahui manfaat praktis</strong> - Clear understanding tentang value dan ROI dari skill ini</li>' . "\n";
    $content .= '<li>✅ <strong>Menguasai langkah-langkah teknis</strong> - Mampu execute setiap tahap dengan benar dan sistematis</li>' . "\n";
    $content .= '<li>✅ <strong>Implement tips profesional</strong> - Equipped dengan best practices dan shortcuts dari experts</li>' . "\n";
    $content .= '<li>✅ <strong>Handle troubleshooting</strong> - Tahu cara identify dan solve common problems secara mandiri</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div>' . "\n\n";
    $content .= '<h3>🚀 Langkah Selanjutnya</h3>' . "\n";
    $content .= '<p>Pembelajaran tidak berhenti di sini. Untuk benar-benar master skill ini, berikut recommended next steps:</p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li><strong>Practice, Practice, Practice</strong> - Apply skill ini di real projects. Semakin sering practice, semakin natural prosesnya</li>' . "\n";
    $content .= '<li><strong>Explore Advanced Techniques</strong> - Setelah comfortable dengan basics, explore teknik dan fitur yang lebih advanced</li>' . "\n";
    $content .= '<li><strong>Join Communities</strong> - Connect dengan people yang punya interest sama. Share knowledge, ask questions, collaborate</li>' . "\n";
    $content .= '<li><strong>Build Portfolio</strong> - Document projects dan hasil kerja Anda. Portfolio yang bagus bisa buka banyak opportunities</li>' . "\n";
    $content .= '<li><strong>Stay Updated</strong> - Technology evolves. Keep learning dan stay current dengan latest developments</li>' . "\n";
    $content .= '</ul>' . "\n\n";
    $content .= '<div class="alert alert-primary">' . "\n";
    $content .= '<h5><i class="fas fa-book-reader"></i> Rekomendasi Tutorial Lainnya</h5>' . "\n";
    $content .= '<p>Perluas skill Anda dengan tutorial related lainnya di DONAN22:</p>' . "\n";
    $content .= '<ul>' . "\n";
    $content .= '<li>Browse kategori <strong>' . htmlspecialchars($category) . '</strong> untuk topik serupa</li>' . "\n";
    $content .= '<li>Check tutorial dengan level kesulitan <strong>' . htmlspecialchars($difficulty) . '</strong></li>' . "\n";
    $content .= '<li>Explore artikel tentang tools dan software terkait</li>' . "\n";
    $content .= '</ul>' . "\n";
    $content .= '</div>' . "\n\n";
    $content .= '<p><strong>Remember:</strong> Setiap expert pernah jadi beginner. Yang membedakan adalah persistence dan willingness to keep learning. ';
    $content .= 'Anda sudah take first step hari ini. Keep going, dan Anda akan amazed dengan progress yang bisa Anda capai!</p>' . "\n\n";
    $content .= '<p>Jika tutorial ini helpful, share dengan teman atau colleague yang mungkin interested. Dan jangan lupa bookmark DONAN22 ';
    $content .= 'untuk tutorial, tips, dan resource lainnya. Happy learning! 🎓</p>' . "\n\n";
    return $content;
}
function generateTableOfContents($content) {
    preg_match_all('/<h([2-3])[^>]*id=["\']([^"\']+)["\'][^>]*>(.*?)<\/h[2-3]>/i', $content, $matches, PREG_SET_ORDER);
    if (empty($matches)) {
        return '';
    }
    $toc = '<div class="table-of-contents-auto">' . "\n";
    $toc .= '<h4><i class="fas fa-list-ul"></i> Daftar Isi</h4>' . "\n";
    $toc .= '<ul class="toc-list">' . "\n";
    foreach ($matches as $match) {
        $level = $match[1];
        $id = $match[2];
        $text = strip_tags($match[3]);
        $class = $level == 2 ? 'toc-h2' : 'toc-h3';
        $toc .= '<li class="' . $class . '"><a href="#' . $id . '">' . htmlspecialchars($text) . '</a></li>' . "\n";
    }
    $toc .= '</ul>' . "\n";
    $toc .= '</div>' . "\n";
    return $toc;
}