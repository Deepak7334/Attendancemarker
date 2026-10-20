<?php
// profile.php
require_once __DIR__ . '/../../models/User.php';
session_start();

// Check auth
if (!isset($_SESSION['user']['id'])) {
    header("Location: /login.php");
    exit();
}

$user = User::findById($_SESSION['user']['id']);
$username = $user['username'] ?? 'Employee';
$email = $user['email'] ?? '';
$role = $user['role'] ?? 'Team Member'; // Placeholder if not in DB
$avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=10b981&color=fff";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-slate-800">

    <?php include 'views/layout/employee-header.php'; ?>

    <div class="max-w-4xl mx-auto px-4 py-10">
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-8 text-center">
                <div class="relative inline-block group">
                    <img class="h-28 w-28 rounded-full mx-auto border-4 border-white object-cover shadow-md" 
                         src="<?php echo htmlspecialchars($avatarUrl); ?>" 
                         alt="Avatar">
                </div>
                <h2 class="mt-3 text-2xl font-bold text-white"><?php echo htmlspecialchars($username); ?></h2>
                <p class="text-emerald-100"><?php echo htmlspecialchars($role); ?></p>
            </div>

            <div class="p-8">
                <h3 class="text-gray-900 font-semibold text-lg mb-4 border-b pb-2">Personal Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide">Username</label>
                        <p class="mt-1 text-lg text-gray-900 font-medium"><?php echo htmlspecialchars($username); ?></p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide">Email Address</label>
                        <p class="mt-1 text-lg text-gray-900 font-medium"><?php echo htmlspecialchars($email); ?></p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide">Employee ID</label>
                        <p class="mt-1 text-lg text-gray-900 font-medium">#<?php echo htmlspecialchars($user['id']); ?></p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide">Account Status</label>
                        <span class="inline-flex mt-1 items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end items-center">
                <a href="edit-profile.php" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-lg transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>

</body>
</html>