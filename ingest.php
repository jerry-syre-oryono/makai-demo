<?php
require_once 'embed.php';

// Increase execution time and memory for large ingestion
set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '1024M');
require_once 'qdrant.php';
require_once 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

// Check if Qdrant collection exists, create if not
function ensureCollection()
{
    $check = @file_get_contents(QDRANT_HOST . "/collections/" . COLLECTION_NAME);

    if (strpos($http_response_header[0], '404') !== false) {
        // Collection doesn't exist, create it
        $data = json_encode([
            "vectors" => [
                "size" => 768, // nomic-embed-text dimension
                "distance" => "Cosine"
            ]
        ]);

        $options = [
            "http" => [
                "header" => "Content-Type: application/json\r\n",
                "method" => "PUT",
                "content" => $data
            ]
        ];

        file_get_contents(
            QDRANT_HOST . "/collections/" . COLLECTION_NAME,
            false,
            stream_context_create($options)
        );

        echo "Created collection: " . COLLECTION_NAME . "\n";
    }
}

// Extract text from file (supports .txt and .pdf)
function extractTextFromFile($filepath)
{
    $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

    if ($extension === 'txt') {
        return file_get_contents($filepath);
    } elseif ($extension === 'pdf') {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($filepath);
            $text = $pdf->getText();

            // Clean up the text
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);

            return $text;
        } catch (Exception $e) {
            echo "Error parsing PDF: " . $e->getMessage() . "\n";
            return '';
        }
    }

    return '';
}

// Smart chunking
function chunkText($text, $maxLength = 500)
{
    $chunks = [];
    $sentences = preg_split('/(?<=[.!?])\s+/', $text);
    $currentChunk = '';

    foreach ($sentences as $sentence) {
        if (strlen($currentChunk) + strlen($sentence) < $maxLength) {
            $currentChunk .= $sentence . ' ';
        } else {
            if (!empty(trim($currentChunk))) {
                $chunks[] = trim($currentChunk);
            }
            $currentChunk = $sentence . ' ';
        }
    }

    if (!empty(trim($currentChunk))) {
        $chunks[] = trim($currentChunk);
    }

    return $chunks;
}

// Ingest a file
function ingestFile($filepath, $filename)
{
    echo "Extracting text from {$filename}...\n";
    $text = extractTextFromFile($filepath);

    if (empty($text)) {
        echo "⚠ Skipping {$filename} (no text extracted)\n";
        return 0;
    }

    $chunks = chunkText($text);

    $total = count($chunks);
    echo "Processing {$filename} ({$total} chunks)...\n";

    foreach ($chunks as $i => $chunk) {
        if (strlen(trim($chunk)) < 30)
            continue;

        $vector = embedText($chunk);

        if (empty($vector)) {
            echo "Skipping chunk {$i} (embedding failed)\n";
            continue;
        }

        $point = [
            "points" => [
                [
                    "id" => md5($filename . $i),
                    "vector" => $vector,
                    "payload" => [
                        "content" => $chunk,
                        "source" => $filename,
                        "chunk_index" => $i
                    ]
                ]
            ]
        ];

        $options = [
            "http" => [
                "header" => "Content-Type: application/json\r\n",
                "method" => "PUT",
                "content" => json_encode($point)
            ]
        ];

        file_get_contents(
            QDRANT_HOST . "/collections/" . COLLECTION_NAME . "/points?wait=true",
            false,
            stream_context_create($options)
        );

        if ($i % 10 === 0) {
            echo "  Processed {$i}/{$total} chunks\n";
        }
    }

    echo "✓ Completed: {$filename}\n";
    return $total;
}

// Main ingestion
ensureCollection();

// Get all .txt and .pdf files
$txtFiles = glob("knowledge/*.txt");
$pdfFiles = glob("knowledge/*.pdf");
$files = array_merge($txtFiles, $pdfFiles);

$totalChunks = 0;

echo "Starting ingestion...\n";
echo "Found " . count($files) . " files to process\n\n";

foreach ($files as $file) {
    $filename = basename($file);
    $chunks = ingestFile($file, $filename);
    $totalChunks += $chunks;
}

echo "\n✅ Ingestion complete! Total chunks: {$totalChunks}\n";