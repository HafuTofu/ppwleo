<?php
header('Content-Type: application/json');

include "../connect.php";

session_start();

$iduser = $_SESSION['id'];
$username = $_POST['username'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$gender = $_POST['gender'] ?? null;
$phone = $_POST['phone'];
$address = $_POST['address'];
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'] ?? null;

// Handle the profile picture upload
$profilePicPath = null;
if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/'; // Directory to store uploaded files
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
    }

    $fileTmpPath = $_FILES['profilePic']['tmp_name'];
    $fileName = basename($_FILES['profilePic']['name']);
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = uniqid('profile_', true) . '.' . $fileExtension;

    $profilePicPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $profilePicPath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload profile picture.']);
        exit;
    }

    // Update the path for database storage
    $profilePicPath = str_replace('../', '', $profilePicPath); // Store relative path
}

try {
    // Verify the current password
    $stmt = $conn->prepare("SELECT Password FROM userdata WHERE ID_user = ?");
    $stmt->bind_param('i', $iduser);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || $currentPassword != $user['Password']) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }

    if ($newPassword == '') {
        echo json_encode(['success' => false, 'message' => 'New Password can\'t be blank.']);
        exit;
    }

    // Update the user data
    $updateQuery = 'UPDATE userdata SET fullname = ?, Email = ?, gender = ?, phone = ?, address = ?, Password = ?';
    if ($profilePicPath) {
        $updateQuery .= ', fotouser = ?';
    }
    $updateQuery .= ' WHERE ID_user = ?';

    $updateStmt = $conn->prepare($updateQuery);

    if ($profilePicPath) {
        $updateStmt->bind_param(
            'sssssssi',
            $fullname,
            $email,
            $gender,
            $phone,
            $address,
            $newPassword,
            $profilePicPath,
            $iduser
        );
    } else {
        $updateStmt->bind_param(
            'ssssssi',
            $fullname,
            $email,
            $gender,
            $phone,
            $address,
            $newPassword,
            $iduser
        );
    }

    $updateStmt->execute();

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
