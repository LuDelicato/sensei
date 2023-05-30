<?php

require_once("models/products.php");

$model = new Products();

if (!isset($id) || !is_numeric($id)) {
    http_response_code(400);
    die("Invalid Request");
}

$products = $model->getByCategory($id);

if (empty($products)) {
    http_response_code(404);
    die("Not found");
}

require("views/products.php");
