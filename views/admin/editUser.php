<?php
require_once("templates/adminNav.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <title>Edit User - <?= $user['name']; ?></title>
</head>
<body>
<h2 class="user-edit-title">Edit User</h2>
<?php if (isset($message) && isset($_POST['save-user'])): ?>
    <div class="<?php echo ($errors ? 'error-message' : 'success-message'); ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
<form class="user-edit-form" method="post" action="/admin/user/<?= $user['user_id']; ?>">

    <input type="hidden" name="selected_user" value="<?= $user['user_id']; ?>">

    <div class="user-form-group">
        <label>Name:
            <input type="text" id="name" name="name" value="<?= $user['name']; ?>">
        </label>
    </div>

    <div class="user-form-group">
        <label>Email:
            <input type="email" id="email" name="email" value="<?= $user['email']; ?>">
        </label>
    </div>

    <div class="user-form-group">
        <label>Address:
            <input type="text" id="address" name="address" value="<?= $user['address']; ?>">
        </label>
    </div>

    <div class="user-form-group">
        <label>City:
            <input type="text" id="city" name="city" value="<?= $user['city']; ?>">
        </label>
    </div>

    <div class="user-form-group">
        <label>Postal Code:
            <input type="text" id="postal_code" name="postal_code" value="<?= $user['postal_code']; ?>">
        </label>
    </div>

    <div class="user-form-group">
        <label>Country:
            <select id="country" name="country">
                <?php foreach ($countries as $country) : ?>
                    <option value="<?= $country['code']; ?>" <?= ($user['country'] == $country['code']) ? 'selected' : ''; ?>>
                        <?= $country['country']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="user-form-group">
        <label>Status:
            <select id="status" name="status">
                <option value="1" <?= ($user['isActive'] == 1) ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?= ($user['isActive'] == 0) ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </label>
    <div class="user-form-group">
        <button type="submit" name="save-user" class="user-save-button">Save</button>
    </div>
</form>
</body>
</html>
