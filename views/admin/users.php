<?php require_once("templates/adminNav.php"); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <title>User Management</title>
</head>
<body>
<h2 class="user-h1">User Management</h2>

<table class="user-table">
    <thead>
    <tr>
        <th class="user-table-header">Name</th>
        <th class="user-table-header">Email</th>
        <th class="user-table-header">Address</th>
        <th class="user-table-header">City</th>
        <th class="user-table-header">Postal Code</th>
        <th class="user-table-header">Country</th>
        <th class="user-table-header">Admin</th>
        <th class="user-table-header">Active</th>
        <th class="user-table-header">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user) : ?>
        <tr class="user-row">
            <td><?= $user['name']; ?></td>
            <td><?= $user['email']; ?></td>
            <td><?= $user['address']; ?></td>
            <td><?= $user['city']; ?></td>
            <td><?= $user['postal_code']; ?></td>
            <td><?= $user['country']; ?></td>
            <td><?= $user['isAdmin'] ? 'Yes' : 'No'; ?></td>
            <td><?= $user['isActive'] ? 'Yes' : 'No'; ?></td>
            <td>
                <a class="edit-user" href="/admin/user/<?= $user['user_id']; ?>">Edit</a>
                <a class="delete-user" href="/admin/delete/<?= $user['user_id']; ?>">Delete</a>
                <a class="orders-user" href="/admin/<?= $user['user_id']; ?>/orders">Orders</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>

</script>
</body>
</html>