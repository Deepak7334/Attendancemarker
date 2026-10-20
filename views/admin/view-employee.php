<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

$username = $_SESSION['user']['username'] ?? 'Admin';
$employee = $employee ?? null;
$employeeId = $_GET['id'] ?? null;

if (!$employee && $employeeId) {
    // Fetch employee data if not provided by controller
    $employee = User::findById($employeeId);
}
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="min-h-screen bg-gray-100 font-sans">

<div class="min-h-screen bg-gray-100 font-sans">

    <!-- Right main area -->
    <div class="flex flex-col">

        <!-- Top green header -->
        <header class="h-16 bg-emerald-600 text-white flex items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <a href="index.php?page=admin-dashboard" class="bg-white text-emerald-600 px-3 py-1 rounded text-sm hover:bg-gray-100">
                    ← Back to Dashboard
                </a>
                <h2 class="text-xl font-semibold">View Employee</h2>
            </div>
            <div class="text-sm">
                Welcome,
                <span class="font-semibold">
                    <?php echo htmlspecialchars($username); ?>
                </span>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Employee Details</h3>
                <a href="index.php?page=admin-employees"
                   class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">
                    Back to Employees
                </a>
            </div>

            <?php if ($employee): ?>
            <div class="bg-white rounded shadow-sm p-6">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mr-6">
                        <?php if (!empty($employee['profile_image'])): ?>
                            <img src="public/images/employees/<?php echo $employee['profile_image']; ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-2xl font-bold text-gray-600">
                                <?php echo strtoupper(substr($employee['username'], 0, 2)); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($employee['username']); ?></h4>
                        <p class="text-gray-600"><?php echo htmlspecialchars($employee['email']); ?></p>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?php echo htmlspecialchars($employee['role']); ?>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h5>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($employee['username']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($employee['email']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($employee['phone'] ?? 'N/A'); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($employee['role']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h5>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Employee ID</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($employee['id']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created At</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo date('M d, Y', strtotime($employee['created_at'] ?? 'now')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <a href="index.php?page=admin-edit-employee&id=<?php echo $employee['id']; ?>"
                       class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                        Edit Employee
                    </a>
                    <a href="index.php?page=admin-employee-attendance&id=<?php echo $employee['id']; ?>"
                       class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm hover:bg-purple-700">
                        View Attendance
                    </a>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-white rounded shadow-sm p-6 text-center">
                <p class="text-gray-500">Employee not found.</p>
                <a href="index.php?page=admin-employees"
                   class="mt-4 inline-block bg-emerald-600 text-white px-4 py-2 rounded-md text-sm hover:bg-emerald-700">
                    Back to Employees
                </a>
            </div>
            <?php endif; ?>

            <?php include 'recent-attendance.php'; ?>
        </main>
    </div>
</div>

</body>
