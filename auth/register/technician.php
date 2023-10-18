<?php
include_once "../../vendor/autoload.php";
include_once "../../db/index.php";
include_once "../../utils/index.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents('php://input'));

$name = mysqli_real_escape_string($conn, $data->name);
$email = mysqli_real_escape_string($conn, $data->email);
$phone = mysqli_real_escape_string($conn, $data->phone);
$category = mysqli_real_escape_string($conn, $data->category);
$speciality = mysqli_real_escape_string($conn, $data->speciality);
$location = mysqli_real_escape_string($conn, $data->location);
$image = mysqli_real_escape_string($conn, $data->image);
$password = mysqli_real_escape_string($conn, $data->password);

// Set your secret key for token encoding
$key = "7af31dd79c6ccc103cd31ed0beb3dcff207a981c8ba067e0903e1cee35e14c32";

function sendError($message): string
{
    $data = new stdClass();
    $data->error = true;
    $data->message = $message;
    return json_encode($data);
}

if (empty($name)) {
    echo sendError("Please provide name");
    return;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo sendError("Please provide valid email");
    return;
}

if (empty($phone) || !is_valid_10_digit_number($phone)) {
    echo sendError("Please provide valid phone number");
    return;
}

if (empty($password) || !is_valid_6_char_alphanumeric_password($password)) {
    echo sendError("Please provide valid password (atleast 6 digit, alphanumeric)");
    return;
}

$categories = json_decode(dbQuery("select name from services"));

if (empty($category) || !array_search($category, $categories->data, true)) {
    // echo "not found";
} else {
    // echo "found";
}

print_r($categories);

// echo json_encode($categories);

if (empty($category)) {
}



// $password = password_hash($password, PASSWORD_DEFAULT);

// $result = json_decode(dbQuery(
//     "insert into users (`name`,`email`,`password`,`phone`,`type`)
//         values
//     ('$name','$email','$password','$phone','user')"
// ));

// if ($result->error) {
//     echo json_encode($result);
//     return;
// }

// $data = new stdClass();
// $data->name =  $name;
// $data->email =  $email;
// $data->phone =  $phone;
// $data->type =  'user';

// $tokenPayload = [
//     "data" => $data,
//     "exp" => time() + 7776000, // Token expires in 90 days
// ];

// $jwt = JWT::encode($tokenPayload, $key, 'HS256');

// $result->data = ["user" => $data, "token" => $jwt];

// echo json_encode($result);
