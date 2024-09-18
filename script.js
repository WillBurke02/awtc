document.addEventListener("DOMContentLoaded", function () {
    const categorySelect = document.getElementById("category");
    const subcategorySelect = document.getElementById("subcategory");
    const productDetailsDiv = document.getElementById("product-details");
    const addToOrderButton = document.getElementById("add_to_order");
    const finishOrderButton = document.getElementById("finish_order");
    const selectedItemsList = document.getElementById("selected-items-list");

    console.log("categorySelect:", categorySelect);
    console.log("subcategorySelect:", subcategorySelect);
    console.log("productDetailsDiv:", productDetailsDiv);
    console.log("addToOrderButton:", addToOrderButton);
    console.log("finishOrderButton:", finishOrderButton);
    console.log("selectedItemsList:", selectedItemsList);

    const categoryIdArray = [];
    const subcategoryIdArray = [];
    const items = [];

    let totalPrice = 0;

if (selectedItemsList) {
    addToOrderButton.addEventListener("click", function () {
        console.log("Add to order button clicked");
        // Get selected values from dropdowns
        const productName = document.getElementById("product-details").querySelector("p:nth-child(2)").textContent;
        const productPrice = document.getElementById("product-details").querySelector("p:nth-child(3)").textContent;
        const categoryId = document.getElementById("category").value;
        const subcategoryId = document.getElementById("subcategory").value;

        console.log("category ID:", categoryId);
        console.log("subcategory ID:", subcategoryId);

        // Display selected values in side box
        if (productName && productPrice && categoryId && subcategoryId) {
            // Add selected values to the side box
            const dishItem = document.createElement("li");
            dishItem.innerHTML = `Dish: ${productName}, ${productPrice} <button class="remove_from_order">Remove</button>`;
            selectedItemsList.appendChild(dishItem);

            categoryIdArray.push(categoryId);
            subcategoryIdArray.push(subcategoryId);

            console.log("categoryId:", categoryIdArray);
            console.log("subcategoryId: ", subcategoryIdArray);

            const price = parseFloat(productPrice.replace(/[^\d.]/g, ''));
            totalPrice += price;
            updateTotalPrice();
        } else {
            alert("Please select both a dish and a type.");
        }
    });

    finishOrderButton.addEventListener("click", function () {
            // Iterate over the arrays and add each pair of category ID and subcategory ID to the items array
            for (let i = 0; i < categoryIdArray.length; i++) {
                // Check if there is a subcategory ID for the current category ID
                items.push([categoryIdArray[i], subcategoryIdArray[i]]);
            }

            console.log("Items:", items);

        // Redirect the user to order.php with the items array appended as URL parameters
          window.location.href = "order.php?items=" + encodeURIComponent(JSON.stringify(items));
    });


    // Function to update total price
    function updateTotalPrice() {
        const totalPriceElement = document.getElementById("total-price");
        totalPriceElement.textContent = `Total Price: £${totalPrice.toFixed(2)}`;
    }

    // Function to remove items 
    selectedItemsList.addEventListener("click", function (event) {
        if (event.target.classList.contains("remove_from_order")) {
            // Find the parent li element which contains the product details
            const listItem = event.target.closest("li");
            if (listItem) {
                // Get the index of the list item in the selected items list
                const removeButtonIndex = Array.from(listItem.parentNode.children).indexOf(listItem);
    
                // Retrieve the price of the removed item from the DOM
                const productPriceText = listItem.textContent.match(/£(\d+(\.\d+)?)/);
                const removedPrice = parseFloat(productPriceText[1]);
    
                // Remove the corresponding category ID and subcategory ID from the arrays
                const removedCategoryId = categoryIdArray.splice(removeButtonIndex, 1)[0];
                const removedSubcategoryId = subcategoryIdArray.splice(removeButtonIndex, 1)[0];
    
                console.log("Removed Category ID:", removedCategoryId);
                console.log("Removed Subcategory ID:", removedSubcategoryId);
    
                // Remove the item from the selected items list
                listItem.remove();
    
                // Subtract the price of the removed item from the total price
                totalPrice -= removedPrice;
                updateTotalPrice();
            }
        }
    });
    
    
}

    // Event listener for category select change
    categorySelect.addEventListener("change", function () {
        const categoryId = this.value;
        if (categoryId !== "") {
            fetchSubcategories(categoryId);
            subcategorySelect.disabled = false;
        } else {
            subcategorySelect.innerHTML = "<option value=''>Select Type</option>";
            subcategorySelect.disabled = true;
        }
    });

    // Event listener for subcategory select change
    subcategorySelect.addEventListener("change", function () {
        const subcategoryId = this.value;
        if (subcategoryId !== "") {
            fetchProductDetails(subcategoryId);
        } else {
            productDetailsDiv.innerHTML = ""; // Clear product details
        }
    });

    // Function to fetch subcategories based on category
    function fetchSubcategories(categoryId) {
        // AJAX request to fetch subcategories
        fetch(`get_subcategories.php?category_id=${categoryId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Populate subcategory dropdown with fetched data
                subcategorySelect.innerHTML = "<option value=''>Select Type</option>";
                data.forEach(subcategory => {
                    subcategorySelect.innerHTML += `<option value="${subcategory.subcategory_id}">${subcategory.name}</option>`;
                });
            })
            .catch(error => console.error('Error fetching subcategories:', error));
    }

    // Function to fetch and display product details based on subcategory
    function fetchProductDetails(subcategoryId) {
        // AJAX request to fetch product details
        fetch(`get_products.php?subcategory_id=${subcategoryId}`)
            .then(response => response.json())
            .then(data => {
                // Display product details
                let html = "<h2>Product Details</h2>";
                data.forEach(product => {
                    html += `
                        <div>
                            <img src="${product.image_url}" alt="${product.name}" style="width: 250px; height: auto; border: 3px solid black;">
                            <p>${product.name}</p>
                            <p>Price: £${product.price}</p>
                            <input type="hidden" class="product_id" value="${product.product_id}">
                        </div>
                    `;
                });
                productDetailsDiv.innerHTML = html;
            })
            .catch(error => console.error('Error fetching product details:', error));
    }

})
