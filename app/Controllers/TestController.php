<?php

namespace App\Controllers;

class TestController extends BaseController
{
    public function index()
    {
        return view('test_view');
    }
    
    public function dbTest()
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->query('SELECT 1 as test');
            $result = $query->getResult();
            
            if ($result) {
                return 'Database connection successful!';
            } else {
                return 'Database connected but query failed';
            }
        } catch (\Exception $e) {
            return 'Database connection failed: ' . $e->getMessage();
        }
    }
    
    public function phpInfo()
    {
        return 'PHP Version: ' . PHP_VERSION . '<br>Environment: ' . ENVIRONMENT . '<br>Base URL: ' . base_url();
    }
}
