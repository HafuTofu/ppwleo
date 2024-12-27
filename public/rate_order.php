<?php
require "../public/sess.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $transactionId = $data['transactionId'] ?? null;
    $rating = $data['rating'] ?? null;
    $komentar = $data['komentar'] ?? '';
    $productId = $data['productId'] ?? null;

    if ($transactionId && $rating >= 1 && $rating <= 5 && $productId) {
        $stmt = $conn->prepare('INSERT INTO rating (komentar, ID_transaksi, rate, ID_produk) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('siis', $komentar, $transactionId, $rating, $productId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid data provided.']);
    }
} else {
    echo json_encode(value: ['success' => false, 'error' => 'Invalid request method.']);
}
