<?php
$username = $_SESSION['user']['username'] ?? 'Admin';
$employees = $employees ?? [];
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
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Manage Employees</h3>

            <!-- Header with search and add button -->
            <div class="flex justify-between items-center mb-6">
                <form method="GET" action="index.php" class="flex gap-4">
                    <input type="hidden" name="page" value="admin-employees">
                    <input type="text"
                           name="search"
                           placeholder="Search employees..."
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                           class="border border-gray-300 px-4 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-300">
                        Search
                    </button>
                </form>

                <a href="index.php?page=admin-add-employee"
                   class="bg-emerald-600 text-white px-4 py-2 rounded-md text-sm hover:bg-emerald-700">
                    Add New Employee
                </a>
            </div>

            <!-- Employees Table -->
            <div class="bg-white rounded shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $i = 1; foreach ($employees as $emp): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo $i++; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                    <?php if (!empty($emp['profile_image'])): ?>
                                        <img src="public/images/employees/<?php echo $emp['profile_image']; ?>"
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-xs font-bold text-gray-600">
                                            <?php echo strtoupper(substr($emp['username'], 0, 2)); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($emp['username']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($emp['email']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo htmlspecialchars($emp['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="index.php?page=admin-view-employee&id=<?php echo $emp['id']; ?>"
                                       class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="index.php?page=admin-edit-employee&id=<?php echo $emp['id']; ?>"
                                       class="text-green-600 hover:text-green-900">Edit</a>
                                    <a href="index.php?page=admin-employee-attendance&id=<?php echo $emp['id']; ?>"
                                       class="text-purple-600 hover:text-purple-900">Attendance</a>
                                    <a href="index.php?page=admin-employee-salary&id=<?php echo $emp['id']; ?>"
                                       class="text-yellow-600 hover:text-yellow-900">Salary</a>
                                    <a href="index.php?page=admin-delete-employee&id=<?php echo $emp['id']; ?>"
                                       class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('Are you sure you want to delete this employee?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($employees)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No employees found
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php include 'recent-attendance.php'; ?>
        </main>
    </div>
</div>

</body>
