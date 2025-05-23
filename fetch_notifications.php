<?php
session_start();
require_once 'pdo_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT n.id, n.type, n.post_id, n.created_at, n.read_status,
               u.username AS actor_name
        FROM notifications n
        JOIN users u ON n.actor_id = u.id
        WHERE n.user_id = :user_id
        ORDER BY n.created_at DESC
        LIMIT 20
    ");
    $stmt->execute([':user_id' => $user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'notifications' => $notifications]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
