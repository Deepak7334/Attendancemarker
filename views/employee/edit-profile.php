<?php
// edit-profile.php
require_once __DIR__ . '/../../models/User.php';
session_start();

// 1. Auth Check
if (!isset($_SESSION['user']['id'])) {
    header("Location: /login.php");
    exit();
}

// 2. Load current user data
$user = User::findById($_SESSION['user']['id']);
$error = '';

// 3. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim($_POST['username']);
    $newEmail = trim($_POST['email']);

    // Basic Validation
    if (empty($newUsername) || empty($newEmail)) {
        $error = "All fields are required.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Attempt Update
        $isUpdated = User::updateProfile($_SESSION['user']['id'], $newUsername, $newEmail);

        if ($isUpdated) {
            // CRITICAL: Update the session data immediately so the header changes too
            $_SESSION['user']['username'] = $newUsername;
            $_SESSION['user']['email'] = $newEmail;

            // Set success message for the next page
            $_SESSION['success'] = "Profile updated successfully!";
            
            // Redirect back to view profile
            header("Location: profile.php");
            exit();
        } else {
            $error = "Failed to update. Email might already be taken.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-slate-800">

    <?php include 'views/layout/employee-header.php'; ?>

    <div class="max-w-2xl mx-auto px-4 py-10">
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Edit Profile</h2>
                <a href="profile.php" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            </div>

            <?php if($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-6 mb-0">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="p-6 space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" id="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50">
                    <a href="profile.php" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>