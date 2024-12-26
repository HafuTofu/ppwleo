<?php
require "../public/sess.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = ['success' => false, 'message' => 'Invalid data'];

    try {
        if (isset($data['username'], $data['action'])) {
            $username = $data['username'];

            // Fetch the current status of the user
            $stmt = $conn->prepare("SELECT status FROM userdata WHERE Username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($currentStatus);
            $stmt->fetch();
            $stmt->close();

            // Determine the new status based on the current status
            $newStatus = ($currentStatus === 'active') ? 'blocked' : 'active';
            $action = ($newStatus === 'active') ? 'Unbanned' : 'Banned';

            // Update the user status
            $stmt = $conn->prepare("UPDATE userdata SET status = ? WHERE Username = ?");
            $stmt->bind_param('ss', $newStatus, $username);

            if ($stmt->execute()) {
                $response = [
                    'success' => true,
                    'message' => "User has been $action successfully.",
                    'newStatus' => $newStatus
                ];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update user status'];
            }
            $stmt->close();
        }

        if (isset($data['id'], $data['status'])) {
            $prodid = intval($data['id']);
            $status = $data['status'];

            $stmt = $conn->prepare("UPDATE produk SET statusproduk = ? WHERE ID_produk = ?");
            $stmt->bind_param("si", $status, $prodid);

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Product status updated successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update product status'];
            }
            $stmt->close();
        }

        if (isset($data['order_id'], $data['status'])) {
            $orderId = intval($data['order_id']);
            $newStatus = $data['status'];

            $stmt = $conn->prepare("UPDATE transactions SET order_status = ? WHERE ID_transaksi = ?");
            $stmt->bind_param("si", $newStatus, $orderId);

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Order status updated successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update order status'];
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
    }

    echo json_encode($response);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}
