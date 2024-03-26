<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Banking</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.css" />
</head>

<body>
    <div class="container">
        <div class="row col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <h1>Login</h1>
                </div>
                <div class="panel-body">
                    <form action="index.php" method="POST">
                        <div class="form-group">
                            <!-- <form method="GET" action="Banking_Attempt2.php"> -->
                            <label for="FullName">Name</label>
                            <input type="text" class="form-control" id="FullName" name="FullName" required />
                            <!-- </form> -->
                        </div>
                        <div class="form-group">
                            <label for="number">Password</label>
                            <input type="password" class="form-control" id="password" name="Password" required />
                        </div>
                        <a href='BuyerEnd.php'><input type="submit" class="btn btn-primary" /></a>
                    </form>
                </div>
                <div class="panel-footer text-right">
                    <small>&copy; E-commerce Website </small>
                </div>
                <?php
                $_SESSION["FullNameVariable"] = "{$_POST["FullName"]}";
                ?>
            </div>
        </div>
    </div>
</body>

</html>

<?php
//Banking First Version
//Done without the HTML page

//New Code
try {
    $conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', 'root');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $test = 'Connection Failed!';
    echo "<script>alert('$test');</script>";
}

if (!empty($_POST)) {
    $FullName = $conn->prepare("SELECT * FROM User_Table WHERE FullName = :fullname");
    $FullName->execute([':fullname' => $_POST["FullName"]]);
    $FullName_Check = $FullName->rowCount();

    if ($FullName_Check <= 0) {
        $wrongfullname = 'The Fullname you entered is Incorrect';
        echo "<script>alert('$wrongfullname');</script>";
        exit;
    }

    $a = $_POST['Password'];

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        $Password_Compare = $user['Password_'];
    }

var_dump("a");

    if (password_verify($a, $Password_Compare)) {
        // header('Location: BuyerEnd.php');
    } else {
        $wrongpassword = 'The Password you entered is Incorrect';
        echo "<script>alert('$wrongpassword');</script>";
    }
}


//Closing Connection       
$conn->close();
?>