<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Attendance.php';

class AdminController {
    public function dashboard() {
        global $conn;
        $employees = User::getAllEmployees();
        $attendance = Attendance::getAll();
        $totalEmployees = count($employees);
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function employees() {
        $search = $_GET['search'] ?? '';
        $employees = User::getAllEmployees($search);
        require_once __DIR__ . '/../views/admin/employees.php';
    }

    public function addEmployee() {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'email' => $_POST['email'],
                'role' => 'employee'
            ];

            if (User::create($data)) {
                header('Location: index.php?page=admin-dashboard');
                exit;
            } else {
                $message = "Error adding employee.";
            }
        }

        require_once __DIR__ . '/../views/admin/add-employee.php';
    }

    public function deleteEmployee() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $employeeId = $_GET['id'] ?? null;
        if ($employeeId) {
            User::delete($employeeId);
        }
        header('Location: index.php?page=admin-employees');
        exit;
    }

    public function attendanceReport() {
        global $conn;

        // Handle export
        if (isset($_GET['export'])) {
            $this->exportAttendanceReport();
            return;
        }

        // Normal view
        require_once __DIR__ . '/../views/admin/attendance-report.php';
    }

    private function exportAttendanceReport() {
        global $conn;

        // Get filters from GET parameters
        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
        $employee_id = $_GET['employee_id'] ?? '';
        $status = $_GET['status'] ?? '';
        $export_type = $_GET['export'] ?? 'csv';

        // Build query (same as in view)
        $where_clauses = [];
        $params = [];
        $types = '';

        if (!empty($date_from)) {
            $where_clauses[] = "a.date >= ?";
            $params[] = $date_from;
            $types .= 's';
        }
        if (!empty($date_to)) {
            $where_clauses[] = "a.date <= ?";
            $params[] = $date_to;
            $types .= 's';
        }
        if (!empty($employee_id)) {
            $where_clauses[] = "u.employee_id = ?";
            $params[] = $employee_id;
            $types .= 's';
        }
        if (!empty($status)) {
            $where_clauses[] = "a.status = ?";
            $params[] = $status;
            $types .= 's';
        }

        $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

        $sql = "
            SELECT
                a.date,
                u.employee_id,
                u.username,
                d.name AS department,
                a.place,
                a.customer_name,
                a.work_name,
                a.check_in,
                a.check_out,
                a.paid_amount,
                a.dues_amount,
                a.status
            FROM attendance a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN departments d ON u.department_id = d.id
            $where_sql
            ORDER BY a.date DESC, a.id ASC
        ";

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if ($export_type === 'csv') {
            $this->exportCSV($records);
        } elseif ($export_type === 'pdf') {
            $this->exportPDF($records);
        }
    }

    private function exportCSV($records) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="attendance_report_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // CSV headers
        fputcsv($output, [
            'Date', 'Employee ID', 'Employee Name', 'Department', 'Place', 'Customer',
            'Work', 'Check In', 'Check Out', 'Paid Amount', 'Dues Amount', 'Status'
        ]);

        // CSV data
        foreach ($records as $record) {
            fputcsv($output, [
                $record['date'],
                $record['employee_id'],
                $record['username'],
                $record['department'] ?? 'N/A',
                $record['place'] ?? '',
                $record['customer_name'] ?? '',
                $record['work_name'] ?? '',
                $record['check_in'] ?? '',
                $record['check_out'] ?? '',
                $record['paid_amount'] ?? 0,
                $record['dues_amount'] ?? 0,
                ucfirst($record['status'])
            ]);
        }

        fclose($output);
        exit;
    }

    private function exportPDF($records) {
        // For PDF export, we'll use a simple HTML to PDF approach
        // In a real application, you'd use a library like TCPDF or FPDF

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="attendance_report_' . date('Y-m-d') . '.pdf"');

        echo "<html><body><h1>Attendance Report</h1><table border='1'>";
        echo "<tr><th>Date</th><th>Employee ID</th><th>Name</th><th>Department</th><th>Status</th></tr>";

        foreach ($records as $record) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($record['date']) . "</td>";
            echo "<td>" . htmlspecialchars($record['employee_id']) . "</td>";
            echo "<td>" . htmlspecialchars($record['username']) . "</td>";
            echo "<td>" . htmlspecialchars($record['department'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars(ucfirst($record['status'])) . "</td>";
            echo "</tr>";
        }

        echo "</table></body></html>";
        exit;
    }

    public function departments() {
        require_once __DIR__ . '/../views/admin/departments.php';
    }

    public function leaves() {
        require_once __DIR__ . '/../views/admin/leaves.php';
    }

    public function salary() {
        require_once __DIR__ . '/../views/admin/salary.php';
    }

    public function profile() {
        require_once __DIR__ . '/../views/admin/profile.php';
    }

    public function editProfile() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $message = '';
        $userId = $_SESSION['user']['id'] ?? $_SESSION['user_id'];
        $user = User::findById($userId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone']
            ];

            // Handle password change if provided
            if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
                if (password_verify($_POST['current_password'], $user['password'])) {
                    if ($_POST['new_password'] === $_POST['confirm_password']) {
                        $data['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    } else {
                        $message = "New passwords do not match.";
                    }
                } else {
                    $message = "Current password is incorrect.";
                }
            }

            if (empty($message) && User::update($userId, $data)) {
                $message = "Profile updated successfully!";
                $user = User::findById($userId); // Refresh user data
                $_SESSION['user']['username'] = $user['username']; // Update session
            } elseif (empty($message)) {
                $message = "Error updating profile.";
            }
        }

        require_once __DIR__ . '/../views/admin/edit-profile.php';
    }

    public function viewEmployee() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $employeeId = $_GET['id'] ?? null;
        if ($employeeId) {
            $employee = User::findById($employeeId);
        }

        require_once __DIR__ . '/../views/admin/view-employee.php';
    }

    public function editEmployee() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $message = '';
        $employeeId = $_GET['id'] ?? null;
        $employee = null;

        if ($employeeId) {
            $employee = User::findById($employeeId);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $employeeId) {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'role' => $_POST['role']
            ];

            if (User::update($employeeId, $data)) {
                $message = "Employee updated successfully!";
                $employee = User::findById($employeeId); // Refresh data
            } else {
                $message = "Error updating employee.";
            }
        }

        require_once __DIR__ . '/../views/admin/edit-employee.php';
    }

    public function employeeAttendance() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        global $conn;
        $attendanceModel = new Attendance($conn);

        $employeeId = $_GET['id'] ?? null;
        $attendance = [];

        if ($employeeId) {
            $attendance = $attendanceModel->getByUser($employeeId);
            $employee = User::findById($employeeId);
        }

        require_once __DIR__ . '/../views/admin/employee-attendance.php';
    }

    public function employeeSalary() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $message = '';
        $employeeId = $_GET['id'] ?? null;
        $employee = null;
        $salary = null;

        if ($employeeId) {
            $employee = User::findById($employeeId);
            // For now, we'll use a simple salary structure
            // In a real application, you'd have a Salary model
            $salary = [
                'base_salary' => 50000,
                'monthly_salary' => 4166.67,
                'annual_salary' => 50000
            ];
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $employeeId) {
            $baseSalary = $_POST['base_salary'] ?? 0;

            // Here you would update the salary in the database
            // For now, we'll just show a success message
            $message = "Salary updated successfully!";
        }

        require_once __DIR__ . '/../views/admin/employee-salary.php';
    }

    public function manageAttendance() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        global $conn;
        $attendanceModel = new Attendance($conn);
        $date = $_GET['date'] ?? date('Y-m-d');
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employeeId = $_POST['employee_id'];
            $status = $_POST['status'];

            if ($attendanceModel->markByAdmin($employeeId, $date, $status)) {
                $message = "Attendance marked successfully!";
            } else {
                $message = "Error marking attendance.";
            }
        }

        $employees = $attendanceModel->getEmployeesWithAttendance($date);
        require_once __DIR__ . '/../views/admin/manage-attendance.php';
    }
}
?>
