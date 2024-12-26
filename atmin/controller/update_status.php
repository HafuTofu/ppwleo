<?php
require "../public/sess.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['status'])) {
        $prodid = intval($data['id']);
        $status = $data['status']; // Adjust based on your database

        $query = "UPDATE produk SET statusproduk = ? WHERE ID_produk = ? ";
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

    if (isset($data['order_id']) && isset($data['status'])) {
        $orderId = $data['order_id'];
        $newStatus = $data['status'];

        // Update the database
        $stmt = $conn->prepare("UPDATE transactions SET order_status = ? WHERE ID_transaksi = ?");
        $stmt->bind_param("si", $newStatus, $orderId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Status updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update status."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid input."]);
    }
    header("Location: ../view/orderadmin.php");
    exit();
}
?>