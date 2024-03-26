<?php

mysqli_report(MYSQLI_REPORT_ALL);
header('Access-Control-Allow-Origin: http://localhost:5173'); // Allow requests from your React app's origin
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Adjust methods as needed
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Include any other headers you need to allow
header('Content-Type: application/json');

// echo "Testing";

// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//   exit(0); 
// }




  try {
    if (!isset($_POST["FullName"])) {
        throw new Exception("fullname must be provided");
    }
    $conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $hashedPassword = password_hash($_POST["Password"], PASSWORD_DEFAULT);
    $sql = "INSERT INTO user_table (fullname, email, password_, `address`, `number`) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_POST["FullName"], $_POST["Email"], $hashedPassword, $_POST["Address"], $_POST["Phone"]]);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
    exit;
  } catch (PDOException $e) {
    $errorMessage = 'Error occurred during database operation.';
    $errorInfo = $e->errorInfo;

    if (isset($errorInfo[0]) && $errorInfo[0] == '23000') {
      if (str_contains($errorInfo[2], 'user_table.email_UNIQUE')) {
        $errorMessage = 'The Email you entered is taken.';
      } elseif (str_contains($errorInfo[2], 'user_table.fullname_UNIQUE')) {
        $errorMessage = 'The Fullname you entered is taken.';
      } elseif (str_contains($errorInfo[2], 'user_table.number_UNIQUE')) {
        $errorMessage = 'The number you entered is taken.';
      } else {
        $errorMessage = "testing1";
      }
      
    } else {
        $errorMessage = "testing2";
    }

    header('Content-Type: application/json');
    echo json_encode(["success" => false, "error" => $errorMessage, 'info' => $errorInfo]);
    exit;
  } catch (Exception $e){
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
  }
  ?>