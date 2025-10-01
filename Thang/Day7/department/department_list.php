<?php
require '../employee/employee.php';
$departments = get_all_department();
disconnect_db();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Danh sách phòng ban</title>
</head>

<body>
    <h1>Danh sách phòng ban</h1>
    <a href="department_add.php">Thêm phòng ban</a><br /><br />
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tên phòng ban</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($departments as $dep): ?>
            <tr>
                <td><?= $dep['department_id'] ?></td>
                <td><?= $dep['department_name'] ?></td>
                <td>
                    <a href="department_edit.php?id=<?= $dep['department_id'] ?>">Sửa</a> |
                    <form method="post" action="department_delete.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $dep['department_id'] ?>">
                        <input type="submit" onclick="return confirm('Bạn có chắc muốn xóa không?');" value="Xóa">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>