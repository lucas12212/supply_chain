<?php

mysqli_report(MYSQLI_REPORT_ALL);
header('Access-Control-Allow-Origin: http://localhost:5173'); // Allow requests from your React app's origin
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Adjust methods as needed
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Include any other headers you need to allow
header('Content-Type: application/json');

error_log("Received POST data: " . print_r($_POST, true));

$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $result = false;

    if (isset($_POST["FullName"])) {
        $ValidateInput = "SELECT 1 FROM user_table WHERE fullname = ?";
        $stmt = $conn->prepare($ValidateInput);
        $stmt->execute([$_POST["FullName"]]);
        $result = $stmt->rowCount() > 0;
    } elseif (isset($_POST["Email"])) {
        $ValidateInput = "SELECT 1 FROM user_table WHERE email = ?";
        $stmt = $conn->prepare($ValidateInput);
        $stmt->execute([$_POST["Email"]]);
        $result = $stmt->rowCount() > 0;
    } elseif (isset($_POST["Number"])) {
        $number = $_POST["Number"];
        // Check if the number is indeed numeric
        if (!is_numeric($number)) {
            // If not numeric, return an error message
            header('Content-Type: application/json');
            echo json_encode(["error" => "Invalid number format"]);
            exit; // Stop further execution
        } else {
            // If numeric, proceed with your duplication check
            $ValidateInput = "SELECT 1 FROM user_table WHERE number_ = ?";
            $stmt = $conn->prepare($ValidateInput);
            $stmt->execute([$number]);
            $result = $stmt->rowCount() > 0;

            // Return the result of the duplication check
            header('Content-Type: application/json');
            echo json_encode(["duplicate" => $result]);
        }
    }

    header('Content-Type: application/json');
    echo json_encode(["duplicate" => $result]);
} catch (Exception $e) {
    // Error handling
    http_response_code(500);
    echo json_encode(["error" => "Server error occurred"]);
}
?>
