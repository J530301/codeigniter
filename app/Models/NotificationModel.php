<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'title', 'message', 'type', 'is_read'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at column in table

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'title'   => 'required|min_length[3]|max_length[255]',
        'message' => 'required|min_length[3]',
        'type'    => 'permit_empty|max_length[50]',
        'is_read' => 'permit_empty|in_list[0,1,false,true]'
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    public function getUnreadNotifications()
    {
        return $this->select('notifications.*, users.first_name, users.last_name')
                    ->join('users', 'users.id = notifications.user_id')
                    ->where('notifications.is_read', false)
                    ->orderBy('notifications.created_at', 'DESC')
                    ->findAll();
    }

    public function getAllNotifications()
    {
        return $this->select('notifications.*, users.first_name, users.last_name')
                    ->join('users', 'users.id = notifications.user_id')
                    ->orderBy('notifications.created_at', 'DESC')
                    ->findAll();
    }

    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => true]);
    }

    public function markAllAsRead()
    {
        return $this->set('is_read', true)
                    ->where('is_read', false)
                    ->update();
    }

    public function createLoginNotification($userId, $userFullName)
    {
        return $this->insert([
            'user_id' => (int)$userId,
            'title'   => 'User Login',
            'message' => "User {$userFullName} has logged in",
            'type'    => 'login',
            'is_read' => false // Use boolean for PostgreSQL
        ]);
    }

    public function createBillNotification($userId, $billData)
    {
        return $this->insert([
            'user_id' => (int)$userId,
            'title'   => 'New Bill Created',
            'message' => "New bill created: {$billData['customer_name']} - $" . number_format($billData['price'], 2),
            'type'    => 'bill',
            'is_read' => false // Use boolean for PostgreSQL
        ]);
    }

    public function debugInsert($data)
    {
        // Log the data being inserted
        log_message('info', 'NotificationModel debugInsert data: ' . json_encode($data));
        
        // Ensure proper data types for PostgreSQL
        if (isset($data['user_id'])) {
            $data['user_id'] = (int)$data['user_id'];
        }
        if (isset($data['is_read'])) {
            // Convert to boolean for PostgreSQL
            $data['is_read'] = $data['is_read'] ? true : false;
        }
        
        // Try insert without validation first
        $this->skipValidation(true);
        $result = $this->insert($data);
        
        if (!$result) {
            log_message('error', 'NotificationModel insert failed: ' . json_encode($this->errors()));
            
            // Try raw SQL as fallback
            $db = \Config\Database::connect();
            $sql = "INSERT INTO notifications (user_id, title, message, type, is_read) VALUES (?, ?, ?, ?, ?)";
            $rawResult = $db->query($sql, [
                $data['user_id'],
                $data['title'],
                $data['message'],
                $data['type'] ?? 'info',
                $data['is_read'] ?? false
            ]);
            
            if ($rawResult) {
                log_message('info', 'Raw SQL fallback successful, ID: ' . $db->insertID());
                return $db->insertID();
            }
        }
        
        return $result;
    }
}
