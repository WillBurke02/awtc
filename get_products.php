<?php
$mysqli = require __DIR__ . "/database.php";

// Check if subcategory_id is provided 
if (isset($_GET['subcategory_id'])) {
    $subcategory_id = $_GET['subcategory_id'];

    // SQL statement to fetch products based on subcategory_id
    $sql = "SELECT * FROM products WHERE subcategory_id = ?";

    // Prepare & execute 
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $subcategory_id);
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Data 
    $products = $result->fetch_all(MYSQLI_ASSOC);

    // Close 
    $stmt->close();

    // Output products as JSON
    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    // Handle error if subcategory_id is not provided
    http_response_code(400);
    echo json_encode(array('error' => 'Subcategory ID is required'));
}

// Close connection
$mysqli->close();
?>
