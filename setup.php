<?php
require_once 'config/database.php';

echo "Starting database setup...<br><br>";

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS attendance_system";
if ($conn->query($sql) === TRUE) {
    echo "Database 'attendance_system' created or already exists.<br><br>";
} else {
    echo "Error creating database: " . $conn->error . "<br><br>";
}

// Select database
$conn->select_db('attendance_system');

// Create users table
$usersTable = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(20) UNIQUE,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'employee') DEFAULT 'employee',
    department_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
)";

if ($conn->query($usersTable) === TRUE) {
    echo "Users table created successfully.<br><br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br><br>";
}

// Add missing columns to users table if they don't exist
$alterUsersTable = "ALTER TABLE users
    ADD COLUMN IF NOT EXISTS employee_id VARCHAR(20) UNIQUE,
    ADD COLUMN IF NOT EXISTS department_id INT,
    ADD CONSTRAINT fk_department_id FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL";

if ($conn->query($alterUsersTable) === TRUE) {
    echo "Users table altered successfully.<br><br>";
} else {
    echo "Error altering users table: " . $conn->error . "<br><br>";
}

// Create departments table
$departmentsTable = "
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($departmentsTable) === TRUE) {
    echo "Departments table created successfully.<br><br>";
} else {
    echo "Error creating departments table: " . $conn->error . "<br><br>";
}

// Create attendance table
$attendanceTable = "
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    date DATE,
    check_in TIME,
    check_out TIME,
    status VARCHAR(20)
)";

if ($conn->query($attendanceTable) === TRUE) {
    echo "Attendance table created successfully.<br><br>";
} else {
    echo "Error creating attendance table: " . $conn->error . "<br><br>";
}

// Insert sample admin user
$adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
$insertAdmin = "INSERT IGNORE INTO users (username, password, email, role) VALUES ('admin', '$adminPassword', 'admin@example.com', 'admin')";

if ($conn->query($insertAdmin) === TRUE) {
    echo "Admin user inserted successfully.<br><br>";
} else {
    echo "Error inserting admin user: " . $conn->error . "<br><br>";
}

// Insert sample employee user
$employeePassword = password_hash('employee123', PASSWORD_DEFAULT);
$insertEmployee = "INSERT IGNORE INTO users (username, password, email, role) VALUES ('employee1', '$employeePassword', 'employee1@example.com', 'employee')";

if ($conn->query($insertEmployee) === TRUE) {
    echo "Employee user inserted successfully.<br><br>";
} else {
    echo "Error inserting employee user: " . $conn->error . "<br><br>";
}

echo "Setup completed successfully!<br><br>";
echo "You can now access the attendance management system at: http://localhost/Attendance<br>";
echo "Login credentials:<br>";
echo "- Admin: username 'admin', password 'admin123'<br>";
echo "- Employee: username 'employee1', password 'employee123'<br>";

$conn->close();
?>
