<?php
require '../employee/employee.php';
$roles = get_all_role();
disconnect_db();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Danh sách chức vụ</title>
</head>

<body>
    <h1>Danh sách chức vụ</h1>
    <a href="role_add.php">Thêm chức vụ</a><br /><br />
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tên chức vụ</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($roles as $role): ?>
            <tr>
                <td><?= $role['role_id'] ?></td>
                <td><?= $role['role_name'] ?></td>
                <td>
                    <a href="role_edit.php?id=<?= $role['role_id'] ?>">Sửa</a> |
                    <form method="post" action="role_delete.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $role['role_id'] ?>">
                        <input type="submit" onclick="return confirm('Bạn có chắc muốn xóa không?');" value="Xóa">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>