<?php
/**
 * Face Recognition API Wrapper
 * Location: C:\xampp\htdocs\makai-demo\face_api.php
 */

class FaceRecognitionAPI {
    private $pythonScript;
    private $pythonPath = 'python'; // or 'python3' on Linux/Mac
    private $uploadDir;
    private $logFile;
    
    public function __construct() {
        // Set exact paths for Windows
        $this->pythonScript = 'C:\\xampp\\htdocs\\makai-demo\\python\\face_processor.py';
        $this->uploadDir = 'C:\\xampp\\htdocs\\makai-demo\\uploads\\';
        $this->logFile = 'C:\\xampp\\htdocs\\makai-demo\\logs\\face_api.log';
        
        // Create logs directory if it doesn't exist
        if (!is_dir(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }
    
    /**
     * Log messages for debugging
     */
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents(
            $this->logFile, 
            "[$timestamp] $message\n", 
            FILE_APPEND
        );
    }
    
    /**
     * Execute Python command and return result
     */
    private function executePython($command, $args = []) {
        // Build command
        $cmd = sprintf(
            '%s %s %s %s 2>&1',
            $this->pythonPath,
            escapeshellarg($this->pythonScript),
            escapeshellarg($command),
            implode(' ', array_map('escapeshellarg', $args))
        );
        
        $this->log("Executing: $cmd");
        
        // Execute and capture output
        $output = shell_exec($cmd);
        $this->log("Output: $output");
        
        // Parse JSON output
        $result = json_decode($output, true);
        
        if ($result === null && $output) {
            // If not JSON, return as error
            return ['error' => $output];
        }
        
        return $result ?: ['error' => 'No output from Python script'];
    }
    
    /**
     * Add a face to the database
     */
    public function addFace($imagePath, $personName, $additionalInfo = []) {
        $args = [$imagePath, $personName];
        
        if (!empty($additionalInfo)) {
            $args[] = json_encode($additionalInfo);
        }
        
        return $this->executePython('add', $args);
    }
    
    /**
     * Recognize faces in an image
     */
    public function recognizeFaces($imagePath, $threshold = 0.6) {
        return $this->executePython('recognize', [$imagePath, (string)$threshold]);
    }
    
    /**
     * Find similar faces to a person
     */
    public function findSimilar($personName, $limit = 10) {
        return $this->executePython('similar', [$personName, (string)$limit]);
    }
    
    /**
     * List all faces in database
     */
    public function listAllFaces($limit = 100) {
        return $this->executePython('list', [(string)$limit]);
    }
    
    /**
     * Delete a face from database
     */
    public function deleteFace($faceId) {
        return $this->executePython('delete', [$faceId]);
    }
    
    /**
     * Save uploaded file and return path
     */
    public function saveUploadedFile($file, $subdir = 'faces') {
        $targetDir = $this->uploadDir . $subdir . '/';
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $filename = uniqid() . '_' . basename($file['name']);
        $targetPath = $targetDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $targetPath;
        }
        
        return false;
    }
}