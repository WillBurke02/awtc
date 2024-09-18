<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";
    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    // Check if the user is an admin
    $isAdmin = $user["admin"] == 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to Tasty Bites! Explore our delicious food selection and place your order online."> 
    <meta name="keywords" content="Burger, Sandwich, Food selection, Order online, Log in, Register">
    <link rel="stylesheet" href="menustyle.css">
    <title>Tasty Bites</title>
</head>
<body>

<div class="container">
    <nav>
        <!-- Display Admin link if user is admin -->
        <?php if (isset($isAdmin) && $isAdmin): ?>
            <p id="admin"><a href="admin_interface.php">Admin</a></p>
        <?php endif; ?>

        <!-- Display Login link if user is logged in, else logout -->
        <div class="session">
            <?php if (isset($user)): ?>
                <p><a href="logout.php">Log out</a></p>
            <?php else: ?>
                <p><a href="login.php">Log in</a> or <a href="register.php">Register</a></p>
            <?php endif; ?>
        </div>
    </nav>

    <?php if (isset($user)): ?>
    <h1>Hello <?php echo $user['name']; ?>! Welcome to Tasty Bites!</h1>
<?php else: ?>
    <h1>Welcome to Tasty Bites!</h1>
<?php endif; ?>

<div class="main-content">
    <div class="wrapper">
        <div class="food-selection">
            <h2>Please select a dish below!</h2>
            <div class="dropdown">
                <label for="category">Select Dish:</label>
                <select id="category" name="category">
                    <option value="">Select Dish</option>
                    <option value="1">Burger</option>
                    <option value="2">Sandwich</option>
                </select>
            </div>
            <div class="dropdown">
                <label for="subcategory">Select Type:</label>
                <select id="subcategory" name="subcategory">
                    <option value="">Select Type</option>
                </select>
            </div>

            <!-- Add to Order button -->
            <?php if (isset($user)): ?>
            <div class="order">
                <button type="button" id="add_to_order">Add to Order</button>
            </div>
            <?php else: ?>
            <p>You must be logged in to order</p>
            <?php endif; ?>

        </div>

        <div id="product-details"></div>

    </div>

    <form id="orderForm" method="post" action="order.php">
        <div class="basket-container">
            <?php if (isset($user)): ?>
            <div class="selected-items-box">
                <h2>Basket</h2>
                <div id="total-price">Total Price: £0.00</div>
                <ul id="selected-items-list"></ul>
                <div class="order">
                    <button type="button" id="finish_order">Finish Order</button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </form>


    </div class="main-content">

</div class="container">

<script src="script.js"></script>

</body>
</html>
