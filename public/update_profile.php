<?php
header('Content-Type: application/json');

include ".../connect.php";

$data = json_decode(file_get_contents('php://input'), true);

$iduser = $_SESSION['id'];
$username = $data['username'];
$fullname = $data['fullname'];
$email = $data['email'];
$gender = $data['gender'] ?? null;
$phone = $data['phone'];
$address = $data['address'];
$currentPassword = $data['currentPassword'];
$newPassword = $data['newPassword'] ?? null;

try {
    $query->bind_param('i', $iduser);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if (!$user || $currentPassword != $user['Password']) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }else if($newPassword == '') {
        echo json_encode(['success'=> false,'message'=> 'New Password Can\'t be Blank Broww']);
        exit;
    }

    $updateQuery = 'UPDATE userdata SET fullname = ? , Email = ? , gender = ? , phone = ? , address = ? , Password = ? WHERE ID_user = ?';

    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('ssssssi',$fullname,$email, $gender, $phone, $address, $newPassword, $iduser);

    $updateStmt->execute();

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>