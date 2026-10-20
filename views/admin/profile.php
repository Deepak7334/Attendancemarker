<?php
$username = $_SESSION['user']['username'] ?? 'Admin';
$user = User::findById($_SESSION['user']['id'] ?? $_SESSION['user_id']);
$message = $message ?? '';
$editMode = isset($_GET['edit']) && $_GET['edit'] == '1';
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="min-h-screen bg-gray-100 font-sans">

<div class="min-h-screen bg-gray-100 font-sans">

    <!-- Right main area -->
    <div class="flex flex-col">

        <!-- HEADER -->
        <?php include 'views/layout/admin-header.php'; ?>

        <!-- Content -->
        <main class="flex-1 bg-gray-50 p-8 overflow-y-auto">
            <div class="max-w-6xl mx-auto">

                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Professional Profile</h1>
                    <p class="text-gray-600">Manage your professional information and account settings</p>
                </div>

                <!-- Success/Error Messages -->
                <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-lg shadow-sm <?php echo strpos($message, 'successfully') !== false ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'; ?>">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <?php if (strpos($message, 'successfully') !== false): ?>
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium"><?php echo htmlspecialchars($message); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="max-w-2xl mx-auto">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <!-- Profile Header -->
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 h-32 relative">
                            <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                                <div class="w-32 h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden">
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <img src="public/images/employees/<?php echo $user['profile_image']; ?>"
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                            <span class="text-4xl font-bold text-white">
                                                <?php echo strtoupper(substr($user['username'] ?? 'A', 0, 2)); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Content -->
                        <div class="pt-20 pb-8 px-6 text-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($user['username'] ?? 'Administrator'); ?></h3>
                            <p class="text-emerald-600 font-medium mb-4">System Administrator</p>

                            <!-- Profile Details -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-700"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span class="text-gray-700"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></span>
                                </div>
                            </div>

                            <!-- Edit Profile Button -->
                            <div class="mt-8">
                                <a href="index.php?page=admin-edit-profile"
                                   class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
<?php
