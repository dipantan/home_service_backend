<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function sendJson($params, $message, $status)
{
    $data = new stdClass();
    $data->error = $status;
    $data->message = $message;
    $data->data = $params;
    return json_encode($data);
}

function is_valid_10_digit_number($number)
{
    // Regular expression for a 10-digit number
    $pattern = '/^\d{10}$/';

    return preg_match($pattern, $number);
}

function is_valid_6_char_alphanumeric_password($password)
{
    // Regular expression for a 6-character alphanumeric password
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/';

    return preg_match($pattern, $password);
}

function isAuthorized($token)
{
    // Set your secret key for token encoding
    $key = "7af31dd79c6ccc103cd31ed0beb3dcff207a981c8ba067e0903e1cee35e14c32";

    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return $decoded;
    } catch (Exception $error) {
        $data = new stdClass();
        $data->error = true;
        $data->message = $error->getMessage();
        echo json_encode($data);
        return false;
    }
}
