<?php
require_once "studentCRUD.php";

// Xử lý thêm
if (isset($_POST['add'])) {
    addStudent($_POST['hoten'], $_POST['gioitinh'], $_POST['ngaysinh']);
    header("Location: index.php");
    exit;
}

// Xử lý sửa
if (isset($_POST['update'])) {
    updateStudent($_POST['id'], $_POST['hoten'], $_POST['gioitinh'], $_POST['ngaysinh']);
    header("Location: index.php");
    exit;
}

// Xử lý xóa
if (isset($_POST['delete'])) {
    deleteStudent($_POST['id']);
    header("Location: index.php");
    exit;
}

// Lấy danh sách sinh viên
$students = getAllStudents();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý sinh viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Danh sách sinh viên</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Thêm sinh viên</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?= $s['masinhvien'] ?></td>
                        <td><?= $s['hoten'] ?></td>
                        <td><?= $s['gioitinh'] ?></td>
                        <td><?= $s['ngaysinh'] ?></td>
                        <td>
                            <!-- Nút sửa -->
                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $s['masinhvien'] ?>">Sửa</button>
                            <!-- Nút xóa -->
                            <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal<?= $s['masinhvien'] ?>">Xóa</button>
                        </td>
                    </tr>

                    <!-- Modal sửa -->
                    <div class="modal fade" id="editModal<?= $s['masinhvien'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sửa sinh viên</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $s['masinhvien'] ?>">
                                        <div class="mb-3">
                                            <label>Họ tên</label>
                                            <input type="text" name="hoten" class="form-control" value="<?= $s['hoten'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Giới tính</label>
                                            <select name="gioitinh" class="form-control">
                                                <option value="Nam" <?= $s['gioitinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                                <option value="Nữ" <?= $s['gioitinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Ngày sinh</label>
                                            <input type="date" name="ngaysinh" class="form-control" value="<?= $s['ngaysinh'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update" class="btn btn-success">Lưu</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal xóa -->
                    <div class="modal fade" id="deleteModal<?= $s['masinhvien'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Xóa sinh viên</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc muốn xóa <b><?= $s['hoten'] ?></b> không?
                                        <input type="hidden" name="id" value="<?= $s['masinhvien'] ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="delete" class="btn btn-danger">Xóa</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal thêm sinh viên -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm sinh viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Họ tên</label>
                            <input type="text" name="hoten" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Giới tính</label>
                            <select name="gioitinh" class="form-control">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Ngày sinh</label>
                            <input type="date" name="ngaysinh" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add" class="btn btn-success">Lưu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>