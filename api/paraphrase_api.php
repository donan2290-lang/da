<?php
if (!defined('PARAPHRASE_FUNCTIONS_ONLY')) {
    define('ADMIN_ACCESS', true);
    require_once '../config_modern.php';
    require_once '../admin/system/security_system.php';
    requireLogin();
    header('Content-Type: application/json');
    $security = new SecurityManager($pdo);
    if (!isset($_POST['csrf_token']) || !$security->verifyCSRFToken($_POST['csrf_token'])) {
        echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid']);
        exit;
    }
    if (!hasPermission('manage_posts')) {
        echo json_encode(['success' => false, 'message' => 'Tidak memiliki akses']);
        exit;
    }
    // Get parameters
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $method = isset($_POST['method']) ? $_POST['method'] : 'simple';
    $preserveKeywords = isset($_POST['preserve_keywords']) && $_POST['preserve_keywords'] == '1';
    if ($postId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Post ID tidak valid']);
        exit;
    }
    try {
        // Get post content
        $stmt = $pdo->prepare("SELECT id, title, content FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch();
        if (!$post) {
            echo json_encode(['success' => false, 'message' => 'Post tidak ditemukan']);
            exit;
        }
        // Store original content for debugging
        $originalContent = $post['content'];
        // Paraphrase using manual method with synonyms.txt
        $paraphrasedContent = paraphraseHtmlContent($post['content'], $method, $preserveKeywords);
        if ($originalContent === $paraphrasedContent) {
            echo json_encode([
                'success' => false,
                'message' => 'Konten tidak berubah. Coba uncheck "Pertahankan kata kunci" atau gunakan metode berbeda.',
                'debug' => [
                    'method' => $method,
                    'preserveKeywords' => $preserveKeywords,
                    'original_length' => strlen($originalContent),
                    'new_length' => strlen($paraphrasedContent)
                ]
            ]);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE posts SET content = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$paraphrasedContent, $postId]);
        echo json_encode([
            'success' => true,
            'message' => 'Konten berhasil diparafrasekan!',
            'post_id' => $postId,
            'debug' => [
                'method' => $method,
                'preserveKeywords' => $preserveKeywords,
                'original_length' => strlen($originalContent),
                'new_length' => strlen($paraphrasedContent),
                'changed' => $originalContent !== $paraphrasedContent
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    // Exit to prevent function definitions from being executed again
    exit;
}

function loadSynonymsFromFile($forceReload = false) {
    static $synonyms = null;
    // Clear cache if force reload
    if ($forceReload) {
        $synonyms = null;
    }
    // Return cached data if available
    if ($synonyms !== null) {
        return $synonyms;
    }
    $synonyms = [];
    $filePath = __DIR__ . '/../sinonim_gabungan.csv';
    if (!file_exists($filePath)) {
        error_log("Synonyms CSV file not found: $filePath");
        return $synonyms;
    }
    $handle = fopen($filePath, 'r');
    if ($handle === false) {
        error_log("Failed to open synonyms CSV file: $filePath");
        return $synonyms;
    }
    // Skip header row
    $header = fgetcsv($handle);
    $lineCount = 0;
    $successCount = 0;
    while (($data = fgetcsv($handle, 10000, ',')) !== false) {
        $lineCount++;
        // CSV format: kata,sinonim
        if (count($data) < 2) {
            continue;
        }
        // Extra cleanup: remove all whitespace variations
        $word = preg_replace('/\s+/', ' ', trim($data[0]));
        $synonymsString = trim($data[1]);
        // Skip empty entries or entries with empty word
        if (empty($word) || empty($synonymsString)) {
            continue;
        }
        // Parse synonyms (separated by comma within quotes)
        $syns = explode(',', $synonymsString);
        // Clean up synonyms - remove extra spaces and filter empty
        $syns = array_map(function($s) {
            return preg_replace('/\s+/', ' ', trim($s));
        }, $syns);
        $syns = array_filter($syns, function($s) {
            return !empty($s);
        });
        if (!empty($word) && !empty($syns)) {
            // Normalize word (lowercase for consistent matching)
            $wordKey = mb_strtolower($word, 'UTF-8');
            // If word already exists, merge synonyms (karena ada duplikasi di CSV)
            if (isset($synonyms[$wordKey])) {
                $synonyms[$wordKey] = array_values(array_unique(array_merge($synonyms[$wordKey], $syns)));
            } else {
                $synonyms[$wordKey] = array_values($syns);
            }
            $successCount++;
        }
    }
    fclose($handle);
    error_log("Loaded $successCount unique words from $lineCount total lines in CSV file");
    return $synonyms;
}

function paraphraseHtmlContent($html, $method = 'simple', $preserveKeywords = true) {
    // Extract all HTML tags and preserve them
    $tagPattern = '/<[^>]+>/';
    $tags = [];
    // Replace all tags with placeholders
    $textOnly = preg_replace_callback($tagPattern, function($matches) use (&$tags) {
        $placeholder = '###TAG_' . count($tags) . '###';
        $tags[] = $matches[0];
        return $placeholder;
    }, $html);
    // Paraphrase the text-only version
    $paraphrasedText = paraphraseText($textOnly, $method, $preserveKeywords);
    // Restore the tags
    foreach ($tags as $index => $tag) {
        $placeholder = '###TAG_' . $index . '###';
        $paraphrasedText = str_replace($placeholder, $tag, $paraphrasedText);
    }
    return $paraphrasedText;
}

function paraphraseText($text, $method = 'simple', $preserveKeywords = true) {
    // Keywords to preserve (only very specific ones)
    $keywordsToPreserve = [];
    if ($preserveKeywords) {
        // Only preserve specific technical terms and versions
        // Trello, macOS, Intel, Apple Silicon, M1, M2, M3, etc - but NOT common words
        preg_match_all('/\b(Trello|macOS|Intel|Apple\s+Silicon|M[123]|Mojave|Sonoma|SSD|RAM|GB|MB|Core\s+i[0-9]|\.dmg|\.pkg)\b/', $text, $specificTerms);
        preg_match_all('/\b\d+\.\d+(?:\.\d+)?\b/', $text, $versions); // Version numbers
        $keywordsToPreserve = array_merge(
            $specificTerms[0] ?? [],
            $versions[0] ?? []
        );
    }
    switch ($method) {
        case 'simple':
            return paraphraseSimple($text, $keywordsToPreserve);
        case 'moderate':
            return paraphraseModerate($text, $keywordsToPreserve);
        case 'advanced':
            return paraphraseAdvanced($text, $keywordsToPreserve);
        default:
            return paraphraseSimple($text, $keywordsToPreserve);
    }
}

function paraphraseSimple($text, $keywordsToPreserve = []) {
    // Load synonyms from file
    $synonymDict = loadSynonymsFromFile();
    $result = $text;
    $replacedWords = []; // Track replaced words to avoid multiple replacements
    // Single pass with random synonyms (no repetitive loops)
    foreach ($synonymDict as $original => $synonyms) {
        // Skip if it's a keyword to preserve
        $shouldPreserve = false;
        foreach ($keywordsToPreserve as $keyword) {
            if (stripos($original, $keyword) !== false) {
                $shouldPreserve = true;
                break;
            }
        }
        if ($shouldPreserve) {
            continue;
        }
        // Check if word exists in text (case-insensitive)
        $pattern = '/\b' . preg_quote($original, '/') . '\b/iu';
        if (!preg_match($pattern, $result)) {
            continue;
        }
        // Pick random synonym for variety
        $replacement = $synonyms[array_rand($synonyms)];
        foreach ($keywordsToPreserve as $keyword) {
            if (stripos($replacement, $keyword) !== false) {
                $shouldPreserve = true;
                break;
            }
        }
        if ($shouldPreserve) {
            continue;
        }
        // Track to avoid replacing same word multiple times
        $replacementKey = mb_strtolower($original, 'UTF-8');
        if (isset($replacedWords[$replacementKey])) {
            continue;
        }
        // Replace with word boundary for accuracy
        $result = preg_replace($pattern, $replacement, $result, -1, $count);
        if ($count > 0) {
            $replacedWords[$replacementKey] = true;
        }
    }
    return $result;
}

function paraphraseModerate($text, $keywordsToPreserve = []) {
    // First apply simple paraphrase
    $result = paraphraseSimple($text, $keywordsToPreserve);
    // Split into sentences
    $sentences = preg_split('/([.!?]+\s+)/', $result, -1, PREG_SPLIT_DELIM_CAPTURE);
    $paraphrased = [];
    for ($i = 0; $i < count($sentences); $i += 2) {
        $sentence = trim($sentences[$i]);
        $delimiter = $sentences[$i + 1] ?? '';
        if (empty($sentence)) {
            continue;
        }
        // Rearrange some sentence patterns
        $sentence = rearrangeSentence($sentence, $keywordsToPreserve);
        $paraphrased[] = $sentence . $delimiter;
    }
    return implode('', $paraphrased);
}

function paraphraseAdvanced($text, $keywordsToPreserve = []) {
    // First apply moderate paraphrase
    $result = paraphraseModerate($text, $keywordsToPreserve);
    // Add variation to sentence starters
    $result = addSentenceVariation($result);
    return $result;
}

function rearrangeSentence($sentence, $keywordsToPreserve) {
    // Pattern: "Anda dapat X dengan Y" -> "X bisa dilakukan dengan Y"
    $sentence = preg_replace('/Anda dapat (.+?) dengan (.+?)$/iu', 'X bisa dilakukan dengan $2', $sentence);
    $sentence = preg_replace('/Anda bisa (.+?) dengan (.+?)$/iu', 'X dapat dilakukan melalui $2', $sentence);
    // Pattern: "X adalah Y" -> "Y yaitu X"
    if (!preg_match('/[A-Z][a-z]+ adalah/', $sentence)) { // Skip proper nouns
        $sentence = preg_replace('/(.+?) adalah (.+?)$/iu', '$2 yaitu $1', $sentence);
        $sentence = preg_replace('/(.+?) merupakan (.+?)$/iu', '$2 ialah $1', $sentence);
    }
    // Pattern: "Untuk X, Y" -> "Y guna X"
    $sentence = preg_replace('/^Untuk (.+?), (.+?)$/iu', '$2 guna $1', $sentence);
    // Pattern: "dengan X" -> "melalui X"
    $sentence = preg_replace('/\bdengan\b/iu', 'melalui', $sentence);
    // Pattern: "yang memiliki" -> "dengan"
    $sentence = preg_replace('/yang memiliki/iu', 'dengan', $sentence);
    // Pattern: "di mana" -> "dimana"
    $sentence = preg_replace('/di mana/iu', 'dimana', $sentence);
    return $sentence;
}

function addSentenceVariation($text) {
    $variations = [
        '/^Anda dapat/iu' => 'Pengguna mampu',
        '/^Anda bisa/iu' => 'Kamu mampu',
        '/^Untuk/iu' => 'Guna',
        '/^Dengan/iu' => 'Melalui',
        '/^Jika/iu' => 'Apabila',
        '/^Ketika/iu' => 'Saat',
        '/^Setelah/iu' => 'Sesudah',
        '/^Sebelum/iu' => 'Sebelumnya',
        '/^Dalam/iu' => 'Di dalam',
        '/^Pada/iu' => 'Di',
        '/^Software ini/iu' => 'Aplikasi ini',
        '/^Aplikasi ini/iu' => 'Program ini',
        '/^Program ini/iu' => 'Software ini',
    ];
    // Split into sentences
    $sentences = preg_split('/([.!?]+\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
    $result = [];
    for ($i = 0; $i < count($sentences); $i += 2) {
        $sentence = $sentences[$i];
        $delimiter = $sentences[$i + 1] ?? '';
        if (empty(trim($sentence))) {
            $result[] = $sentence . $delimiter;
            continue;
        }
        // Apply variations randomly (50% chance)
        foreach ($variations as $pattern => $replacement) {
            if (rand(0, 1) == 1) {
                $sentence = preg_replace($pattern, $replacement, $sentence, 1);
            }
        }
        $result[] = $sentence . $delimiter;
    }
    return implode('', $result);
}