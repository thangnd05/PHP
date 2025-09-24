<?php
global $conn;

function connect_db()
{
  global $conn;
  $servername = "sql307.infinityfree.com";
  $username = "if0_39690589";
  $password = "Thang100705";
  $dbname = "if0_39690589_employee_db";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
  }
}

function disconnect_db()
{
  global $conn;
  $conn = null;
}

/* ================== EMPLOYEES ================== */
function get_all_employees()
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("
        SELECT e.employee_id, e.first_name, e.last_name, 
               d.department_id, d.department_name, 
               r.role_id, r.role_name
        FROM employees e
        JOIN departments d ON e.department_id = d.department_id
        JOIN employeeroles r ON e.role_id = r.role_id
    ");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_employee($employee_id)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = :id");
  $stmt->bindParam(':id', $employee_id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function add_employee($first_name, $last_name, $department_id, $role_id)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("INSERT INTO employees (first_name,last_name,department_id,role_id) VALUES (:fname,:lname,:dep,:role)");
  $stmt->bindParam(':fname', $first_name);
  $stmt->bindParam(':lname', $last_name);
  $stmt->bindParam(':dep', $department_id, PDO::PARAM_INT);
  $stmt->bindParam(':role', $role_id, PDO::PARAM_INT);
  $stmt->execute();
}

function edit_employee($employee_id, $first_name, $last_name, $department_id, $role_id)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("UPDATE employees SET first_name=:fname,last_name=:lname,department_id=:dep,role_id=:role WHERE employee_id=:id");
  $stmt->bindParam(':fname', $first_name);
  $stmt->bindParam(':lname', $last_name);
  $stmt->bindParam(':dep', $department_id, PDO::PARAM_INT);
  $stmt->bindParam(':role', $role_id, PDO::PARAM_INT);
  $stmt->bindParam(':id', $employee_id, PDO::PARAM_INT);
  $stmt->execute();
}

function delete_employee($employee_id)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id=:id");
  $stmt->bindParam(':id', $employee_id, PDO::PARAM_INT);
  $stmt->execute();
}

/* ================== DEPARTMENTS ================== */
function get_all_department()
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("SELECT * FROM departments");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_department($department_name)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("INSERT INTO departments (department_name) VALUES (:name)");
  $stmt->bindParam(':name', $department_name);
  $stmt->execute();
}

function edit_department($department_id, $department_name)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("UPDATE departments SET department_name=:name WHERE department_id=:id");
  $stmt->bindParam(':name', $department_name);
  $stmt->bindParam(':id', $department_id, PDO::PARAM_INT);
  $stmt->execute();
}

function delete_department($department_id)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("DELETE FROM departments WHERE department_id=:id");
  $stmt->bindParam(':id', $department_id, PDO::PARAM_INT);
  $stmt->execute();
}

/* ================== EMPLOYEEROLES ================== */
function get_all_role()
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("SELECT * FROM employeeroles");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_role($role_name)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("INSERT INTO employeeroles (role_name) VALUES (:name)");
  $stmt->bindParam(':name', $role_name);
  $stmt->execute();
}

function edit_role($role_id, $role_name)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("UPDATE employeeroles SET role_name=:name WHERE role_id=:id");
  $stmt->bindParam(':name', $role_name);
  $stmt->bindParam(':id', $role_id, PDO::PARAM_INT);
  $stmt->execute();
}

function delete_role($role_id)
{
  global $conn;
  connect_db();
  $stmt = $conn->prepare("DELETE FROM employeeroles WHERE role_id=:id");
  $stmt->bindParam(':id', $role_id, PDO::PARAM_INT);
  $stmt->execute();
}
// Hàm tìm kiếm nhân viên theo tất cả giá trị
function search_employees($first_name = '', $last_name = '', $department_id = '', $role_id = '')
{
  global $conn;
  connect_db();

  $sql = "SELECT e.employee_id, e.first_name, e.last_name, 
                   d.department_id, d.department_name, 
                   r.role_id, r.role_name
            FROM employees e
            JOIN departments d ON e.department_id = d.department_id
            JOIN employeeroles r ON e.role_id = r.role_id
            WHERE 1"; // 1 để dễ nối AND

  $params = [];

  if ($first_name !== '') {
    $sql .= " AND e.first_name LIKE :first_name";
    $params[':first_name'] = "%$first_name%";
  }
  if ($last_name !== '') {
    $sql .= " AND e.last_name LIKE :last_name";
    $params[':last_name'] = "%$last_name%";
  }
  if ($department_id !== '') {
    $sql .= " AND e.department_id = :department_id";
    $params[':department_id'] = $department_id;
  }
  if ($role_id !== '') {
    $sql .= " AND e.role_id = :role_id";
    $params[':role_id'] = $role_id;
  }

  try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
  } catch (PDOException $e) {
    echo "Lỗi tìm kiếm: " . $e->getMessage();
    return [];
  }
}
