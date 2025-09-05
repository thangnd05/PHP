<?php
$servername = "sql307.infinityfree.com"; // MySQL Hostname
$username = "if0_39690589"; // MySQL Username
$password = "Thang100705"; // MySQL Password
$dbname = "if0_39690589_my_guest"; // TÃªn Database cá»§a báº¡n (thay XXX báº±ng tÃªn CSDL báº¡n ÄÃ£ táº¡o)

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
