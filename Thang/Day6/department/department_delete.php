<?php
require '../employee/employee.php';
$id = $_POST['id'] ?? 0;
if ($id) {
    global $conn;
    connect_db();
    try {
        $sql = "DELETE FROM Departments WHERE department_id=$id";
        $conn->exec($sql);
    } catch (PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
    }
    disconnect_db();
}
header("Location: department_list.php");
