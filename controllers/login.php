<?php

use ReallySimpleJWT\Token;
require("vendor/autoload.php");

if(isset($_POST["send"])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = trim(htmlspecialchars(strip_tags($value)));
    }

    if(
        filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) &&
        mb_strlen($_POST["password"]) >= 8 &&
        mb_strlen($_POST["password"]) <= 1000
    ) {
        require("models/users.php");
        $model = new Users();
        $user = $model->getUserFromEmail( $_POST["email"] );

        if(
            !empty($user) &&
            password_verify($_POST["password"], $user["password"])
        ) {
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["user_name"] = $user["name"];
            header("Location: /");
        }
        else {
            $message = "Invalid email or password";
        }
    }
    else {
        $message = "Invalid inputs";
    }
}
elseif($_SERVER["REQUEST_METHOD"] === "POST") {

    $rawData = file_get_contents("php://input");
    if(!empty($rawData)) {
        $data = json_decode($rawData);

        if(empty($data) || !isset($data->email) ) {
            http_response_code(400);
            exit;
        }

        require("models/users.php");
        $model = new Users();
        $user = $model->getUserFromEmail( $data->email );

        if(
            !empty($user) &&
            password_verify($data->password, $user["password"])
        ) {

            $payload = [
                "iat" => time(),
                "user_id" => $user["user_id"],
                "name" => $user["name"],
                "exp" => time() + (60 * 60 * 24),
                "iss" => "localhost"
            ];

            $token = Token::customPayload($payload, ENV["JWT_SECRET_KEY"] );

            header("Authorization: Bearer " . $token);
            die("Authorization: Bearer " . $token);
        }
        else {
            http_response_code(422);
            exit;
        }
    }

}

require("views/login.php");
