<?php
require '../public/sess.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['username'], $data['action'])) {
    $username = $data['username'];
    $action = $data['action'];

    $newStatus = ($action === 'ban') ? 'blocked' : 'active';

    $query = "UPDATE userdata SET is_banned = ? WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $newStatus, $username);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>