<?php session_start(); ?>
<aside class="sidebar">
    <nav>
        <ul>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <li><a href="index.php?page=admin-dashboard">Dashboard</a></li>
                <li><a href="index.php?page=admin-employees">Employees</a></li>
                <li><a href="index.php?page=admin-add-employee">Add Employee</a></li>
                <li><a href="index.php?page=admin-attendance-report">Attendance Report</a></li>
                <li><a href="index.php?page=admin-profile">Profile</a></li>
            <?php else: ?>
                <li><a href="index.php?page=employee-dashboard">Dashboard</a></li>
                <li><a href="index.php?page=mark-attendance">Mark Attendance</a></li>
                <li><a href="index.php?page=employee-my-attendance">My Attendance</a></li>
                <li><a href="index.php?page=employee-profile">Profile</a></li>
            <?php endif; ?>
            <li><a href="index.php?page=logout">Logout</a></li>
        </ul>
    </nav>
</aside>
