<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="main-content min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <nav class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/user/bills" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800"><?= $title ?></h1>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="user-content p-4 sm:p-6 pb-24 min-h-screen"> <!-- Optimized mobile padding -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow rounded-lg">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Bill #<?= $bill['id'] ?></h3>
                            <p class="text-sm text-gray-600 mt-1">Created on <?= date('F j, Y \a\t h:i A', strtotime($bill['created_at'])) ?></p>
                        </div>
                        <div>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
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
                                <i class="fas fa-<?php
                                switch($bill['status']) {
                                    case 'pending':
                                        echo 'clock';
                                        break;
                                    case 'approved':
                                        echo 'check-circle';
                                        break;
                                    case 'rejected':
                                        echo 'times-circle';
                                        break;
                                    default:
                                        echo 'question-circle';
                                }
                                ?> mr-1"></i>
                                <?= ucfirst($bill['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Bill Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Item Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Item Name</label>
                                <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                    <?= htmlspecialchars($bill['item_name']) ?>
                                </p>
                            </div>
                            
                            <?php if (!empty($bill['description'])): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                        <?= htmlspecialchars($bill['description']) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pricing Information -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Price per Item</label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                        $<?= number_format($bill['price'], 2) ?>
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                        <?= $bill['quantity'] ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900 bg-indigo-50 px-3 py-2 rounded-md border border-indigo-200">
                                    $<?= number_format($bill['total_amount'], 2) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status Information -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <strong>Status:</strong> 
                            <?php
                            switch($bill['status']) {
                                case 'pending':
                                    echo 'Your bill is under review by the administrator.';
                                    break;
                                case 'approved':
                                    echo 'Your bill has been approved and processed.';
                                    break;
                                case 'rejected':
                                    echo 'Your bill has been rejected. Please contact the administrator for more details.';
                                    break;
                                default:
                                    echo 'Status unknown.';
                            }
                            ?>
                        </div>
                        
                        <?php if (!empty($bill['updated_at']) && $bill['updated_at'] !== $bill['created_at']): ?>
                            <div class="text-sm text-gray-500">
                                Last updated: <?= date('M j, Y h:i A', strtotime($bill['updated_at'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between">
                        <a href="/user/bills" 
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Bills
                        </a>
                        
                        <div class="space-x-3">
                            <a href="/user/create-bill" 
                               class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-plus mr-2"></i>Create New Bill
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile scroll spacer - ensures content can be fully scrolled on mobile -->
            <div class="block sm:hidden h-16"></div> <!-- Optimized spacer height -->
        </div>
    </main>
</div>
<?= $this->endSection() ?>
