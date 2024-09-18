$(document).ready(function () {
    // Function to display order details on the page
    function displayOrderDetails(order) {
        // Clear previous order details
        $('#orderDetails').empty();
        
        // Show the order details box
        $('.order-details-box').show();

        // Check if order details exist
        if (order) {
            // Create HTML list to display order details
            var orderList = $('<ul>');

            // Iterate over order details and append to list
            $.each(order, function (key, value) {
                var listItem = $('<li>').html('<strong>' + key + ':</strong> ' + (key === 'Products Ordered' ? value.join('<br>') : value));
                orderList.append(listItem);
            });

            // Append list to order details container
            $('#orderDetails').append(orderList);
        } else {
            // Order not found
            $('#orderDetails').html('<p>No order found with the provided ID.</p>');
        }
    }

    // Handle form submission 
    $('#orderForm').submit(function (event) {
        // Prevent default form submission
        event.preventDefault();

        // Get order ID 
        var orderId = $('#orderId').val();

        // Make AJAX request to fetch order details
        $.ajax({
            url: 'getOrderDetails.php',
            method: 'GET',
            data: { order_id: orderId },
            dataType: 'json',
            success: function (response) {
                // Display order details on the page
                displayOrderDetails(response);
            },
            error: function (xhr, status, error) {
                // Log error details
                console.error("AJAX Error:", xhr.status, xhr.statusText);
                console.error("Response Text:", xhr.responseText);
                console.error("Status:", status);
                console.error("Error:", error);
            
                // Display error message if error occurs
                $('#orderDetails').html('<p>Error: ' + xhr.responseText + '</p>');
            }
            
        });
    });
});
