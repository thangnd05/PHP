<?php require 'employee.php';
 
// Thực hiện xóa
$id = isset($_POST['id']) ? (int)$_POST['id'] : '';
if ($id){
    delete_employee($id);
}
 
// Trở về trang danh sách
header("location: employee_list.php");
?>
