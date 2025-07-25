<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\NotificationModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->notificationModel = new NotificationModel();
        helper('form');
    }

    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('user_id')) {
            $role = session()->get('role');
            return redirect()->to($role === 'admin' ? '/admin/dashboard' : '/user/dashboard');
        }

        return view('auth/login');
    }

    public function loginProcess()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Try to find user by username or email
        $user = $this->userModel->getUserByUsername($username);
        if (!$user) {
            $user = $this->userModel->getUserByEmail($username);
        }

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] === 'inactive') {
                return redirect()->back()->with('error', 'Your account is inactive. Please contact administrator.');
            }

            // Set session data
            session()->set([
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'logged_in'  => true
            ]);

            // Create login notification for admin
            if ($user['role'] === 'user') {
                $fullName = $user['first_name'] . ' ' . $user['last_name'];
                
                // Get all admin users to notify them about user login
                $admins = $this->userModel->where('role', 'admin')->findAll();
                
                log_message('info', 'User login: Creating notifications for ' . count($admins) . ' admin users');
                
                foreach ($admins as $admin) {
                    $notificationData = [
                        'user_id' => (int)$admin['id'], // Notification FOR admin - ensure integer
                        'title' => 'User Login',
                        'message' => "User {$fullName} has logged in",
                        'type' => 'login',
                        'is_read' => false // Use boolean for PostgreSQL
                    ];
                    
                    try {
                        $result = $this->notificationModel->debugInsert($notificationData);
                        
                        if ($result) {
                            log_message('info', 'Login notification created for admin ID: ' . $admin['id']);
                        } else {
                            log_message('error', 'Login notification failed via model for admin ID: ' . $admin['id'] . ', errors: ' . json_encode($this->notificationModel->errors()));
                            
                            // Fallback to raw SQL
                            $db = \Config\Database::connect();
                            $sql = "INSERT INTO notifications (user_id, title, message, type, is_read) VALUES (?, ?, ?, ?, ?)";
                            $rawResult = $db->query($sql, [
                                $notificationData['user_id'],
                                $notificationData['title'],
                                $notificationData['message'],
                                $notificationData['type'],
                                false // Use boolean for PostgreSQL
                            ]);
                            
                            if ($rawResult) {
                                log_message('info', 'Login notification created via raw SQL for admin ID: ' . $admin['id']);
                            } else {
                                log_message('error', 'Login notification failed completely for admin ID: ' . $admin['id']);
                            }
                        }
                    } catch (\Exception $e) {
                        log_message('error', 'Exception during login notification: ' . $e->getMessage());
                    }
                }
            }

            // Redirect based on role
            if ($user['role'] === 'admin') {
                return redirect()->to(base_url('admin/dashboard'))->with('success', 'Welcome back, Admin!');
            } else {
                return redirect()->to(base_url('user/dashboard'))->with('success', 'Welcome back!');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid username/email or password.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'You have been logged out successfully.');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function registerProcess()
    {
        $rules = [
            'username'   => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[6]',
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'role'       => 'user',
            'status'     => 'active'
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/login')->with('success', 'Registration successful! You can now login.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
    }
}
