<?php
// Configuration
set_time_limit(0); // Increase execution time for all scripts including this config
define('OLLAMA_HOST', 'http://localhost:11434');
define('QDRANT_HOST', 'http://localhost:6333');
define('COLLECTION_NAME', 'makai');
define('EMBEDDING_MODEL', 'nomic-embed-text');
define('LLM_MODEL', 'qwen2.5'); // or 'mistral', 'qwen2.5'

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize chat history
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}

// Limit chat history
function addToChatHistory($role, $message)
{
    $_SESSION['chat_history'][] = [
        'role' => $role,
        'message' => $message,
        'time' => date('H:i')
    ];

    // Keep only last 20 messages
    if (count($_SESSION['chat_history']) > 20) {
        $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -20);
    }
}