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
            <a href="/admin/users" class="flex items-center px-3 py-2 text-white bg-indigo-600 rounded-md">
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
    </nav>
</div>

<!-- Main Content -->
<div class="main-content min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <nav class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800"><?= $title ?></h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Search Bar - Desktop -->
                    <div class="hidden md:block relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search users..." 
                               class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    
                    <!-- Search Icon - Mobile -->
                    <button id="mobileSearchBtn" onclick="toggleMobileSearch()" class="md:hidden text-gray-600 hover:text-gray-800">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                    <!-- Notifications -->
                    <div class="relative">
                        <button id="notificationButton" onclick="toggleNotificationDropdown()" class="text-gray-600 hover:text-gray-800 relative">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                <?= $unreadNotifications ?? 0 ?>
                            </span>
                        </button>
                        
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 max-w-sm bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                </div>
                                <div id="notificationContainer" class="max-h-64 overflow-y-auto">
                                    <!-- Notifications will be loaded here -->
                                </div>
                                <div class="px-4 py-3 border-t border-gray-200">
                                    <a href="/admin/notifications" class="text-sm text-indigo-600 hover:text-indigo-500 block text-center">View all notifications</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile -->
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

    <!-- Mobile Search Bar (hidden by default) -->
    <div id="mobileSearchBar" class="hidden md:hidden bg-white border-b border-gray-200 px-4 py-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" 
                   id="mobileSearchInput" 
                   placeholder="Search users..." 
                   class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button onclick="toggleMobileSearch()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <main class="p-4 sm:p-6 pb-32 min-h-screen"> <!-- Enhanced mobile padding -->
        <?php if (session()->get('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->get('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->get('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="bg-white shadow rounded-lg hidden md:block">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">User Management</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="bg-white divide-y divide-gray-200">
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-indigo-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                                </div>
                                                <div class="text-sm text-gray-500">@<?= htmlspecialchars($user['username']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= htmlspecialchars($user['email']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= ucfirst($user['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="/admin/users/edit/<?= $user['id'] ?>" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        
                                        <a href="/admin/users/toggle-status/<?= $user['id'] ?>" 
                                           class="<?= $user['status'] === 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' ?>"
                                           onclick="return confirm('Are you sure you want to <?= $user['status'] === 'active' ? 'deactivate' : 'activate' ?> this user?')">
                                            <i class="fas fa-<?= $user['status'] === 'active' ? 'ban' : 'check' ?> mr-1"></i>
                                            <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                        </a>
                                        
                                        <a href="/admin/users/delete/<?= $user['id'] ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View (visible only on mobile) -->
        <div class="block md:hidden">
            <div class="bg-white shadow rounded-lg mb-4 p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">User Management</h3>
            </div>
            
            <?php if (empty($users)): ?>
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-center text-gray-500">No users found</div>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($users as $user): ?>
                        <div class="mobile-user-card bg-white shadow rounded-lg p-4 border border-gray-200">
                            <!-- User Header -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 mr-3">
                                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-user text-indigo-600 text-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-semibold text-gray-900">
                                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">@<?= htmlspecialchars($user['username']) ?></div>
                                    </div>
                                </div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="mb-3 pb-3 border-b border-gray-100">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Contact</div>
                                <div class="text-sm text-gray-900"><?= htmlspecialchars($user['email']) ?></div>
                            </div>
                            
                            <!-- Account Details -->
                            <div class="mb-3 pb-3 border-b border-gray-100">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Account Created</div>
                                <div class="text-sm text-gray-900"><?= date('M j, Y', strtotime($user['created_at'])) ?></div>
                                <div class="text-xs text-gray-500"><?= date('g:i A', strtotime($user['created_at'])) ?></div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="pt-2">
                                <div class="space-y-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="/admin/users/edit/<?= $user['id'] ?>" 
                                           class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>Edit User
                                        </a>
                                        
                                        <a href="/admin/users/toggle-status/<?= $user['id'] ?>" 
                                           class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white 
                                           <?= $user['status'] === 'active' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' ?> transition-colors"
                                           onclick="return confirm('Are you sure you want to <?= $user['status'] === 'active' ? 'deactivate' : 'activate' ?> this user?')">
                                            <i class="fas fa-<?= $user['status'] === 'active' ? 'ban' : 'check' ?> mr-2"></i>
                                            <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                        </a>
                                    </div>
                                    
                                    <a href="/admin/users/delete/<?= $user['id'] ?>" 
                                       class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors"
                                       onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <i class="fas fa-trash mr-2"></i>Delete User
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function toggleMobileSearch() {
    const mobileSearchBar = document.getElementById('mobileSearchBar');
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    
    if (mobileSearchBar.classList.contains('hidden')) {
        mobileSearchBar.classList.remove('hidden');
        mobileSearchInput.focus();
    } else {
        mobileSearchBar.classList.add('hidden');
        mobileSearchInput.value = '';
        // Trigger search clear
        filterUsers('');
    }
}

function filterUsers(searchTerm) {
    const users = document.querySelectorAll('#usersTableBody tr, .mobile-user-card');
    
    users.forEach(user => {
        const text = user.textContent.toLowerCase();
        if (text.includes(searchTerm.toLowerCase()) || searchTerm === '') {
            user.style.display = '';
        } else {
            user.style.display = 'none';
        }
    });
}

// Add event listeners for search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterUsers(this.value);
        });
    }
    
    if (mobileSearchInput) {
        mobileSearchInput.addEventListener('input', function() {
            filterUsers(this.value);
        });
    }
});
</script>

        <!-- Mobile scroll spacer - ensures content can be fully scrolled on mobile -->
        <div class="block sm:hidden h-20"></div>
    </main>
</div>
<?= $this->endSection() ?>
