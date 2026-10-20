<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

$username = $_SESSION['user']['username'] ?? 'Admin';
$user = User::findById($_SESSION['user']['id'] ?? $_SESSION['user_id']);
$message = $message ?? '';
?>

<script src="https://cdn.tailwindcss.com"></script>

<body class="min-h-screen bg-gray-100 font-sans">

<div class="min-h-screen bg-gray-100 font-sans">

    <!-- Right main area -->
    <div class="flex flex-col">

        <!-- Top green header -->
        <header class="h-16 bg-emerald-600 text-white flex items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <a href="index.php?page=admin-profile" class="bg-white text-emerald-600 px-3 py-1 rounded text-sm hover:bg-gray-100">
                    ← Back to Profile
                </a>
                <h2 class="text-xl font-semibold">Edit Profile</h2>
            </div>
            <div class="text-sm">
                Welcome,
                <span class="font-semibold">
                    <?php echo htmlspecialchars($username); ?>
                </span>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 bg-gray-50 p-8 overflow-y-auto">
            <div class="max-w-2xl mx-auto">

                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Profile</h1>
                    <p class="text-gray-600">Update your personal information and account settings</p>
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

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_profile">

                        <div class="space-y-6">
                            <div>
                                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="username"
                                       name="username"
                                       value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors duration-200">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors duration-200">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mobile Number
                                </label>
                                <input type="tel"
                                       id="phone"
                                       name="phone"
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors duration-200">
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Change Password (Optional)</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-xs font-medium text-gray-600 mb-1">
                                            Current Password
                                        </label>
                                        <input type="password"
                                               id="current_password"
                                               name="current_password"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                                    </div>
                                    <div>
                                        <label for="new_password" class="block text-xs font-medium text-gray-600 mb-1">
                                            New Password
                                        </label>
                                        <input type="password"
                                               id="new_password"
                                               name="new_password"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                                    </div>
                                    <div>
                                        <label for="confirm_password" class="block text-xs font-medium text-gray-600 mb-1">
                                            Confirm New Password
                                        </label>
                                        <input type="password"
                                               id="confirm_password"
                                               name="confirm_password"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-4">
                            <button type="submit"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Changes
                            </button>
                            <a href="index.php?page=admin-profile"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 inline-block text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Add password confirmation validation
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    function validatePassword() {
        if (newPassword.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    newPassword.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);
});
</script>

</body>
