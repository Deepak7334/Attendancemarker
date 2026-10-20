<?php
require_once __DIR__ . '/../../config/database.php';

$recentAttendance = [];
$result = $conn->query("
    SELECT a.*, u.username
    FROM attendance a
    LEFT JOIN users u ON u.id = a.user_id
    ORDER BY a.date DESC
    LIMIT 10
");
if ($result) {
    $recentAttendance = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!-- RECENT ATTENDANCE -->
<h3 class="text-lg font-semibold mb-3">Recent Attendance</h3>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Employee</th>
                <th class="p-3">Date</th>
                <th class="p-3">Check In</th>
                <th class="p-3">Check Out</th>
                <th class="p-3">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentAttendance as $r): ?>
            <tr class="border-t">
                <td class="p-3"><?= htmlspecialchars($r['username'] ?? '-') ?></td>
                <td class="p-3"><?= date('d M Y', strtotime($r['date'])) ?></td>
                <td class="p-3"><?= $r['check_in'] ?? '--' ?></td>
                <td class="p-3"><?= $r['check_out'] ?? '--' ?></td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs bg-emerald-100 text-emerald-700">
                        <?= ucfirst($r['status'] ?? 'Present') ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (empty($recentAttendance)): ?>
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">
                    No attendance records found
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
