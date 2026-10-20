<?php
require_once 'config/database.php';

// Get employee user IDs
$result = $conn->query("SELECT id FROM users WHERE role = 'employee'");
$employees = $result->fetch_all(MYSQLI_ASSOC);

// Insert sample attendance data for the last 30 days
$today = date('Y-m-d');
for ($i = 0; $i < 30; $i++) {
    $date = date('Y-m-d', strtotime("-$i days"));
    foreach ($employees as $employee) {
        $userId = $employee['id'];

        // Randomly decide if employee was present (80% chance)
        if (rand(1, 10) <= 8) {
            $checkIn = date('H:i:s', strtotime('09:00:00') + rand(-3600, 3600)); // 8-10 AM
            $checkOut = date('H:i:s', strtotime('17:00:00') + rand(-3600, 3600)); // 4-6 PM

            $sql = "INSERT INTO attendance (user_id, date, check_in, check_out, status) VALUES (?, ?, ?, ?, 'Present')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $userId, $date, $checkIn, $checkOut);
            $stmt->execute();
        }
    }
}

echo "Sample attendance data inserted successfully!";
?>
