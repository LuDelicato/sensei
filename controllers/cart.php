<?php
require("models/products.php");

$model = new Products();

if (isset($_POST["send"]) && intval($_POST["quantity"]) > 0) {

    $product = $model->getProductWithinStock($_POST);

    if (!empty($product)) {
        $_SESSION["cart"][$product["product_id"]] = [
            "product_id" => $product["product_id"],
            "quantity" => intval($_POST["quantity"]),
            "name" => $product["name"],
            "price" => $product["price"],
            "stock" => $product["stock"]
        ];
    }

    header("Location: /cart/");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //update qty items
    if (isset($_POST["update"])) {
        $product_id = $_POST["product_id"];
        $quantity = $_POST["quantity"];

        if (isset($_SESSION["cart"][$product_id])) {
            $_SESSION["cart"][$product_id]["quantity"] = $quantity;
        }
    } else if (isset($_POST["remove"])) {
        $product_id = $_POST["product_id"];
        // delete from cart
        unset($_SESSION["cart"][$product_id]);
    }
}

require("views/cart.php");
