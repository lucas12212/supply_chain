<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin, Authorization, X-Requested-With, Cache-Control, Access-Control-Allow-Headers");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Connect to the database
$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', 'root');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Initialize the query
        $queryStr = "SELECT product_id, product_name, to_base64(image_src) AS image_src FROM product_table";
        $query = $conn->prepare($queryStr);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'products' => $result
        ]);
        break;

    case 'POST':
        // Initialize the query for filtering products
        $queryStr = "SELECT product_id, product_name, to_base64(image_src) AS image_src FROM product_table WHERE 1=1";
        $params = [];
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data['brand'])) {
            $queryStr .= " AND product_category = :brand";
            $params['brand'] = $data['brand'];
        }

        if (!empty($data['min_price'])) {
            $queryStr .= " AND product_cost >= :min_price";
            $params['min_price'] = $data['min_price'];
        }

        if (!empty($data['max_price'])) {
            $queryStr .= " AND product_cost <= :max_price";
            $params['max_price'] = $data['max_price'];
        }

        if (!empty($data['rating'])) {
            $queryStr .= " AND avg_rating >= :rating";
            $params['rating'] = $data['rating'];
        }

        // Debugging: Output SQL query and parameters
        error_log("SQL Query: " . $queryStr);
        error_log("Params: " . print_r($params, true));

        $query = $conn->prepare($queryStr);
        $query->execute($params);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'products' => $result
        ]);
        break;

    case 'PUT':
        $queryStr = "SELECT DISTINCT product_category FROM product_table";
        $query = $conn->prepare($queryStr);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode([
            'brands' => $result
        ]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
