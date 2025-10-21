<?php


function getKeywordSuggestions($seed) {
    $suggestions = [];
    // Common modifiers for download site
    $modifiers = [
        'download',
        'gratis',
        'full version',
        'terbaru',
        'crack',
        'free',
        'full crack',
        'latest version',
        'offline installer',
        'full patch',
        'aktivasi',
        'keygen',
        'cara download',
        'cara install',
        'tutorial'
    ];
    // Generate combinations
    foreach ($modifiers as $modifier) {
        $suggestions[] = $seed . ' ' . $modifier;
        $suggestions[] = $modifier . ' ' . $seed;
    }
    // Add year variants
    $currentYear = date('Y');
    $suggestions[] = $seed . ' ' . $currentYear;
    $suggestions[] = 'download ' . $seed . ' ' . $currentYear;
    return array_unique($suggestions);
}

function getHighVolumeKeywords() {
    return [
        // Software Downloads
        ['keyword' => 'download idm full crack', 'volume' => 50000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download photoshop gratis', 'volume' => 40000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download office 2021 full', 'volume' => 30000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download windows 11', 'volume' => 100000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download corel draw gratis', 'volume' => 25000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download winrar full version', 'volume' => 20000, 'competition' => 'medium', 'intent' => 'transactional'],
        ['keyword' => 'download autocad gratis', 'volume' => 18000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download adobe premiere pro', 'volume' => 15000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download adobe after effects', 'volume' => 12000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download sony vegas pro', 'volume' => 10000, 'competition' => 'medium', 'intent' => 'transactional'],
        // Games Downloads
        ['keyword' => 'download gta v gratis', 'volume' => 60000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download minecraft gratis', 'volume' => 50000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download pes 2021 gratis', 'volume' => 35000, 'competition' => 'medium', 'intent' => 'transactional'],
        ['keyword' => 'download fifa 23 gratis', 'volume' => 30000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'download gta san andreas', 'volume' => 28000, 'competition' => 'medium', 'intent' => 'transactional'],
        // Activators
        ['keyword' => 'kms activator windows 10', 'volume' => 22000, 'competition' => 'medium', 'intent' => 'transactional'],
        ['keyword' => 'kmspico download', 'volume' => 20000, 'competition' => 'high', 'intent' => 'transactional'],
        ['keyword' => 'office activator', 'volume' => 15000, 'competition' => 'medium', 'intent' => 'transactional'],
    ];
}

function getLongTailKeywords() {
    return [
        ['keyword' => 'cara install idm tanpa registrasi', 'volume' => 2000, 'competition' => 'low', 'intent' => 'informational'],
        ['keyword' => 'download adobe premiere pro cc 2025', 'volume' => 5000, 'competition' => 'medium', 'intent' => 'transactional'],
        ['keyword' => 'photoshop portable gratis windows 10', 'volume' => 3000, 'competition' => 'low', 'intent' => 'transactional'],
        ['keyword' => 'cara crack corel draw 2023', 'volume' => 2500, 'competition' => 'low', 'intent' => 'informational'],
        ['keyword' => 'download office 2021 full crack google drive', 'volume' => 4000, 'competition' => 'medium', 'intent' => 'transactional'],
        ['keyword' => 'autocad 2024 full version gratis', 'volume' => 3500, 'competition' => 'medium', 'intent' => 'transactional'],
        ['keyword' => 'download idm terbaru tanpa fake serial', 'volume' => 2800, 'competition' => 'low', 'intent' => 'transactional'],
        ['keyword' => 'cara aktivasi windows 10 permanent', 'volume' => 6000, 'competition' => 'medium', 'intent' => 'informational'],
        ['keyword' => 'sony vegas pro 19 full crack', 'volume' => 2200, 'competition' => 'low', 'intent' => 'transactional'],
        ['keyword' => 'download winrar 64 bit full version', 'volume' => 1800, 'competition' => 'low', 'intent' => 'transactional'],
    ];
}
function generateKeywordReport($pdo) {
    $report = [
        'total_posts' => 0,
        'keywords_covered' => [],
        'missing_keywords' => [],
        'opportunities' => []
    ];
    try {
        // Get total posts
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM posts WHERE status = 'published'");
        $report['total_posts'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        // Get all post titles to check keyword coverage
        $stmt = $pdo->query("SELECT title FROM posts WHERE status = 'published'");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $highVolumeKeywords = getHighVolumeKeywords();
        $longTailKeywords = getLongTailKeywords();
        foreach ($highVolumeKeywords as $kw) {
            $keyword = $kw['keyword'];
            $found = false;
            foreach ($posts as $post) {
                if (stripos($post['title'], $keyword) !== false) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                $report['keywords_covered'][] = $kw;
            } else {
                $report['missing_keywords'][] = $kw;
            }
        }
        // Find opportunities (low competition, decent volume)
        foreach ($longTailKeywords as $kw) {
            if ($kw['competition'] === 'low' && $kw['volume'] >= 2000) {
                $report['opportunities'][] = $kw;
            }
        }
    } catch (PDOException $e) {
        // Handle error
    }
    return $report;
}

function getKeywordRecommendations($category) {
    $recommendations = [];
    $categoryKeywords = [
        'software' => [
            'download {software} full version',
            'cara install {software}',
            '{software} terbaru 2025',
            '{software} full crack gratis',
            'tutorial {software} pemula'
        ],
        'games' => [
            'download game {game} gratis',
            '{game} full version pc',
            'cara main {game} offline',
            '{game} crack working',
            'spesifikasi {game}'
        ],
        'tutorial' => [
            'cara {action}',
            'tutorial {action} lengkap',
            'panduan {action} untuk pemula',
            'belajar {action}',
            '{action} step by step'
        ]
    ];
    $cat = strtolower($category);
    foreach ($categoryKeywords as $key => $keywords) {
        if (stripos($cat, $key) !== false) {
            $recommendations = $keywords;
            break;
        }
    }
    if (empty($recommendations)) {
        $recommendations = $categoryKeywords['software']; // Default
    }
    return $recommendations;
}

function renderKeywordDashboard($report) {
    $html = '
<div class="keyword-dashboard">
    <h1><i class="fas fa-search"></i> Keyword Research Dashboard</h1>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-content">
                <h3>' . $report['total_posts'] . '</h3>
                <p>Total Posts Published</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <h3>' . count($report['keywords_covered']) . '</h3>
                <p>Keywords Covered</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fas fa-exclamation-circle"></i></div>
            <div class="stat-content">
                <h3>' . count($report['missing_keywords']) . '</h3>
                <p>Missing Keywords</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info"><i class="fas fa-lightbulb"></i></div>
            <div class="stat-content">
                <h3>' . count($report['opportunities']) . '</h3>
                <p>Opportunities Found</p>
            </div>
        </div>
    </div>
    <div class="keyword-sections">
        <section class="keyword-section">
            <h2><i class="fas fa-trophy"></i> High Volume Keywords Covered</h2>
            <div class="keyword-table-wrapper">
                <table class="keyword-table">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Volume</th>
                            <th>Competition</th>
                            <th>Intent</th>
                        </tr>
                    </thead>
                    <tbody>';
    foreach ($report['keywords_covered'] as $kw) {
        $html .= '
                        <tr>
                            <td><strong>' . htmlspecialchars($kw['keyword']) . '</strong></td>
                            <td><span class="badge volume">' . number_format($kw['volume']) . '/mo</span></td>
                            <td><span class="badge comp-' . $kw['competition'] . '">' . ucfirst($kw['competition']) . '</span></td>
                            <td><span class="badge intent">' . ucfirst($kw['intent']) . '</span></td>
                        </tr>';
    }
    $html .= '
                    </tbody>
                </table>
            </div>
        </section>
        <section class="keyword-section missing">
            <h2><i class="fas fa-exclamation-triangle"></i> Missing High-Value Keywords</h2>
            <p class="section-desc">These are high-volume keywords you should target with new content:</p>
            <div class="keyword-table-wrapper">
                <table class="keyword-table">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Volume</th>
                            <th>Competition</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>';
    foreach ($report['missing_keywords'] as $kw) {
        $priority = $kw['volume'] > 30000 ? 'High' : ($kw['volume'] > 15000 ? 'Medium' : 'Low');
        $html .= '
                        <tr>
                            <td><strong>' . htmlspecialchars($kw['keyword']) . '</strong></td>
                            <td><span class="badge volume">' . number_format($kw['volume']) . '/mo</span></td>
                            <td><span class="badge comp-' . $kw['competition'] . '">' . ucfirst($kw['competition']) . '</span></td>
                            <td><span class="badge priority-' . strtolower($priority) . '">' . $priority . '</span></td>
                        </tr>';
    }
    $html .= '
                    </tbody>
                </table>
            </div>
        </section>
        <section class="keyword-section opportunities">
            <h2><i class="fas fa-lightbulb"></i> Low Competition Opportunities</h2>
            <p class="section-desc">Long-tail keywords with decent volume and low competition:</p>
            <div class="keyword-table-wrapper">
                <table class="keyword-table">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Volume</th>
                            <th>Competition</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
    foreach ($report['opportunities'] as $kw) {
        $html .= '
                        <tr>
                            <td><strong>' . htmlspecialchars($kw['keyword']) . '</strong></td>
                            <td><span class="badge volume">' . number_format($kw['volume']) . '/mo</span></td>
                            <td><span class="badge comp-low">Low</span></td>
                            <td><a href="#" class="action-btn">Create Content</a></td>
                        </tr>';
    }
    $html .= '
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<style>
.keyword-dashboard {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px;
}
.keyword-dashboard h1 {
    color: #1e293b;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 40px;
    display: flex;
    align-items: center;
    gap: 15px;
}
.keyword-dashboard h1 i {
    color: #3b82f6;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
}
.stat-icon.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}
.stat-icon.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}
.stat-icon.info {
    background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
}
.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.stat-content p {
    color: #64748b;
    margin: 5px 0 0 0;
    font-size: 0.95rem;
}
.keyword-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.keyword-section h2 {
    color: #1e293b;
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.keyword-section h2 i {
    color: #3b82f6;
}
.keyword-section.missing h2 i {
    color: #f59e0b;
}
.keyword-section.opportunities h2 i {
    color: #8b5cf6;
}
.section-desc {
    color: #64748b;
    margin-bottom: 20px;
    font-size: 1rem;
}
.keyword-table-wrapper {
    overflow-x: auto;
}
.keyword-table {
    width: 100%;
    border-collapse: collapse;
}
.keyword-table thead {
    background: #f8fafc;
}
.keyword-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    border-bottom: 2px solid #e2e8f0;
}
.keyword-table td {
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
}
.keyword-table tbody tr:hover {
    background: #f8fafc;
}
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.badge.volume {
    background: #dbeafe;
    color: #1e40af;
}
.badge.comp-high {
    background: #fee2e2;
    color: #991b1b;
}
.badge.comp-medium {
    background: #fed7aa;
    color: #9a3412;
}
.badge.comp-low {
    background: #d1fae5;
    color: #065f46;
}
.badge.intent {
    background: #e0e7ff;
    color: #3730a3;
}
.badge.priority-high {
    background: #fecaca;
    color: #991b1b;
}
.badge.priority-medium {
    background: #fed7aa;
    color: #9a3412;
}
.badge.priority-low {
    background: #d1fae5;
    color: #065f46;
}
.action-btn {
    display: inline-block;
    padding: 6px 16px;
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s ease;
}
.action-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(59,130,246,0.3);
}
@media (max-width: 768px) {
    .keyword-dashboard {
        padding: 15px;
    }
    .keyword-dashboard h1 {
        font-size: 1.8rem;
    }
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .keyword-table {
        font-size: 0.85rem;
    }
    .keyword-table th,
    .keyword-table td {
        padding: 10px;
    }
}
</style>
';
    return $html;
}

function exportKeywordsToCSV($keywords, $filename = 'keywords.csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');
    // Header
    fputcsv($output, ['Keyword', 'Volume', 'Competition', 'Intent', 'Priority']);
    // Data
    foreach ($keywords as $kw) {
        $priority = $kw['volume'] > 30000 ? 'High' : ($kw['volume'] > 15000 ? 'Medium' : 'Low');
        fputcsv($output, [
            $kw['keyword'],
            $kw['volume'],
            $kw['competition'],
            $kw['intent'],
            $priority
        ]);
    }
    fclose($output);
    exit;
}