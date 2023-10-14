<?php
include_once "../vendor/autoload.php";
include "../utils/index.php";
include "../db/index.php";

$token = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);

$result = isAuthorized($token);

if (isset($token) && $result) {
    $email = $result->data->email;
    $sql = "select * from users where email='$email'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(["error" => false, "message" => "Success", "data" => $row]);
    } else {
        echo json_encode(["error" => true, "message" => mysqli_error($conn), "data" => null]);
    }
}
