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
$reaction_type = $_POST['reaction_type'] ?? null;

$valid_reactions = ['like', 'heart', 'haha'];

if (!$post_id || !$reaction_type || !in_array($reaction_type, $valid_reactions)) {
    echo json_encode(['success' => false, 'message' => 'Invalid post ID or reaction type']);
    exit;
}

try {
    // Check if reaction exists
    $stmt = $pdo->prepare("SELECT id, type FROM reactions WHERE post_id = :post_id AND user_id = :user_id");
    $stmt->execute([
        ':post_id' => $post_id,
        ':user_id' => $user_id,
    ]);
    $existing_reaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_reaction) {
        if ($existing_reaction['type'] === $reaction_type) {
            // Same reaction exists, remove it (toggle off)
            $stmt = $pdo->prepare("DELETE FROM reactions WHERE id = :id");
            $stmt->execute([':id' => $existing_reaction['id']]);
        } else {
            // Update reaction type
            $stmt = $pdo->prepare("UPDATE reactions SET type = :type, created_at = CURRENT_TIMESTAMP WHERE id = :id");
            $stmt->execute([
                ':type' => $reaction_type,
                ':id' => $existing_reaction['id'],
            ]);
        }
    } else {
        // Insert new reaction
        $stmt = $pdo->prepare("INSERT INTO reactions (post_id, user_id, type) VALUES (:post_id, :user_id, :type)");
        $stmt->execute([
            ':post_id' => $post_id,
            ':user_id' => $user_id,
            ':type' => $reaction_type,
        ]);
    }

    // Get post owner id
    $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = :post_id");
    $stmt->execute([':post_id' => $post_id]);
    $post_owner = $stmt->fetchColumn();

    if ($post_owner && $post_owner != $user_id) {
        // Insert notification for post owner
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, actor_id, post_id, type) VALUES (:user_id, :actor_id, :post_id, 'reaction')");
        $stmt->execute([
            ':user_id' => $post_owner,
            ':actor_id' => $user_id,
            ':post_id' => $post_id,
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Reaction updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
