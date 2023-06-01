<?php

if ( !isset($id) || !is_numeric($id)) {

    http_response_code(400);
    die("Invalid Request");
}

require("models/products.php");

$model = new Products();

$product = $model->getItem($id);

if ( empty($product)) {

    http_response_code(404);
    die("Page not Found");
}

require ("views/productDetails.php");

