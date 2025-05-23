<?php
session_start();
require_once 'pdo_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'] ?? null;
$comment_text = trim($_POST['comment'] ?? '');

if (!$post_id || $comment_text === '') {
    echo json_encode(['success' => false, 'message' => 'Post ID and comment text are required']);
    exit;
}

try {
    // Insert comment
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, text) VALUES (:post_id, :user_id, :text)");
    $stmt->execute([
        ':post_id' => $post_id,
        ':user_id' => $user_id,
        ':text' => $comment_text,
    ]);
    $comment_id = $pdo->lastInsertId();

    // Get post owner id
    $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = :post_id");
    $stmt->execute([':post_id' => $post_id]);
    $post_owner = $stmt->fetchColumn();

    if ($post_owner && $post_owner != $user_id) {
        // Insert notification for post owner
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, actor_id, post_id, type) VALUES (:user_id, :actor_id, :post_id, 'comment')");
        $stmt->execute([
            ':user_id' => $post_owner,
            ':actor_id' => $user_id,
            ':post_id' => $post_id,
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Comment saved successfully', 'comment_id' => $comment_id]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
