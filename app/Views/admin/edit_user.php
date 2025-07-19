<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-100">
    <!-- Simple header -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/admin/users" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
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
                    <h3 class="text-lg font-medium text-gray-900">Edit User Information</h3>
                </div>
                
                <form action="/admin/users/update/<?= $user['id'] ?>" method="POST" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" 
                                   name="first_name" 
                                   id="first_name" 
                                   value="<?= htmlspecialchars($user['first_name']) ?>"
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" 
                                   name="last_name" 
                                   id="last_name" 
                                   value="<?= htmlspecialchars($user['last_name']) ?>"
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" 
                               name="username" 
                               id="username" 
                               value="<?= htmlspecialchars($user['username']) ?>"
                               required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="<?= htmlspecialchars($user['email']) ?>"
                               required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password (leave blank to keep current)</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Leave blank to keep the current password</p>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="/admin/users" 
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<?= $this->endSection() ?>
