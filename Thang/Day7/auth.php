<?php
// auth.php
session_start();

function check_auth()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
    return true;
}

function check_role($required_role)
{
    if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != $required_role) {
        die("Bạn không có quyền truy cập trang này");
    }
}

function login_user($username, $password)
{
    require_once './employee/employee.php';

    try {
        connect_db();
        global $conn;

        $stmt = $conn->prepare("
            SELECT ea.*, e.first_name, e.last_name, r.role_name 
            FROM employee_accounts ea
            JOIN employees e ON ea.employee_id = e.employee_id
            JOIN employeeroles r ON ea.role_id = r.role_id
            WHERE ea.username = ?
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['employee_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['role_name'] = $user['role_name'];
            $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
            disconnect_db();
            return true;
        }
        disconnect_db();
        return false;
    } catch (PDOException $e) {
        if (isset($conn)) disconnect_db();
        return false;
    }
}

function logout_user()
{
    session_destroy();
    header('Location: login.php');
    exit();
}

function has_permission($required_permission)
{
    $user_role_id = $_SESSION['role_id'] ?? '';
    $role_permissions = [
        1 => ['view', 'add', 'edit', 'delete', 'manage_users'], // admin
        2 => ['view', 'add', 'edit'], // manager
        3 => ['view'] // user
    ];

    return isset($role_permissions[$user_role_id]) &&
        in_array($required_permission, $role_permissions[$user_role_id]);
}

function get_current_user_info()
{
    return [
        'user_id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'role_id' => $_SESSION['role_id'] ?? null,
        'role_name' => $_SESSION['role_name'] ?? null,
        'full_name' => $_SESSION['full_name'] ?? null
    ];
}
