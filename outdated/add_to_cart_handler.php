<?php
session_start();

// Database connection
$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');

// Fetch data from AJAX request
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$user_id = $_POST['user_id'];

// // Determine the active cart
// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
//     $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND status = 'active'");
//     $stmt->execute([$user_id]);
// } else {
//     $session_id = session_id();
//     $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE session_id = ? AND status = 'active'");
//     $stmt->execute([$session_id]);
// }

$stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND status = 'active'");
$stmt->execute([$user_id]);
$result = $stmt->fetch();
$cart_id = $result['cart_id'];

// Check if product is already in the cart
$stmt = $conn->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
$stmt->execute([$cart_id, $product_id]);
$existingItem = $stmt->fetch();

if ($existingItem) {
    // Update the quantity
    $newQuantity = $existingItem['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
    $stmt->execute([$newQuantity, $existingItem['cart_item_id']]);
} else {
    // Add new item to cart
    $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$cart_id, $product_id, $quantity]);
}

// Fetch updated cart count
$stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart_items WHERE cart_id = ?");
$stmt->execute([$cart_id]);
$result = $stmt->fetch();
$totalCartCount = $result['total_items'];

// Return JSON response
echo json_encode([
    'success' => true,
    'totalCartCount' => $totalCartCount
]);
