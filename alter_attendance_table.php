<?php
require_once 'config/database.php';

echo "Altering attendance table to use check_in and check_out...<br><br>";

// Select database
$conn->select_db('attendance_system');

// Check if table exists and has old columns
$result = $conn->query("DESCRIBE attendance");
$columns = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
}

// If table has time_in and time_out, rename them
if (in_array('time_in', $columns) && in_array('time_out', $columns)) {
    $alterQuery = "ALTER TABLE attendance
        CHANGE time_in check_in TIME,
        CHANGE time_out check_out TIME";

    if ($conn->query($alterQuery) === TRUE) {
        echo "Attendance table altered successfully. Columns renamed to check_in and check_out.<br><br>";
    } else {
        echo "Error altering table: " . $conn->error . "<br><br>";
    }
} elseif (in_array('check_in', $columns) && in_array('check_out', $columns)) {
    echo "Table already has check_in and check_out columns.<br><br>";
} else {
    echo "Table structure is unexpected. Please check manually.<br><br>";
}

// Check and add new columns if they don't exist
$columnsToAdd = [
    'place' => "ADD place VARCHAR(100)",
    'customer_name' => "ADD customer_name VARCHAR(100)",
    'work_name' => "ADD work_name VARCHAR(100)",
    'paid_amount' => "ADD paid_amount DECIMAL(10,2)",
    'dues_amount' => "ADD dues_amount DECIMAL(10,2)"
];

$alterParts = [];
foreach ($columnsToAdd as $column => $addQuery) {
    if (!in_array($column, $columns)) {
        $alterParts[] = $addQuery;
    }
}

if (!empty($alterParts)) {
    $alterQuery = "ALTER TABLE attendance " . implode(", ", $alterParts);
    if ($conn->query($alterQuery) === TRUE) {
        echo "New columns added successfully: " . implode(", ", array_keys($columnsToAdd)) . "<br><br>";
    } else {
        echo "Error adding new columns: " . $conn->error . "<br><br>";
    }
} else {
    echo "All new columns already exist.<br><br>";
}

$conn->close();

echo "Alteration completed.<br>";
?>
