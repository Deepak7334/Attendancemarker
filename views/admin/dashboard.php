<?php
require_once __DIR__ . '/../../config/database.php';
global $conn;

$username = $_SESSION['user']['username'];

/* ===== DASHBOARD COUNTS ===== */
$result = $conn->query("SELECT COUNT(*) FROM users WHERE role='employee'");
$totalEmployees = $result ? $result->fetch_row()[0] : 0;

$totalDepartments = 0;
$leavePending = 0;
$leaveApproved = 0;
$leaveRejected = 0;
$leaveApplied = 0;
$monthlyPay = 0;

/* ===== TODAY ATTENDANCE ===== */
$today = date('Y-m-d');

$result = $conn->query("SELECT COUNT(*) FROM attendance WHERE date='$today' AND status='Present'");
$presentToday = $result ? $result->fetch_row()[0] : 0;

$result = $conn->query("SELECT COUNT(*) FROM attendance WHERE date='$today' AND status='Absent'");
$absentToday = $result ? $result->fetch_row()[0] : 0;

/* ===== TODAY'S ATTENDANCE ===== */
$todayAttendance = [];
$result = $conn->query("
    SELECT u.username, a.*
    FROM users u
    LEFT JOIN attendance a ON u.id = a.user_id AND a.date = '$today'
    WHERE u.role = 'employee'
    ORDER BY u.username ASC
");
if ($result) {
    $todayAttendance = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="min-h-screen bg-gray-100 font-sans">

<div class="min-h-screen bg-gray-100 font-sans">

    <div class="flex flex-col">

        <!-- HEADER -->
        <?php include 'views/layout/admin-header.php'; ?>

        <!-- CONTENT -->
        <main class="p-6 flex-1 overflow-y-auto">

            <h3 class="text-lg font-semibold mb-4">Dashboard Overview</h3>

            <!-- CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <?php
                $cards = [
                    ['👥', 'Total Employees', $totalEmployees, 'bg-emerald-500'],
                    ['🏢', 'Total Departments', $totalDepartments, 'bg-yellow-500'],
                    ['💰', 'Monthly Pay', '₹' . number_format($monthlyPay), 'bg-teal-500'],
                    ['✅', 'Present Today', $presentToday, 'bg-green-500'],
                    ['❌', 'Absent Today', $absentToday, 'bg-red-500'],
                    ['⏳', 'Pending Leaves', $leavePending, 'bg-yellow-400'],
                ];

                foreach ($cards as $c): ?>
                    <div class="flex bg-white rounded shadow">
                        <div class="w-20 <?= $c[3] ?> text-white flex items-center justify-center text-3xl">
                            <?= $c[0] ?>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600"><?= $c[1] ?></p>
                            <p class="text-2xl font-bold"><?= $c[2] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- TODAY'S ATTENDANCE -->
          <h3 class="text-lg font-semibold mb-3">Today's Attendance</h3>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Employee</th>
                <th class="p-3">Date</th>
                <th class="p-3">Place</th>
                <th class="p-3">Customer</th>
                <th class="p-3">Work</th>
                <th class="p-3">Paid</th>
                <th class="p-3">Dues</th>
                <th class="p-3">Check In</th>
                <th class="p-3">Check Out</th>
                <th class="p-3">Status</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($todayAttendance as $r): ?>
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium">
                    <?= htmlspecialchars($r['username'] ?? '-') ?>
                </td>

               <td class="p-3">
                    <?= date('d M Y', strtotime($today)) ?>
                </td>

                <td class="p-3">
                    <?= $r['place'] ?? '-' ?>
                </td>

                <td class="p-3">
                    <?= $r['customer_name'] ?? '-' ?>
                </td>

                <td class="p-3">
                    <?= $r['work_name'] ?? '-' ?>
                </td>

                <td class="p-3 text-green-600 font-semibold">
                    ₹<?= number_format($r['paid_amount'] ?? 0, 2) ?>
                </td>

                <td class="p-3 text-red-600 font-semibold">
                    ₹<?= number_format($r['dues_amount'] ?? 0, 2) ?>
                </td>

                <td class="p-3">
                    <?= $r['check_in'] ?? '--' ?>
                </td>

                <td class="p-3">
                    <?= $r['check_out'] ?? '--' ?>
                </td>

                <td class="p-3">
                    <?php
                    $status = $r['status'] ?? 'absent'; // Default to absent if no record
                    $statusColors = [
                        'present' => 'bg-green-100 text-green-700',
                        'absent' => 'bg-red-100 text-red-700',
                        'leave' => 'bg-yellow-100 text-yellow-700',
                        'sick' => 'bg-gray-100 text-gray-700'
                    ];
                    $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                    ?>
                    <span class="px-2 py-1 rounded text-xs font-semibold <?php echo $colorClass; ?>">
                        <?php echo ucfirst($status); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (empty($todayAttendance)): ?>
            <tr>
                <td colspan="10" class="p-4 text-center text-gray-500">
                    No attendance records found for today
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

        </main>
    </div>
</div>

</body>
