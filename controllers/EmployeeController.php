<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Attendance.php';

class EmployeeController
{
    public function dashboard()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        global $conn;
        // ✅ PASS DB CONNECTION HERE
        $attendanceModel = new Attendance($conn);

        $userId = $_SESSION['user']['id'];

        $attendance = $attendanceModel->getByUser($userId);
        $todayAttendance = $attendanceModel->getToday($userId);

        require_once __DIR__ . '/../views/employee/dashboard.php';
    }

    public function markAttendance()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        global $conn;
        // ✅ PASS DB CONNECTION HERE ALSO
        $attendanceModel = new Attendance($conn);

        $attendanceModel->mark($_SESSION['user']['id']);

        header("Location: index.php?page=employee-dashboard");
        exit;
    }

    public function myAttendance()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
            header("Location: index.php?page=login");
            exit;
        }

        global $conn;
        $attendanceModel = new Attendance($conn);

        $userId = $_SESSION['user']['id'];

        // Insert sample data if no attendance exists for the user
        $existingAttendance = $attendanceModel->getByUser($userId);
        if (empty($existingAttendance)) {
            // Insert sample attendance for the last 7 days
            for ($i = 1; $i <= 7; $i++) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $checkIn = date('H:i:s', strtotime('09:00:00'));
                $checkOut = date('H:i:s', strtotime('17:00:00'));
                $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, check_in, check_out) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $userId, $date, $checkIn, $checkOut);
                $stmt->execute();
            }
        }

        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;

        if ($startDate && $endDate) {
            $attendance = $attendanceModel->getByUserAndDateRange($userId, $startDate, $endDate);
        } elseif ($startDate) {
            $attendance = $attendanceModel->getByUserFromDate($userId, $startDate);
        } elseif ($endDate) {
            $attendance = $attendanceModel->getByUserToDate($userId, $endDate);
        } else {
            $attendance = $attendanceModel->getByUser($userId);
        }

        require_once __DIR__ . '/../views/employee/my-attendance.php';
    }

    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email']
            ];

            if (User::update($_SESSION['user']['id'], $data)) {
                $_SESSION['user']['username'] = $data['username'];
                $message = "Profile updated successfully!";
            } else {
                $message = "Error updating profile.";
            }
        }

        require_once __DIR__ . '/../views/employee/profile.php';
    }
}
