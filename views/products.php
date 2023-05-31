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
    <title>Sensei - <?php echo $activeCategory ?? 'All Products'; ?></title>
</head>
<body>
<div class="product-collection">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <h3><?php echo $product['name']; ?></h3>
            <a href="/products/<?php echo $product['product_id']; ?>">
                <img src="/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            </a>
            <p>€<?php echo $product['price']; ?></p>
            <form action="/cart/<?php echo $product['product_id']; ?>" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <div class="quantity-container">
                    <input type="number" name="quantity" class="quantity-input" value="1">
                </div>
                <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<script src="/assets/js/add-to-cart.js"></script>
</body>
</html>