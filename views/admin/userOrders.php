<?php require_once("templates/adminNav.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <title>Admin - <?= $user['name']; ?> - Orders</title>
</head>
<body>
<h1><?= $user['name']; ?> Orders</h1>
<?php if (!empty($userOrders)): ?>
    <?php $renderedOrderIds = []; ?>
    <?php foreach ($userOrders as $order): ?>
        <?php if (!in_array($order['order_id'], $renderedOrderIds)): ?>
            <?php $renderedOrderIds[] = $order['order_id']; ?>
            <div class="user-orders-container">
                <div class="user-orders-order">
                    <h2 class="user-orders-order-id">Order ID: #<?php echo $order['order_id']; ?></h2>
                    <p>Order Date: <?php echo $order['order_date']; ?></p>
                    <form method="post">
                        <select name="status">
                            <?php foreach ($orderStatuses as $status): ?>
                                <option value="<?php echo $status['id']; ?>" <?php if ($status['id'] === $order['status_id']) echo 'selected'; ?>>
                                    <?php echo $status['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" name="update_status" class="update-order-status">
                            Update Order Status
                        </button>
                    </form>
                    <?php if (!empty($order['order_details'])): ?>
                        <div class="user-orders-details">
                            <table class="user-orders-table">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price Each</th>
                                    <th>Quantity</th>
                                    <th rowspan="<?php echo count($order['order_details']); ?>">Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $subtotal = 0; ?>
                                <?php foreach ($order['order_details'] as $index => $orderDetail): ?>
                                    <tr>
                                        <td><?php echo $orderDetail['product_name']; ?></td>
                                        <td>€<?php echo $orderDetail['price_each']; ?></td>
                                        <td><?php echo $orderDetail['quantity']; ?></td>
                                        <?php
                                        $productSubtotal = $orderDetail['price_each'] * $orderDetail['quantity'];
                                        $subtotal += $productSubtotal;
                                        ?>
                                        <?php if ($index === count($order['order_details']) - 1): ?>
                                            <td rowspan="<?php echo count($order['order_details']); ?>">€<?php echo $subtotal; ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="user-orders-no-details">No order details found.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <p>No orders found.</p>
<?php endif; ?>
</body>
</html>
