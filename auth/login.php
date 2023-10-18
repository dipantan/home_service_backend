<?php

require_once('../vendor/autoload.php');
include "../db/index.php";
include "../utils/index.php";

use Firebase\JWT\JWT;

// Set your secret key for token encoding
$key = "7af31dd79c6ccc103cd31ed0beb3dcff207a981c8ba067e0903e1cee35e14c32";

$data = json_decode(file_get_contents('php://input'));

$username = mysqli_real_escape_string($conn, $data->username);
$password = mysqli_real_escape_string($conn, $data->password);

$user = dbQuery("select * from users where email='$username'");

$user_decoded = json_decode($user);
$hashed_password = $user_decoded->data[0]->password;

if (!isset($hashed_password)) {
    // Authentication failed
    http_response_code(401); // Unauthorized
    echo sendJson(null, "User not found", true);
    return;
}

if (!password_verify($password, $hashed_password)) {
    // Authentication failed
    http_response_code(401); // Unauthorized
    echo sendJson(null, "Password not matched", true);
    return;
}

// Authentication successful
$data = new stdClass();
$data->name =  $user_decoded->data[0]->name;
$data->email =  $user_decoded->data[0]->email;
$data->phone =  $user_decoded->data[0]->phone;
$data->type =  $user_decoded->data[0]->type;

$tokenPayload = [
    "data" => $data,
    "exp" => time() + 7776000, // Token expires in 90 days
];

$jwt = JWT::encode($tokenPayload, $key, 'HS256');

// Return the JWT token as a JSON response
$data = ["user" => $data, "token" => $jwt];

echo sendJson($data, "Success", false);
