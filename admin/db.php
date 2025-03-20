<?php
$host = "sql310.infinityfree.com";
$db_name = "if0_38538242_nhom7";
$name = "if0_38538242";
$password = "tyuiop2004";

$conn = new mysqli($host, $name, $password, $db_name);

if ($conn->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
}
?>