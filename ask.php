<?php
require_once 'config.php';
require_once 'qdrant.php';
require_once 'generate.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Enable error logging
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering to capture any unwanted output
ob_start();

// Increase execution time for model loading
set_time_limit(600);

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['question']) || empty(trim($input['question']))) {
        throw new Exception("No question provided");
    }

    $question = trim($input['question']);

    // Add user message to history
    addToChatHistory('user', $question);

    // Search for relevant context
    error_log("Starting knowledge search for: " . $question);
    $chunks = searchKnowledge($question, 3);
    error_log("Found " . count($chunks) . " chunks");

    if (empty($chunks)) {
        error_log("Warning: No chunks found for question");
    }

    // Generate AI response
    error_log("Generating AI response");
    $response = generateAIResponse($question, $chunks);
    error_log("AI response generated successfully");

    // Add AI response to history
    addToChatHistory('assistant', $response);

    // Prepare sources
    $sources = [];
    foreach ($chunks as $chunk) {
        if ($chunk['score'] > 0.7) { // Only show high-confidence sources
            $sources[] = [
                'content' => substr($chunk['content'], 0, 150) . '...',
                'source' => $chunk['source'],
                'score' => round($chunk['score'], 3)
            ];
        }
    }

    // Clean any captured output (warnings, notices, etc.)
    ob_end_clean();

    echo json_encode([
        'success' => true,
        'answer' => $response,
        'sources' => array_slice($sources, 0, 3), // Top 3 sources
        'chunks_used' => count($chunks),
        'history' => $_SESSION['chat_history']
    ]);

} catch (Exception $e) {
    error_log("Error in ask.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'details' => 'Check server logs for more information'
    ]);
}