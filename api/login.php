<?php
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

function getPost(){
    if(!empty($_POST))
    {        // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request        // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P        return $_POST;
    }
    // when using application/json as the HTTP Content-Type in the request     
    $post = json_decode(file_get_contents('php://input'), true);
    if(json_last_error() == JSON_ERROR_NONE)
    {
        return $post;
    }

    return [];
}

// Database connection
$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$_POST = getPost();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
    	$queryStr = "SELECT * FROM User_Table WHERE FullName='{$_POST["name"]}'";
        $query = $conn->prepare($queryStr);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $response = array();
        if ($result) {
        	if (password_verify($_POST["password_"], $result["password_"])) {
        		$response["user_id"] = $result["user_id"];
        		$response["msg"] = "login successfully";
        	} else {
        		$response["msg"] = "wrong password";
        	}
        } else {
        	// No User
        	$response["msg"] = "no such user";
        }

        echo json_encode($response);
    break;
}