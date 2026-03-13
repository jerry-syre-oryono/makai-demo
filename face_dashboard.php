<?php
/**
 * Face Recognition Dashboard
 * Location: C:\xampp\htdocs\makai-demo\face_dashboard.php
 */
require_once 'face_api.php';

$api = new FaceRecognitionAPI();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_face':
                if (isset($_FILES['face_image']) && $_FILES['face_image']['error'] === UPLOAD_ERR_OK) {
                    $imagePath = $api->saveUploadedFile($_FILES['face_image'], 'faces');
                    if ($imagePath) {
                        $result = $api->addFace(
                            $imagePath,
                            $_POST['person_name'],
                            ['notes' => $_POST['notes'] ?? '']
                        );
                        
                        if (isset($result['success'])) {
                            $message = "Face added successfully for {$_POST['person_name']}!";
                        } else {
                            $error = $result['error'] ?? 'Unknown error';
                        }
                    } else {
                        $error = 'Failed to upload image';
                    }
                } else {
                    $error = 'Please select an image file';
                }
                break;
                
            case 'recognize':
                if (isset($_FILES['recognize_image']) && $_FILES['recognize_image']['error'] === UPLOAD_ERR_OK) {
                    $imagePath = $api->saveUploadedFile($_FILES['recognize_image'], 'temp');
                    if ($imagePath) {
                        $result = $api->recognizeFaces(
                            $imagePath,
                            floatval($_POST['threshold'] ?? 0.6)
                        );
                        
                        if (isset($result['success'])) {
                            $recognitionResult = $result;
                        } else {
                            $error = $result['error'] ?? 'Recognition failed';
                        }
                    } else {
                        $error = 'Failed to upload image';
                    }
                } else {
                    $error = 'Please select an image file';
                }
                break;
        }
    }
}

// Get list of faces
$faces = $api->listAllFaces(20);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAKAI Face Recognition</title>
    <link rel="stylesheet" href="styles/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .face-card {
            transition: transform 0.2s;
        }
        .face-card:hover {
            transform: translateY(-5px);
        }
        .match-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-gray-700 hover:text-primary-600 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-face-smile text-primary-600 mr-2"></i>
                        MAKAI Face Recognition System
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-database mr-1"></i>
                        <?php echo $faces['success'] ? count($faces['faces']) : 0; ?> faces stored
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Messages -->
        <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i><?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
        </div>
        <?php endif; ?>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add Face Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-plus-circle text-green-500 mr-2"></i>
                    Add New Face
                </h2>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_face">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Person's Name
                        </label>
                        <input type="text" 
                               name="person_name" 
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="e.g., John Doe">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Face Image
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <input type="file" 
                                   name="face_image" 
                                   id="face_image"
                                   accept="image/*"
                                   required
                                   class="hidden"
                                   onchange="previewImage(this, 'addPreview')">
                            
                            <div onclick="document.getElementById('face_image').click()" 
                                 class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">Click to upload face image</p>
                                <p class="text-sm text-gray-500">JPG, PNG up to 10MB</p>
                            </div>
                            
                            <div id="addPreview" class="hidden mt-4">
                                <img class="max-h-48 mx-auto rounded-lg">
                                <button type="button" 
                                        onclick="resetUpload('face_image', 'addPreview')"
                                        class="mt-2 text-sm text-red-600 hover:text-red-700">
                                    <i class="fas fa-times mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Notes (Optional)
                        </label>
                        <textarea name="notes" 
                                  rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Any additional information about this person"></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-medium">
                        <i class="fas fa-save mr-2"></i>Add Face to Database
                    </button>
                </form>
            </div>
            
            <!-- Recognize Face Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-search text-blue-500 mr-2"></i>
                    Recognize Faces
                </h2>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="recognize">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Image to Recognize
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <input type="file" 
                                   name="recognize_image" 
                                   id="recognize_image"
                                   accept="image/*"
                                   required
                                   class="hidden"
                                   onchange="previewImage(this, 'recognizePreview')">
                            
                            <div onclick="document.getElementById('recognize_image').click()" 
                                 class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">Click to upload image</p>
                                <p class="text-sm text-gray-500">JPG, PNG up to 10MB</p>
                            </div>
                            
                            <div id="recognizePreview" class="hidden mt-4">
                                <img class="max-h-48 mx-auto rounded-lg">
                                <button type="button" 
                                        onclick="resetUpload('recognize_image', 'recognizePreview')"
                                        class="mt-2 text-sm text-red-600 hover:text-red-700">
                                    <i class="fas fa-times mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confidence Threshold: <span id="thresholdValue">0.6</span>
                        </label>
                        <input type="range" 
                               name="threshold" 
                               id="threshold"
                               min="0.1" 
                               max="0.9" 
                               step="0.05" 
                               value="0.6"
                               class="w-full"
                               oninput="document.getElementById('thresholdValue').textContent = this.value">
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-face-smile mr-2"></i>Recognize Faces
                    </button>
                </form>
            </div>
        </div>

        <!-- Recognition Results -->
        <?php if (isset($recognitionResult) && $recognitionResult['success']): ?>
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6 flex items-center">
                <i class="fas fa-clipboard-list text-purple-500 mr-2"></i>
                Recognition Results
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($recognitionResult['faces'] as $face): ?>
                <div class="border rounded-lg overflow-hidden">
                    <?php if ($face['saved_face']): ?>
                    <img src="<?php echo $face['saved_face']; ?>" 
                         class="w-full h-48 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h3 class="font-semibold mb-2">Face <?php echo $face['face_index'] + 1; ?></h3>
                        
                        <?php if (!empty($face['matches'])): ?>
                            <?php foreach ($face['matches'] as $match): ?>
                            <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-green-600">
                                        <?php echo htmlspecialchars($match['name']); ?>
                                    </span>
                                    <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                        <?php echo round($match['confidence'] * 100); ?>% match
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-green-500 h-2 rounded-full" 
                                         style="width: <?php echo $match['confidence'] * 100; ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500">No matches found</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stored Faces -->
        <?php if ($faces['success'] && !empty($faces['faces'])): ?>
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6 flex items-center">
                <i class="fas fa-database text-orange-500 mr-2"></i>
                Stored Faces (<?php echo count($faces['faces']); ?>)
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <?php foreach ($faces['faces'] as $face): ?>
                <div class="face-card bg-gray-50 rounded-lg p-3 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-purple-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <i class="fas fa-user-circle text-4xl text-primary-600"></i>
                    </div>
                    <p class="font-medium truncate"><?php echo htmlspecialchars($face['name']); ?></p>
                    <p class="text-xs text-gray-500">
                        <?php echo date('M j, Y', strtotime($face['timestamp'])); ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const preview = document.getElementById(previewId);
                const img = preview.querySelector('img');
                
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function resetUpload(inputId, previewId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).classList.add('hidden');
        }
    </script>
</body>
</html>