<?php

// Connect to the database
$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Initialize the query
        $queryStr = "SELECT * FROM product_table WHERE 1=1";
        $query = $conn->prepare($queryStr);
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_ASSOC);

        echo json_encode([
            'products' => $result
        ]);
        break;

    case 'POST':
        // Initialize the query for filtering products
        $queryStr = "SELECT * FROM product_table WHERE 1=1";
        $params = [];

        if (!empty($_POST['brand'])) {
            $queryStr .= " AND product_category = :brand";
            $params['brand'] = $_POST['brand'];
        }

        if (!empty($_POST['min_price'])) {
            $queryStr .= " AND product_cost >= :min_price";
            $params['min_price'] = $_POST['min_price'];
        }

        if (!empty($_POST['max_price'])) {
            $queryStr .= " AND product_cost <= :max_price";
            $params['max_price'] = $_POST['max_price'];
        }

        if (!empty($_POST['rating'])) {
            $queryStr .= " AND avg_rating >= :rating";
            $params['rating'] = $_POST['rating'];
        }

        $query = $conn->prepare($queryStr);
        $query->execute($params);
        $result = $query->fetchall(PDO::FETCH_ASSOC);

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
}
