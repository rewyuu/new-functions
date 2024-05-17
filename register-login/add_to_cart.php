<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($data['price'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    array_push($_SESSION['cart'], [
        'name' => $data['name'],
        'price' => $data['price']
    ]);

    echo count($_SESSION['cart']);
} else {
    echo count($_SESSION['cart']);
}
?>
