<?php
$username = $_SESSION['user']['username'] ?? 'Admin';
$message = $message ?? '';
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
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Add New Employee</h3>
                <a href="index.php?page=admin-employees"
                   class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">
                    Back to Employees
                </a>
            </div>

            <?php if ($message): ?>
                <div class="mb-4 p-4 rounded <?php echo strpos($message, 'successfully') !== false ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded shadow-sm p-8">
                    <form method="POST" action="" id="addEmployeeForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                    Username *
                                </label>
                                <input type="text"
                                       id="username"
                                       name="username"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Choose a unique username for the employee</p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address *
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Employee's email address</p>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password *
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       required
                                       minlength="6"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Minimum 6 characters</p>
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password *
                                </label>
                                <input type="password"
                                       id="confirm_password"
                                       name="confirm_password"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Re-enter the password</p>
                            </div>

                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name
                                </label>
                                <input type="text"
                                       id="first_name"
                                       name="first_name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name
                                </label>
                                <input type="text"
                                       id="last_name"
                                       name="last_name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number
                                </label>
                                <input type="tel"
                                       id="phone"
                                       name="phone"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                    Department
                                </label>
                                <select id="department"
                                        name="department"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    <option value="">Select Department</option>
                                    <option value="IT">IT</option>
                                    <option value="HR">HR</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Operations">Operations</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-4">
                            <button type="submit"
                                    class="bg-emerald-600 text-white px-6 py-2 rounded-md text-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                Add Employee
                            </button>
                            <button type="reset"
                                    class="bg-gray-600 text-white px-6 py-2 rounded-md text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php include 'recent-attendance.php'; ?>
        </main>
    </div>
</div>

<script>
document.getElementById('addEmployeeForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();

    // Password validation
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return;
    }

    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long!');
        return;
    }

    // Username validation
    if (username.length < 3) {
        e.preventDefault();
        alert('Username must be at least 3 characters long!');
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address!');
        return;
    }
});

// Real-time password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const button = document.querySelector('button[type="submit"]');

    if (confirmPassword && password !== confirmPassword) {
        this.style.borderColor = '#ef4444';
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        this.style.borderColor = '#d1d5db';
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
    }
});
</script>

</body>
