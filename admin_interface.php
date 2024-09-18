<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Enter an order ID to view order details.">
    <meta name="keywords" content="order details, online orders, order ID">

    <title>Order Details</title>
    <link rel="stylesheet" href="admin_interfacestyle.css">
</head>
<body>
<div class="container">
        <div class="box form-box">
            <h1>Enter Order ID</h1>
            <form id="orderForm">
                <label for="orderId">Order ID:</label>
                <input type="text" id="orderId" name="orderId" required>
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="box order-details-box" id="orderDetails">
            <!-- Order details will be displayed here -->
            <br>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="adminscript.js"></script>
</body>
</html>
