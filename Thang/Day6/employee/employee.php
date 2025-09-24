<?php
global $conn;
function connect_db()
{
  global $conn;
  $servername = "sql307.infinityfree.com"; // Host
  $username = "if0_39690589"; // Username
  $password = "Thang100705"; // Password
  $dbname = "if0_39690589_employee_db"; // Database

  // Tạo kết nối
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Kiểm tra kết nối
  if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
  }

  // Thiết lập charset UTF-8
  $conn->set_charset("utf8");
}

// Hàm ngắt kết nối
function disconnect_db()
{
  $conn = null;
}
// Hàm lấy tất cả nhân viên
function get_all_employees()
{
  global $conn;
  // Hàm kết nối
  connect_db();

  try {
    //khai báo exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Sử dụng Prepare 
    $stmt = $conn->prepare("SELECT Departments.department_id,EmployeeRoles.role_id, employees.employee_id, Employees.first_name, Employees.last_name, Departments.department_name, EmployeeRoles.role_name FROM Employees JOIN Departments ON Employees.department_id = Departments.department_id JOIN EmployeeRoles ON Employees.role_id = EmployeeRoles.role_id");

    // Thực thi câu truy vấn
    $stmt->execute();
    // Khai báo fetch kiểu mảng kết hợp
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // Lấy danh sách kết quả
    $result = $stmt->fetchAll();
    return $result;
  } catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
  }
}


// Hàm lấy nhân viên theo ID
function get_employees($employee_id)
{
  // Gọi tới biến toàn cục $conn
  global $conn;

  // Hàm kết nối
  connect_db();
  // Câu truy vấn lấy tất cả nhân viên
  $stmt = $conn->prepare("select * from employees where employee_id = {$employee_id}");
  // Thực thi câu truy vấn
  $stmt->execute();
  // Khai báo fetch kiểu mảng kết hợp
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // Lấy danh sách kết quả
  $result = $stmt->fetchAll();
  return $result;
  // var_dump($result);

}

// Hàm thêm nhân viên
function add_employee($employee_firstname, $employee_lastname, $employee_dep, $employee_role)
{
  // Gọi tới biến toàn cục $conn
  global $conn;

  // Hàm kết nối
  connect_db();

  // Chống SQL Injection
  // $employee_firstname= addslashes($employee_firstname);
  //$employee_lastname=nameaddslashes($employee_lastname);
  //$employee_role=addslashes($employee_role);
  //$employee_dep=addslashes($employee_dep);
  try {
    //khai báo exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Câu truy vấn thêm
    $sql = " INSERT INTO employees (first_name,last_name,department_id,role_id) VALUES
            ('$employee_firstname', '$employee_lastname',$employee_dep,$employee_role)";

    // Thực hiện câu truy vấn
    $conn->exec($sql);
    echo "Thêm dữ liệu thành công";
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}

// Hàm sửa nhân viên
function edit_employee($employee_id, $employee_firstname, $employee_lastname, $employee_dep, $employee_role)
{
  // Gọi tới biến toàn cục $conn
  global $conn;

  // Hàm kết nối
  connect_db();

  // Chống SQL Injection
  // $employee_firstname= addslashes($employee_firstname);
  //$employee_lastname=nameaddslashes($employee_lastname);
  //$employee_role=addslashes($employee_role);
  //$employee_dep=addslashes($employee_dep);
  try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Câu truy vấn sửa
    $sql = "
            UPDATE employees SET
            first_name = '$employee_firstname',
            last_name= '$employee_lastname',
            department_id = $employee_dep,
            role_id=$employee_role
            WHERE employee_id = $employee_id";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // execute the query
    $stmt->execute();
    // echo a message to say the UPDATE succeeded
    echo $stmt->rowCount() . " records UPDATED successfully";
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}

// Hàm xóa nhân viên
function delete_employee($employee_id)
{
  // Gọi tới biến toàn cục $conn
  global $conn;

  // Hàm kết nối
  connect_db();
  try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Câu SQL xóa
    $sql = " DELETE FROM employees WHERE employee_id = $employee_id ";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // execute the query
    $stmt->execute();
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}
//hàm xử lý với department và role
function get_role($role_id)
{
  // Gọi tới biến toàn cục $conn
  global $conn;

  // Hàm kết nối
  connect_db();
  // Câu truy vấn lấy thông tin role
  $stmt = $conn->prepare("select * from employeeroles where role_id = {$role_id}");
  // Thực thi câu truy vấn
  $stmt->execute();
  // Khai báo fetch kiểu mảng kết hợp
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // Lấy danh sách kết quả
  $result = $stmt->fetchAll();
  return $result;
}
//hàm lấy roleid khi biết role name
function get_roleid($role_name)
{
  // Gọi tới biến toàn cục $conn
  global $conn;

  // Hàm kết nối
  connect_db();
  // Câu truy vấn lấy thông tin chức vụ 
  $stmt = $conn->prepare("select * from employeeroles where role_name = '{$role_name}'");
  // Thực thi câu truy vấn
  $stmt->execute();
  // Khai báo fetch kiểu mảng kết hợp
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // Lấy danh sách kết quả
  $result = $stmt->fetchAll();
  return $result;
}
function get_all_role()
{
  global $conn;
  // Hàm kết nối
  connect_db();

  try {
    //khai báo exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Sử dụng Prepare 
    $stmt = $conn->prepare("SELECT * FROM Employeeroles");

    // Thực thi câu truy vấn
    $stmt->execute();
    // Khai báo fetch kiểu mảng kết hợp
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // Lấy danh sách kết quả
    $result = $stmt->fetchAll();
    return $result;
  } catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
  }
}
//hàm lấy danh sách department
function get_all_department()
{
  global $conn;
  // Hàm kết nối
  connect_db();

  try {
    //khai báo exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Sử dụng Prepare 
    $stmt = $conn->prepare("SELECT * FROM Departments");

    // Thực thi câu truy vấn
    $stmt->execute();
    // Khai báo fetch kiểu mảng kết hợp
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // Lấy danh sách kết quả
    $result = $stmt->fetchAll();
    return $result;
  } catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
  }
}

