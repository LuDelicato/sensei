<?php

if (isset($_POST["send"])) {

    foreach ($_POST as $key => $value) {
        $_POST[$key] = trim(htmlspecialchars(strip_tags($value)));
    }

    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) &&
        mb_strlen($_POST["password"]) >= 8 &&
        mb_strlen($_POST["password"]) <= 1000
    ) {
        require("models/users.php");
        $model = new Users();
        $user = $model->getUserFromEmail($_POST["email"]);

        if (!empty($user) && password_verify($_POST["password"], $user["password"])) {
            if ($user["isActive"] === 1) {
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["user_name"] = $user["name"];
                header("Location: /cart/");
            } else {
                $message = "Your account is inactive. Please contact support.";
            }
        } else {
            $message = "Invalid email or password";
        }
    }
}

require("views/login.php");
