<?php
require "../public/sess.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $orderId = $data['orderId'] ?? null;

    if ($orderId) {
        $stmt = $conn->prepare('UPDATE transactions SET order_status = ? WHERE ID_transaksi = ?');
        $status = 'Canceled';
        $stmt->bind_param('si', $status, $orderId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid order ID.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
