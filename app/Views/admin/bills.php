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
            <a href="/admin/bills" class="flex items-center px-3 py-2 text-white bg-indigo-600 rounded-md">
                <i class="fas fa-file-invoice mr-3"></i>
                Bills Management
            </a>
        </div>
    </nav>
</div>

<!-- Main Content -->
<div class="min-h-screen bg-gray-100">
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

    <!-- Page Content -->
    <main class="p-6">
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
                <h3 class="text-lg font-medium text-gray-900">Bills Management</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($bills)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bills found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bills as $bill): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #<?= $bill['id'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-indigo-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars($bill['first_name'] . ' ' . $bill['last_name']) ?>
                                                </div>
                                                <div class="text-sm text-gray-500"><?= htmlspecialchars($bill['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900"><?= htmlspecialchars($bill['item_name']) ?></div>
                                        <?php if (!empty($bill['description'])): ?>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars(substr($bill['description'], 0, 50)) ?><?= strlen($bill['description']) > 50 ? '...' : '' ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">$<?= number_format($bill['total_amount'], 2) ?></div>
                                        <div class="text-sm text-gray-500">Qty: <?= $bill['quantity'] ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            <?= $bill['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($bill['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                                ($bill['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                                            <?= ucfirst($bill['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('M j, Y', strtotime($bill['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if ($bill['status'] === 'pending'): ?>
                                            <div class="flex flex-col space-y-2">
                                                <div class="flex space-x-2">
                                                    <a href="/admin/bills/approve/<?= $bill['id'] ?>" 
                                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors"
                                                       onclick="return confirm('Are you sure you want to approve this bill?')">
                                                        <i class="fas fa-check mr-1"></i>Approve
                                                    </a>
                                                    <a href="/admin/bills/reject/<?= $bill['id'] ?>" 
                                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors"
                                                       onclick="return confirm('Are you sure you want to reject this bill?')">
                                                        <i class="fas fa-times mr-1"></i>Reject
                                                    </a>
                                                </div>
                                                <a href="/admin/bills/delete/<?= $bill['id'] ?>" 
                                                   class="text-red-600 hover:text-red-900 text-xs transition-colors"
                                                   onclick="return confirm('Are you sure you want to delete this bill? This action cannot be undone.')">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex flex-col space-y-2">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md 
                                                    <?php
                                                    switch($bill['status']) {
                                                        case 'approved':
                                                            echo 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'rejected':
                                                            echo 'bg-red-100 text-red-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                    ?>">
                                                    <i class="fas fa-<?= $bill['status'] === 'approved' ? 'check-circle' : 'times-circle' ?> mr-1"></i>
                                                    <?= ucfirst($bill['status']) ?>
                                                </span>
                                                <a href="/admin/bills/delete/<?= $bill['id'] ?>" 
                                                   class="text-red-600 hover:text-red-900 text-xs transition-colors"
                                                   onclick="return confirm('Are you sure you want to delete this bill? This action cannot be undone.')">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </a>
                                            </div>
                                        <?php endif; ?>
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
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bills Management</h3>
            </div>
            
            <?php if (empty($bills)): ?>
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-center text-gray-500">No bills found</div>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($bills as $bill): ?>
                        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
                            <!-- Bill Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="text-lg font-semibold text-gray-900">
                                    Bill #<?= $bill['id'] ?>
                                </div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    <?= $bill['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($bill['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                        ($bill['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                                    <?= ucfirst($bill['status']) ?>
                                </span>
                            </div>
                            
                            <!-- User Information -->
                            <div class="mb-3 pb-3 border-b border-gray-100">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Customer</div>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 mr-3">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-user text-indigo-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($bill['first_name'] . ' ' . $bill['last_name']) ?>
                                        </div>
                                        <div class="text-xs text-gray-500"><?= htmlspecialchars($bill['email']) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Item Information -->
                            <div class="mb-3 pb-3 border-b border-gray-100">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Item</div>
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($bill['item_name']) ?></div>
                                <?php if (!empty($bill['description'])): ?>
                                    <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($bill['description']) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Amount & Date -->
                            <div class="grid grid-cols-2 gap-4 mb-3 pb-3 border-b border-gray-100">
                                <div>
                                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Amount</div>
                                    <div class="text-lg font-bold text-gray-900">$<?= number_format($bill['total_amount'], 2) ?></div>
                                    <div class="text-xs text-gray-500">Qty: <?= $bill['quantity'] ?></div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Date</div>
                                    <div class="text-sm text-gray-900"><?= date('M j, Y', strtotime($bill['created_at'])) ?></div>
                                    <div class="text-xs text-gray-500"><?= date('g:i A', strtotime($bill['created_at'])) ?></div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="pt-2">
                                <?php if ($bill['status'] === 'pending'): ?>
                                    <div class="space-y-2">
                                        <div class="grid grid-cols-2 gap-2">
                                            <a href="/admin/bills/approve/<?= $bill['id'] ?>" 
                                               class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors"
                                               onclick="return confirm('Are you sure you want to approve this bill?')">
                                                <i class="fas fa-check mr-2"></i>Approve
                                            </a>
                                            <a href="/admin/bills/reject/<?= $bill['id'] ?>" 
                                               class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors"
                                               onclick="return confirm('Are you sure you want to reject this bill?')">
                                                <i class="fas fa-times mr-2"></i>Reject
                                            </a>
                                        </div>
                                        <a href="/admin/bills/delete/<?= $bill['id'] ?>" 
                                           class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors"
                                           onclick="return confirm('Are you sure you want to delete this bill? This action cannot be undone.')">
                                            <i class="fas fa-trash mr-2"></i>Delete Bill
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-center p-2 rounded-md 
                                            <?php
                                            switch($bill['status']) {
                                                case 'approved':
                                                    echo 'bg-green-50 text-green-700';
                                                    break;
                                                case 'rejected':
                                                    echo 'bg-red-50 text-red-700';
                                                    break;
                                                default:
                                                    echo 'bg-gray-50 text-gray-700';
                                            }
                                            ?>">
                                            <i class="fas fa-<?= $bill['status'] === 'approved' ? 'check-circle' : 'times-circle' ?> mr-2"></i>
                                            <span class="text-sm font-medium"><?= ucfirst($bill['status']) ?></span>
                                        </div>
                                        <a href="/admin/bills/delete/<?= $bill['id'] ?>" 
                                           class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors"
                                           onclick="return confirm('Are you sure you want to delete this bill? This action cannot be undone.')">
                                            <i class="fas fa-trash mr-2"></i>Delete Bill
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<?= $this->endSection() ?>
