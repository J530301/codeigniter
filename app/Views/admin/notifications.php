<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Include the same sidebar as dashboard -->
<div id="sidebar" class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg">
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Business System</h2>
        <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav class="mt-4">
        <div class="px-4 py-2">
            <a href="/admin/dashboard" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
        </div>
        
        <div class="px-4 py-2">
            <a href="/admin/users" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-users mr-3"></i>
                User Accounts
            </a>
        </div>
        
        <div class="px-4 py-2">
            <a href="/admin/bills" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-file-invoice mr-3"></i>
                Bills Management
            </a>
        </div>
        
        <div class="px-4 py-2">
            <a href="/admin/notifications" class="flex items-center px-3 py-2 text-white bg-indigo-600 rounded-md">
                <i class="fas fa-bell mr-3"></i>
                Notifications
            </a>
        </div>
    </nav>
</div>

<!-- Main Content -->
<div class="min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-800"><?= $title ?></h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="profileButton" onclick="toggleProfileDropdown()" class="flex items-center text-gray-600 hover:text-gray-800">
                            <i class="fas fa-user-circle text-2xl"></i>
                        </button>
                        
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1">
                                <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="p-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">All Notifications</h3>
                    <button onclick="markAllAsRead()" class="text-sm text-indigo-600 hover:text-indigo-500">
                        Mark all as read
                    </button>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200">
                <?php if (empty($notifications)): ?>
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-bell text-4xl text-gray-300 mb-3"></i>
                        <p class="text-lg font-medium mb-2">No notifications</p>
                        <p class="text-sm">You're all caught up! Notifications will appear here when users log in.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="p-6 hover:bg-gray-50 <?= !$notification['is_read'] ? 'bg-blue-50' : '' ?>">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-<?= $notification['type'] === 'login' ? 'sign-in-alt' : 'bell' ?> text-indigo-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($notification['title']) ?>
                                            <?php if (!$notification['is_read']): ?>
                                                <span class="ml-2 w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                                            <?php endif; ?>
                                        </h4>
                                        <div class="text-sm text-gray-500">
                                            <?= date('M j, Y h:i A', strtotime($notification['created_at'])) ?>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <?= htmlspecialchars($notification['message']) ?>
                                    </p>
                                    <div class="mt-2 flex items-center text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-1"></i>
                                            <?= htmlspecialchars($notification['first_name'] . ' ' . $notification['last_name']) ?>
                                        </span>
                                        <span class="mx-2">•</span>
                                        <span class="capitalize"><?= htmlspecialchars($notification['type']) ?> notification</span>
                                        <?php if (!$notification['is_read']): ?>
                                            <span class="mx-2">•</span>
                                            <button onclick="markAsRead(<?= $notification['id'] ?>)" 
                                                    class="text-indigo-600 hover:text-indigo-500">
                                                Mark as read
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($notifications)): ?>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="text-sm text-gray-700">
                        Showing <?= count($notifications) ?> notification(s)
                        <span class="mx-2">•</span>
                        <?php 
                        $unreadCount = array_sum(array_column($notifications, 'is_read')) - count($notifications);
                        $unreadCount = abs($unreadCount);
                        ?>
                        <?= $unreadCount ?> unread
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('hidden');
}

// Close profile dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    const profileButton = document.getElementById('profileButton');
    
    if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

function markAsRead(notificationId) {
    fetch(`/admin/api/notifications/mark-read/${notificationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

function markAllAsRead() {
    if (confirm('Mark all notifications as read?')) {
        // This would require a new route in the controller
        console.log('Mark all as read functionality would be implemented here');
        location.reload();
    }
}
</script>
<?= $this->endSection() ?>
