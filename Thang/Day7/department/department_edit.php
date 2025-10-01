<?php
require '../employee/employee.php';
$id = $_GET['id'] ?? 0;
$data = get_department($id)[0] ?? null;

if (!$data) header("Location: department_list.php");

if (!empty($_POST['edit_department'])) {
    $name = $_POST['name'] ?? '';
    if ($name != '') {
        global $conn;
        connect_db();
        try {
            $sql = "UPDATE Departments SET department_name='$name' WHERE department_id=$id";
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
    <title>Sửa phòng ban</title>
</head>

<body>
    <h1>Sửa phòng ban</h1>
    <a href="department_list.php">Trở về</a><br /><br />
    <form method="post">
        Tên phòng ban: <input type="text" name="name" value="<?= $data['department_name'] ?>" />
        <?php if (!empty($error)) echo $error; ?>
        <br /><br />
        <input type="submit" name="edit_department" value="Lưu" />
    </form>
</body>

</html>