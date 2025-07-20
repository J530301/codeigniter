<?php

namespace App\Controllers;

class DatabaseController extends BaseController
{
    public function setup()
    {
        try {
            $db = \Config\Database::connect();
            
            // Drop existing tables to recreate with correct schema
            $db->query("DROP TABLE IF EXISTS notifications CASCADE");
            $db->query("DROP TABLE IF EXISTS bills CASCADE");
            $db->query("DROP TABLE IF EXISTS users CASCADE");
            
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
            
            // Create bills table with PRICE column (not amount)
            $billsSql = "CREATE TABLE IF NOT EXISTS bills (
                id SERIAL PRIMARY KEY,
                user_id INTEGER NOT NULL,
                item_name VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10,2) NOT NULL,
                quantity INTEGER DEFAULT 1,
                total_amount DECIMAL(10,2) NOT NULL,
                status VARCHAR(20) DEFAULT 'pending',
                admin_comments TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
                    Tables recreated with correct schema:<br>
                    - users (with proper columns)<br>
                    - bills (with PRICE column, not amount)<br>
                    - notifications<br>
                    Default admin user created: admin / admin123<br>
                    <a href="' . base_url() . '">Go to Login</a> | 
                    <a href="' . base_url('test/checkTables') . '">Check Tables</a>';
            
        } catch (\Exception $e) {
            return 'Database setup failed: ' . $e->getMessage();
        }
    }
    
    public function fixSchema()
    {
        try {
            $db = \Config\Database::connect();
            
            // Check if bills table has wrong schema
            $fields = $db->getFieldNames('bills');
            
            if (in_array('amount', $fields) && !in_array('price', $fields)) {
                // Table has old schema, fix it
                $db->query("ALTER TABLE bills RENAME COLUMN amount TO price");
                return 'Schema fixed! Renamed "amount" column to "price" in bills table.<br>
                        <a href="' . base_url('test/checkTables') . '">Check Tables</a>';
            } elseif (in_array('price', $fields)) {
                return 'Schema is already correct! Bills table has "price" column.<br>
                        <a href="' . base_url('test/checkTables') . '">Check Tables</a>';
            } else {
                return 'Bills table structure is unexpected. Consider running full setup.<br>
                        <a href="' . base_url('database/setup') . '">Full Setup</a>';
            }
            
        } catch (\Exception $e) {
            return 'Schema fix failed: ' . $e->getMessage() . '<br>
                    Consider running full setup: <a href="' . base_url('database/setup') . '">Full Setup</a>';
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
