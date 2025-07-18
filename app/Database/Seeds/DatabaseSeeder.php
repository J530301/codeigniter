<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $this->db->table('users')->insert([
            'username'   => 'admin',
            'email'      => 'admin@example.com',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'role'       => 'admin',
            'status'     => 'active',
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Create test user
        $this->db->table('users')->insert([
            'username'   => 'user1',
            'email'      => 'user1@example.com',
            'password'   => password_hash('user123', PASSWORD_DEFAULT),
            'role'       => 'user',
            'status'     => 'active',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Create sample bill
        $this->db->table('bills')->insert([
            'user_id'      => 2,
            'item_name'    => 'Office Supplies',
            'description'  => 'Monthly office supplies purchase',
            'price'        => 150.00,
            'quantity'     => 1,
            'total_amount' => 150.00,
            'status'       => 'pending',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        // Create sample notification
        $this->db->table('notifications')->insert([
            'user_id'    => 2,
            'title'      => 'User Login',
            'message'    => 'User John Doe has logged in',
            'type'       => 'login',
            'is_read'    => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
