<?php

require ("templates/navigation.php");

require("models/products.php");

$model = new Products();

$products = $model->get(  );

if( empty($products) ) {
    http_response_code(404);
    die("Not found");
}



require("views/products.php");