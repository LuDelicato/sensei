<?php

session_start();

$url_parts = explode("/", $_SERVER["REQUEST_URI"]);

define("ENV", parse_ini_file(".env"));

$controller = "home";


$allowed_controllers = [
    "home"
];

if (!empty($url_parts[1])) {
    $controller = $url_parts[1];
}

if (!empty($url_parts[2])) {
    $id = $url_parts[2];
}

if (!in_array($controller, $allowed_controllers)) {
    http_response_code(404);
    die("Não encontrado");
}

require("controllers/" . $controller . ".php");

require ("templates/navigation.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');


        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }

        .navbar {
            position: sticky;
            top: 0;
            background-color: #f2f2f2;
            padding: 10px 0;
            text-align: center;
            z-index: 100;
        }

        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .navbar ul li {
            margin: 0 10px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
        }

        .navbar ul li a:hover {
            color: #ff7200;
        }

        #welcome {
            text-align: center;
            padding: 0;
        }

        .message {
            font-family: 'Roboto', sans-serif;
            font-size: 24px;
        }
        .middle-section {
            text-align: center;
            padding: 80px 0;
            opacity: 65%;
        }
    </style>
    <title></title>
</head>
<body>
<div class="middle-section">
    <h2>Discover Our Latest Collection</h2>
    <p>Shop for high-quality products that suit your style.</p>
    <img src="images/banner.webp" alt="">

</div>
</body>
</html>
