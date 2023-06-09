<?php
require_once("models/products.php");
require_once("models/categories.php");
require_once("models/admin.php");
require_once("models/base.php");


$model = new Products();
$products = $model->get();

$categoryModel = new Categories();
$categories = $categoryModel->get();

$allowed_options = ["products", "categories", "orders", "users", "admin", "edit"];

// token csrf
if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(16));
}

// login admin
if (isset($_POST["send"])) {
    $adminModel = new Admin();

    // validateLogin
    $loginData = $adminModel->validateLogin($_POST["email"], $_POST["password"]);
    if (!$loginData) {
        die("Invalid login credentials");
    }

    // sanitize
    $_POST["email"] = $adminModel->sanitize($_POST["email"]);
    $_POST["password"] = $adminModel->sanitize($_POST["password"]);

    // login
    $admin = $adminModel->login($loginData);

    if (!empty($admin)) {
        if ($admin['isAdmin'] === 1) {
            $_SESSION["admin"] = $admin;
            $message = "Logged in successfully!";
            require("views/admin/dashboard.php");
            exit;
        }
    } else {
        $message = "Invalid email, password, or not an admin";
    }
}

// redirect -> login if not logged-in session
if (!isset($_SESSION["admin"])) {
    require("views/admin/adminLogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // create new
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category_id = $_POST['category_id'];

        $data = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'category_id' => $category_id,
        ];

        // validate
        $products = new Products();
        $errors = $products->validate($data);
        if (!empty($errors)) {
            $message = implode("<br>", $errors);
        } else {
            // sanitize
            $data = $products->sanitize($data);

            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['tmp_name'];
                $photo = $products->handleUploadedImage($image);
                $data['photo'] = $photo;
            }

            $model->create($data);

            $message = "Product created successfully!";
        }
    }
    // delete(id)
    if (isset($_POST['delete_product'])) {
        if (isset($_POST['selected_product'])) {
            $selectedProductId = $_POST['selected_product'];
            $selectedProduct = $model->getItem($selectedProductId);

            if ($selectedProduct !== null) {
                $model->delete($selectedProduct['product_id']);
                $message = "Product deleted successfully!";
                header("Location: /admin/products");
                exit;
            } else {
                http_response_code(404);
                die("Product not found");
            }
        }
    }
    // product edit (id)
    if (isset($_POST['selected_product'])) {
        $selectedProductId = $_POST['selected_product'];
        $selectedProduct = $model->getItem($selectedProductId);

        if ($selectedProduct !== null) {
            $data = [
                'product_id' => $selectedProduct['product_id'],
                'name' => $_POST['name'] ?? $selectedProduct['name'],
                'description' => $_POST['description'] ?? $selectedProduct['description'],
                'price' => $_POST['price'] ?? $selectedProduct['price'],
                'stock' => $_POST['stock'] ?? $selectedProduct['stock'],
                'category_id' => $_POST['category_id'] ?? $selectedProduct['category_id'],
            ];

            // validate
            $products = new Products();
            $errors = $products->validate($data);

            if (!empty($errors)) {
                $message = implode("<br>", $errors);
            } else {
                // sanitize
                $data = $model->sanitize($data);

                if (isset($_POST['update_product'])) {
                    $model->update($data);
                    $message = "Product updated successfully!";
                }
            }
        } else {
            http_response_code(404);
            $message = "Product not found";
        }
    }
}

if (isset($url_parts[2])) {
    $option = $url_parts[2];
}

if (!empty($url_parts[3])) {
    $resource_id = $url_parts[3];
} else {
    $resource_id = "";
}

if (empty($option)) {

    require("views/admin/dashboard.php");

} elseif ($option === "products") {
    $file = "models/products.php";
    if (file_exists($file)) {
        require($file);
        $className = 'Products';
        $model = new $className;
        $data = $model->get();
        $products = $data;
    } else {
        echo "Model file not found: " . $file;
    }
    require("views/admin/products.php");

} elseif ($option === "edit" && $resource_id !== "") {
    $file = "models/products.php";
    if (file_exists($file)) {
        require($file);
        $className = 'Products';
        $model = new $className;

        $selectedProduct = $model->getItem($resource_id);

        if ($selectedProduct !== null) {
            require_once("views/admin/edit.php");
        } else {
            http_response_code(404);
            die("Product not found");
        }
    } else {
        echo "Model file not found: " . $file;
    }
}