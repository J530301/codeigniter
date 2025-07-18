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
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'title'   => 'required|min_length[3]|max_length[255]',
        'message' => 'required|min_length[3]',
        'type'    => 'required|max_length[50]'
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
            'user_id' => $userId,
            'title'   => 'User Login',
            'message' => "User {$userFullName} has logged in",
            'type'    => 'login',
            'is_read' => false
        ]);
    }
}
