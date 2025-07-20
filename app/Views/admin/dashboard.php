<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Sidebar -->
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

        <!-- Modules Dropdown -->
        <div class="px-4 py-2">
            <button onclick="toggleDropdown('modulesDropdown')" class="flex items-center w-full px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-cubes mr-3"></i>
                Modules
                <i class="fas fa-chevron-down ml-auto"></i>
            </button>
            <div id="modulesDropdown" class="dropdown-content ml-6 mt-2 space-y-1">
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Sales</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Collections</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Credit Memo</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Accounts Payable</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Check Voucher</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Debit Memo</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">General Journal</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Request for Payment</a>
            </div>
        </div>

        <!-- Adjustment Dropdown -->
        <div class="px-4 py-2">
            <button onclick="toggleDropdown('adjustmentDropdown')" class="flex items-center w-full px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-adjust mr-3"></i>
                Adjustment
                <i class="fas fa-chevron-down ml-auto"></i>
            </button>
            <div id="adjustmentDropdown" class="dropdown-content ml-6 mt-2 space-y-1">
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Year End Adjustment</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Audit Adjustment</a>
            </div>
        </div>

        <!-- Budget Dropdown -->
        <div class="px-4 py-2">
            <button onclick="toggleDropdown('budgetDropdown')" class="flex items-center w-full px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-chart-pie mr-3"></i>
                Budget
                <i class="fas fa-chevron-down ml-auto"></i>
            </button>
            <div id="budgetDropdown" class="dropdown-content ml-6 mt-2 space-y-1">
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Budget</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Variance Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Actual Expense Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Budget History</a>
            </div>
        </div>

        <!-- Report Dropdown -->
        <div class="px-4 py-2">
            <button onclick="toggleDropdown('reportDropdown')" class="flex items-center w-full px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-chart-bar mr-3"></i>
                Reports
                <i class="fas fa-chevron-down ml-auto"></i>
            </button>
            <div id="reportDropdown" class="dropdown-content ml-6 mt-2 space-y-1">
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Accounts Payable Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Check Voucher Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Sales Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Accounts Receivable Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">General Journal Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">CAJE Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">PAJE Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Collection Report</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Inventory List</a>
                <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Inventory Movement Report</a>
            </div>
        </div>

        <div class="px-4 py-2">
            <a href="#" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-file-alt mr-3"></i>
                Tax Forms
            </a>
        </div>

        <div class="px-4 py-2">
            <a href="#" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                <i class="fas fa-calculator mr-3"></i>
                Financial Statement
            </a>
        </div>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800"><?= $title ?></h1>
                </div>
                
                <div class="flex items-center space-x-4">
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
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900"><?= session()->get('first_name') . ' ' . session()->get('last_name') ?></p>
                                    <p class="text-sm text-gray-600"><?= session()->get('email') ?></p>
                                </div>
                                <a href="/admin/notifications" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-bell mr-2"></i>Notifications
                                </a>
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
    <main class="p-4 sm:p-6 pb-20"> <!-- Add bottom padding for mobile -->
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

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-2xl text-indigo-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $totalUsers ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-invoice text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Bills</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $totalBills ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Bills</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $pendingBills ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bell text-2xl text-red-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Notifications</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $unreadNotifications ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-4 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="/admin/users" class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-users text-2xl text-indigo-600 mr-3 flex-shrink-0"></i>
                    <div class="min-w-0">
                        <h4 class="font-medium text-gray-900">Manage Users</h4>
                        <p class="text-sm text-gray-600">View and manage user accounts</p>
                    </div>
                </a>
                
                <a href="/admin/bills" class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-file-invoice text-2xl text-green-600 mr-3 flex-shrink-0"></i>
                    <div class="min-w-0">
                        <h4 class="font-medium text-gray-900">Manage Bills</h4>
                        <p class="text-sm text-gray-600">Review and manage bills</p>
                    </div>
                </a>
                
                <a href="/admin/notifications" class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bell text-2xl text-red-600 mr-3 flex-shrink-0"></i>
                    <div class="min-w-0">
                        <h4 class="font-medium text-gray-900">View Notifications</h4>
                        <p class="text-sm text-gray-600">Check recent notifications</p>
                    </div>
                </a>
            </div>
        </div>
    </main>
</div>
<?= $this->endSection() ?>