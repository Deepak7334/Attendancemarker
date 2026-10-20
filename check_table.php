<?php
require_once 'config/database.php';

$conn->select_db('attendance_system');

$result = $conn->query("DESCRIBE attendance");
if ($result) {
    echo "Attendance table structure:\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Table does not exist or error: " . $conn->error;
}

$conn->close();
?>
