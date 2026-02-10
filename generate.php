<?php
require_once 'config.php';
require_once 'qdrant.php';

function generateAIResponse($question, $contextChunks)
{
    // Prepare context
    $contextText = "";
    foreach ($contextChunks as $chunk) {
        $contextText .= "Source: " . $chunk['source'] . "\nContent: " . $chunk['content'] . "\n\n";
    }

    // Create system prompt
    $systemPrompt = "You are MAKAI (Makerere AI Assistant), a professional and helpful support assistant for Makerere University.

    Your goal is to provide clear, actionable, and structured answers based ONLY on the provided context.
    
    GUIDELINES:
    1. Structure your answer with clear headings and numbered steps (e.g., **Step 1:**, **Step 2:**).
    2. Use **bold** for button names, links, or important UI elements.
    3. If the context contains a procedure (like password recovery), format it as a step-by-step guide.
    4. If the context is insufficient, state exactly what is missing.
    5. Do not invent information. If the answer is not in the context, say 'I don't have information about that in my knowledge base.'
    
    CONTEXT:
    {$contextText}
    ";

    // Prepare messages sequence
    $messages = [
        ["role" => "system", "content" => $systemPrompt]
    ];

    // Add recent chat history (last 3 exchanges to keep context focused)
    if (isset($_SESSION['chat_history'])) {
        $recentHistory = array_slice($_SESSION['chat_history'], -6);
        foreach ($recentHistory as $exchange) {
            $messages[] = ["role" => $exchange['role'], "content" => $exchange['message']];
        }
    }

    // Add the current user question
    $messages[] = ["role" => "user", "content" => $question];

    $data = json_encode([
        "model" => LLM_MODEL,
        "messages" => $messages,
        "stream" => false,
        "options" => [
            "temperature" => 0.1, // Lower temperature for more factual/structured responses
            "num_predict" => 512  // Reduced for faster/more concise responses
        ]
    ]);

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => $data,
            "timeout" => 600 // Increased timeout to 10 minutes
        ]
    ];

    try {
        $context = stream_context_create($options);
        $response = @file_get_contents(OLLAMA_HOST . "/api/chat", false, $context);

        if ($response === FALSE) {
            $error = error_get_last();
            throw new Exception("Ollama generation failed: " . ($error['message'] ?? 'Unknown error'));
        }

        $result = json_decode($response, true);

        if (isset($result['message']['content'])) {
            return $result['message']['content'];
        } else {
            throw new Exception("No content in AI response");
        }
    } catch (Exception $e) {
        error_log("AI generation error: " . $e->getMessage());
        return "I apologize, but I'm having trouble generating a detailed response right now. \n\nHowever, I found some relevant documents (see Sources below) that might help with your query.";
    }
}