<?php
// Retrieve items array from URL 
$items = json_decode($_GET['items'], true);
// Function to get all information from database
function getAllFromDatabase($categoryId, $subcategoryId) {
    $mysqli = require __DIR__ . "/database.php";

    // Array to store all information
    $allInformation = array();

    // Query to fetch all information based on category and subcategory IDs
    $query = "SELECT * FROM products WHERE category_id = $categoryId AND subcategory_id = $subcategoryId";
    $result = mysqli_query($mysqli, $query);

    // Fetch all rows and store them in the array
    while ($row = mysqli_fetch_assoc($result)) {
        $allInformation[] = $row;
    }

    return $allInformation;
}

function getProductIdsFromDatabase($categoryId, $subcategoryId) {
    $mysqli = require __DIR__ . "/database.php";

    // Array to store product IDs
    $productIds = array();

    // Query to fetch product IDs based on category and subcategory IDs
    $query = "SELECT product_id FROM products WHERE category_id = $categoryId AND subcategory_id = $subcategoryId";
    $result = mysqli_query($mysqli, $query);

    // Fetch all product IDs and store them in the array
    while ($row = mysqli_fetch_assoc($result)) {
        $productIds[] = $row['product_id'];
    }

    return $productIds;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Thank you for ordering from Tasty Bites! Here's your receipt with details of your order.">
<meta name="keywords" content="Order receipt, Receipt, Order details, Tasty Bites, Food order">
<title>Receipt</title>
<style>
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  th {
    background-color: #f2f2f2;
  }
  img {
    max-width: 100px;
    height: auto;
  }
</style>
</head>
<body>

<h2>Thanks for ordering!</h2>
<h2>Here's your Receipt:</h2>

<?php
$mysqli = require __DIR__ . "/database.php";

// Fetch user ID from session
session_start();
$userID = $_SESSION["user_id"];

// Start the table structure outside the loop
echo "<table>";
echo "<tr><th>Name</th><th>Price</th><th>Image</th></tr>";

$totalPrice = 0;
$orderID = null;

// Calculate total price
foreach ($items as $item) {
    $categoryId = $item[0];
    $subcategoryId = $item[1];
    $allInformation = getAllFromDatabase($categoryId, $subcategoryId);

    foreach ($allInformation as $product) {
        $totalPrice += $product['price'];
    }
}

// Insert order details into orders table
$sql_insert_order = "INSERT INTO orders (user_id, price, created_at) VALUES (?, ?, CURRENT_TIMESTAMP)";
$stmt_insert_order = $mysqli->prepare($sql_insert_order);
if (!$stmt_insert_order) {
    die("Error: " . $mysqli->error);
}
$stmt_insert_order->bind_param("id", $userID, $totalPrice);
$result_insert_order = $stmt_insert_order->execute();
if ($result_insert_order) {
    $orderID = $stmt_insert_order->insert_id; 
} else {
    die("Error inserting order: " . $mysqli->error);
}

// Insert order items into order_items table
foreach ($items as $item) {
    $categoryId = $item[0];
    $subcategoryId = $item[1];
    $productIds = getProductIdsFromDatabase($categoryId, $subcategoryId);

    foreach ($productIds as $productId) {
        $sql_insert_order_item = "INSERT INTO order_items (order_id, product_id) VALUES (?, ?)";
        $stmt_insert_order_item = $mysqli->prepare($sql_insert_order_item);
        if (!$stmt_insert_order_item) {
            die("Error: " . $mysqli->error);
        }
        $stmt_insert_order_item->bind_param("ii", $orderID, $productId);
        $result_insert_order_item = $stmt_insert_order_item->execute();
        if (!$result_insert_order_item) {
            die("Error inserting order item: " . $mysqli->error);
        }
    }
}

// Iterate over each product and create a new row in the table for each product
foreach ($items as $item) {
    $categoryId = $item[0];
    $subcategoryId = $item[1];
    $allInformation = getAllFromDatabase($categoryId, $subcategoryId);

    foreach ($allInformation as $product) {
        echo "<tr>";
        echo "<td>" . $product['name'] . "</td>";
        echo "<td>£" . $product['price'] . "</td>";
        echo "<td><img src='" . $product['image_url'] . "' alt='" . $product['name'] . "'></td>";
        echo "</tr>";
    }
}

// Close the table structure after the loop
echo "</table>";
?>


</body>
</html>
