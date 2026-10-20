<?php
require_once __DIR__ . '/../../config/database.php';
global $conn;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php?page=login");
    exit;
}

$username = $_SESSION['user']['username'] ?? 'Admin';

/* FILTERS */
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$employee_id = $_GET['employee_id'] ?? '';
$status = $_GET['status'] ?? '';

/* BUILD QUERY */
$where_clauses = [];
$params = [];
$types = '';

if (!empty($date_from)) {
    $where_clauses[] = "a.date >= ?";
    $params[] = $date_from;
    $types .= 's';
}
if (!empty($date_to)) {
    $where_clauses[] = "a.date <= ?";
    $params[] = $date_to;
    $types .= 's';
}
if (!empty($employee_id)) {
    $where_clauses[] = "u.employee_id = ?";
    $params[] = $employee_id;
    $types .= 's';
}
if (!empty($status)) {
    $where_clauses[] = "a.status = ?";
    $params[] = $status;
    $types .= 's';
}

$where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

/* FETCH ATTENDANCE */
$sql = "
    SELECT
        a.id,
        u.username,
        u.employee_id,
        d.name AS department,
        a.date,
        a.status,
        a.check_in,
        a.check_out,
        a.place,
        a.customer_name,
        a.work_name,
        a.paid_amount,
        a.dues_amount
    FROM attendance a
    JOIN users u ON a.user_id = u.id
    LEFT JOIN departments d ON u.department_id = d.id
    $where_sql
    ORDER BY a.date DESC, a.id ASC
";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* DOWNLOAD CSV */
if(isset($_GET['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance_report.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['S No', 'Date', 'Employee Name', 'Department', 'Place', 'Customer', 'Work', 'Check In', 'Check Out', 'Paid', 'Dues', 'Status']);
    $i=1;
    foreach($records as $row) {
        fputcsv($output, [
            $i++,
            date('d M Y', strtotime($row['date'])),
            ucfirst($row['username']),
            $row['department'] ?? 'N/A',
            $row['place'] ?? '-',
            $row['customer_name'] ?? '-',
            $row['work_name'] ?? '-',
            $row['check_in'] ?? '--',
            $row['check_out'] ?? '--',
            '₹' . number_format($row['paid_amount'] ?? 0, 2),
            '₹' . number_format($row['dues_amount'] ?? 0, 2),
            ucfirst($row['status'])
        ]);
    }
    fclose($output);
    exit;
}

/* GET EMPLOYEES FOR FILTER */
$employees_sql = "SELECT employee_id, username FROM users WHERE role='employee' ORDER BY username";
$employees_result = $conn->query($employees_sql);
$employees = $employees_result ? $employees_result->fetch_all(MYSQLI_ASSOC) : [];
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="bg-gray-100 min-h-screen font-sans">

<div class="min-h-screen bg-gray-100 font-sans">

    <!-- MAIN -->
    <div class="flex flex-col">

        <!-- HEADER -->
        <?php include 'views/layout/admin-header.php'; ?>

        <!-- CONTENT -->
        <main class="p-6">
            <!-- FILTER -->
            <form method="GET" class="flex items-center gap-4 mb-6">
                <input type="hidden" name="page" value="admin-attendance-report">

                <label class="text-sm font-medium">Filter By Date:</label>
                <input type="date" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>"
                       class="border px-3 py-2 rounded text-sm" placeholder="Start Date">
                <input type="date" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>"
                       class="border px-3 py-2 rounded text-sm" placeholder="End Date">

                <button class="bg-emerald-600 text-white px-4 py-2 rounded text-sm">
                    Search
                </button>
            </form>

            <!-- DOWNLOAD BUTTON -->
            <div class="mb-4">
                <a href="?page=admin-attendance-report&download=1&<?php echo http_build_query($_GET); ?>" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    📥 Download Report (CSV)
                </a>
            </div>

            <!-- TABLE -->
            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">S No</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Employee Name</th>
                            <th class="px-4 py-3">Department</th>
                            <th class="px-4 py-3">Place</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Work</th>
                            <th class="px-4 py-3">Check In</th>
                            <th class="px-4 py-3">Check Out</th>
                            <th class="px-4 py-3">Paid</th>
                            <th class="px-4 py-3">Dues</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php if ($records): $i=1; foreach ($records as $row): ?>
                        <tr>
                            <td class="px-4 py-3"><?php echo $i++; ?></td>
                            <td class="px-4 py-3"><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                            <td class="px-4 py-3"><?php echo ucfirst(htmlspecialchars($row['username'])); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($row['department'] ?? 'N/A'); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($row['place'] ?? '-'); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($row['customer_name'] ?? '-'); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($row['work_name'] ?? '-'); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($row['check_in'] ?? '--'); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($row['check_out'] ?? '--'); ?></td>
                            <td class="px-4 py-3 text-green-600 font-semibold">₹<?php echo number_format($row['paid_amount'] ?? 0, 2); ?></td>
                            <td class="px-4 py-3 text-red-600 font-semibold">₹<?php echo number_format($row['dues_amount'] ?? 0, 2); ?></td>
                            <td class="px-4 py-3">
                                <?php
                                $colors = [
                                    'present'=>'bg-green-100 text-green-700',
                                    'absent'=>'bg-red-100 text-red-700',
                                    'leave'=>'bg-yellow-100 text-yellow-700',
                                    'sick'=>'bg-gray-100 text-gray-700'
                                ];
                                $cls = $colors[$row['status']] ?? 'bg-gray-100';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $cls; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="13" class="text-center py-6 text-gray-500">
                                No attendance records found
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>

</body>
