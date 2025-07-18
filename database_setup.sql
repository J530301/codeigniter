-- Business Management System Database Setup
-- Run this script in your MySQL database

-- Create database (optional - you can create it through phpMyAdmin)
-- CREATE DATABASE business_system;
-- USE business_system;

-- Users table
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bills table
CREATE TABLE `bills` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bills_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Notifications table
CREATE TABLE `notifications` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data
INSERT INTO `users` (`username`, `email`, `password`, `role`, `status`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', 'Admin', 'User', NOW(), NOW()),
('user1', 'user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'John', 'Doe', NOW(), NOW()),
('user2', 'user2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Jane', 'Smith', NOW(), NOW());

-- Insert sample bills
INSERT INTO `bills` (`user_id`, `item_name`, `description`, `price`, `quantity`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Office Supplies', 'Monthly office supplies purchase including pens, papers, and folders', 150.00, 1, 150.00, 'pending', NOW(), NOW()),
(2, 'Software License', 'Annual subscription for project management software', 299.99, 1, 299.99, 'approved', NOW(), NOW()),
(3, 'Business Cards', 'Professional business cards for marketing', 75.50, 2, 151.00, 'pending', NOW(), NOW());

-- Insert sample notifications
INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(2, 'User Login', 'User John Doe has logged in', 'login', 0, NOW(), NOW()),
(3, 'User Login', 'User Jane Smith has logged in', 'login', 0, NOW(), NOW());

-- Note: The password for demo users are as follows:
-- You can login with:
-- Admin: admin / admin123
-- User: user1 / user123 or user2 / user123
