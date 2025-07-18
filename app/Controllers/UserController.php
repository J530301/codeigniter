<?php

namespace App\Controllers;

use App\Models\BillModel;
use App\Models\NotificationModel;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $billModel;
    protected $notificationModel;
    protected $userModel;

    public function __construct()
    {
        $this->billModel = new BillModel();
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
        helper('form');
    }

    public function dashboard()
    {
        // Check if user is logged in
        if (!$this->isUser()) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $data = [
            'title' => 'User Dashboard',
            'bills' => $this->billModel->getBillsByUserId($userId),
            'totalBills' => $this->billModel->where('user_id', $userId)->countAllResults(),
            'pendingBills' => $this->billModel->where('user_id', $userId)->where('status', 'pending')->countAllResults(),
            'approvedBills' => $this->billModel->where('user_id', $userId)->where('status', 'approved')->countAllResults()
        ];

        return view('user/dashboard', $data);
    }

    public function createBill()
    {
        if (!$this->isUser()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Create Bill'
        ];

        return view('user/create_bill', $data);
    }

    public function storeBill()
    {
        if (!$this->isUser()) {
            return redirect()->to('/login');
        }

        $rules = [
            'item_name'   => 'required|min_length[3]|max_length[255]',
            'price'       => 'required|decimal|greater_than[0]',
            'quantity'    => 'required|integer|greater_than[0]',
            'description' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id'      => session()->get('user_id'),
            'item_name'    => $this->request->getPost('item_name'),
            'description'  => $this->request->getPost('description'),
            'price'        => $this->request->getPost('price'),
            'quantity'     => $this->request->getPost('quantity'),
            'total_amount' => $this->request->getPost('price') * $this->request->getPost('quantity'),
            'status'       => 'pending'
        ];

        // Skip model validation since we're validating in the controller
        $this->billModel->skipValidation(true);

        $insertResult = $this->billModel->insert($data);
        
        if ($insertResult) {
            // Get the inserted bill ID
            $billId = $this->billModel->getInsertID();
            
            // Get the current user's information
            $currentUser = $this->userModel->find(session()->get('user_id'));
            $userName = $currentUser['first_name'] . ' ' . $currentUser['last_name'];
            $itemName = $this->request->getPost('item_name');
            $totalAmount = number_format($this->request->getPost('price') * $this->request->getPost('quantity'), 2);
            
            // Create notification for all admins
            $admins = $this->userModel->where('role', 'admin')->findAll();
            
            foreach ($admins as $admin) {
                $notificationData = [
                    'user_id' => $admin['id'],
                    'title' => 'New Bill Created',
                    'message' => "User {$userName} has created a new bill (#{$billId}) for \"{$itemName}\" worth \${$totalAmount}. Please review and approve/reject the bill.",
                    'type' => 'bill_created',
                    'is_read' => 0
                ];
                
                $this->notificationModel->skipValidation(true);
                $this->notificationModel->insert($notificationData);
            }
            
            return redirect()->to('/user/dashboard')->with('success', 'Bill created successfully!');
        } else {
            // Log the error for debugging
            log_message('error', 'Failed to insert bill: ' . json_encode($this->billModel->errors()));
            return redirect()->back()->withInput()->with('error', 'Failed to create bill. Please try again.');
        }
    }

    public function viewBill($id)
    {
        if (!$this->isUser()) {
            return redirect()->to('/login');
        }

        $bill = $this->billModel->find($id);
        
        // Check if bill exists and belongs to current user
        if (!$bill || $bill['user_id'] != session()->get('user_id')) {
            return redirect()->to('/user/dashboard')->with('error', 'Bill not found.');
        }

        $data = [
            'title' => 'View Bill',
            'bill' => $bill
        ];

        return view('user/view_bill', $data);
    }

    public function bills()
    {
        if (!$this->isUser()) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $data = [
            'title' => 'My Bills',
            'bills' => $this->billModel->getBillsByUserId($userId)
        ];

        return view('user/bills', $data);
    }

    private function isUser()
    {
        return session()->get('logged_in') && 
               (session()->get('role') === 'user' || session()->get('role') === 'admin');
    }
}
