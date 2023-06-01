<?php
header("Content-Type: application/json");
/*
http://localhost/api/products/123
                 ^        ^     ^
            controller option resource_id
*/
$allowed_options = ["products", "categories", "orders"];

if(isset($url_parts[2])) {
    $option = $url_parts[2];
}

if(!empty($url_parts[3])) {
    $resource_id = $url_parts[3];
}

if(empty($option)) {
    http_response_code(400);
    die('{"message":"Bad Request"}');
}
else if(!in_array($option, $allowed_options)) {
    http_response_code(404);
    die('{"message":"Not Found"}');
}

require("models/" .$option. ".php");

$className = ucwords($option);

$model = new $className();

if( $_SERVER["REQUEST_METHOD"] === "GET") {

    if(isset($resource_id)) {
        $response = $model->getItem($resource_id);
    }
    else {
        $response = $model->get();
    }
}
else if($_SERVER["REQUEST_METHOD"] === "POST") {

    $body = file_get_contents("php://input");
    $data = json_decode($body, true);

    $data = $model->sanitizer($data);

    http_response_code(202);
    $response = $model->create( $data );
}
else if($_SERVER["REQUEST_METHOD"] === "PUT") {
    $body = file_get_contents("php://input");
    $data = json_decode($body, true);

    $data["id"] = $resource_id;

    http_response_code(202);
    $response = $model->update( $data );
}
else if($_SERVER["REQUEST_METHOD"] === "DELETE") {

    http_response_code(202);

    $response = [
        "success" => $model->delete( $resource_id )
    ];
}

if(empty($response)) {
    http_response_code(404);
    die('{"message":"Not Found"}');
}

echo json_encode($response);