<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON input from the AJAX request
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['selected_cart_ids']) && is_array($input['selected_cart_ids'])) {
        // Store the selected cart IDs in the session
        $_SESSION['selected_cart_ids'] = $input['selected_cart_ids'];

        // Respond with success
        echo json_encode(['success' => true]);
    } else {
        // Invalid input
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
