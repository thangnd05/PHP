<?php
require 'employee.php';
$employee = get_all_employees();
$role=get_all_role();
$department=get_all_department();
 
// Lấy thông tin hiển thị lên để người dùng sửa
$id = isset($_GET['id']) ? (int)$_GET['id'] : '';
if ($id){
    $data = get_employees($id);
   // var_dump($data);
    //echo $data['employee_id'];
    foreach ($data as $row) {
    $emid= $row['employee_id'] ;
    $emfirstname=$row['first_name'] ;
    $emlastname=$row['last_name'];
    $emroleid=$row['role_id'];
    $emdepartmentid=$row['department_id'];
    }
    //echo $emroleid;
    //echo $emdepartmentid;
    //lấy tên role
    $role1=get_role($emroleid);
    foreach ($role1 as $row) {
    $rolename= $row['role_name'] ;
}
//lấy tên department
    $department1=get_department($emdepartmentid);
    foreach ($department1 as $row) {
    $departmentname= $row['department_name'] ;
}
}
// Nếu không có dữ liệu tức không tìm thấy nhân viên cần sửa
if (!$data){
   header("location: employee_list.php");
}
 
// Nếu người dùng submit form
if (!empty($_POST['edit_employee']))
{
    // Lay data
    $data['firstname']        = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $data['lastname']         = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $data['department']    = isset($_POST['department']) ? $_POST['department'] : '';
    $data['role']          = isset($_POST['role']) ? $_POST['role'] : '';
     $data['employee_id']          = isset($_POST['id']) ? $_POST['id'] : '';
     
    // Validate thong tin
    $errors = array();
    if (empty($data['firstname'])){
        $errors['firstname'] = 'Họ nhân viên không bỏ trống';
    }
     
    if (empty($data['lastname'])){
        $errors['lastname'] = 'Tên nhân viên không bỏ trống';
    }
     
    // Nếu không có lỗi thì cập nhật
   // if (!$errors){
    
    //trả về role id
    $emroleid1= get_roleid($data['role']);
    foreach ($emroleid1 as $row) {
    $emroleid= $row['role_id'] ;
}
//trả về department id
    $emdepartmentid1= get_departmentid($data['department']);
    foreach ($emdepartmentid1 as $row) {
    $emdepartmentid= $row['department_id'] ;
}
    edit_employee($data['employee_id'], $data['firstname'], $data['lastname'], $emdepartmentid,$emroleid );
      // Trở về trang danh sách
     header("location: employee_list.php");
    }
//}
 
disconnect_db();
?>
 
<!DOCTYPE html>
<html>
    <head>
        <title>Sửa thông tin nhân viên</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Sửa thông tin nhân viên </h1>
        <a href="employee_list.php">Trở về</a> <br/> <br/>
        <form method="post" action="employee_edit.php?id=<?php $emid ?>">
            <table width="50%" border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td>First name</td>
                    <td>
                        <input type="text" name="firstname" value="<?php echo $emfirstname; ?>"/>
                        <?php if (!empty($errors['firstname'])) echo $errors['firstname']; ?>
                    </td>
                </tr>
                <tr>
                    <td>Last name</td>
                    <td>
                        <input type="text" name="lastname" value="<?php echo $emlastname; ?>"/>
                        <?php if (!empty($errors['lastname'])) echo $errors['lastname']; ?>
                    </td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td>
                        <select name="role">
                             <option><?php echo $rolename; ?> </option>
                            <?php foreach ($role as $item){ ?>
                            <option><?php echo $item['role_name']; ?> </option>
                            
                        <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>
                        <select name="department">
                            <option><?php echo $departmentname; ?> </option>
                            <?php foreach ($department as $item){ ?>
                            <option><?php echo $item['department_name']; ?> </option>
                        
                        <?php } ?>
                        </select>
                    </td>
                </tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $emid; ?>"/>
                        <input type="submit" name="edit_employee" value="Lưu"/>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>