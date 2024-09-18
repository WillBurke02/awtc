<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = require __DIR__ . "/database.php";

if (!$mysqli) {
    // Database connection failed
    http_response_code(500);
    echo json_encode(array("message" => "Database connection failed."));
    exit;
}

if (isset($_GET['order_id'])) {
    // Get the order ID from the request
    $orderId = $_GET['order_id'];

    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT orders.*, products.name AS product_name
            FROM orders
            LEFT JOIN order_items ON orders.order_id = order_items.order_id
            LEFT JOIN products ON order_items.product_id = products.product_id
            WHERE orders.order_id = ?";

    // Prepare and bind the query
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        // Error preparing the statement
        http_response_code(500);
        echo json_encode(array("message" => "Error preparing statement: " . $mysqli->error));
        exit;
    }
    $stmt->bind_param("i", $orderId);

    // Execute query
    $stmt->execute();

    // Check if query was executed successfully
    if ($stmt->errno) {
        // Error executing query
        http_response_code(500);
        echo json_encode(array("message" => "Error executing query: " . $stmt->error));
        exit;
    }

    // Get result 
    $result = $stmt->get_result();

    // Check if rows were found
    if ($result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();

        // Initialize order details variables
        $order_id = $row['order_id'];
        $total_price = $row['price'];
        $created_at = $row['created_at'];
        $product_names = array();

        // Add product names associated with the order
        do {
            $product_names[] = $row['product_name'];
        } while ($row = $result->fetch_assoc());

        // Format created_at
        $created_at_formatted = date('d-m-Y H:i:s', strtotime($created_at));

        // Construct the response array
        $response = array(
            "Order ID" => $order_id,
            "Total Price" => $total_price,
            "Created at" => $created_at_formatted,
            "Products Ordered" => $product_names
        );

        // Return the response as JSON
        http_response_code(200);
        echo json_encode($response);
    } else {
        // Set HTTP status code to 404 
        http_response_code(404);

        // Return error message as JSON
        echo json_encode(array("error" => "Order not found."));
    }

}


// Close database conn
$mysqli->close();


?>
