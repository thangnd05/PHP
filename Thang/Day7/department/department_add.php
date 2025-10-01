<?php
require '../employee/employee.php';
if (!empty($_POST['add_department'])) {
    $name = $_POST['name'] ?? '';
    if ($name != '') {
        global $conn;
        connect_db();
        try {
            $sql = "INSERT INTO Departments (department_name) VALUES ('$name')";
            $conn->exec($sql);
            header("Location: department_list.php");
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
        }
        disconnect_db();
    } else {
        $error = "Tên phòng ban không được để trống";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Thêm phòng ban</title>
</head>

<body>
    <h1>Thêm phòng ban</h1>
    <a href="department_list.php">Trở về</a><br /><br />
    <form method="post">
        Tên phòng ban: <input type="text" name="name" />
        <?php if (!empty($error)) echo $error; ?>
        <br /><br />
        <input type="submit" name="add_department" value="Lưu" />
    </form>
</body>

</html>