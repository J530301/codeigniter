<?php

namespace App\Controllers;

class TestController extends BaseController
{
    public function index()
    {
        return '<h1>CodeIgniter 4 Test Page</h1>
                <p>✅ If you can see this, your CodeIgniter app is working!</p>
                <p>Environment: ' . ENVIRONMENT . '</p>
                <p>Base URL: ' . base_url() . '</p>
                
                <h2>Test Links:</h2>
                <ul>
                    <li><a href="' . base_url('test/dbTest') . '">Test Database Connection</a></li>
                    <li><a href="' . base_url('test/phpInfo') . '">PHP Info</a></li>
                    <li><a href="' . base_url('database/setup') . '">Setup Database</a></li>
                    <li><a href="' . base_url('login') . '">Go to Login</a></li>
                </ul>';
    }
    
    public function dbTest()
    {
        try {
            $db = \Config\Database::connect();
            
            // Simple connection test
            if ($db->connID) {
                return '✅ Database connection successful!<br>
                        Driver: ' . get_class($db) . '<br>
                        <a href="' . base_url('test') . '">← Back to Test</a>';
            } else {
                return '❌ Database connected but no connection ID<br>
                        <a href="' . base_url('test') . '">← Back to Test</a>';
            }
        } catch (\Exception $e) {
            return '❌ Database connection failed: ' . $e->getMessage() . '<br>
                    <a href="' . base_url('test') . '">← Back to Test</a>';
        }
    }
    
    public function phpInfo()
    {
        return '📋 PHP Version: ' . PHP_VERSION . '<br>
                Environment: ' . ENVIRONMENT . '<br>
                Base URL: ' . base_url() . '<br>
                Working Directory: ' . getcwd() . '<br>
                <a href="' . base_url('test') . '">← Back to Test</a>';
    }
}
