<?php
require_once("models/products.php");
require_once("models/categories.php");
require_once("models/admin.php");

$model = new Products();
$products = $model->get();

$categoryModel = new Categories();
$categories = $categoryModel->get();

$allowed_options = ["products", "categories", "orders", "users", "admin"];

// token csrf
if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(16));
}

// login admin
if (isset($_POST["send"])) {
    $adminModel = new Admin();
    $admin = $adminModel->login($_POST);

    if (!empty($admin)) {
        if ($admin['isAdmin'] === 1) {
            $_SESSION["admin"] = $admin;
            $message = "Logged in successfully!";
            require("views/admin/dashboard.php");
            exit;
        }
    } else {
        $message = "Invalid email, password or not an Admin";
    }
}

// redirect -> login if not logged in session
if (!isset($_SESSION["admin"])) {
    require("views/admin/adminLogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = new Products();

    // edit (id)
    if (isset($_POST['selected_product'])) {
        $selectedProductId = $_POST['selected_product'];
        $selectedProduct = $model->getItem($selectedProductId);

        if ($selectedProduct !== null) {
            $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : $selectedProduct['name'];
            $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : $selectedProduct['description'];
            $price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : $selectedProduct['price'];
            $stock = isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : $selectedProduct['stock'];
            $category_id = isset($_POST['category_id']) ? htmlspecialchars($_POST['category_id']) : $selectedProduct['category_id'];

            $data = [
                'product_id' => $selectedProduct['product_id'],
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'category_id' => $category_id,
            ];

            if ($name !== $selectedProduct['name']) {
                $name = htmlspecialchars($_POST['name']);
                $data['name'] = $name;
            }

            if ($description !== $selectedProduct['description']) {
                $description = htmlspecialchars($_POST['description']);
                $data['description'] = $description;
                $updatedFields[] = 'Description';
            }

            if ($price !== $selectedProduct['price']) {
                $price = htmlspecialchars($_POST['price']);
                $data['price'] = $price;
                $updatedFields[] = 'Price';
            }

            if ($stock !== $selectedProduct['stock']) {
                $stock = htmlspecialchars($_POST['stock']);
                $data['stock'] = $stock;
                $updatedFields[] = 'Stock';
            }

            if ($category_id !== $selectedProduct['category_id']) {
                $category_id = htmlspecialchars($_POST['category_id']);
                $data['category_id'] = $category_id;
                $updatedFields[] = 'Category';
            }

            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['tmp_name'];
                $photo = $model->handleUploadedImage($image);
                $data['photo'] = $photo;
                $updatedFields[] = 'Image';
            }

            $model->update($data);

            $message = "Product updated successfully!";
        } else {
            http_response_code(404);
            die("Product not found");
        }

        // create new
    } elseif (!empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_POST['stock']) && !empty($_POST['category_id'])) {
        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);
        $price = htmlspecialchars($_POST['price']);
        $stock = htmlspecialchars($_POST['stock']);
        $category_id = htmlspecialchars($_POST['category_id']);

        $data = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'category_id' => $category_id,
        ];

        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['tmp_name'];
            $photo = $model->handleUploadedImage($image);
            $data['photo'] = $photo;
        }

        $model->create($data);

        $message = "Product created successfully!";
    } else {
        http_response_code(400);
        die("Invalid request");
    }

    //delete(id)
    if (isset($_POST['delete_product'])) {
        if (isset($_POST['selected_product'])) {
            $selectedProductId = $_POST['selected_product'];
            $selectedProduct = $model->getItem($selectedProductId);

            if ($selectedProduct !== null) {
                $model->delete($selectedProduct['product_id']);

                $message = "Product deleted successfully!";
            } else {
                http_response_code(404);
                die("Product not found");
            }
        } else {
            $message = "Please select a product first to delete it.";
        }
        header("Location: /admin/products");
        exit();
    }
}

if (isset($url_parts[2])) {
    $option = $url_parts[2];
}

if (!empty($url_parts[3])) {
    $resource_id = $url_parts[3];
}

if (empty($option)) {
    require("views/admin/dashboard.php");
} elseif (!empty($resource_id)) {
    require("models/" . $option . ".php");
    $className = ucwords($option);
    $model = new $className;

    if (isset($_POST["send"])) {
        $model->update($_POST);
    }

    $selectedProduct = $model->getItem($resource_id);

    require("views/admin/" . $option . ".php");
} else {
    require("models/" . $option . ".php");
    $className = ucwords($option);
    $model = new $className;

    $data = $model->get();

    if ($option === "products") {
        $products = $data;
    }

    require("views/admin/" . $option . ".php");
}
