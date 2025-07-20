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
            // Get database URL and parse it
            $databaseUrl = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL') ?? '';
            
            echo '<h2>🔍 Database Connection Debug</h2>';
            echo '<strong>DATABASE_URL:</strong> ' . ($databaseUrl ? 'Set (length: ' . strlen($databaseUrl) . ')' : 'NOT SET') . '<br><br>';
            
            if ($databaseUrl) {
                $dbParts = parse_url($databaseUrl);
                echo '<strong>Parsed URL Components:</strong><br>';
                echo '- Host: ' . ($dbParts['host'] ?? 'N/A') . '<br>';
                echo '- Port: ' . ($dbParts['port'] ?? 'N/A') . '<br>';
                echo '- Database: ' . ltrim($dbParts['path'] ?? '', '/') . '<br>';
                echo '- Username: ' . ($dbParts['user'] ?? 'N/A') . '<br>';
                echo '- Password: ' . (isset($dbParts['pass']) ? '[SET]' : 'N/A') . '<br><br>';
            }
            
            // Try to get database config
            $db = \Config\Database::connect();
            echo '<strong>Database Object:</strong> ' . get_class($db) . '<br>';
            
            // Check connection ID
            if ($db->connID) {
                echo '✅ <strong>Connection ID:</strong> Found<br>';
                
                // Try a simple query
                try {
                    $query = $db->query('SELECT 1 as test');
                    $result = $query->getResult();
                    
                    if ($result) {
                        echo '✅ <strong>Query Test:</strong> SUCCESS<br>';
                        echo '✅ <strong>Database connection is fully working!</strong><br>';
                    } else {
                        echo '❌ <strong>Query Test:</strong> Query executed but no result<br>';
                    }
                } catch (\Exception $queryError) {
                    echo '❌ <strong>Query Test:</strong> ' . $queryError->getMessage() . '<br>';
                }
                
            } else {
                echo '❌ <strong>Connection ID:</strong> NOT FOUND<br>';
                
                // Try to get more details about the connection
                echo '<strong>Connection Details:</strong><br>';
                echo '- Database: ' . $db->getDatabase() . '<br>';
                echo '- Platform: ' . $db->getPlatform() . '<br>';
                
                // Check if we can get error info
                if (method_exists($db, 'error')) {
                    $error = $db->error();
                    if ($error) {
                        echo '- Error Code: ' . $error['code'] . '<br>';
                        echo '- Error Message: ' . $error['message'] . '<br>';
                    }
                }
            }
            
            echo '<br><a href="' . base_url('test') . '">← Back to Test</a>';
            
        } catch (\Exception $e) {
            echo '❌ <strong>Database connection failed:</strong> ' . $e->getMessage() . '<br>';
            echo '<strong>Error Details:</strong><br>';
            echo '- File: ' . $e->getFile() . '<br>';
            echo '- Line: ' . $e->getLine() . '<br>';
            echo '<br><a href="' . base_url('test') . '">← Back to Test</a>';
        }
    }
    
    public function phpInfo()
    {
        return '📋 <strong>Environment Information:</strong><br>
                PHP Version: ' . PHP_VERSION . '<br>
                Environment: ' . ENVIRONMENT . '<br>
                Base URL: ' . base_url() . '<br>
                Working Directory: ' . getcwd() . '<br>
                DATABASE_URL: ' . (getenv('DATABASE_URL') ? 'SET' : 'NOT SET') . '<br>
                CI_ENVIRONMENT: ' . (getenv('CI_ENVIRONMENT') ? getenv('CI_ENVIRONMENT') : 'NOT SET') . '<br>
                <br><strong>Available Extensions:</strong><br>
                pgsql: ' . (extension_loaded('pgsql') ? 'YES' : 'NO') . '<br>
                pdo_pgsql: ' . (extension_loaded('pdo_pgsql') ? 'YES' : 'NO') . '<br>
                mysqli: ' . (extension_loaded('mysqli') ? 'YES' : 'NO') . '<br>
                <a href="' . base_url('test') . '">← Back to Test</a>';
    }
}
