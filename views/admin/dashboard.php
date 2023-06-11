<?php
require_once("templates/adminNav.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<main>
    <h1 class="dashboard-title">Welcome to the Admin Dashboard</h1>

    <section class="dashboard-quick-stats" style="display: flex; justify-content: space-between; font-size: 24px;">
        <ul class="dashboard-stat-list" style="display: flex;">
            <li class="dashboard-stat-item" style="margin-right: 20px;">Total Products: <?php echo $productCount; ?></li>
            <li class="dashboard-stat-item" style="margin-right: 20px;">Total Orders: <?php echo $orderCount; ?></li>
            <li class="dashboard-stat-item">Total Users: <?php echo $userCount; ?></li>
        </ul>
    </section>

    <section class="dashboard-recent-orders">
        <h2 class="dashboard-section-title">Recent Orders</h2>
        <table class="dashboard-table">
            <thead>
            <tr>
                <th class="dashboard-table-header">Order ID</th>
                <th class="dashboard-table-header">User</th>
                <th class="dashboard-table-header">Order Date</th>
                <th class="dashboard-table-header">Status</th>
                <th class="dashboard-table-header">SubTotal</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($recentOrders)): ?>
                <?php foreach ($recentOrders as $order): ?>
                    <tr class="dashboard-table-row">
                        <td class="dashboard-table-data"><?= $order['order_id'] ?></td>
                        <td class="dashboard-table-data"><?= $order['user'] ?></td>
                        <td class="dashboard-table-data"><?= $order['order_date'] ?></td>
                        <td class="dashboard-table-data"><?= $order['status'] ?></td>
                        <td class="dashboard-table-data">
                            <?php if (!empty($order['order_id'])):
                                $ordersModel = new Orders();
                                $totalAmount = $ordersModel->calculateOrderTotal($order['order_id']);
                                echo "€" . number_format($totalAmount, 2);
                            else:
                                echo 'N/A';
                            endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="dashboard-table-row">
                    <td colspan="5" class="dashboard-table-data">No recent orders found.</td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>
    </section>

    <section class="dashboard-product-inventory">
        <h2 class="dashboard-section-title">Low inventory Products below 50 units</h2>
        <table class="dashboard-table">
            <thead>
            <tr>
                <th class="dashboard-table-header">Product Name</th>
                <th class="dashboard-table-header">Stock</th>
                <th class="dashboard-table-header">Price</th>
                <th class="dashboard-table-header">Category</th>
                <th class="dashboard-table-header">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($productInventory)): ?>
                <?php foreach ($productInventory as $product): ?>
                    <tr class="dashboard-table-row">
                        <td class="dashboard-table-data"><?= $product['name'] ?></td>
                        <td class="dashboard-table-data"><?= $product['stock'] ?></td>
                        <td class="dashboard-table-data"><?= $product['price'] ?></td>
                        <td class="dashboard-table-data"><?= $product['category_name'] ?></td>
                        <td class="dashboard-table-data">
                            <a href="/admin/edit/<?= $product['product_id'] ?>" class="dashboard-link">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="dashboard-table-row">
                    <td colspan="5" class="dashboard-table-data">No products found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
