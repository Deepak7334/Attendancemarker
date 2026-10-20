<header class="h-16 bg-emerald-600 text-white flex items-center justify-between px-6 relative">
    <span>Welcome <b><?php echo htmlspecialchars($username); ?></b></span>
    <div class="relative">
        <button id="dropdownToggle" class="text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01"/>
            </svg>
        </button>
        <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
            <div class="py-1">
                <a href="index.php?page=admin-dashboard" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    🏠 Dashboard
                </a>
                <a href="index.php?page=admin-employees" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    👥 Employees
                </a>
                <a href="index.php?page=admin-departments" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    🏢 Departments
                </a>
                <a href="index.php?page=admin-manage-attendance" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    📊 Attendance
                </a>
                <a href="index.php?page=admin-attendance-report" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    📈 Attendance Report
                </a>
                <a href="index.php?page=admin-salary" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    💳 Salary
                </a>
                <a href="index.php?page=admin-profile" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    👤 Profile
                </a>
                <a href="index.php?page=logout" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    🚪 Logout
                </a>
            </div>
        </div>
    </div>
</header>
<script>
document.getElementById('dropdownToggle').addEventListener('click', function() {
    var menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
});
// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    var toggle = document.getElementById('dropdownToggle');
    var menu = document.getElementById('dropdownMenu');
    if (!toggle.contains(event.target) && !menu.contains(event.target)) {
        menu.classList.add('hidden');
    }
});
</script>
