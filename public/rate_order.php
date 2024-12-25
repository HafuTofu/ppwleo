<?php
require "../public/sess.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $orderId = $data['orderId'] ?? null;
    $rating = $data['rating'] ?? null;

    if ($orderId && $rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare('UPDATE transactions SET rating = ?, order_status = ? WHERE transaction_id = ?');
        $status = 'Done';
        $stmt->bind_param('isi', $rating, $status, $orderId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid order ID or rating.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
