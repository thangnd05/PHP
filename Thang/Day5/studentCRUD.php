<?php
require_once "db_connect.php";

// Lấy danh sách sinh viên
function getAllStudents()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM student");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Thêm sinh viên
function addStudent($hoten, $gioitinh, $ngaysinh)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO student(hoten, gioitinh, ngaysinh) VALUES (?, ?, ?)");
    return $stmt->execute([$hoten, $gioitinh, $ngaysinh]);
}

// Cập nhật sinh viên
function updateStudent($id, $hoten, $gioitinh, $ngaysinh)
{
    global $pdo;
    $sql = "UPDATE student 
            SET hoten = :hoten, gioitinh = :gioitinh, ngaysinh = :ngaysinh 
            WHERE masinhvien = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':hoten' => $hoten,
        ':gioitinh' => $gioitinh,
        ':ngaysinh' => $ngaysinh,
        ':id' => $id
    ]);
}

// Xóa sinh viên
function deleteStudent($id)
{
    global $pdo;
    $sql = "DELETE FROM student WHERE masinhvien = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
