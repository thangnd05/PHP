<?php
require '../employee/employee.php';
if (!empty($_POST['add_role'])) {
    $name = $_POST['name'] ?? '';
    if ($name != '') {
        global $conn;
        connect_db();
        try {
            $sql = "INSERT INTO EmployeeRoles (role_name) VALUES ('$name')";
            $conn->exec($sql);
            header("Location: role_list.php");
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
        }
        disconnect_db();
    } else {
        $error = "Tên chức vụ không được để trống";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Thêm chức vụ</title>
</head>

<body>
    <h1>Thêm chức vụ</h1>
    <a href="role_list.php">Trở về</a><br /><br />
    <form method="post">
        Tên chức vụ: <input type="text" name="name" />
        <?php if (!empty($error)) echo $error; ?>
        <br /><br />
        <input type="submit" name="add_role" value="Lưu" />
    </form>
</body>

</html>