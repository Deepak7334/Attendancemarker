<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Attendance.php';

class AttendanceController
{
    public function mark()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        // ✅ MAKE DB CONNECTION AVAILABLE
        global $conn;

        $attendanceModel = new Attendance($conn);
        $attendanceModel->mark($_SESSION['user']['id']);

        $_SESSION['success'] = "Attendance updated successfully";

        header("Location: index.php?page=employee-dashboard");
        exit;
    }
}
