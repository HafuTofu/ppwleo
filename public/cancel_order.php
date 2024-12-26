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
        $stmtsearch = $conn->query("SELECT * FROM (transaction_details NATURAL JOIN (cart NATURAL JOIN produk)) WHERE ID_transaksi = $orderId");
        while ($row = $stmtsearch->fetch_assoc()) {
            $idprod = $row['ID_produk'];
            $qty = $row['qty'];
            $stmtup = $conn->prepare("UPDATE produk SET stok = stok + ?, terjual = terjual - ? WHERE ID_produk = ?");
            $stmtup->bind_param("iii", $qty, $qty, $idprod);
            $stmtup->execute();
        }
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
