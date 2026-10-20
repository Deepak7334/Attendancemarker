<?php

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
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Departments Management</h3>
            <p class="text-gray-600">This is the Departments page. Functionality to be implemented.</p>

            <?php include 'recent-attendance.php'; ?>
        </main>
    </div>
</div>

</body>
