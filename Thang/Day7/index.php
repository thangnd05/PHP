<?php
// index.php
// Ẩn hiển thị lỗi
error_reporting(0);
ini_set('display_errors', 0);

require_once 'auth.php';
check_auth();

require './employee/employee.php';

// --- Kiểm tra quyền cho các thao tác ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhân viên
    if (isset($_POST['add_employee'])) {
        if (!has_permission('add')) die("Bạn không có quyền thêm nhân viên");
        add_employee($_POST['first_name'], $_POST['last_name'], $_POST['department_id'], $_POST['role_id']);
    } elseif (isset($_POST['edit_employee'])) {
        if (!has_permission('edit')) die("Bạn không có quyền sửa nhân viên");
        edit_employee($_POST['employee_id'], $_POST['first_name'], $_POST['last_name'], $_POST['department_id'], $_POST['role_id']);
    } elseif (isset($_POST['delete_employee'])) {
        if (!has_permission('delete')) die("Bạn không có quyền xóa nhân viên");
        delete_employee($_POST['employee_id']);
    }

    // Phòng ban
    if (isset($_POST['add_department'])) {
        if (!has_permission('add')) die("Bạn không có quyền thêm phòng ban");
        add_department($_POST['department_name']);
    } elseif (isset($_POST['edit_department'])) {
        if (!has_permission('edit')) die("Bạn không có quyền sửa phòng ban");
        edit_department($_POST['department_id'], $_POST['department_name']);
    } elseif (isset($_POST['delete_department'])) {
        if (!has_permission('delete')) die("Bạn không có quyền xóa phòng ban");
        delete_department($_POST['department_id']);
    }

    // Chức vụ
    if (isset($_POST['add_role'])) {
        if (!has_permission('add')) die("Bạn không có quyền thêm chức vụ");
        add_role($_POST['role_name']);
    } elseif (isset($_POST['edit_role'])) {
        if (!has_permission('edit')) die("Bạn không có quyền sửa chức vụ");
        edit_role($_POST['role_id'], $_POST['role_name']);
    } elseif (isset($_POST['delete_role'])) {
        if (!has_permission('delete')) die("Bạn không có quyền xóa chức vụ");
        delete_role($_POST['role_id']);
    }
}

// --- Xử lý tìm kiếm ---
$search_first_name = $_GET['search_first_name'] ?? '';
$search_last_name = $_GET['search_last_name'] ?? '';
$search_department_id = $_GET['search_department_id'] ?? '';
$search_role_id = $_GET['search_role_id'] ?? '';

// Lấy dữ liệu
$employees = search_employees($search_first_name, $search_last_name, $search_department_id, $search_role_id);
$roles = get_all_role();
$departments = get_all_department();

