<?php
require 'employee.php';
$employee = get_all_employees();
disconnect_db();
?>
 
<!DOCTYPE html>
<html>
    <head>
        <title>Danh sách nhân viên</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Danh sách nhân viên</h1>
        <a href="employee_add.php">Thêm nhân viên</a> <br/> <br/>
        <table width="100%" border="1" cellspacing="0" cellpadding="10">
            <tr>
                <td><b>First name</b></td>
                <td>Last name</td>
                <td>Role</td>
                <td>Departments</td>
                <td>Chọn thao tác</td>
            </tr>
            <?php foreach ($employee as $item){ ?>
            <tr>
                <td><?php echo $item['first_name']; ?></td>
                <td><?php echo $item['last_name']; ?></td>
                <td><?php echo $item['role_name']; ?></td>
                <td><?php echo $item['department_name']; ?></td>
                <td>
                    <form method="post" action="employee_delete.php">
                        <input onclick="window.location = 'employee_edit.php?id=<?php echo $item['employee_id']; ?>'" type="button" value="Sửa"/>
                        <input type="hidden" name="id" value="<?php echo $item['employee_id']; ?>"/>
                        <input onclick="return confirm('Bạn có chắc muốn xóa không?');" type="submit" name="delete" value="Xóa"/>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>