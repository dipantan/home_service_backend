<?php
require_once('../vendor/autoload.php');
include "../db/index.php";
include "../utils/index.php";

use Firebase\JWT\JWT;

// Set your secret key for token encoding
$key = "7af31dd79c6ccc103cd31ed0beb3dcff207a981c8ba067e0903e1cee35e14c32";

$data = json_decode(file_get_contents('php://input'));

$email = $data->email;

if (!isset($email) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" => true, "message" => "Provice a valid email"]);
    return;
}

$sql = "select email, phone, name, type from users where email='$email'";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $tokenPayload = [
            "data" => $data,
            "exp" => time() + 7776000, // Token expires in 90 days
        ];
        $jwt = JWT::encode($tokenPayload, $key, 'HS256');
        $data = ["user" => $data, "token" => $jwt];
        echo sendJson($data, "Success", false);
    } else {
        echo json_encode(["error" => true, "message" => "User not found"]);
    }
}
