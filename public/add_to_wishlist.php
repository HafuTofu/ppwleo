<?php
session_start();
require '../connect.php'; // Your database connection file

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);

    $idprod = $input['idprod'];
    $iduser = $_SESSION['id'];

    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE ID_produk = ? AND ID_user = ?");
    $stmt->bind_param("ii", $idprod, $iduser);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE ID_produk = ? AND ID_user = ? ");
        $stmt->bind_param("ii", $idprod, $iduser);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO wishlist (ID_user, ID_produk) VALUES (?, ?)");
        $stmt->bind_param("ii", $iduser, $idprod);
        $stmt->execute();
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
