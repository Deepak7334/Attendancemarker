<?php
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="min-h-screen bg-gray-100 font-sans">

<div class="min-h-screen bg-gray-100 font-sans">

    <!-- Right main area -->
    <div class="flex flex-col">

        <!-- Top green header -->
        <header class="h-16 bg-emerald-600 text-white flex items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <a href="index.php?page=admin-dashboard" class="bg-white text-emerald-600 px-3 py-1 rounded text-sm hover:bg-gray-100">
                    ← Back to Dashboard
                </a>
                <h2 class="text-xl font-semibold">Profile</h2>
            </div>
            <div class="text-sm">
                Welcome,
                <span class="font-semibold">
                    <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                </span>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Admin Profile</h3>

            <div class="bg-white rounded shadow-sm p-6">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mr-6">
                        <span class="text-2xl font-bold text-gray-600">
                            <?php echo strtoupper(substr($_SESSION['user']['username'], 0, 2)); ?>
                        </span>
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></h4>
                        <p class="text-gray-600"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Admin
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h5>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role</label>
                                <p class="mt-1 text-sm text-gray-900">Administrator</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h5>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">User ID</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($_SESSION['user']['id']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Joined Date</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo date('M d, Y', strtotime($_SESSION['user']['created_at'] ?? 'now')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'recent-attendance.php'; ?>
        </main>
    </div>
</div>

</body>
