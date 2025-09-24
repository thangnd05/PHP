<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tạo Database & Tables</title>
</head>

<body>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<input type="submit" name="cr_db" value="create_db">
	</form>

	<?php
	$servername = "localhost";
	$username = "root";
	$password = "Thang100705";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		try {
			// Kết nối MySQL
			$conn = new PDO("mysql:host=$servername", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Tạo database
			$conn->exec("CREATE DATABASE IF NOT EXISTS employee_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
			echo "Database created successfully<br>";

			// Chọn database vừa tạo
			$conn->exec("USE employee_db");

			// Tạo bảng Departments
			$conn->exec("
                CREATE TABLE IF NOT EXISTS Departments (
                    department_id INT AUTO_INCREMENT PRIMARY KEY,
                    department_name VARCHAR(100) NOT NULL
                )
            ");

			// Tạo bảng EmployeeRoles
			$conn->exec("
                CREATE TABLE IF NOT EXISTS EmployeeRoles (
                    role_id INT AUTO_INCREMENT PRIMARY KEY,
                    role_name VARCHAR(100) NOT NULL
                )
            ");

			// Tạo bảng Employees
			$conn->exec("
                CREATE TABLE IF NOT EXISTS Employees (
                    employee_id INT AUTO_INCREMENT PRIMARY KEY,
                    first_name VARCHAR(100) NOT NULL,
                    last_name VARCHAR(100) NOT NULL,
                    department_id INT,
                    role_id INT,
                    FOREIGN KEY (department_id) REFERENCES Departments(department_id),
                    FOREIGN KEY (role_id) REFERENCES EmployeeRoles(role_id)
                )
            ");

			echo "Tables created successfully";
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}

		// Đóng kết nối
		$conn = null;
	}
	?>
</body>

</html>