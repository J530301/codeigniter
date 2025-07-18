<?php

namespace App\Models;

use CodeIgniter\Model;

class BillModel extends Model
{
    protected $table            = 'bills';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'item_name', 'description', 'price', 
        'quantity', 'total_amount', 'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id'      => 'required|integer',
        'item_name'    => 'required|min_length[3]|max_length[255]',
        'price'        => 'required|decimal|greater_than[0]',
        'quantity'     => 'required|integer|greater_than[0]',
        'total_amount' => 'required|decimal|greater_than[0]',
        'status'       => 'required|in_list[pending,approved,rejected]'
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['calculateTotal'];
    protected $beforeUpdate   = ['calculateTotal'];

    protected function calculateTotal(array $data)
    {
        if (isset($data['data']['price']) && isset($data['data']['quantity'])) {
            $data['data']['total_amount'] = $data['data']['price'] * $data['data']['quantity'];
        }
        return $data;
    }

    public function getBillsWithUser()
    {
        return $this->select('bills.*, users.first_name, users.last_name, users.email')
                    ->join('users', 'users.id = bills.user_id')
                    ->orderBy('bills.created_at', 'DESC')
                    ->findAll();
    }

    public function getBillsByUserId($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
}
