<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderData = json_decode(file_get_contents('php://input'), true);

    if (isset($orderData['cancelOrderId'])) {
        if (!isset($_SESSION["user"])) {
            echo json_encode(["success" => false, "message" => "User not logged in"]);
            exit;
        }

        $orderId = $orderData['cancelOrderId'];
        if (isset($_SESSION["orders"][$orderId])) {
            unset($_SESSION["orders"][$orderId]);
            $_SESSION["orders"] = array_values($_SESSION["orders"]); 
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Order not found"]);
        }
        exit;
    } else {
        if (!isset($_SESSION["user"])) {
            echo json_encode(["success" => false, "message" => "User not logged in"]);
            exit;
        }

        $orderData['user'] = $_SESSION["user"];
        $orderData['status'] = 'Pending';

        if (!isset($_SESSION["orders"])) {
            $_SESSION["orders"] = [];
        }

        $_SESSION["orders"][] = $orderData;

        echo json_encode(["success" => true]);
        exit;
    }
}

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$orders = isset($_SESSION["orders"]) ? $_SESSION["orders"] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .go-back-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .go-back-button:hover {
            background-color: #0056b3;
        }
        .order {
            border-bottom: 1px solid #e9ecef;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order h3 {
            margin: 0;
        }
        .order p {
            margin: 5px 0;
        }
        .order-items {
            list-style-type: none;
            padding: 0;
        }
        .order-items li {
            margin: 5px 0;
        }
        .cancel-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .cancel-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="orders-container">
        <div class="header">
            <h1>Your Orders</h1>
            <button class="go-back-button" onclick="window.location.href='index.php'">Go Back Home</button>
        </div>
        <?php if (empty($orders)): ?>
            <p>No orders yet.</p>
        <?php else: ?>
            <?php foreach ($orders as $index => $order): ?>
                <div class="order" id="order-<?= $index ?>">
                    <div>
                        <h3>Order Address: <?= htmlspecialchars($order['address']) ?></h3>
                        <p>Payment Type: <?= htmlspecialchars($order['paymentType']) ?></p>
                        <h4>Items:</h4>
                        <ul class="order-items">
                            <?php foreach ($order['items'] as $item): ?>
                                <li><?= htmlspecialchars($item['name']) ?> - P<?= number_format($item['price'], 2) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p>Status: <?= htmlspecialchars($order['status']) ?></p>
                    </div>
                    <button class="cancel-button" onclick="cancelOrder(<?= $index ?>)">Cancel Order</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        function cancelOrder(orderId) {
            fetch('orders.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cancelOrderId: orderId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`order-${orderId}`).remove();
                    alert('Order cancelled successfully.');
                } else {
                    alert('There was an error cancelling the order.');
                }
            });
        }
    </script>
</body>
</html>
