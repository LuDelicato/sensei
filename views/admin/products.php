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
    <title>ADMIN - PRODUCTS</title>
</head>
<body>

<h1>Admin Products</h1>

<h2>Add Product</h2>
<?php if (isset($message) && isset($_POST['add_product'])): ?>
    <div class="<?php echo ($errors ? 'error-message' : 'success-message'); ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
<form action="/admin/products" method="POST" enctype="multipart/form-data">
    <label>
        Product Name:
        <input type="text" name="name" required maxlength="100" placeholder="name must be less than 100 characters">
    </label>
    <label>
        Product Price:
        <input type="number" name="price" step="0.01" required placeholder="ex: 13.05">
    </label>
    <label>
        Product Description:
        <textarea name="description" required maxlength="1000" placeholder="description must be less than 1000 characters"></textarea>
    </label>
    <label>
        Category:
        <select name="category_id" required>
            <?php foreach ($categories as $category) : ?>
                <option value="<?= $category['category_id']; ?>"><?= $category['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        Stock:
        <input type="number" name="stock" required placeholder="stock must be an integer">
    </label>
    <label>
        Upload Image (Max 50MB, JPG, WebP, GIF):
        <input type="file" name="photo" accept=".jpg, .webp, .gif">
    </label>

    <button type="submit" name="add_product">Add Product</button>
</form>
<h2>Edit or Delete a Product</h2>
<ol>
    <?php foreach ($products as $product) : ?>
        <li>
            <div>
                <p><?= $product['name']; ?></p>
                <form action="/admin/edit/<?= $product['product_id']; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="selected_product" value="<?= $product['product_id']; ?>">
                    <button class="edit-button" type="submit">Edit</button>
                    <button class="delete-button" name="delete_product" value="<?= $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                </form>
            </div>
        </li>
    <?php endforeach; ?>

</ol>
</body>
</html>
