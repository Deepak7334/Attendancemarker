<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Attendance.php';

class Employee {
    public static function getAll() {
        if (defined('DEMO_MODE') && DEMO_MODE) {
            return [
                ['id' => 2, 'username' => 'employee1', 'email' => 'employee1@example.com', 'role' => 'employee']
            ];
        }
        global $conn;
        $result = $conn->query("SELECT id, username, email, role FROM users WHERE role = 'employee'");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getAttendance($user_id) {
        return Attendance::getUserAttendance($user_id);
    }

    public static function getTodayAttendance($user_id) {
        return Attendance::getTodayAttendance($user_id);
    }
}
?>
