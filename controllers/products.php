<?php

require_once("models/products.php");

$model = new Products();

$products = $model->get();

if( empty($products) ) {
    http_response_code(404);
    die("Not found");
}


if ($_SERVER['REQUEST_URI'] === '/products') {
    require("views/products.php");
}