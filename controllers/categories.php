<?php

require_once("models/products.php");
require_once("models/categories.php");

$model = new Products();
$categoryModel = new Categories();

if (!isset($id) || !is_numeric($id)) {
    http_response_code(400);
    die("Invalid Request");
}

$category = $categoryModel->getItem($id);
$activeCategory = $category['name'];

$products = $model->getByCategory($id);

if (empty($products)) {
    http_response_code(404);
    die("Not found");
}

require("views/products.php");
