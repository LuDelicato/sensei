<?php
require_once("models/users.php");
require_once("models/orders.php");

$model = new Users();
$orders = new Orders();

if (!isset($_SESSION["user_id"])) {
    header("Location: /login");
    exit;
}

$user_id = $_SESSION["user_id"];
$user = $model->getUserById($user_id);

if (!$user) {
    die("User profile not found.");
    header("Location: /login");
}

$userOrders = $orders->getUserOrders($user_id);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        empty($_POST["name"]) &&
        empty($_POST["email"]) &&
        empty($_POST["address"]) &&
        empty($_POST["city"]) &&
        empty($_POST["postal_code"]) &&
        empty($_POST["country"])
    ) {
        $errors[] = "No fields to update.";
    } else {

        http_response_code(400);

        if (!empty($_POST["name"]) && (mb_strlen($_POST["name"]) < 3 || mb_strlen($_POST["name"]) > 60)) {
            $errors["name"] = "Name must be at least 3 characters.";
        }

        if (!empty($_POST["email"]) && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format.";
        }

        if (!empty($_POST["address"]) && (mb_strlen($_POST["address"]) < 10 || mb_strlen($_POST["address"]) > 120)) {
            $errors["address"] = "Address must be at least 10 characters.";
        }

        if (!empty($_POST["city"]) && (mb_strlen($_POST["city"]) < 3 || mb_strlen($_POST["city"]) > 40)) {
            $errors["city"] = "City must be at least 3 and at most 40 characters.";
        }

        if (!empty($_POST["postal_code"]) && (mb_strlen($_POST["postal_code"]) < 4 || mb_strlen($_POST["postal_code"]) > 20)) {
            $errors["postal_code"] = "Postal code must be at least 4 characters.";
        }

        if (empty($errors)) {

            http_response_code(200);
            $data = [
                "user_id" => $user_id,
                "name" => $_POST["name"],
                "email" => $_POST["email"],
                "address" => $_POST["address"],
                "city" => $_POST["city"],
                "postal_code" => $_POST["postal_code"],
            ];

            $result = $model->updateUser($data);

            if ($result) {
                header("Location: /profile");
                exit;
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["update_password"])) {

        $newPassword = $_POST["new_password"];
        $confirmPassword = $_POST["confirm_password"];

        if (empty($newPassword) || empty($confirmPassword)) {

            $errorMessageClass = "show";
            $errors[] = "Password cannot be blank.";

        } else if (strlen($newPassword) < 8) {

            $errorMessageClass = "show";
            $errors[] = "Password must be at least 8 characters long.";

        } else if ($newPassword !== $confirmPassword) {

            $passwordError = "Passwords do not match.";
        } else {

            $result = $model->updatePassword([
                "user_id" => $user_id,
                "password" => $newPassword
            ]);

            if ($result) {
                http_response_code(200);
                $successMessage = "Password successfully changed.";

            } else {
                http_response_code(400);
                $errorMessageClass = "show";
                $errors[] = "Failed to update the password.";
            }
        }
    }
}
require("views/profile.php");
