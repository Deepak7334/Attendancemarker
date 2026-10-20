<?php
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

    <!-- MAIN -->
    <div class="flex flex-col">

        <!-- HEADER -->
        <?php include 'views/layout/admin-header.php'; ?>

        <!-- CONTENT -->
        <main class="p-6 flex-1 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Edit Employee</h3>
                <a href="index.php?page=admin-employees"
                   class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">
                    Back to Employees
                </a>
            </div>

            <?php if ($employee): ?>
            <div class="bg-white rounded shadow-sm p-6">

                <form method="POST" action="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- LEFT COLUMN -->
                        <div class="space-y-4">

                            <div>
                                <label class="text-sm font-medium">Employee ID</label>
                                <input type="text" name="employee_id"
                                       value="<?= htmlspecialchars($employee['employee_id'] ?? '') ?>"
                                       class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Gender</label>
                                <select name="gender" class="w-full border rounded px-3 py-2">
                                    <option <?= ($employee['gender'] ?? '')=='Male'?'selected':'' ?>>Male</option>
                                    <option <?= ($employee['gender'] ?? '')=='Female'?'selected':'' ?>>Female</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Designation</label>
                                <input type="text" name="designation"
                                       value="<?= htmlspecialchars($employee['designation'] ?? '') ?>"
                                       class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Salary</label>
                                <input type="number" name="salary"
                                       value="<?= htmlspecialchars($employee['salary'] ?? '') ?>"
                                       class="w-full border rounded px-3 py-2">
                            </div>

                        </div>

                        <!-- RIGHT COLUMN -->
                        <div class="space-y-4">

                            <div>
                                <label class="text-sm font-medium">Date of Birth</label>
                                <input type="date" name="dob"
                                       value="<?= htmlspecialchars($employee['dob'] ?? '') ?>"
                                       class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Marital Status</label>
                                <select name="marital_status" class="w-full border rounded px-3 py-2">
                                    <option <?= ($employee['marital_status'] ?? '')=='Single'?'selected':'' ?>>Single</option>
                                    <option <?= ($employee['marital_status'] ?? '')=='Married'?'selected':'' ?>>Married</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Department</label>
                                <select name="department" class="w-full border rounded px-3 py-2">
                                    <option <?= ($employee['department'] ?? '')=='Database'?'selected':'' ?>>Database</option>
                                    <option <?= ($employee['department'] ?? '')=='IT'?'selected':'' ?>>IT</option>
                                    <option <?= ($employee['department'] ?? '')=='HR'?'selected':'' ?>>HR</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Role</label>
                                <select name="role" class="w-full border rounded px-3 py-2">
                                    <option value="employee" <?= ($employee['role'] ?? '')=='employee'?'selected':'' ?>>Employee</option>
                                    <option value="admin" <?= ($employee['role'] ?? '')=='admin'?'selected':'' ?>>Admin</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div class="mt-8">
                        <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-md text-lg hover:bg-blue-700">
                            Update Employee
                        </button>
                    </div>

                </form>
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
