<?php
/**
 * Face Recognition Handler (API Endpoint)
 * Location: C:\xampp\htdocs\makai-demo\face_handler.php
 */

require_once 'face_api.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$api = new FaceRecognitionAPI();

// GET requests - list faces
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? 'list';
    
    switch ($action) {
        case 'list':
            $limit = intval($_GET['limit'] ?? 100);
            echo json_encode($api->listAllFaces($limit));
            break;
            
        case 'similar':
            $name = $_GET['name'] ?? '';
            $limit = intval($_GET['limit'] ?? 10);
            
            if (empty($name)) {
                echo json_encode(['error' => 'Name parameter required']);
            } else {
                echo json_encode($api->findSimilar($name, $limit));
            }
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    exit();
}

// POST requests - handle operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['error' => 'No image uploaded']);
                exit();
            }
            
            $name = $_POST['name'] ?? '';
            if (empty($name)) {
                echo json_encode(['error' => 'Name required']);
                exit();
            }
            
            $imagePath = $api->saveUploadedFile($_FILES['image'], 'faces');
            if (!$imagePath) {
                echo json_encode(['error' => 'Failed to save image']);
                exit();
            }
            
            $notes = $_POST['notes'] ?? '';
            $result = $api->addFace($imagePath, $name, ['notes' => $notes]);
            echo json_encode($result);
            break;
            
        case 'recognize':
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['error' => 'No image uploaded']);
                exit();
            }
            
            $imagePath = $api->saveUploadedFile($_FILES['image'], 'temp');
            if (!$imagePath) {
                echo json_encode(['error' => 'Failed to save image']);
                exit();
            }
            
            $threshold = floatval($_POST['threshold'] ?? 0.6);
            $result = $api->recognizeFaces($imagePath, $threshold);
            
            // Clean up temp file
            @unlink($imagePath);
            
            echo json_encode($result);
            break;
            
        case 'delete':
            $faceId = $_POST['face_id'] ?? '';
            if (empty($faceId)) {
                echo json_encode(['error' => 'Face ID required']);
                exit();
            }
            
            echo json_encode($api->deleteFace($faceId));
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    exit();
}

// Invalid method
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);