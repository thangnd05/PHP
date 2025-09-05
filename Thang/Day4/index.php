<?php
include 'db_connect.php';

// Xử lý các yêu cầu: Thêm, Sửa, Xóa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Thêm nhân viên
    if (isset($_POST['add'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $reg_date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO nhanvien (Firstname, Lastname, Reg_Date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $firstname, $lastname, $reg_date);
        $stmt->execute();
        $stmt->close();
    }
    // Sửa nhân viên
    elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $sql = "UPDATE nhanvien SET Firstname=?, Lastname=? WHERE Id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $firstname, $lastname, $id);
        $stmt->execute();
        $stmt->close();
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM nhanvien WHERE Id=$id";
    $conn->query($sql_delete);
}

$sql = "SELECT * FROM nhanvien";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Nhân Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-links a {
            margin-right: 10px;
            text-decoration: none;
            color: blue;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        form.inline-form {
            display: inline;
        }

        input[type="text"] {
            padding: 5px;
        }

        .btn-add {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-edit,
        .btn-update {
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        .btn-cancel {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
    </style>
    <script>
        function showEditForm(id, firstname, lastname) {
            document.getElementById('display-row-' + id).style.display = 'none';
            document.getElementById('edit-row-' + id).style.display = 'table-row';
            document.getElementById('edit-firstname-' + id).value = firstname;
            document.getElementById('edit-lastname-' + id).value = lastname;
        }

        function cancelEdit(id) {
            document.getElementById('display-row-' + id).style.display = 'table-row';
            document.getElementById('edit-row-' + id).style.display = 'none';
        }
    </script>
</head>

<body>

    <h2>Quản Lý Nhân Viên</h2>

    <h3>Thêm Nhân Viên Mới</h3>
    <form action="" method="post">
        <input type="text" name="firstname" placeholder="Firstname" required>
        <input type="text" name="lastname" placeholder="Lastname" required>
        <button type="submit" name="add" class="btn-add">Thêm</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Reg_Date</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Hàng hiển thị
                    echo "<tr id='display-row-" . $row['Id'] . "'>";
                    echo "<td>" . $row['Id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['Firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Lastname']) . "</td>";
                    echo "<td>" . $row['Reg_Date'] . "</td>";
                    echo "<td class='action-links'>";
                    echo "<a href='#' onclick='showEditForm(" . $row['Id'] . ", \"" . htmlspecialchars($row['Firstname']) . "\", \"" . htmlspecialchars($row['Lastname']) . "\")' class='btn-edit'>Sửa</a> | ";
                    echo "<a href='?delete_id=" . $row['Id'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa không?\")'>Xóa</a>";
                    echo "</td>";
                    echo "</tr>";

                    // Hàng form sửa
                    echo "<tr id='edit-row-" . $row['Id'] . "' style='display:none;'>";
                    echo "<form action='' method='post' class='inline-form'>";
                    echo "<td>" . $row['Id'] . "</td>";
                    echo "<td><input type='text' name='firstname' id='edit-firstname-" . $row['Id'] . "'></td>";
                    echo "<td><input type='text' name='lastname' id='edit-lastname-" . $row['Id'] . "'></td>";
                    echo "<td>" . $row['Reg_Date'] . "</td>";
                    echo "<td>";
                    echo "<input type='hidden' name='id' value='" . $row['Id'] . "'>";
                    echo "<button type='submit' name='update' class='btn-update'>Cập Nhật</button> ";
                    echo "<button type='button' onclick='cancelEdit(" . $row['Id'] . ")' class='btn-cancel'>Hủy</button>";
                    echo "</td>";
                    echo "</form>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Không có nhân viên nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>

</html>