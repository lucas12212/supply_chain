<?php
session_start();

$response = array('success' => false);

try {
    // Connect to the database
    $conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');

    // Determine the active cart
    $cart_id = null;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND status = 'active'");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        if ($result) {
            $cart_id = $result['cart_id'];
        }
    } else {
        $session_id = session_id();
        $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE session_id = ? AND status = 'active'");
        $stmt->execute([$session_id]);
        $result = $stmt->fetch();
        if ($result) {
            $cart_id = $result['cart_id'];
        }
    }

    if ($cart_id) {
        // Delete items from the cart
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $stmt->execute([$cart_id]);

        $response['success'] = true;
    }
} catch (Exception $e) {
    // Error handling (if needed)
}

// Return the response as JSON
echo json_encode($response);
?>