<?php
require_once 'config/database.php';

try {
    $result = $conn->query("
        SELECT a.*, u.username
        FROM attendance a
        LEFT JOIN users u ON u.id = a.user_id
        ORDER BY a.date DESC
        LIMIT 10
    ");
    if ($result) {
        $recentAttendance = $result->fetch_all(MYSQLI_ASSOC);
        echo "Query executed successfully. Number of records: " . count($recentAttendance) . "\n";
        foreach ($recentAttendance as $r) {
            echo "Username: " . ($r['username'] ?? '-') . ", Date: " . $r['date'] . "\n";
        }
    } else {
        echo "Query failed: " . $conn->error . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$conn->close();
?>