disconnect_db();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Quản lý nhân sự</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            align-items: stretch;
        }

        nav {
            width: 200px;
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            min-height: 100vh;
            box-sizing: border-box;
        }

        nav a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            padding: 5px 10px;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #34495e;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table,
        th,
        td {
            border: 1px solid #999;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .form-container {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background: #f4f4f4;
        }

        input[type="text"],
        select {
            padding: 5px;
            margin: 0 5px 5px 0;
        }

        input[type="submit"] {
            padding: 5px 10px;
        }

        .user-info {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #34495e;
            color: #ecf0f1;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px;
            text-align: center;
            display: block;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .disabled-btn {
            background: #95a5a6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <nav>
        <h2>Menu</h2>
        <a href="#employees">Nhân viên</a>
        <a href="#departments">Phòng ban</a>
        <a href="#roles">Chức vụ</a>

        <div class="user-info">
            <p style="margin-bottom: 10px;">
                Xin chào,<br>
                <strong><?= htmlspecialchars($_SESSION['full_name'] ?? '') ?></strong><br>
                <small>(<?= $_SESSION['role_name'] ?? '' ?>)</small>
            </p>
            <a href="logout.php" class="logout-btn">Đăng xuất</a>
        </div>
    </nav>

    <div class="content">

        <!-- NHÂN VIÊN -->
        <h2 id="employees">Danh sách nhân viên</h2>

        <!-- Form tìm kiếm -->
        <div class="form-container">
            <h3>Tìm kiếm nhân viên</h3>
            <form method="get">
                First Name: <input type="text" name="search_first_name" value="<?= htmlspecialchars($search_first_name) ?>">
                Last Name: <input type="text" name="search_last_name" value="<?= htmlspecialchars($search_last_name) ?>">
                Department:
                <select name="search_department_id">
                    <option value="">--Tất cả--</option>
                    <?php foreach ($departments as $dep): ?>
                        <option value="<?= $dep['department_id'] ?>" <?= $search_department_id == $dep['department_id'] ? 'selected' : '' ?>><?= $dep['department_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                Role:
                <select name="search_role_id">
                    <option value="">--Tất cả--</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['role_id'] ?>" <?= $search_role_id == $role['role_id'] ? 'selected' : '' ?>><?= $role['role_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Tìm kiếm">
            </form>
        </div>

        <table>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Role</th>
                <th>Department</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <form method="post">
                        <td><input type="text" name="first_name" value="<?= htmlspecialchars($emp['first_name']) ?>"></td>
                        <td><input type="text" name="last_name" value="<?= htmlspecialchars($emp['last_name']) ?>"></td>
                        <td>
                            <select name="role_id">
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['role_id'] ?>" <?= $role['role_id'] == $emp['role_id'] ? 'selected' : '' ?>><?= htmlspecialchars($role['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="department_id">
                                <?php foreach ($departments as $dep): ?>
                                    <option value="<?= $dep['department_id'] ?>" <?= $dep['department_id'] == $emp['department_id'] ? 'selected' : '' ?>><?= htmlspecialchars($dep['department_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="employee_id" value="<?= $emp['employee_id'] ?>">
                            <input type="submit" name="edit_employee" value="Sửa" <?= !has_permission('edit') ? 'disabled class="disabled-btn"' : '' ?>>
                            <input type="submit" name="delete_employee" onclick="return confirm('Bạn có chắc muốn xóa không?');" value="Xóa" <?= !has_permission('delete') ? 'disabled class="disabled-btn"' : '' ?>>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Form thêm nhân viên -->
        <?php if (has_permission('add')): ?>
            <div class="form-container">
                <h3>Thêm nhân viên</h3>
                <form method="post">
                    First Name: <input type="text" name="first_name" required>
                    Last Name: <input type="text" name="last_name" required>
                    Department:
                    <select name="department_id">
                        <?php foreach ($departments as $dep): ?>
                            <option value="<?= $dep['department_id'] ?>"><?= htmlspecialchars($dep['department_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    Role:
                    <select name="role_id">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" name="add_employee" value="Thêm">
                </form>
            </div>
        <?php endif; ?>

        <!-- PHÒNG BAN -->
        <h2 id="departments">Danh sách phòng ban</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tên phòng ban</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($departments as $dep): ?>
                <tr>
                    <form method="post">
                        <td><?= $dep['department_id'] ?></td>
                        <td><input type="text" name="department_name" value="<?= htmlspecialchars($dep['department_name']) ?>"></td>
                        <td>
                            <input type="hidden" name="department_id" value="<?= $dep['department_id'] ?>">
                            <input type="submit" name="edit_department" value="Sửa" <?= !has_permission('edit') ? 'disabled class="disabled-btn"' : '' ?>>
                            <input type="submit" name="delete_department" onclick="return confirm('Bạn có chắc muốn xóa không?');" value="Xóa" <?= !has_permission('delete') ? 'disabled class="disabled-btn"' : '' ?>>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (has_permission('add')): ?>
            <div class="form-container">
                <h3>Thêm phòng ban</h3>
                <form method="post">
                    Tên phòng ban: <input type="text" name="department_name" required>
                    <input type="submit" name="add_department" value="Thêm">
                </form>
            </div>
        <?php endif; ?>

        <!-- CHỨC VỤ -->
        <h2 id="roles">Danh sách chức vụ</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tên chức vụ</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <form method="post">
                        <td><?= $role['role_id'] ?></td>
                        <td><input type="text" name="role_name" value="<?= htmlspecialchars($role['role_name']) ?>"></td>
                        <td>
                            <input type="hidden" name="role_id" value="<?= $role['role_id'] ?>">
                            <input type="submit" name="edit_role" value="Sửa" <?= !has_permission('edit') ? 'disabled class="disabled-btn"' : '' ?>>
                            <input type="submit" name="delete_role" onclick="return confirm('Bạn có chắc muốn xóa không?');" value="Xóa" <?= !has_permission('delete') ? 'disabled class="disabled-btn"' : '' ?>>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (has_permission('add')): ?>
            <div class="form-container">
                <h3>Thêm chức vụ</h3>
                <form method="post">
                    Tên chức vụ: <input type="text" name="role_name" required>
                    <input type="submit" name="add_role" value="Thêm">
                </form>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>