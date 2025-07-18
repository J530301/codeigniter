<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/user/dashboard" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800"><?= $title ?></h1>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="p-6">
        <?php if (session()->get('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    <?php foreach (session()->get('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->get('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= session()->get('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= session()->get('error') ?>
            </div>
        <?php endif; ?>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Create New Bill</h3>
                    <p class="text-sm text-gray-600 mt-1">Fill out the form below to submit a new bill for approval</p>
                </div>
                
                <form action="/user/store-bill" method="POST" class="p-6 space-y-6" id="billForm">
                    <div>
                        <label for="item_name" class="block text-sm font-medium text-gray-700">Item Name *</label>
                        <input type="text" 
                               name="item_name" 
                               id="item_name" 
                               value="<?= old('item_name') ?>"
                               required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="Enter the item name">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Enter a description (optional)"><?= old('description') ?></textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional: Provide additional details about the item</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price per Item *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="price" 
                                       id="price" 
                                       step="0.01"
                                       min="0.01"
                                       value="<?= old('price') ?>"
                                       required
                                       onchange="calculateTotal()"
                                       class="pl-7 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="0.00">
                            </div>
                        </div>
                        
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   min="1"
                                   value="<?= old('quantity', 1) ?>"
                                   required
                                   onchange="calculateTotal()"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="1">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                        <div class="mt-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md">
                            <span class="text-lg font-semibold text-gray-900" id="totalAmount">$0.00</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">This will be calculated automatically based on price and quantity</p>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="/user/dashboard" 
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i>
                            Create Bill
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function calculateTotal() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const quantity = parseInt(document.getElementById('quantity').value) || 0;
    const total = price * quantity;
    
    document.getElementById('totalAmount').textContent = '$' + total.toFixed(2);
}

// Calculate total on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
<?= $this->endSection() ?>
