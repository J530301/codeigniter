<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Business Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.active {
            transform: translateX(0);
        }
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        .dropdown-content.active {
            max-height: 500px;
        }
        
        /* Mobile notification dropdown styles */
        @media (max-width: 640px) {
            #notificationDropdown {
                position: fixed !important;
                top: 4rem !important;
                right: 0.5rem !important;
                left: 0.5rem !important;
                width: auto !important;
                max-width: none !important;
                z-index: 50 !important;
            }
            
            #notificationDropdown .max-h-64 {
                max-height: 50vh;
            }
        }
        
        @media (max-width: 480px) {
            #notificationDropdown {
                right: 0.25rem !important;
                left: 0.25rem !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <?= $this->renderSection('content') ?>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Toggle dropdown
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('active');
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Toggle notification dropdown
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');
            
            // Load notifications if admin
            if (!dropdown.classList.contains('hidden')) {
                loadNotifications();
            }
        }

        // Load notifications for admin
        function loadNotifications() {
            fetch('/admin/api/notifications')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('notificationContainer');
                    if (data.length === 0) {
                        container.innerHTML = '<div class="p-4 text-gray-500 text-center text-sm">No new notifications</div>';
                        return;
                    }
                    
                    let html = '';
                    data.forEach(notification => {
                        // Determine icon based on notification type
                        let icon = 'fas fa-info-circle text-blue-500';
                        if (notification.type === 'bill_created') {
                            icon = 'fas fa-file-invoice text-green-500';
                        } else if (notification.type === 'login') {
                            icon = 'fas fa-sign-in-alt text-indigo-500';
                        }
                        
                        html += `
                            <div class="p-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer" onclick="handleNotificationClick('${notification.type}', ${notification.id})">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="${icon} text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">${notification.title}</p>
                                                <p class="text-sm text-gray-600 break-words">${notification.message}</p>
                                                <p class="text-xs text-gray-400 mt-1">${new Date(notification.created_at).toLocaleString()}</p>
                                            </div>
                                            ${!notification.is_read ? '<div class="w-2 h-2 bg-blue-500 rounded-full ml-2 mt-1 flex-shrink-0"></div>' : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                })
                .catch(error => {
                    // Silent error handling for production
                    document.getElementById('notification-count').textContent = '0';
                });
        }

        // Mark notification as read
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
                    loadNotifications();
                }
            })
            .catch(error => {
                // Silent error handling for production
            });
        }

        function handleNotificationClick(type, notificationId) {
            // Mark as read first
            markAsRead(notificationId);
            
            // Redirect based on notification type
            if (type === 'bill_created') {
                window.location.href = '<?= base_url('admin/bills') ?>';
            }
            // Add more redirects for other types as needed
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileButton = document.getElementById('profileButton');
            const notificationButton = document.getElementById('notificationButton');

            if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.add('hidden');
            }

            if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>