<?php
require 'employee.php';
//gọi hàm để lấy thông tin phòng ban, chức vụ
$employee = get_all_employees();
$role = get_all_role();
$department = get_all_department();

// Nếu người dùng submit form
if (!empty($_POST['add_employee'])) {
    // Lay data
    $data['firstname']        = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $data['lastname']         = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $data['role']    = isset($_POST['role']) ? $_POST['role'] : '';
    $data['department']    = isset($_POST['department']) ? $_POST['department'] : '';

    // Validate thong tin
    $errors = array();
    if (empty($data['firstname'])) {
        $errors['firstname'] = 'Chưa nhập họ đệm nhân viên';
    }

    if (empty($data['lastname'])) {
        $errors['lastname'] = 'Chưa nhập tên nhân viên';
    }
    $role_id1 = get_roleid($data['role']);
    // var_dump($role_id1);
    foreach ($role_id1 as $row) {
        $x = $row['role_id'];
    }
    $department_id1 = get_departmentid($data['department']);
    // var_dump($department_id1);
    foreach ($department_id1 as $row) {
        $y = $row['department_id'];
    }
    // Neu ko co loi thi insert
    if (!$errors) {
        add_employee($data['firstname'], $data['lastname'], $y, $x);

        // Trở về trang danh sách
        header("location: employee_list.php");
    }
}
disconnect_db();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Thêm nhân viên</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1>Thêm nhân viên </h1>
    <a href="employee_list.php">Trở về</a> <br /> <br />
    <form method="post" action="employee_add.php">
        <table width="50%" border="1" cellspacing="0" cellpadding="10">
            <tr>
                <td>First name</td>
                <td>
                    <input type="text" name="firstname" value="<?php echo !empty($data['firstname']) ? $data['firstname'] : ''; ?>" />
                    <?php if (!empty($errors['firstname'])) echo $errors['firstname']; ?>
                </td>
            </tr>
            <tr>
                <td>Last name</td>
                <td>
                    <input type="text" name="lastname" value="<?php echo !empty($data['lastname']) ? $data['lastname'] : ''; ?>" />
                    <?php if (!empty($errors['lastname'])) echo $errors['lastname']; ?>
                </td>
            </tr>
            <tr>
                <td>Role</td>
                <td>
                    <select name="role">
                        <?php foreach ($role as $item) { ?>
                            <option><?php echo $item['role_name']; ?> </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Department</td>
                <td>
                    <select name="department">
                        <?php foreach ($department as $item) { ?>
                            <option><?php echo $item['department_name']; ?> </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <td></td>
            <td>
                <input type="submit" name="add_employee" value="Lưu" />
            </td>
            </tr>
        </table>
    </form>
</body>

</html>