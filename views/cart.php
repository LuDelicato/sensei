<?php
require_once("templates/navigation.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Cart</title>
</head>
<body>
<div class="cart-container">
    <?php if (!empty($_SESSION["cart"])) { ?>
        <table class="cart-table">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;

            foreach ($_SESSION["cart"] as $key => $item) {
                $subtotal = $item["price"] * $item["quantity"];
                $total += $subtotal;
                ?>
                <tr>
                    <td><a href="/products/<?php echo $item['product_id']; ?>"><?php echo $item["name"]; ?></a></td>
                    <td>€<?php echo $item["price"]; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $key; ?>">
                            <input type="number" name="quantity" value="<?php echo $item["quantity"]; ?>" min="1">
                            <button type="submit" name="update">Update</button>
                        </form>
                    </td>
                    <td>€<?php echo $subtotal; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $key; ?>">
                            <button type="submit" name="remove">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td>Total:</td>
                <td></td>
                <td></td>
                <td>€<?php echo $total; ?></td>
                <td></td>
            </tr>
        </table>

        <div class="cart-button">
            <a href="/checkout/">Complete purchase</a>
        </div>
    <?php } else {
        echo "<p class='cart-empty-cart-message'>Your cart is empty</p>";
    } ?>
</div>

</body>
</html>