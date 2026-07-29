<?php
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "smart_flat_finder";
$port = 3306;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
date_default_timezone_set("Asia/Kolkata");
?>