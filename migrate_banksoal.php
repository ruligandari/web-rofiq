<?php

/**
 * Migration Script: Import Bank Soal from banksoal.txt
 * 
 * This script reads questions from banksoal.txt and imports them into the database.
 * Run this script once to migrate the initial question bank.
 * 
 * Usage: php migrate_banksoal.php
 */

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
        }
    }
}

// Database configuration
$dbHost = getenv('database.default.hostname') ?: 'localhost';
$dbUser = getenv('database.default.username') ?: 'root';
$dbPass = getenv('database.default.password') ?: '';
$dbName = getenv('database.default.database') ?: 'db_quiz';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connected successfully.\n\n";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

// Read banksoal.txt
$filePath = __DIR__ . '/banksoal.txt';
if (!file_exists($filePath)) {
    die("Error: banksoal.txt not found!\n");
}

$content = file_get_contents($filePath);
$lines = explode("\n", $content);

// Parse questions
$questions = [];
$currentQuestion = null;

foreach ($lines as $line) {
    $line = trim($line);
    
    if (empty($line)) {
        // Empty line indicates end of current question
        if ($currentQuestion && isset($currentQuestion['soal'])) {
            $questions[] = $currentQuestion;
            $currentQuestion = null;
        }
        continue;
    }
    
    // Check if it's a question (doesn't start with A., B., C., D., or Jawaban:)
    if (!preg_match('/^[A-D]\./', $line) && !preg_match('/^Jawaban:/', $line)) {
        // This is a question
        $currentQuestion = [
            'soal' => $line,
            'pilihan_a' => '',
            'pilihan_b' => '',
            'pilihan_c' => '',
            'pilihan_d' => '',
            'jawaban' => '',
        ];
    }
    // Check if it's an option
    elseif (preg_match('/^A\.\s*(.+)/', $line, $matches)) {
        if ($currentQuestion) {
            $currentQuestion['pilihan_a'] = trim($matches[1]);
        }
    }
    elseif (preg_match('/^B\.\s*(.+)/', $line, $matches)) {
        if ($currentQuestion) {
            $currentQuestion['pilihan_b'] = trim($matches[1]);
        }
    }
    elseif (preg_match('/^C\.\s*(.+)/', $line, $matches)) {
        if ($currentQuestion) {
            $currentQuestion['pilihan_c'] = trim($matches[1]);
        }
    }
    elseif (preg_match('/^D\.\s*(.+)/', $line, $matches)) {
        if ($currentQuestion) {
            $currentQuestion['pilihan_d'] = trim($matches[1]);
        }
    }
    // Check if it's the answer
    elseif (preg_match('/^Jawaban:\s*([A-D])/', $line, $matches)) {
        if ($currentQuestion) {
            $currentQuestion['jawaban'] = trim($matches[1]);
        }
    }
}

// Add last question if exists
if ($currentQuestion && isset($currentQuestion['soal'])) {
    $questions[] = $currentQuestion;
}

// Get default kategori_id (first category or create a default one)
$stmt = $pdo->query("SELECT id FROM kategori LIMIT 1");
$kategori = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kategori) {
    // Create default category
    $pdo->exec("INSERT INTO kategori (nama, created_at, updated_at) VALUES ('Umum', NOW(), NOW())");
    $kategori_id = $pdo->lastInsertId();
    echo "Created default category 'Umum' with ID: $kategori_id\n\n";
} else {
    $kategori_id = $kategori['id'];
    echo "Using existing category with ID: $kategori_id\n\n";
}

// Prepare insert statement
$insertStmt = $pdo->prepare("
    INSERT INTO soal (level, kategori_id, foto, soal, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban, bobot_nilai, is_active, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

// Insert questions into database
$inserted = 0;
$errors = 0;

echo "Starting migration...\n";
echo "Found " . count($questions) . " questions to import.\n\n";

foreach ($questions as $index => $question) {
    // Validate question data
    if (empty($question['soal']) || empty($question['pilihan_a']) || 
        empty($question['pilihan_b']) || empty($question['pilihan_c']) || 
        empty($question['pilihan_d']) || empty($question['jawaban'])) {
        echo "✗ Skipping question " . ($index + 1) . " - incomplete data\n";
        $errors++;
        continue;
    }
    
    try {
        $insertStmt->execute([
            1, // level
            $kategori_id,
            null, // foto
            $question['soal'],
            $question['pilihan_a'],
            $question['pilihan_b'],
            $question['pilihan_c'],
            $question['pilihan_d'],
            $question['jawaban'],
            10, // bobot_nilai
            1 // is_active
        ]);
        
        $inserted++;
        echo "✓ Imported question " . ($index + 1) . ": " . substr($question['soal'], 0, 50) . "...\n";
    } catch (PDOException $e) {
        echo "✗ Error importing question " . ($index + 1) . ": " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n";
echo "========================================\n";
echo "Migration completed!\n";
echo "Successfully imported: $inserted questions\n";
echo "Errors: $errors\n";
echo "========================================\n";
