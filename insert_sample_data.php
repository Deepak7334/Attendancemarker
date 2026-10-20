<?php
require_once 'config/database.php';

// Insert sample admin user
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, password, email, role) VALUES ('admin', '$admin_password', 'admin@example.com', 'admin')";
$conn->query($sql);

// Insert sample employee users
$employee_password = password_hash('employee123', PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, password, email, role) VALUES ('employee1', '$employee_password', 'employee1@example.com', 'employee')";
$conn->query($sql);

$sql = "INSERT INTO users (username, password, email, role) VALUES ('employee2', '$employee_password', 'employee2@example.com', 'employee')";
$conn->query($sql);

echo "Sample data inserted successfully!";
?>
