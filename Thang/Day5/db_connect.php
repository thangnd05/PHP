<?php
$servername = "sql307.infinityfree.com"; // MySQL Hostname
$username   = "if0_39690589";            // MySQL Username
$password   = "Thang100705";             // MySQL Password
$dbname     = "if0_39690589_school";     // Database name

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Nếu muốn timezone chuẩn VN
    $pdo->exec("SET time_zone = '+07:00'");
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
