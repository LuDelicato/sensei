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
<form action="/admin/products" method="POST" enctype="multipart/form-data">
    <label>
        Product Name:
        <input type="text" name="name" required>
    </label>
    <label>
        Product Price:
        <input type="number" name="price" step="0.01" required>
    </label>
    <label>
        Product Description:
        <textarea name="description" required></textarea>
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
        <input type="number" name="stock" required>
    </label>
    <label>
        Upload Image (Max 50MB, JPG, WebP, GIF):
        <input type="file" name="photo" accept=".jpg, .webp, .gif">
    </label>

    <button type="submit" name="send">Add Product</button>
</form>
<?php if (isset($message)): ?>
    <div class="success-message">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
<?php if (!empty($selectedProduct) && isset($selectedProduct['product_id'])): ?>
    <h2>Edit Product or Delete a Product</h2>
    <form action="/admin/products/<?= $selectedProduct['product_id']; ?>" method="POST" enctype="multipart/form-data">
        <label>
            Product Name:
            <select name="selected_product" id="product-dropdown" onchange="populateProductFields(this)">
                <?php foreach ($products as $product) : ?>
                    <option value="<?= $product['product_id']; ?>"
                            data-price="<?= $product['price'] ?? ''; ?>"
                            data-description="<?= $product['description'] ?? ''; ?>"
                            data-stock="<?= $product['stock'] ?? ''; ?>"
                            data-category="<?= $product['category_id'] ?? ''; ?>">
                        <?= $product['name']; ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Product Price:
            <input type="number" name="price" step="0.01" id="product-price">
        </label>
        <label>
            Product Description:
            <textarea name="description" id="product-description"></textarea>
        </label>
        <label>
            Category:
            <select name="category_id" id="product-category">
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['category_id']; ?>"><?= $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Stock:
            <input type="number" name="stock">
        </label>
        <label>
            Upload Image (Max 50MB, JPG, WebP, GIF):
            <input type="file" name="photo" accept=".jpg, .webp, .gif">
        </label>
    </form>
<?php else: ?>
    <h2>Select a product to edit</h2>
    <form action="/admin/products" method="post" enctype="multipart/form-data">
        <label>
            Product Name:
            <select name="selected_product" id="product-dropdown" onchange="populateProductFields(this)">
                <option value="">Select a product</option>
                <?php foreach ($products as $product) : ?>
                    <option value="<?= $product['product_id']; ?>" data-price="<?= $product['price']; ?>" data-description="<?= $product['description']; ?>" data-stock="<?= $product['stock']; ?>" data-category="<?= $product['category_id']; ?>">
                        <?= $product['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Product Price (€):
            <input type="number" name="price" step="0.01" id="product-price">
        </label>
        <label>
            Product Description:
            <textarea name="description" id="product-description"></textarea>
        </label>
        <label>
            Category:
            <select name="category_id" id="product-category">
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['category_id']; ?>"<?= (!empty($selectedProduct) && $selectedProduct['category_id'] == $category['category_id']) ? ' selected' : ''; ?>>
                        <?= $category['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Product Stock:
            <input type="number" name="stock" id="product-stock">
        </label>
        <label>
            Upload Image (Max 50MB, JPG, WebP, GIF):
            <input type="file" name="photo" accept=".jpg, .webp, .gif">
        </label>

        <button type="submit" name="update_product">>Update Product</button>
        <button type="submit" name="delete_product" class="delete-button" onclick="return confirm('Are you sure you want to delete this product? This action is not reversible and the product will be deleted from the database.')">Delete Selected Product</button>
    </form>
<?php endif; ?>

<script>
	function populateProductFields(select) {

		// populate
		const selectedOption = select.options[select.selectedIndex];
		const productPrice = document.querySelector("#product-price");
		const productDescription = document.querySelector("#product-description");
		const productStock = document.querySelector("#product-stock");

		const price = selectedOption.getAttribute("data-price");
		const description = selectedOption.getAttribute("data-description");
		const stock = selectedOption.getAttribute("data-stock");
		const categoryId = selectedOption.getAttribute("data-category");

		productPrice.value = price ? price : "";
		productDescription.value = description ? description : "";
		productStock.value = stock ? stock : "";

		// Select category
		const categoryOption = document.querySelector("#product-category option[value='" + categoryId + "']");
		if (categoryOption) {
			categoryOption.selected = true;
		}
	}
</script>

</body>
</html>
