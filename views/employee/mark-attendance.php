<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . "/../../config/database.php";

$userId = $_SESSION['user']['id'];
$today  = date("Y-m-d");
$now    = date("H:i:s");

/* FETCH TODAY ATTENDANCE */
$stmt = $conn->prepare("SELECT * FROM attendance WHERE user_id=? AND date=?");
$stmt->bind_param("is", $userId, $today);
$stmt->execute();
$attendance = $stmt->get_result()->fetch_assoc();

/* CHECK IN */
if (isset($_POST['check_in']) && !$attendance) {

    $place         = $_POST['place'];
    $customer_name = $_POST['customer_name'];
    $work_name     = $_POST['work_name'];
    $paid_amount   = $_POST['paid_amount'];
    $dues_amount   = $_POST['dues_amount'];

    $stmt = $conn->prepare("
        INSERT INTO attendance 
        (user_id, date, place, customer_name, work_name, paid_amount, dues_amount, check_in, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Present')
    ");
    $stmt->bind_param(
        "issssdds",
        $userId,
        $today,
        $place,
        $customer_name,
        $work_name,
        $paid_amount,
        $dues_amount,
        $now
    );
    $stmt->execute();

    header("Location: index.php?page=mark-attendance&success=checkedin");
    exit;
}

/* CHECK OUT */
if (isset($_POST['check_out']) && $attendance && empty($attendance['check_out'])) {
    $stmt = $conn->prepare("UPDATE attendance SET check_out=? WHERE id=?");
    $stmt->bind_param("si", $now, $attendance['id']);
    $stmt->execute();

    header("Location: index.php?page=employee-dashboard");
    exit;
}
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-4xl bg-white shadow rounded">

    <!-- HEADER -->
    <div class="bg-emerald-600 text-white px-6 py-4 rounded-t flex justify-between items-center">
        <h2 class="text-xl font-semibold">Employee Attendance</h2>

        <!-- BACK BUTTON -->
        
    </div>

    <div class="p-6 space-y-6">

        <?php if (!$attendance): ?>
        <!-- DETAILS FORM -->
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <input name="place" required placeholder="Place"
                   class="border p-2 rounded">

            <input name="customer_name" required placeholder="Customer Name"
                   class="border p-2 rounded">

            <input name="work_name" required placeholder="Work Name"
                   class="border p-2 rounded">

            <input type="number" step="0.01" name="paid_amount"
                   placeholder="Paid Amount"
                   class="border p-2 rounded">

            <input type="number" step="0.01" name="dues_amount"
                   placeholder="Dues Amount"
                   class="border p-2 rounded">

            <div class="md:col-span-2 flex gap-3">
                <button name="check_in"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded">
                    Check In
                </button>

                <button type="button" onclick="history.back()"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded">
                    Back
                </button>
            </div>
        </form>

        <?php else: ?>
            <div class="border rounded p-4 space-y-2">
                <p><b>Date:</b> <?= $today ?></p>
                <p><b>Check In:</b> <?= $attendance['check_in'] ?></p>

                <?php if ($attendance['check_out']): ?>
                    <p><b>Check Out:</b> <?= $attendance['check_out'] ?></p>
                    <p class="text-green-600 font-medium">Attendance completed</p>

                    <button onclick="history.back()"
                            class="mt-3 bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded">
                        Back
                    </button>

                <?php else: ?>
                    <form method="POST" class="flex gap-3">
                        <button name="check_out"
                                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded">
                            Check Out
                        </button>

                        <button type="button" onclick="history.back()"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded">
                            Back
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
