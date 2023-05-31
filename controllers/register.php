<?php

require("models/countries.php");
$modelCountries = new Countries();

$countries = $modelCountries->get();

$country_codes = [];
foreach ($countries as $country) {
    $country_codes[] = $country["code"];
}

if (isset($_POST["send"])) {

    foreach ($_POST as $key => $value) {
        $_POST[$key] = trim(htmlspecialchars(strip_tags($value)));
    }

    if (
        mb_strlen($_POST["name"]) >= 3 &&
        mb_strlen($_POST["name"]) <= 60 &&
        mb_strlen($_POST["address"]) >= 10 &&
        mb_strlen($_POST["address"]) <= 120 &&
        mb_strlen($_POST["city"]) >= 3 &&
        mb_strlen($_POST["city"]) <= 40 &&
        mb_strlen($_POST["postal_code"]) >= 4 &&
        mb_strlen($_POST["postal_code"]) <= 20 &&
        mb_strlen($_POST["password"]) >= 8 &&
        mb_strlen($_POST["password"]) <= 1000 &&
        filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) &&
        $_POST["password"] === $_POST["repeat_password"] &&
        in_array($_POST["country"], $country_codes) &&
        isset($_POST["agrees"])
    ) {
        $message = "Account created!";

        require("models/users.php");

        $modelUsers = new Users();

        $user_id = $modelUsers->create($_POST);

        if (!empty($user_id)) {
            $_SESSION["user_id"] = $user_id;
            header("Location: /");
        } else {
            $message = "E-mail already in use";
        }
    } else {
        $message = "Invalid data";
    }
}

require("views/register.php");