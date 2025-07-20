<?php

namespace App\Controllers;

class DatabaseController extends BaseController
{
    public function setup()
    {
        try {
            $db = \Config\Database::connect();
            
            // Create users table
            $usersSql = "CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                username VARCHAR(100) UNIQUE NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) DEFAULT 'user',
                status VARCHAR(20) DEFAULT 'active',
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            $db->query($usersSql);
            
            // Create bills table
            $billsSql = "CREATE TABLE IF NOT EXISTS bills (
                id SERIAL PRIMARY KEY,
                user_id INTEGER NOT NULL,
                item_name VARCHAR(255) NOT NULL,
                description TEXT,
                amount DECIMAL(10,2) NOT NULL,
                quantity INTEGER DEFAULT 1,
                total_amount DECIMAL(10,2) NOT NULL,
                status VARCHAR(20) DEFAULT 'pending',
                admin_comments TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )";
            
            $db->query($billsSql);
            
            // Create notifications table
            $notificationsSql = "CREATE TABLE IF NOT EXISTS notifications (
                id SERIAL PRIMARY KEY,
                user_id INTEGER NOT NULL,
                title VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                type VARCHAR(50) DEFAULT 'info',
                is_read BOOLEAN DEFAULT false,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )";
            
            $db->query($notificationsSql);
            
            // Create default admin user
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $adminSql = "INSERT INTO users (username, email, password, role, first_name, last_name) 
                        VALUES ('admin', 'admin@example.com', ?, 'admin', 'Admin', 'User')
                        ON CONFLICT (username) DO NOTHING";
            
            $db->query($adminSql, [$hashedPassword]);
            
            return 'Database setup completed successfully!<br>
                    Tables created: users, bills, notifications<br>
                    Default admin user created: admin / admin123<br>
                    <a href="' . base_url() . '">Go to Login</a>';
            
        } catch (\Exception $e) {
            return 'Database setup failed: ' . $e->getMessage();
        }
    }
    
    public function reset()
    {
        try {
            $db = \Config\Database::connect();
            
            $db->query("DROP TABLE IF EXISTS notifications");
            $db->query("DROP TABLE IF EXISTS bills");
            $db->query("DROP TABLE IF EXISTS users");
            
            return 'Database reset completed! <a href="' . base_url('database/setup') . '">Setup again</a>';
            
        } catch (\Exception $e) {
            return 'Database reset failed: ' . $e->getMessage();
        }
    }
}
