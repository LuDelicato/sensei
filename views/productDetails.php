<?php
require_once ("templates/navigation.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title><?php echo $product["name"]; ?></title>
</head>
<body>
<div class="product-details-container">
    <div class="product-details">
        <div class="product-details-image">
            <img src="/images/<?php echo $product['image']; ?>" alt="Product Image">
        </div>
        <div class="product-details-info">
            <h1 class="product-details-title"><?php echo $product['name']; ?></h1>
            <p class="product-details-description"><?php echo $product['description']; ?></p>
            <p class="product-details-price">€<?php echo $product['price']; ?></p>
            <form action="/cart/<?php echo $product['product_id']; ?>" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <label>
                Quantity
                <input class="product-details-quantity"
                        type="number"
                        name="quantity"
                        required
                        min="1"
                        max="<?php echo $product["stock"]; ?>"
                        value="1"
                >
            </label>
                <input type="hidden" name="product_id" value="<?php echo $product["product_id"]; ?>">
                <button type="submit" name="send" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
