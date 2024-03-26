<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Fetch total number of products sold per day
        $stmt = $conn->query("SELECT DATE(purchase_date) as date, SUM(quantity) as total_items_sold FROM `purchases` GROUP BY DATE(purchase_date) ORDER BY date");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $sales = [];

        foreach ($result as $row) {
            $labels[] = $row['date'];
            $sales[] = $row['total_items_sold'];
        }

        echo json_encode(['labels' => $labels, 'sales' => $sales]);
        break;

    case 'POST':


    case 'PUT':
}
