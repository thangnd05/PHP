<?php
// login.php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Ẩn hiển thị lỗi
error_reporting(0);
ini_set('display_errors', 0);

require_once './employee/employee.php';

function create_default_accounts()
{
    global $conn;

    try {
        connect_db();

        // Kiểm tra xem đã có tài khoản nào chưa
        $stmt = $conn->query("SELECT COUNT(*) as count FROM employee_accounts");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $account_count = $result['count'] ?? 0;

        if ($account_count == 0) {
            // Lấy employee đầu tiên để tạo tài khoản admin
            $stmt = $conn->query("SELECT employee_id FROM employees LIMIT 1");
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($employee) {
                // Tạo tài khoản admin mặc định
                $username = "admin";
                $password = "123456";
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $employee_id = $employee['employee_id'];
                $role_id = 1; // admin role

                $stmt = $conn->prepare("INSERT INTO employee_accounts (employee_id, username, password, role_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$employee_id, $username, $hashed_password, $role_id]);

                error_log("Đã tạo tài khoản admin mặc định: admin / 123456");
            }
        }

        disconnect_db();
    } catch (PDOException $e) {
        error_log("Lỗi khi tạo tài khoản mặc định: " . $e->getMessage());
    }
}

// Gọi hàm tạo tài khoản mặc định
create_default_accounts();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    require_once 'auth.php';

    if (login_user($username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Quản lý nhân sự</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #34495e;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        .default-account {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 style="text-align: center;">Đăng nhập hệ thống</h2>

        <!-- <div class="default-account">
            <strong>Tài khoản mặc định:</strong><br>
            Username: <strong>admin</strong><br>
            Password: <strong>123456</strong>
        </div> -->

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Tên đăng nhập:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>

</html>