<?php
require '../public/sess.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['Username'], $data['address'], $data['Email'], $data['phone'])) {
    $username = $data['Username'];
    $address = $data['address'];
    $email = $data['Email'];
    $phone = $data['phone'];

    $stmt = $conn->prepare("UPDATE userdata SET address = ?, Email = ?, phone = ? WHERE Username = ?");
    $stmt->bind_param('ssss', $address, $email, $phone, $username);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
?>
