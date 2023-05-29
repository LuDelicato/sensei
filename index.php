<?php
require_once ("templates/navigation.php");
session_start();

$url_parts = explode("/", $_SERVER["REQUEST_URI"]);

define("ENV", parse_ini_file(".env"));

$controller = "home";


$allowed_controllers = [
    "home",
    "products",
    "api"
];

if (!empty($url_parts[1])) {
    $controller = $url_parts[1];
}

if (!empty($url_parts[2])) {
    $id = $url_parts[2];
}

if (!in_array($controller, $allowed_controllers)) {
    http_response_code(404);
    die("Not Found");
}

if ($controller === "products" && !empty($id)) {

    require("controllers/productDetails.php");
}



require("controllers/" . $controller . ".php");