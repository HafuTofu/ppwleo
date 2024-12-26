<?php
header('Content-Type: application/json');

include ".../connect.php"; 

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['selectedItems']) && is_array($data['selectedItems'])) {
        $selectedItems = $data['selectedItems'];

        $placeholders = implode(',', array_fill(0, count($selectedItems), '?'));
        $query = "DELETE FROM cart WHERE ID_cart IN ($placeholders)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat('i', count($selectedItems)), ...$selectedItems);
    } else {

        $cartID = $data['idcart'];

        $stmt = $conn->prepare("DELETE FROM cart WHERE ID_cart = ?");
        $stmt->bind_param("i", $cartID);
    }
    $stmt->execute();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}