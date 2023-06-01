<?php

if( !isset($_SESSION["user_id"]) ) {

    header("Location: /login/");
    exit;
}

if( empty($_SESSION["cart"]) ) {

    header("Location: /");
    exit;
}

require("models/orders.php");
require("models/products.php");

$modelOrders = new Orders();
$modelProducts = new Products();

$order_id = $modelOrders->createHeader( $_SESSION["user_id"] );

foreach($_SESSION["cart"] as $product) {

    $modelOrders->createDetail($order_id, $product);

    $modelProducts->updateProductStock($product);
}
unset( $_SESSION["cart"] );

require("views/checkout.php");