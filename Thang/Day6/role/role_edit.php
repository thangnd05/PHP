<?php
require '../employee/employee.php';
$id = $_GET['id'] ?? 0;
$data = get_role($id)[0] ?? null;

if (!$data) header("Location: role_list.php");

if (!empty($_POST['edit_role'])) {
    $name = $_POST['name'] ?? '';
    if ($name != '') {
        global $conn;
        connect_db();
        try {
            $sql = "UPDATE EmployeeRoles SET role_name='$name' WHERE role_id=$id";
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
    <title>Sửa chức vụ</title>
</head>

<body>
    <h1>Sửa chức vụ</h1>
    <a href="role_list.php">Trở về</a><br /><br />
    <form method="post">
        Tên chức vụ: <input type="text" name="name" value="<?= $data['role_name'] ?>" />
        <?php if (!empty($error)) echo $error; ?>
        <br /><br />
        <input type="submit" name="edit_role" value="Lưu" />
    </form>
</body>

</html>