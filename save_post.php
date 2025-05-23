<?php
session_start();
require_once 'pdo_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$content = trim($_POST['content'] ?? '');
$image_path = null;
$video_path = null;

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageTmpPath = $_FILES['image']['tmp_name'];
    $imageName = uniqid('img_') . '_' . basename($_FILES['image']['name']);
    $imageDest = 'uploads/images/' . $imageName;

    if (!is_dir('uploads/images')) {
        mkdir('uploads/images', 0755, true);
    }

    if (move_uploaded_file($imageTmpPath, $imageDest)) {
        $image_path = $imageDest;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded image']);
        exit;
    }
}

// Handle video upload
if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
    $videoTmpPath = $_FILES['video']['tmp_name'];
    $videoName = uniqid('vid_') . '_' . basename($_FILES['video']['name']);
    $videoDest = 'uploads/videos/' . $videoName;

    if (!is_dir('uploads/videos')) {
        mkdir('uploads/videos', 0755, true);
    }

    if (move_uploaded_file($videoTmpPath, $videoDest)) {
        $video_path = $videoDest;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded video']);
        exit;
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, image_path, video_path) VALUES (:user_id, :content, :image_path, :video_path)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':content' => $content,
        ':image_path' => $image_path,
        ':video_path' => $video_path,
    ]);
    $post_id = $pdo->lastInsertId();

    echo json_encode(['success' => true, 'message' => 'Post saved successfully', 'post_id' => $post_id]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
