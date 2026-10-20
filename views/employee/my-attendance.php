<?php
session_start();
$username = $_SESSION['user']['username'] ?? 'Employee';
$attendance = $attendance ?? [];
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="min-h-screen bg-gray-100 font-sans">

<div class="min-h-screen bg-gray-100 font-sans">

    <!-- HEADER -->
    <?php include 'views/layout/employee-header.php'; ?>

    <!-- MAIN -->
    <div class="flex flex-col">

        <!-- Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">My Attendance Records</h3>

            <div class="bg-white rounded shadow-sm overflow-hidden">
                <?php if (empty($attendance)): ?>
                    <div class="p-6 text-center text-gray-500">
                        <p>No attendance records found for the selected dates.</p>
                        <p class="text-sm mt-2">Try selecting different dates or check if you have marked attendance.</p>
                    </div>
                <?php else: ?>
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
                        <?php foreach ($attendance as $record): ?>
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
                                <?php if (!empty($record['check_out'])): ?>
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
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

</body>
