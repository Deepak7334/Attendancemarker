<?php
$username = $_SESSION['user']['username'] ?? 'Admin';
$date = $_GET['date'] ?? date('Y-m-d');

/* ---------- MARK ATTENDANCE ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empId = $_POST['employee_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        INSERT INTO attendance (user_id, date, status)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE status = VALUES(status)
    ");
    $stmt->bind_param("sss", $empId, $date, $status);
    $stmt->execute();

    header("Location: index.php?page=admin-manage-attendance&date=$date");
    exit;
}

/* ---------- FETCH EMPLOYEES ---------- */
$employees = $conn->query("
    SELECT u.id, u.username, u.email, a.status
    FROM users u
    LEFT JOIN attendance a
        ON u.id = a.user_id
        AND a.date = '$date'
    WHERE u.role = 'employee'
    ORDER BY u.id ASC
")->fetch_all(MYSQLI_ASSOC);
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="bg-gray-100">

<div class="min-h-screen bg-gray-100 font-sans">

<!-- MAIN -->
<div class="flex flex-col">

<!-- HEADER -->
<?php include 'views/layout/admin-header.php'; ?>

<!-- CONTENT -->
<main class="p-6">

<div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold">
        Mark Attendance for <?= date('d-m-Y', strtotime($date)) ?>
    </h3>
    <div class="flex gap-2">
        <a href="?page=admin-manage-attendance&date=<?= date('Y-m-d', strtotime($date . ' -1 day')) ?>"
           class="bg-gray-500 text-white px-4 py-2 rounded">Previous Day</a>
        <a href="?page=admin-manage-attendance&date=<?= date('Y-m-d', strtotime($date . ' +1 day')) ?>"
           class="bg-gray-500 text-white px-4 py-2 rounded">Next Day</a>
        <a href="index.php?page=admin-attendance-report"
           class="bg-emerald-600 text-white px-4 py-2 rounded">
            Attendance Report
        </a>
    </div>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-gray-100">
<tr>
    <th class="p-3 text-left">S No</th>
    <th class="p-3 text-left">Name</th>
    <th class="p-3 text-left">Email</th>
    <th class="p-3 text-left">Status</th>
    <th class="p-3 text-left">Action</th>
</tr>
</thead>
<tbody>

<?php $i=1; foreach ($employees as $emp): ?>
<tr class="border-t">
    <td class="p-3"><?= $i++ ?></td>
    <td class="p-3"><?= htmlspecialchars($emp['username']) ?></td>
    <td class="p-3"><?= htmlspecialchars($emp['email']) ?></td>
    <td class="p-3">
        <?php if (!empty($emp['status'])): ?>
            <?php
            $statusColors = [
                'Present' => 'bg-green-100 text-green-700',
                'Absent' => 'bg-red-100 text-red-700'
            ];
            $colorClass = $statusColors[$emp['status']] ?? 'bg-gray-100 text-gray-700';
            ?>
            <span class="px-2 py-1 rounded text-xs font-semibold <?php echo $colorClass; ?>">
                <?php echo ucfirst($emp['status']); ?>
            </span>
        <?php else: ?>
            <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                Not Marked
            </span>
        <?php endif; ?>
    </td>
    <td class="p-3">
        <div class="flex gap-2">

        <?php
        $btn = function($label,$color) use ($emp,$date){
            $isChecked = $emp['status'] === $label ? 'checked' : '';
            return "
            <form method='POST' class='inline'>
                <input type='hidden' name='employee_id' value='{$emp['id']}'>
                <input type='hidden' name='status' value='$label'>
                <button type='submit' class='px-3 py-1 text-xs rounded bg-$color-600 text-white hover:bg-$color-700 $isChecked'>
                    $label
                </button>
            </form>";
        };

        // If status is already set, show only that button
        if (!empty($emp['status'])) {
            $statusColors = ['Present'=>'green', 'Absent'=>'red'];
            $color = $statusColors[$emp['status']] ?? 'gray';
            echo $btn($emp['status'], $color);
        } else {
            // If no status set, show all buttons
            echo $btn('Present','green');
            echo $btn('Absent','red');
        }
        ?>

        </div>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</main>
</div>
</div>

<?php include 'recent-attendance.php'; ?>

</body>
