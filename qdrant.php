<?php
require_once 'embed.php';

function searchKnowledge($question, $limit = 5)
{
    $vector = embedText($question);

    if (empty($vector)) {
        return [];
    }

    $data = json_encode([
        "vector" => $vector,
        "limit" => $limit,
        "with_payload" => true,
        "score_threshold" => 0.5
    ]);

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => $data,
            "timeout" => 60  // Increased timeout
        ]
    ];

    try {
        $context = stream_context_create($options);
        $response = @file_get_contents(
            QDRANT_HOST . "/collections/" . COLLECTION_NAME . "/points/search",
            false,
            $context
        );

        if ($response === FALSE) {
            throw new Exception("Qdrant connection failed");
        }

        $result = json_decode($response, true);

        $chunks = [];
        if (isset($result['result'])) {
            foreach ($result['result'] as $hit) {
                if (isset($hit['payload']['content'])) {
                    $chunks[] = [
                        'content' => $hit['payload']['content'],
                        'score' => $hit['score'],
                        'source' => $hit['payload']['source'] ?? 'unknown'
                    ];
                }
            }
        }

        return $chunks;
    } catch (Exception $e) {
        error_log("Qdrant search error: " . $e->getMessage());
        return [];
    }
}