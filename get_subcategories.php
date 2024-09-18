<?php
$mysqli = require __DIR__ . "/database.php";

// Check if category_id is provided via GET request
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Prepare SQL statement to fetch subcategories based on category_id
    $sql = "SELECT * FROM subcategories WHERE category_id = ?";

    // Prepare statement
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $category_id);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Data
    $subcategories = $result->fetch_all(MYSQLI_ASSOC);

    // Close 
    $stmt->close();
    $mysqli->close();

    // Output subcategories as JSON
    header('Content-Type: application/json');
    echo json_encode($subcategories);
} else {
    // Handle error if category_id is not provided
    http_response_code(400);
    echo json_encode(array('error' => 'Category ID is required'));
}
?>
