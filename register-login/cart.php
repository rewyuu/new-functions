<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-weight: 700;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .cart-container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .cart-item p {
            margin: 0;
        }
        .cart-item button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .cart-item button:hover {
            background-color: #c82333;
        }
        #total-price {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }
        .cart-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .cart-buttons button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        .cart-buttons button:hover {
            background-color: #0056b3;
        }
        .address-input, .payment-method {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }
        .address-input label, .payment-method label {
            margin-bottom: 8px;
            font-weight: bold;
        }
        .address-input input, .payment-method select {
            padding: 8px;
            font-size: 1em;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h1>Your Cart</h1>
        <div id="cart-items"></div>
        <div id="total-price"></div>
        <div class="address-input">
            <label for="address">Delivery Address</label>
            <input type="text" id="address" placeholder="Enter your address">
        </div>
        <div class="payment-method">
            <label for="payment-type">Payment Method</label>
            <select id="payment-type">
                <option value="Cash on Delivery">Cash on Delivery</option>
                <option value="gcash">GCash</option>
                <option value="credit-card">Credit Card</option>
            </select>
        </div>
        <div class="cart-buttons">
            <button onclick="submitOrder()">Submit Order</button>
            <button onclick="goToHomePage()">Home</button>
        </div>
    </div>

    <script>
        function goToHomePage() {
            window.location.href = "index.php";
        }

        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        console.log('Cart items:', cartItems);

        function displayCartItems() {
            let cartContainer = document.getElementById('cart-items');
            let totalPriceElement = document.getElementById('total-price');
            cartContainer.innerHTML = '';

            let totalPrice = 0;

            cartItems.forEach(item => {
                let itemElement = document.createElement('div');
                itemElement.className = 'cart-item';
                itemElement.innerHTML = `
                    <p>${item.name} - P${item.price.toFixed(2)}</p>
                    <button onclick="removeFromCart('${item.id}')">Remove</button>
                `;
                cartContainer.appendChild(itemElement);
                totalPrice += item.price;
            });

            totalPriceElement.innerHTML = `<p>Total Price: P${totalPrice.toFixed(2)}</p>`;
        }

        function removeFromCart(itemId) {
            console.log('Removing item with ID:', itemId);
            let itemIndex = cartItems.findIndex(item => item.id === itemId);
            if (itemIndex > -1) {
                cartItems.splice(itemIndex, 1);
            }
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            displayCartItems();

            let cartItemCount = localStorage.getItem('cartItemCount') ? parseInt(localStorage.getItem('cartItemCount')) : 0;
            if (cartItemCount > 0) {
                cartItemCount--;
                localStorage.setItem('cartItemCount', cartItemCount);
                document.getElementById('cart-button').innerText = `Cart Items: ${cartItemCount}`;
            }
        }

        function submitOrder() {
            let address = document.getElementById('address').value;
            let paymentType = document.getElementById('payment-type').value;
            if (!address) {
                alert('Please enter your delivery address.');
                return;
            }
            if (!paymentType) {
                alert('Please select a payment method.');
                return;
            }

            let orderDetails = {
                address: address,
                paymentType: paymentType,
                items: cartItems
            };

            fetch('orders.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderDetails)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Order submitted! Delivery to: ${address}. Payment Method: ${paymentType}. Please wait until your food arrives.`);
                    localStorage.removeItem('cartItems');
                    localStorage.removeItem('cartItemCount');
                    displayCartItems();
                    window.location.href = "orders.php";
                } else {
                    alert('There was an error submitting your order. Please try again.');
                }
            });
        }

        displayCartItems();
    </script>
</body>
</html>
