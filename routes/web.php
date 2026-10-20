<?php

$page = $_GET['page'] ?? 'login';

switch ($page) {

    case 'login':
        require_once __DIR__ . '/../views/auth/login.php';
        break;

    case 'register':
        require_once __DIR__ . '/../views/auth/register.php';
        break;

    case 'admin-dashboard':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'admin-employees':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->employees();
        break;

    case 'admin-add-employee':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->addEmployee();
        break;

    case 'admin-departments':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->departments();
        break;



    case 'admin-salary':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->salary();
        break;

    case 'admin-profile':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->profile();
        break;

    case 'admin-edit-profile':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->editProfile();
        break;

    case 'admin-view-employee':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->viewEmployee();
        break;

    case 'admin-edit-employee':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->editEmployee();
        break;

    case 'admin-employee-attendance':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->employeeAttendance();
        break;

    case 'admin-employee-salary':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->employeeSalary();
        break;

    case 'admin-manage-attendance':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->manageAttendance();
        break;

    case 'admin-attendance-report':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->attendanceReport();
        break;

    case 'admin-delete-employee':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteEmployee();
        break;

    case 'employee-dashboard':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        $controller = new EmployeeController();
        $controller->dashboard();
        break;

    case 'mark-attendance':
        require_once __DIR__ . '/../views/employee/mark-attendance.php';
        break;

    case 'employee-my-attendance':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        $controller = new EmployeeController();
        $controller->myAttendance();
        break;

    case 'employee-profile':
        require_once __DIR__ . '/../controllers/EmployeeController.php';
        $controller = new EmployeeController();
        $controller->profile();
        break;

    case 'logout':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    default:
        echo "<h2 style='text-align:center'>404 Page Not Found</h2>";
}
