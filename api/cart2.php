<?php

// Database connection
$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':

        // Fetch data from AJAX request
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $user_id = $_POST['user_id'] === "" ? null : $_POST['user_id'];
        $session_id = $_POST['session_id'];


        if ($user_id) {
            $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND status = 'active'");
            $stmt->execute([$user_id]);
        } else {
            $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE session_id = ? AND status = 'active'");
            $stmt->execute([$session_id]);
        }

        $result = $stmt->fetch();
        $cart_id = $result['cart_id'];


        // New code to create a new cart if none exists
        if (!$cart_id) {
            $stmt = $conn->prepare("INSERT INTO carts (user_id, session_id, status) VALUES (?,?, 'active')");
            $stmt->execute([$user_id, $session_id]);
            $cart_id = $conn->lastInsertId();
        }

        // Check if product is already in the cart
        $stmt = $conn->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cart_id, $product_id]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Update the quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ?");
            $stmt->execute([$newQuantity, $existingItem['cart_id']]);
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
        if ($totalCartCount == null) {
            $totalCartCount = 0;
        }

        // Return JSON response
        echo json_encode([
            'success' => true,
            'totalCartCount' => $totalCartCount
        ]);
        break;

    case 'DELETE':

        //To retrieve request body data as an associative array
        $request_body = file_get_contents('php://input');
        parse_str($request_body, $body);
        $user_id = $body["user_id"] === "" ? null : $body['user_id'];
        $session_id = $body["session_id"];

        $response = array('success' => false);
        // Fetch the active cart_id first
        if ($user_id) {
            $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND status = 'active'");
            $stmt->execute([$user_id]);
        } else {
            $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE session_id = ? AND status = 'active'");
            $stmt->execute([$session_id]);
        }

        $result = $stmt->fetch();
        $active_cart_id = $result['cart_id'];

        if ($active_cart_id) {
            // Now, delete all items associated with this cart from the cart_items table
            $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
            $r = $stmt->execute([$active_cart_id]);

            $response['success'] = $r;
        }

        echo json_encode($response);
        break;

    case 'GET':
        // Determine the active cart
        $result = null;
        $user_id = $_GET['user_id'] === "" ? null : $_GET['user_id'];
        $session_id = $_GET['session_id'];

        if ($user_id) {
            $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND status = 'active'");
            $stmt->execute([$user_id]);
        } else {
            $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE session_id = ? AND status = 'active'");
            $stmt->execute([$session_id]);
        }

        $result = $stmt->fetch();

        // Fetch items from the cart_items table
        $cart_items = [];
        $totalCartCount = 0; // Initialize total cart count to zero
        if ($result) {
            $cart_id = $result['cart_id'];
            $stmt = $conn->prepare("SELECT ci.product_id, ci.quantity, p.product_name, p.product_cost 
                                            FROM cart_items ci 
                                            JOIN product_table p ON ci.product_id = p.product_id
                                            WHERE ci.cart_id = ?");
            $stmt->execute([$cart_id]);
            $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch the total cart count
            $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart_items WHERE cart_id = ?");
            $stmt->execute([$cart_id]);
            $result = $stmt->fetch();
            $totalCartCount = $result['total_items'] ?? 0;  // Use null coalescing operator to handle null
        }

        // Return both cart items and total cart count
        echo json_encode([
            'cart_items' => $cart_items,
            'totalCartCount' => $totalCartCount
        ]);
        break;
    case 'PUT':
        $request_body = file_get_contents('php://input');
        parse_str($request_body, $body);
        $user_id = $body['user_id'] === "" ? null : $body['user_id'];
        $session_id = $body['session_id'];

        // Determine the active cart
        if ($user_id) {
            $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND status = 'active'");
            $stmt->execute([$user_id]);
        } else {
            $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE session_id = ? AND status = 'active'");
            $stmt->execute([$session_id]);
        }
        $result = $stmt->fetch();
        $cart_id = $result['cart_id'];

        if ($cart_id) {
            // Fetch cart items and their associated product details
            $stmt = $conn->prepare("SELECT ci.product_id, ci.quantity, p.product_cost 
                                        FROM cart_items ci 
                                        JOIN product_table p ON ci.product_id = p.product_id
                                        WHERE ci.cart_id = ?");
            $stmt->execute([$cart_id]);
            $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Record each item in the purchases table
            foreach ($cart_items as $item) {
                $stmt = $conn->prepare("INSERT INTO purchases (cart_id, product_id, quantity, purchase_price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$cart_id, $item['product_id'], $item['quantity'], $item['product_cost']]);
            }

            // Remove items from the cart
            $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
            $stmt->execute([$cart_id]);

            // Update the cart status to 'checked_out'
            $stmt = $conn->prepare("UPDATE carts SET status = 'checked_out' WHERE cart_id = ?");
            $stmt->execute([$cart_id]);

            // Create a new cart
            if ($user_id) {
                $stmt = $conn->prepare("INSERT INTO carts (user_id, status) VALUES (?, 'active')");
                $stmt->execute([$user_id]);
            } else {
                $stmt = $conn->prepare("INSERT INTO carts (session_id, status) VALUES (?, 'active')");
                $stmt->execute([$session_id]);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Cart checked out, items cleared and new cart created successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No active cart found'
            ]);
        }
        break;
}
