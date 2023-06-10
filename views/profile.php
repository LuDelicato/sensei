<?php
require_once("templates/navigation.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title><?php echo $user['name']; ?> - User Profile</title>
</head>
<body>
<h1 class="profile-h1">Hi, <?php echo $user['name']; ?></h1>
<form action="/logout" method="post">
    <button class="logout-btn" type="submit" name="logout">Logout</button>
</form>
<p class="profile-p">Here you can edit your profile and check your orders</p>
<div class="profile-container">
    <section class="user-profile">
        <form class="profile-form-container" method="post" action="/profile">
            <div class="profile-group">
                <label class="profile-label" for="name">Name:</label>
                <input class="profile-input-field" type="text" id="name" name="name"
                       value="<?php echo $_POST['name'] ?? $user['name']; ?>">
                <?php if (isset($errors['name'])): ?>
                    <div class="error"><?php echo $errors['name']; ?></div>
                <?php endif; ?>
            </div>

            <div class="profile-group">
                <label class="profile-label" for="email">Email:</label>
                <input class="profile-input-field" type="email" id="email" name="email"
                       value="<?php echo $_POST['email'] ?? $user['email']; ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>

            <div class="profile-group">
                <label class="profile-label" for="address">Address:</label>
                <input class="profile-input-field" type="text" id="address" name="address"
                       value="<?php echo $_POST['address'] ?? $user['address']; ?>">
                <?php if (isset($errors['address'])): ?>
                    <div class="error"><?php echo $errors['address']; ?></div>
                <?php endif; ?>
            </div>

            <div class="profile-group">
                <label class="profile-label" for="city">City:</label>
                <input class="profile-input-field" type="text" id="city" name="city"
                       value="<?php echo $_POST['city'] ?? $user['city']; ?>">
                <?php if (isset($errors['city'])): ?>
                    <div class="error"><?php echo $errors['city']; ?></div>
                <?php endif; ?>
            </div>

            <div class="profile-group">
                <label class="profile-label" for="postal_code">Postal Code:</label>
                <input class="profile-input-field" type="text" id="postal_code" name="postal_code"
                       value="<?php echo $_POST['postal_code'] ?? $user['postal_code']; ?>">
                <?php if (isset($errors['postal_code'])): ?>
                    <div class="error"><?php echo $errors['postal_code']; ?></div>
                <?php endif; ?>
            </div>

            <div class="profile-group">
                <input class="profile-submit-btn" type="submit" name="send" value="Update">
            </div>
        </form>

        <div class="password-update-container">
            <form id="password-update-form" class="profile-form-container" method="post" action="/profile">
                <div class="profile-group">
                    <label class="profile-label" for="new_password">New Password:</label>
                    <input class="profile-input-field" type="password" id="new_password" name="new_password" placeholder="Minimum 8 characters">
                </div>

                <div class="profile-group">
                    <label class="profile-label" for="confirm_password">Confirm Password:</label>
                    <input class="profile-input-field" type="password" id="confirm_password" name="confirm_password">
                </div>

                <div class="profile-group">
                    <button class="profile-submit-btn" type="submit" name="update_password">Update Password</button>
                </div>

                <?php if (isset($_POST["update_password"]) && (empty($_POST["new_password"]) || empty($_POST["confirm_password"]))) : ?>
                    <div id="password-error" class="profile-error">
                        Password cannot be blank.
                    </div>
                <?php elseif (isset($_POST["update_password"]) && strlen($_POST["new_password"]) < 8) : ?>
                    <div id="password-error" class="profile-error">
                        Password must be at least 8 characters long.
                    </div>
                <?php elseif (isset($_POST["update_password"]) && $_POST["new_password"] !== $_POST["confirm_password"]) : ?>
                    <div id="password-error" class="profile-error">
                        Passwords do not match.
                    </div>
                <?php elseif (isset($successMessage)) : ?>
                    <div id="password-success" class="profile-success">
                        <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>


    </section>

    <section class="user-orders">
        <h2>Order History</h2>
        <div class="table-container">
            <table class="user-orders-table">
                <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Payment Date</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price Each</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $orderGroups = [];
                foreach ($userOrders as $order) {
                    $orderId = $order['order_id'];
                    if (!isset($orderGroups[$orderId])) {
                        $orderGroups[$orderId] = [
                            'orders' => [],
                            'subtotal' => 0,
                        ];
                    }
                    $orderGroups[$orderId]['orders'][] = $order;
                    $subtotal = $order['price_each'] * $order['quantity'];
                    $orderGroups[$orderId]['subtotal'] += $subtotal;
                }

                foreach ($orderGroups as $orderId => $group) {
                    $subtotal = $group['subtotal'];
                    $orders = $group['orders'];
                    $rowCount = count($orders);
                    foreach ($orders as $index => $order) {
                        $productName = $order['product_name'];
                        $quantity = $order['quantity'];
                        $priceEach = $order['price_each'];
                        $orderSubtotal = $quantity * $priceEach;
                        ?>
                        <tr>
                            <?php if ($index === 0): ?>
                                <td rowspan="<?php echo $rowCount; ?>">#<?php echo $orderId; ?></td>
                                <td rowspan="<?php echo $rowCount; ?>"><?php echo date('Y-m-d', strtotime($order['order_date'])); ?></td>
                                <td rowspan="<?php echo $rowCount; ?>"><?php echo isset($order['payment_date']) ? date('Y-m-d', strtotime($order['payment_date'])) : 'N/A'; ?></td>
                            <?php endif; ?>
                            <td><?php echo $productName; ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td>€<?php echo $priceEach; ?></td>
                            <?php if ($index === 0): ?>
                                <td rowspan="<?php echo $rowCount; ?>">€<?php echo $subtotal; ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		const errorMessageClass = "<?php echo $errorMessageClass ?? ''; ?>";
		const passwordError = document.getElementById("password-error");

		const isEmpty = <?php echo isset($_POST["update_password"]) && empty($_POST["new_password"]) ? 'true' : 'false'; ?>;
		const isShort = <?php echo isset($_POST["update_password"]) && strlen($_POST["new_password"]) < 8 ? 'true' : 'false'; ?>;
		const isEqual = <?php echo isset($_POST["update_password"]) && ($_POST["new_password"] === $_POST["confirm_password"]) ? 'true' : 'false'; ?>;

		if (errorMessageClass || isEmpty || isShort || !isEqual) {
			passwordError.style.display = "block";
		} else {
			passwordError.style.display = "none";
		}
	});
</script>
</body>
</html>