<?php
require_once 'config.php';

function embedText($text)
{
    $data = json_encode([
        "model" => EMBEDDING_MODEL,
        "prompt" => trim($text)
    ]);

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => $data,
            "timeout" => 120  // Increased timeout for model loading
        ]
    ];

    try {
        $context = stream_context_create($options);
        $response = @file_get_contents(OLLAMA_HOST . "/api/embeddings", false, $context);

        if ($response === FALSE) {
            throw new Exception("Ollama connection failed");
        }

        $result = json_decode($response, true);

        if (isset($result['embedding'])) {
            return $result['embedding'];
        } else {
            throw new Exception("No embedding in response");
        }
    } catch (Exception $e) {
        error_log("Embedding error: " . $e->getMessage());
        return [];
    }
}