function get_department($department_id)
{
  // Gọi tới biến toàn cục $conn
  global $conn;

  // Hàm kết nối
  connect_db();
  // Câu truy vấn lấy tất cả sinh viên
  $stmt = $conn->prepare("select * from departments where department_id = {$department_id}");
  // Thực thi câu truy vấn
  $stmt->execute();
  // Khai báo fetch kiểu mảng kết hợp
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // Lấy danh sách kết quả
  $result = $stmt->fetchAll();
  return $result;
}
//hàm lấy departmentid khi biết department name
function get_departmentid($department_name)
{
  // Gọi tới biến toàn cục $conn
  global $conn;

  // Hàm kết nối
  connect_db();
  // Câu truy vấn lấy thông tin departments
  $stmt = $conn->prepare("select * from departments where department_name = '{$department_name}'");
  // Thực thi câu truy vấn
  $stmt->execute();
  // Khai báo fetch kiểu mảng kết hợp
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // Lấy danh sách kết quả
  $result = $stmt->fetchAll();
  //var_dump($result);
  return $result;
}

/* =================== CRUD cho Departments =================== */

// Thêm department
function add_department($department_name)
{
  global $conn;
  connect_db();
  try {
    $sql = "INSERT INTO Departments (department_name) VALUES ('$department_name')";
    $conn->exec($sql);
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}

// Sửa department
function edit_department($department_id, $department_name)
{
  global $conn;
  connect_db();
  try {
    $sql = "UPDATE Departments SET department_name='$department_name' WHERE department_id=$department_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}

// Xóa department
function delete_department($department_id)
{
  global $conn;
  connect_db();
  try {
    $sql = "DELETE FROM Departments WHERE department_id=$department_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}

/* =================== CRUD cho EmployeeRoles =================== */

// Thêm role
function add_role($role_name)
{
  global $conn;
  connect_db();
  try {
    $sql = "INSERT INTO EmployeeRoles (role_name) VALUES ('$role_name')";
    $conn->exec($sql);
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}

// Sửa role
function edit_role($role_id, $role_name)
{
  global $conn;
  connect_db();
  try {
    $sql = "UPDATE EmployeeRoles SET role_name='$role_name' WHERE role_id=$role_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}

// Xóa role
function delete_role($role_id)
{
  global $conn;
  connect_db();
  try {
    $sql = "DELETE FROM EmployeeRoles WHERE role_id=$role_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
}
