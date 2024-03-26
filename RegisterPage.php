<?php
mysqli_report(MYSQLI_REPORT_ALL);
header('Access-Control-Allow-Origin: http://localhost:5173'); // Allow requests from your React app's origin
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Adjust methods as needed
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Include any other headers you need to allow
header('Content-Type: application/json');

// Handle OPTIONS request for preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit(0); // No further action needed for preflight
}

?>



<!DOCTYPE html>
<html>

<head>
  <title>Banking</title>
  <link rel="stylesheet" href="newcss.css">
  <link rel="stylesheet" href="style2.css"> <!-- New Line -->
</head>

<body>
  <div class="main-container">
    <div class="form-container">

      <div class="form-body">
        <h2 class="title">Register</h2>

        <form action="RegisterPage.php" method="POST" class="the-form">

          <label for="FullName">Full Name</label>
          <input type="text" name="FullName" id="FullName" placeholder="Enter your full name" required>

          <label for="Email">Email</label>
          <input type="email" name="Email" id="Email" placeholder="Enter your email" required>

          <label for="Password">Password</label>
          <input type="password" name="Password" id="Password" placeholder="Enter your password" required>

          <label for="Address">Address</label>
          <input type="text" name="Address" id="Address" placeholder="Enter your address" required>

          <label for="Phone">Phone</label>
          <input type="number" name="Phone" id="Phone" placeholder="Enter your phone number" required>

          <input type="submit" name="submit" value="Register">

        </form>

      </div><!-- FORM BODY-->

      <div class="form-footer">
        <div>
          <span>Already have an account?</span> <a href="LoginPage.php">Log In</a>
        </div>
      </div><!-- FORM FOOTER -->

    </div><!-- FORM CONTAINER -->
  </div>

  <?php
  try {
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
      if (str_contains($errorInfo[2], 'UNIQUE constraint failed: user_table.email')) {
        $errorMessage = 'The Email you entered is taken.';
      } elseif (str_contains($errorInfo[2], 'UNIQUE constraint failed: user_table.fullname')) {
        $errorMessage = 'The Fullname you entered is taken.';
      } elseif (str_contains($errorInfo[2], 'UNIQUE constraint failed: user_table.number')) {
        $errorMessage = 'The number you entered is taken.';
      }
    }

    header('Content-Type: application/json');
    echo json_encode(["success" => false, "error" => $errorMessage]);
    exit;
  }
  ?>

</body>

</html>