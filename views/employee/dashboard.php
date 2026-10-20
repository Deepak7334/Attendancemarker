<?php
// Safe data access
$user_id = $_SESSION['user']['user_id'] ?? ($_SESSION['user']['id'] ?? null);
$username = $_SESSION['user']['username'] ?? 'Employee';

// Attendance data (from controller)
$attendance = $attendance ?? [];
$recentAttendance = array_slice($attendance, 0, 7);

// Monthly present count
$presentDays = 0;
$currentMonth = date('Y-m');
foreach ($attendance as $record) {
    if (!empty($record['date']) &&
        date('Y-m', strtotime($record['date'])) === $currentMonth &&
        $record['status'] === 'Present') {
        $presentDays++;
    }
}

// Today attendance
$todayAttendance = null;
$today = date('Y-m-d');
foreach ($attendance as $record) {
    if (!empty($record['date']) && $record['date'] === $today) {
        $todayAttendance = $record;
        break;
    }
}
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="min-h-screen bg-gray-100 font-sans">

<div class="min-h-screen flex flex-col">

    <!-- Right main area -->
    <div class="flex-1 flex flex-col">

        <!-- Top green header -->
        <header class="h-16 bg-emerald-600 text-white flex items-center justify-between px-6">
            <div class="text-sm">
                Welcome,
                <span class="font-semibold">
                    <?php echo htmlspecialchars($username); ?>
                </span>
            </div>
            <div class="relative">
                <button id="dropdownButton" class="text-white focus:outline-none">
                    <span class="text-xl">⋮</span>
                </button>
                <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                    <a href="index.php?page=employee-dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🏠 Dashboard</a>
                    <a href="index.php?page=mark-attendance" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📝 Mark Attendance</a>
                    <a href="index.php?page=employee-my-attendance" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📊 My Attendance</a>
                    <a href="index.php?page=employee-profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">👤 Profile</a>
                    <a href="index.php?page=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Overview</h3>

            <!-- First row: overview cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Today Attendance -->
                <div class="flex bg-white rounded shadow-sm overflow-hidden">
                    <div class="w-20 bg-yellow-500 flex items-center justify-center text-white text-3xl">
                        📅
                    </div>
                    <div class="flex-1 px-4 py-3 flex flex-col justify-between">
                        <p class="text-sm text-gray-600">Today Attendance</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php echo $todayAttendance ? ($todayAttendance['status'] ?? 'Not Marked') : 'Not Marked'; ?>
                        </p>
                    </div>
                </div>

                <!-- Monthly Present Days -->
                <div class="flex bg-white rounded shadow-sm overflow-hidden">
                    <div class="w-20 bg-emerald-500 flex items-center justify-center text-white text-3xl">
                        📊
                    </div>
                    <div class="flex-1 px-4 py-3 flex flex-col justify-between">
                        <p class="text-sm text-gray-600">Monthly Present</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $presentDays; ?></p>
                    </div>
                </div>

                <!-- Total Days -->
                <div class="flex bg-white rounded shadow-sm overflow-hidden">
                    <div class="w-20 bg-teal-500 flex items-center justify-center text-white text-3xl">
                        📆
                    </div>
                    <div class="flex-1 px-4 py-3 flex flex-col justify-between">
                        <p class="text-sm text-gray-600">Total Days</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo date('t'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance Table -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Attendance</h3>
            <div class="bg-white rounded shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($recentAttendance as $record): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo date('M d, Y', strtotime($record['date'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo $record['check_in'] ?? '--:--'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo $record['check_out'] ?? '--:--'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (!empty($record['status'])): ?>
                                    <?php
                                    $statusColors = [
                                        'Present' => 'bg-green-100 text-green-800',
                                        'Absent' => 'bg-red-100 text-red-800',
                                        'Sick' => 'bg-gray-100 text-gray-800',
                                        'Leave' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $colorClass = $statusColors[$record['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $colorClass; ?>">
                                        <?php echo htmlspecialchars($record['status']); ?>
                                    </span>
                                <?php elseif (!empty($record['check_out'])): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Present
                                    </span>
                                <?php elseif (!empty($record['check_in'])): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Partial
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Absent
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('dropdownButton').addEventListener('click', function() {
    var menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
});

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    var button = document.getElementById('dropdownButton');
    var menu = document.getElementById('dropdownMenu');
    if (!button.contains(event.target) && !menu.contains(event.target)) {
        menu.classList.add('hidden');
    }
});
</script>
</body>
