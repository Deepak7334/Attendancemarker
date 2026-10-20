<?php
$username = $_SESSION['user']['username'] ?? 'Admin';
$employee = $employee ?? null;
$salary = $salary ?? null;
$message = $message ?? '';
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="min-h-screen bg-gray-100 font-sans">

<div class="min-h-screen bg-gray-100 font-sans">

    <!-- Right main area -->
    <div class="flex flex-col">

        <!-- HEADER -->
        <?php include 'views/layout/admin-header.php'; ?>

        <!-- Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Manage Employee Salary</h3>
                <a href="index.php?page=admin-employees"
                   class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">
                    Back to Employees
                </a>
            </div>

            <?php if ($message): ?>
                <div class="mb-4 p-4 rounded <?php echo strpos($message, 'successfully') !== false ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($employee): ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Employee Info -->
                <div class="bg-white rounded shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Employee Information</h4>
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mr-4">
                            <?php if (!empty($employee['profile_image'])): ?>
                                <img src="public/images/employees/<?php echo $employee['profile_image']; ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-lg font-bold text-gray-600">
                                    <?php echo strtoupper(substr($employee['username'], 0, 2)); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($employee['username']); ?></h5>
                            <p class="text-gray-600"><?php echo htmlspecialchars($employee['email']); ?></p>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?php echo htmlspecialchars($employee['role']); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Salary Details -->
                <div class="bg-white rounded shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Current Salary</h4>
                    <?php if ($salary): ?>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Base Salary:</span>
                            <span class="font-semibold">$<?php echo number_format($salary['base_salary'], 2); ?>/year</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Monthly Salary:</span>
                            <span class="font-semibold">$<?php echo number_format($salary['monthly_salary'], 2); ?>/month</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Annual Salary:</span>
                            <span class="font-semibold">$<?php echo number_format($salary['annual_salary'], 2); ?>/year</span>
                        </div>
                    </div>
                    <?php else: ?>
                    <p class="text-gray-500">No salary information available</p>
                    <?php endif; ?>
                </div>

                <!-- Update Salary Form -->
                <div class="bg-white rounded shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Update Salary</h4>
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label for="base_salary" class="block text-sm font-medium text-gray-700 mb-2">
                                Base Salary (Annual)
                            </label>
                            <input type="number"
                                   id="base_salary"
                                   name="base_salary"
                                   value="<?php echo $salary ? $salary['base_salary'] : ''; ?>"
                                   step="0.01"
                                   min="0"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <button type="submit"
                                class="w-full bg-emerald-600 text-white px-4 py-2 rounded-md text-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            Update Salary
                        </button>
                    </form>
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
        </main>
    </div>
</div>

</body>
