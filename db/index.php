<?php
error_reporting(E_ALL ^ E_WARNING);
include_once __DIR__ . '../utils/index.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "home_service";
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("mysqli errro: " . mysqli_connect_error());
}

function dbQuery($sql)
{
    global $conn;
    try {
        $mysqli_result = mysqli_query($conn, $sql);
        if ($mysqli_result) {
            if (!$mysqli_result == true) {
                if (mysqli_num_rows($mysqli_result) == 0) {
                    return sendJson(null, "No data found", true);
                }
            }
            return sendJson($mysqli_result === true ? null : mysqli_fetch_all($mysqli_result, MYSQLI_ASSOC), "Success", false);
        } else {
            return sendJson(null, mysqli_error($conn), true);
        }
    } catch (Exception $error) {
        return sendJson(null, $error->getMessage(), true);
    } finally {
        mysqli_close($conn);
    }
}
