<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderIndex = $_POST['orderIndex'];
    $action = $_POST['action'];

    if (isset($_SESSION["orders"][$orderIndex])) {
        if ($action == 'accept') {
            $_SESSION["orders"][$orderIndex]['status'] = 'Accepted';
        } elseif ($action == 'decline') {
            $_SESSION["orders"][$orderIndex]['status'] = 'Declined';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .orders-container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .order-item p {
            margin: 0;
        }
        .order-item button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .order-item button:hover {
            background-color: #0056b3;
        }
        .order-item form {
            display: inline-block;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="orders-container">
        <h1>Manage Orders</h1>
        <?php
        if (isset($_SESSION["orders"]) && count($_SESSION["orders"]) > 0) {
            foreach ($_SESSION["orders"] as $index => $order) {
                echo "<div class='order-item'>";
                echo "<p>Order by: {$order['user']}<br>Address: {$order['address']}<br>Payment: {$order['paymentType']}<br>Status: {$order['status']}</p>";
                if ($order['status'] == 'Pending') {
                    echo "<form method='POST'>
                            <input type='hidden' name='orderIndex' value='{$index}'>
                            <button type='submit' name='action' value='accept'>Accept</button>
                          </form>";
                    echo "<form method='POST'>
                            <input type='hidden' name='orderIndex' value='{$index}'>
                            <button type='submit' name='action' value='decline'>Decline</button>
                          </form>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No orders found.</p>";
        }
        ?>
    </div>
</body>
</html>
