<?php
session_start();
include '.../connect.php'; 

header('Content-Type: application/json');

try {

    $input = json_decode(file_get_contents('php://input'), true);

    $idprod = $input['idprod'];
    $iduser = $input['iduser'];
    $qty = intval($input['qty']);
    $harga = intval($input['harga']);
    $total_harga = $harga * $qty;

    $stmt = $conn->prepare("SELECT qty FROM cart WHERE (ID_produk = ? AND ID_user = ? AND checkorno = 'unchecked')");
    $stmt->bind_param("ii", $idprod, $iduser);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    $stmtp = $conn->prepare("SELECT stok FROM produk WHERE ID_produk = ?");
    $stmtp->bind_param("i", $idprod);
    $stmtp->execute();
    $rowp = $stmtp->get_result()->fetch_assoc();

    if ($row) {
        $newQty = $row['qty'] + $qty;

        if ($newQty >= $rowp['stok']) {
            $newQty = $rowp['stok'];
        }

        $newTotal = $newQty * $harga;

        $stmt = $conn->prepare("UPDATE cart SET qty = ?, total_harga = ? WHERE ID_produk = ? AND ID_user = ?");
        $stmt->bind_param("idii", $newQty, $newTotal, $idprod, $iduser);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (ID_user, ID_produk, qty, total_harga) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $iduser, $idprod, $qty, $total_harga);
    }

    $stmt->execute();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>