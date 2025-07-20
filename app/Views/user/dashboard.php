<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="main-content min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <nav class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-800"><?= $title ?></h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="profileButton" onclick="toggleProfileDropdown()" class="flex items-center text-gray-600 hover:text-gray-800">
                            <span class="mr-2 text-sm"><?= session()->get('first_name') ?></span>
                            <i class="fas fa-user-circle text-2xl"></i>
                        </button>
                        
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900"><?= session()->get('first_name') . ' ' . session()->get('last_name') ?></p>
                                    <p class="text-sm text-gray-600"><?= session()->get('email') ?></p>
                                </div>
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
    <main class="user-content p-4 sm:p-6 pb-40 min-h-screen"> <!-- Increased mobile padding to pb-40 -->
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

        <!-- Welcome Section -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome back, <?= session()->get('first_name') ?>!</h2>
            <p class="text-gray-600">Manage your bills and track your submissions here.</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-invoice text-2xl text-blue-600"></i>
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
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Approved Bills</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $approvedBills ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/user/create-bill" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg border border-indigo-200">
                    <i class="fas fa-plus-circle text-2xl text-indigo-600 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Create New Bill</h4>
                        <p class="text-sm text-gray-600">Submit a new bill for approval</p>
                    </div>
                </a>
                
                <a href="/user/bills" class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200">
                    <i class="fas fa-list text-2xl text-gray-600 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">View All Bills</h4>
                        <p class="text-sm text-gray-600">See all your submitted bills</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Bills -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Bills</h3>
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="recentBillsTableBody">
                        <?php if (empty($bills)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No bills found. <a href="/user/create-bill" class="text-indigo-600 hover:text-indigo-500">Create your first bill</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach (array_slice($bills, 0, 5) as $bill): ?>
                                <tr class="recent-bill-row">
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium"><?= htmlspecialchars($bill['item_name']) ?></div>
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
                                            <?php
                                            switch($bill['status']) {
                                                case 'pending':
                                                    echo 'bg-yellow-100 text-yellow-800';
                                                    break;
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
                                            <?= ucfirst($bill['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('M j, Y', strtotime($bill['created_at'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile Card View -->
            <div class="md:hidden" id="recentBillsCardContainer">
                <?php if (empty($bills)): ?>
                    <div class="p-6 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-file-invoice text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm mb-2">No bills found</p>
                            <a href="/user/create-bill" class="text-indigo-600 hover:text-indigo-500 text-sm">Create your first bill</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach (array_slice($bills, 0, 5) as $bill): ?>
                            <div class="p-4 recent-bill-card">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            <?php
                                            switch($bill['status']) {
                                                case 'pending':
                                                    echo 'bg-yellow-100 text-yellow-800';
                                                    break;
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
                                            <?= ucfirst($bill['status']) ?>
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500"><?= date('M j, Y', strtotime($bill['created_at'])) ?></div>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <h4 class="text-sm font-medium text-gray-900 mb-1"><?= htmlspecialchars($bill['item_name']) ?></h4>
                                    <?php if (!empty($bill['description'])): ?>
                                        <p class="text-xs text-gray-600"><?= htmlspecialchars(substr($bill['description'], 0, 80)) ?><?= strlen($bill['description']) > 80 ? '...' : '' ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">$<?= number_format($bill['total_amount'], 2) ?></div>
                                        <div class="text-xs text-gray-500">Qty: <?= $bill['quantity'] ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (count($bills) > 5): ?>
                <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-200">
                    <a href="/user/bills" class="text-sm text-indigo-600 hover:text-indigo-500">
                        View all <?= count($bills) ?> bills →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('hidden');
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const profileButton = document.getElementById('profileButton');
        if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // No additional functionality needed for recent bills section
});
</script>

        <!-- Mobile scroll spacer - ensures content can be fully scrolled on mobile -->
        <div class="block sm:hidden h-32"></div> <!-- Increased from h-20 to h-32 -->
    </main>
</div>
<?= $this->endSection() ?>
