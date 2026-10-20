<?php
require_once 'config/database.php';

$sql = "ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL";

if ($conn->query($sql) === TRUE) {
    echo "Phone column added successfully!";
} else {
    echo "Error adding phone column: " . $conn->error;
}

$conn->close();
?>
