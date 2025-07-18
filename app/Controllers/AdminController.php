<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BillModel;
use App\Models\NotificationModel;

/**
 * AdminController
 * 
 * Handles all administrative operations including user management,
 * bill approvals, and notification management for the business system.
 */
class AdminController extends BaseController
{
    protected $userModel;
    protected $billModel;
    protected $notificationModel;

    /**
     * Constructor - Initialize models and helpers
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->billModel = new BillModel();
        $this->notificationModel = new NotificationModel();
        helper('form');
    }

    /**
     * Display admin dashboard with system statistics
     * 
     * @return mixed
     */
    public function dashboard()
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Admin Dashboard',
            'totalUsers' => $this->userModel->where('role', 'user')->countAllResults(),
            'totalBills' => $this->billModel->countAllResults(),
            'pendingBills' => $this->billModel->where('status', 'pending')->countAllResults(),
            'unreadNotifications' => $this->notificationModel->where('is_read', false)->countAllResults()
        ];

        return view('admin/dashboard', $data);
    }

    public function users()
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->getAllUsers(),
            'unreadNotifications' => $this->notificationModel->where('is_read', false)->countAllResults()
        ];

        return view('admin/users', $data);
    }

    public function editUser($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'unreadNotifications' => $this->notificationModel->where('is_read', false)->countAllResults()
        ];

        return view('admin/edit_user', $data);
    }

    public function updateUser($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        $rules = [
            'username'   => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'email'      => "required|valid_email|is_unique[users.email,id,{$id}]",
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name')
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        // Skip model validation since we're validating in the controller
        $this->userModel->skipValidation(true);
        
        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/admin/users')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update user.');
        }
    }

    public function deleteUser($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->to('/admin/users')->with('error', 'Failed to delete user.');
        }
    }

    public function toggleUserStatus($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
        
        if ($this->userModel->updateUserStatus($id, $newStatus)) {
            $action = $newStatus === 'active' ? 'activated' : 'deactivated';
            return redirect()->to('/admin/users')->with('success', "User {$action} successfully.");
        } else {
            return redirect()->to('/admin/users')->with('error', 'Failed to update user status.');
        }
    }

    public function bills()
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Bills Management',
            'bills' => $this->billModel->getBillsWithUser(),
            'unreadNotifications' => $this->notificationModel->where('is_read', false)->countAllResults()
        ];

        return view('admin/bills', $data);
    }

    public function deleteBill($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        if ($this->billModel->delete($id)) {
            return redirect()->to('/admin/bills')->with('success', 'Bill deleted successfully.');
        } else {
            return redirect()->to('/admin/bills')->with('error', 'Failed to delete bill.');
        }
    }

    public function notifications()
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Notifications',
            'notifications' => $this->notificationModel->getAllNotifications()
        ];

        return view('admin/notifications', $data);
    }

    public function getNotifications()
    {
        if (!$this->isAdmin()) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $notifications = $this->notificationModel->getUnreadNotifications();
        return $this->response->setJSON($notifications);
    }

    public function markNotificationRead($id)
    {
        if (!$this->isAdmin()) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        if ($this->notificationModel->markAsRead($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['error' => 'Failed to mark as read']);
        }
    }

    public function approveBill($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        if ($this->billModel->update($id, ['status' => 'approved'])) {
            return redirect()->to('/admin/bills')->with('success', 'Bill approved successfully.');
        } else {
            return redirect()->to('/admin/bills')->with('error', 'Failed to approve bill.');
        }
    }

    public function rejectBill($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/login');
        }

        if ($this->billModel->update($id, ['status' => 'rejected'])) {
            return redirect()->to('/admin/bills')->with('success', 'Bill rejected successfully.');
        } else {
            return redirect()->to('/admin/bills')->with('error', 'Failed to reject bill.');
        }
    }

    private function isAdmin()
    {
        return session()->get('logged_in') && session()->get('role') === 'admin';
    }
}
