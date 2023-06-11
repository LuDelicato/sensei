<?php
require_once("models/products.php");
require_once("models/categories.php");
require_once("models/admin.php");
require_once("models/base.php");
require_once "models/countries.php";
require_once("models/users.php");
require_once("models/orders.php");

if (isset($url_parts[2])) {
    $option = $url_parts[2];
}

if (!empty($url_parts[3])) {
    $resource_id = $url_parts[3];
} else {
    $resource_id = "";
}

$model = new Products();
$products = $model->get();

$categoryModel = new Categories();
$categories = $categoryModel->get();

$userModel = new Users();
$user = $userModel->getUserById($resource_id);

$orderModel = new Orders();
$userOrders = $orderModel->getUserOrderById($resource_id);

$orderStatuses = $orderModel->getOrderStatuses();
$userOrders = $orderModel->getUserOrderById($resource_id);

/* stats */
$productCount = $model->getProductCount();
$orderCount = $orderModel->getOrderCount();
$userCount = $userModel->getUsersCount();
$productInventory = $model->getProductBelowStock();

$limit = 5;
$recentOrders = $orderModel->getRecentOrders($limit);

foreach ($userOrders as &$order) {
    $order['order_details'] = $orderModel->getOrderDetails($order['order_id']);
}

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
    // product create new
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
    // product delete(id)
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
    // edit user(id)
    if (isset($_POST['selected_user'])) {
        $selectedUserId = $_POST['selected_user'];
        $selectedUser = $userModel->getUserById($selectedUserId);

        if ($selectedUser !== null) {
            $data = [
                'user_id' => $selectedUser['user_id'],
                'name' => $_POST['name'] ?? $selectedUser['name'],
                'email' => $_POST['email'] ?? $selectedUser['email'],
                'address' => $_POST['address'] ?? $selectedUser['address'],
                'city' => $_POST['city'] ?? $selectedUser['city'],
                'postal_code' => $_POST['postal_code'] ?? $selectedUser['postal_code'],
                'country' => $_POST['country'] ?? $selectedUser['country'],
            ];

            // validate
            $errors = [];

            $usersModel = new Users();
            $countryCodes = $usersModel->getCountryCodes();

            if (!in_array($data['country'], $countryCodes)) {
                $errors[] = "Invalid country.";
            }

            if (empty($data['name'])) {
                $errors[] = "Name field is required.";
            } elseif (mb_strlen($data['name']) < 3 || mb_strlen($data['name']) > 60) {
                $errors[] = "Name must be between 3 and 60 characters.";
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email address.";
            }

            if (empty($data['address'])) {
                $errors[] = "Address field is required.";
            } elseif (mb_strlen($data['address']) < 10 || mb_strlen($data['address']) > 120) {
                $errors[] = "Address must be between 10 and 120 characters.";
            }

            if (empty($data['city'])) {
                $errors[] = "City field is required.";
            } elseif (mb_strlen($data['city']) < 3 || mb_strlen($data['city']) > 40) {
                $errors[] = "City must be between 3 and 40 characters.";
            }

            if (empty($data['postal_code'])) {
                $errors[] = "Postal code field is required.";
            } elseif (mb_strlen($data['postal_code']) < 4 || mb_strlen($data['postal_code']) > 20) {
                $errors[] = "Postal code must be between 4 and 20 characters.";
            }

            if (isset($_POST['status'])) {
                $status = ($_POST['status'] === '1') ? 1 : 0;
                $data['status'] = $status;
                $userModel->updateUserStatus($data);
                $data['isActive'] = $status;
            }

            // sanitize
            $data['name'] = $model->sanitize($data['name']);
            $data['email'] = $model->sanitize($data['email']);
            $data['address'] = $model->sanitize($data['address']);
            $data['city'] = $model->sanitize($data['city']);
            $data['postal_code'] = $model->sanitize($data['postal_code']);
            $data['country'] = $model->sanitize($data['country']);

            if (empty($errors)) {
                $userModel->updateUser($data);
                $message = "User updated successfully!";
            } else {
                $message = implode(' ', $errors);
            }
        } else {
            http_response_code(404);
            $message = "User not found";
        }
    }

    if (isset($_POST['selected_user'])) {
        $orderModel = new Orders();
        $userOrders = $orderModel->getUserOrders($resource_id);

        $data['userOrders'] = $userOrders;

        require("views/admin/userOrders.php");
    }

    if (isset($_POST['update_status'])) {

        $orderId = $_POST['order_id'];
        $statusId = $_POST['status'];

        $orders = new Orders();

        $data = [
            'order_id' => $orderId,
            'status' => $statusId
        ];

        //update
        $result = $orders->updateOrderStatus($data);

        if ($result) {
            echo "Order status updated. Please refresh the page to update the drop-down";
        } else {
            echo "Failed to update the order status.";
        }
    }
}
if (empty($option)) {

    require("views/admin/dashboard.php");

} else if ($option === "products") {
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

} else if ($option === "edit" && $resource_id !== "") {
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

    }
} else if ($option === "users") {
    $file = "models/users.php";
    if (file_exists($file)) {
        require ($file);
        $className = "Users";
        $model = new $className;
        $data = $model->get();
        $users = $data;
    } else {
        echo "Model file not found: " . $file;
    }
    require ("views/admin/users.php");
} else if ($option === "user" && !empty($url_parts[4]) && $url_parts[4] === "orders") {
    $resource_id = $url_parts[2];
    $file = "models/orders.php";
    if (file_exists($file)) {
        require($file);
        $className = "Orders";
        $model = new $className;
        $data = $model->getUserOrders($resource_id);

        require("views/admin/userOrders.php");
    }
} else if ($option ==="user") {
    $file = "models/users.php";
    if (file_exists($file)) {
        require ($file);
        $className = "Users";
        $model = new $className;
        $data = $model->getUserById($resource_id);
        $user = $data;
    } else {
        echo "Model file not found: " . $file;
    }
    $countriesModel = new Countries();
    $countries = $countriesModel->get();
    require ("views/admin/editUser.php");
}
else {
    http_response_code(404);
    die("Page not found");
}