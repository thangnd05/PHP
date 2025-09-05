<?php
$servername = "sql307.infinityfree.com"; // MySQL Hostname
$username = "if0_39690589"; // MySQL Username
$password = "Thang100705"; // MySQL Password
$dbname = "if0_39690589_XXX"; // Tên Database của bạn (thay XXX bằng tên CSDL bạn đã tạo)

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
