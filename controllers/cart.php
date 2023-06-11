<?php

require_once("models/products.php");

$model = new Products();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["send"]) && intval($_POST["quantity"]) > 0) {
        $product = $model->getProductWithinStock($_POST);

        if (!empty($product) && $product["stock"] >= intval($_POST["quantity"])) {
            $_SESSION["cart"][$product["product_id"]] = [
                "product_id" => $product["product_id"],
                "quantity" => intval($_POST["quantity"]),
                "name" => $product["name"],
                "price" => $product["price"],
                "stock" => $product["stock"]
            ];
        } else {
            echo "<p>There are not enough units available for this product.</p>";
        }

        header("Location: /cart/");
        exit();
    } elseif (isset($_POST["update"])) {
        $product_id = $_POST["product_id"];
        $quantity = $_POST["quantity"];

        if (isset($_SESSION["cart"][$product_id])) {
            $product = $model->getProductWithinStock([
                "product_id" => $product_id,
                "quantity" => $quantity
            ]);

            if (!empty($product) && $product["stock"] >= $quantity) {
                $_SESSION["cart"][$product_id]["quantity"] = $quantity;
            } else {
                echo "<p>There are not enough units available for this product.</p>";
            }
        }
    } elseif (isset($_POST["remove"])) {
        $product_id = $_POST["product_id"];
        // Delete from cart
        unset($_SESSION["cart"][$product_id]);
    }
}

require("views/cart.php");
