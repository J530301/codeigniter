<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        
        // Clear existing data
        $this->db->table('notifications')->truncate();
        $this->db->table('bills')->truncate();
        $this->db->table('users')->truncate();
        
        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Create demo users with correct passwords
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status' => 'active',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'status' => 'active',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'user2',
                'email' => 'user2@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'status' => 'active',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('users')->insertBatch($data);

        // Get user IDs for sample bills
        $users = $this->db->table('users')->where('role', 'user')->get()->getResult();
        
        if (count($users) >= 2) {
            // Insert sample bills
            $billData = [
                [
                    'user_id' => $users[0]->id,
                    'item_name' => 'Office Supplies',
                    'description' => 'Monthly office supplies purchase including pens, papers, and folders',
                    'price' => 150.00,
                    'quantity' => 1,
                    'total_amount' => 150.00,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'user_id' => $users[0]->id,
                    'item_name' => 'Software License',
                    'description' => 'Annual subscription for project management software',
                    'price' => 299.99,
                    'quantity' => 1,
                    'total_amount' => 299.99,
                    'status' => 'approved',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'user_id' => $users[1]->id,
                    'item_name' => 'Business Cards',
                    'description' => 'Professional business cards for marketing',
                    'price' => 75.50,
                    'quantity' => 2,
                    'total_amount' => 151.00,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ];

            $this->db->table('bills')->insertBatch($billData);

            // Insert sample notifications
            $notificationData = [
                [
                    'user_id' => $users[0]->id,
                    'title' => 'User Login',
                    'message' => 'User John Doe has logged in',
                    'type' => 'login',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'user_id' => $users[1]->id,
                    'title' => 'User Login',
                    'message' => 'User Jane Smith has logged in',
                    'type' => 'login',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ];

            $this->db->table('notifications')->insertBatch($notificationData);
        }

        echo "Demo users created successfully!\n";
        echo "Admin: admin / admin123\n";
        echo "User1: user1 / user123\n";
        echo "User2: user2 / user123\n";
    }
}
