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
    <title>ADMIN - EDIT PRODUCT</title>
</head>
<body>
<h1>Edit Product</h1>
<?php if (isset($message) && isset($_POST['update_product'])): ?>
    <div class="<?php echo ($errors ? 'error-message' : 'success-message'); ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
<?php if ($selectedProduct !== null) : ?>
    <form action="/admin/edit/<?= $selectedProduct['product_id']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="selected_product" value="<?= $selectedProduct['product_id']; ?>">
        <label>
            Product Name:
            <input type="text" name="name" value="<?= $selectedProduct['name']; ?>" required maxlength="100"
                   placeholder="Name must be less than 100 characters">
        </label>
        <label>
            Product Price:
            <input type="number" name="price" step="0.01" value="<?= $selectedProduct['price']; ?>" required
                   placeholder="Ex: 13.05">
        </label>
        <label>
            Product Description:
            <textarea name="description" required maxlength="1000"
                      placeholder="Description must be less than 1000 characters"><?= $selectedProduct['description']; ?></textarea>
        </label>
        <label>
            Category:
            <select name="category_id" required>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['category_id']; ?>" <?php if ($selectedProduct['category_id'] === $category['category_id']) echo 'selected'; ?>>
                        <?= $category['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Stock:
            <input type="number" name="stock" value="<?= $selectedProduct['stock']; ?>" required
                   placeholder="Stock must be an integer">
        </label>
        <label>
            Upload Image (Max 50MB, JPG, WebP, GIF):
            <input type="file" name="photo" accept=".jpg, .webp, .gif">
        </label>
        <button type="submit" name="update_product">Update Product</button>
        <button class="delete-button" name="delete_product" value="<?= $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
    </form>
<?php else : ?>
    <p>No product selected.</p>
<?php endif; ?>
</body>
</html>
