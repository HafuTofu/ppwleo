<?php
require "../public/sess.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['status'])) {
        $prodid = intval($data['id']);
        $status = $data['status']; // Adjust based on your database

        $query = "UPDATE produk SET statusprod = ? WHERE ID_produk = ? ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $prodid);
        if ($stmt->execute() === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
    }
}
?>
