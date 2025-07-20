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

        try {
            // Debug: Log the data being inserted
            log_message('info', 'Attempting to insert bill data: ' . json_encode($data));
            
            $insertResult = $this->billModel->insert($data);
            
            // Debug: Log the result
            log_message('info', 'Insert result: ' . ($insertResult ? 'SUCCESS' : 'FAILED'));
            log_message('info', 'Model errors: ' . json_encode($this->billModel->errors()));
            
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
                
                log_message('info', 'Found ' . count($admins) . ' admin users for notifications');
                
                foreach ($admins as $admin) {
                    $notificationData = [
                        'user_id' => $admin['id'],
                        'title' => 'New Bill Created',
                        'message' => "User {$userName} has created a new bill (#{$billId}) for \"{$itemName}\" worth \${$totalAmount}. Please review and approve/reject the bill.",
                        'type' => 'bill_created',
                        'is_read' => 0
                    ];
                    
                    log_message('info', 'Creating notification for admin ID: ' . $admin['id'] . ', Data: ' . json_encode($notificationData));
                    
                    $this->notificationModel->skipValidation(true);
                    $notificationResult = $this->notificationModel->insert($notificationData);
                    
                    if ($notificationResult) {
                        log_message('info', 'Notification created successfully, ID: ' . $this->notificationModel->getInsertID());
                    } else {
                        log_message('error', 'Failed to create notification for admin ID: ' . $admin['id'] . ', Errors: ' . json_encode($this->notificationModel->errors()));
                    }
                }
                
                return redirect()->to(base_url('user/dashboard'))->with('success', 'Bill created successfully!');
            } else {
                // Get more detailed error information
                $errors = $this->billModel->errors();
                $dbError = $this->billModel->db->error();
                
                log_message('error', 'Failed to insert bill. Model errors: ' . json_encode($errors));
                log_message('error', 'Database error: ' . json_encode($dbError));
                
                $errorMessage = 'Failed to create bill. ';
                if (!empty($errors)) {
                    $errorMessage .= 'Validation errors: ' . implode(', ', $errors) . '. ';
                }
                if (!empty($dbError['message'])) {
                    $errorMessage .= 'Database error: ' . $dbError['message'];
                }
                
                return redirect()->back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during bill creation: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Failed to create bill. Error: ' . $e->getMessage());
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
