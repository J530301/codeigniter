<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/user/dashboard" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800"><?= $title ?></h1>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Mobile Search Toggle -->
                    <button id="mobileSearchToggle" class="sm:hidden text-gray-600 hover:text-gray-800 p-2">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                    
                    <!-- Desktop Search -->
                    <div class="hidden sm:flex items-center">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search bills..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <a href="/user/create-bill" class="bg-indigo-600 text-white px-3 py-2 sm:px-4 rounded-md hover:bg-indigo-700 text-sm">
                        <i class="fas fa-plus mr-1 sm:mr-2"></i><span class="hidden sm:inline">Create New Bill</span><span class="sm:hidden">New</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Search Bar (Hidden by default) -->
    <div id="mobileSearchBar" class="sm:hidden bg-white border-b border-gray-200 px-4 py-3 hidden">
        <div class="relative">
            <input type="text" id="mobileSearchInput" placeholder="Search bills..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <main class="p-4 sm:p-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">All Bills</h3>
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="billsTableBody">
                        <?php if (empty($bills)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-lg font-medium mb-2">No bills found</p>
                                        <p class="text-sm mb-4">You haven't created any bills yet.</p>
                                        <a href="/user/create-bill" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                            <i class="fas fa-plus mr-2"></i>Create your first bill
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bills as $bill): ?>
                                <tr class="hover:bg-gray-50 bill-row" 
                                    data-search="<?= htmlspecialchars(strtolower($bill['item_name'] . ' ' . $bill['description'] . ' ' . $bill['status'] . ' ' . $bill['id'])) ?>">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #<?= $bill['id'] ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium"><?= htmlspecialchars($bill['item_name']) ?></div>
                                        <?php if (!empty($bill['description'])): ?>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars(substr($bill['description'], 0, 50)) ?><?= strlen($bill['description']) > 50 ? '...' : '' ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-semibold">$<?= number_format($bill['total_amount'], 2) ?></div>
                                        <div class="text-sm text-gray-500">
                                            $<?= number_format($bill['price'], 2) ?> × <?= $bill['quantity'] ?>
                                        </div>
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
                                        <div><?= date('M j, Y', strtotime($bill['created_at'])) ?></div>
                                        <div class="text-xs"><?= date('h:i A', strtotime($bill['created_at'])) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="/user/bill/<?= $bill['id'] ?>" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <?php if ($bill['status'] === 'pending'): ?>
                                            <span class="text-gray-400">
                                                <i class="fas fa-clock mr-1"></i>Pending Review
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile Card View -->
            <div class="md:hidden" id="billsCardContainer">
                <?php if (empty($bills)): ?>
                    <div class="p-6 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                            <p class="text-lg font-medium mb-2">No bills found</p>
                            <p class="text-sm mb-4">You haven't created any bills yet.</p>
                            <a href="/user/create-bill" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                <i class="fas fa-plus mr-2"></i>Create your first bill
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($bills as $bill): ?>
                            <div class="p-4 bill-card" 
                                 data-search="<?= htmlspecialchars(strtolower($bill['item_name'] . ' ' . $bill['description'] . ' ' . $bill['status'] . ' ' . $bill['id'])) ?>">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">#<?= $bill['id'] ?></span>
                                        <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
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
                                        <div class="text-sm text-gray-500"><?= date('M j, Y', strtotime($bill['created_at'])) ?></div>
                                        <div class="text-xs text-gray-400"><?= date('h:i A', strtotime($bill['created_at'])) ?></div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <h4 class="text-base font-medium text-gray-900 mb-1"><?= htmlspecialchars($bill['item_name']) ?></h4>
                                    <?php if (!empty($bill['description'])): ?>
                                        <p class="text-sm text-gray-600"><?= htmlspecialchars($bill['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-lg font-semibold text-gray-900">$<?= number_format($bill['total_amount'], 2) ?></div>
                                        <div class="text-sm text-gray-500">
                                            $<?= number_format($bill['price'], 2) ?> × <?= $bill['quantity'] ?>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="/user/bill/<?= $bill['id'] ?>" 
                                           class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <?php if ($bill['status'] === 'pending'): ?>
                                            <span class="text-xs text-gray-400 flex items-center">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($bills)): ?>
                <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span class="font-medium" id="showingCount"><?= count($bills) ?></span> bill(s)
                        </div>
                        <div class="text-sm text-gray-500">
                            Total Amount: <span class="font-semibold text-gray-900">
                                $<?= number_format(array_sum(array_column($bills, 'total_amount')), 2) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile search toggle functionality
    const mobileSearchToggle = document.getElementById('mobileSearchToggle');
    const mobileSearchBar = document.getElementById('mobileSearchBar');
    const searchInput = document.getElementById('searchInput');
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    const billRows = document.querySelectorAll('.bill-row');
    const billCards = document.querySelectorAll('.bill-card');
    const showingCount = document.getElementById('showingCount');

    // Toggle mobile search bar
    if (mobileSearchToggle && mobileSearchBar) {
        mobileSearchToggle.addEventListener('click', function() {
            mobileSearchBar.classList.toggle('hidden');
            if (!mobileSearchBar.classList.contains('hidden')) {
                mobileSearchInput.focus();
            }
        });
    }

    // Search functionality
    function performSearch(query) {
        const searchTerm = query.toLowerCase().trim();
        let visibleCount = 0;

        // Search in desktop table rows
        billRows.forEach(function(row) {
            const searchData = row.getAttribute('data-search');
            if (searchData && searchData.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Search in mobile cards
        billCards.forEach(function(card) {
            const searchData = card.getAttribute('data-search');
            if (searchData && searchData.includes(searchTerm)) {
                card.style.display = '';
                if (window.innerWidth < 768) visibleCount++; // Only count for mobile view
            } else {
                card.style.display = 'none';
            }
        });

        // Update showing count (only count visible items based on current view)
        if (showingCount) {
            if (window.innerWidth >= 768) {
                // Desktop view - count table rows
                visibleCount = Array.from(billRows).filter(row => row.style.display !== 'none').length;
            } else {
                // Mobile view - count cards
                visibleCount = Array.from(billCards).filter(card => card.style.display !== 'none').length;
            }
            showingCount.textContent = visibleCount;
        }
    }

    // Add search event listeners
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            performSearch(this.value);
        });
    }

    if (mobileSearchInput) {
        mobileSearchInput.addEventListener('input', function() {
            performSearch(this.value);
            // Sync with desktop search
            if (searchInput) {
                searchInput.value = this.value;
            }
        });
    }

    // Sync search inputs
    if (searchInput && mobileSearchInput) {
        searchInput.addEventListener('input', function() {
            mobileSearchInput.value = this.value;
        });
    }

    // Handle window resize to update count display
    window.addEventListener('resize', function() {
        if (searchInput && searchInput.value) {
            performSearch(searchInput.value);
        } else if (mobileSearchInput && mobileSearchInput.value) {
            performSearch(mobileSearchInput.value);
        }
    });
});
</script>
<?= $this->endSection() ?>
