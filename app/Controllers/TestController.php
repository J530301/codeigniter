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
                    <li><a href="' . base_url('test/checkTables') . '">Check Database Tables</a></li>
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
            
            // Get the database configuration to verify our parsing
            $config = new \Config\Database();
            echo '<strong>Generated Database Config:</strong><br>';
            echo '- Host: ' . $config->default['hostname'] . '<br>';
            echo '- Port: ' . $config->default['port'] . '<br>';
            echo '- Database: ' . $config->default['database'] . '<br>';
            echo '- Username: ' . $config->default['username'] . '<br>';
            echo '- Password: ' . ($config->default['password'] ? '[SET]' : 'EMPTY') . '<br>';
            echo '- DBDriver: ' . $config->default['DBDriver'] . '<br><br>';
            
            // Try to get database connection
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
                echo '❌ <strong>Connection ID:</strong> NOT FOUND (Connection failed)<br>';
                
                // Try manual connection to get better error info
                echo '<strong>Attempting manual connection test...</strong><br>';
                try {
                    $connString = "host={$config->default['hostname']} port={$config->default['port']} dbname={$config->default['database']} user={$config->default['username']} password={$config->default['password']} sslmode=require";
                    echo '- Connection string: ' . str_replace($config->default['password'], '[PASSWORD]', $connString) . '<br>';
                    
                    $manualConn = pg_connect($connString);
                    if ($manualConn) {
                        echo '✅ <strong>Manual connection:</strong> SUCCESS<br>';
                        pg_close($manualConn);
                    } else {
                        echo '❌ <strong>Manual connection:</strong> FAILED<br>';
                        echo '- Error: ' . pg_last_error() . '<br>';
                    }
                } catch (\Exception $manualError) {
                    echo '❌ <strong>Manual connection error:</strong> ' . $manualError->getMessage() . '<br>';
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
                HTTP_HOST: ' . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . '<br>
                HTTPS: ' . ($_SERVER['HTTPS'] ?? 'NOT SET') . '<br>
                HTTP_X_FORWARDED_PROTO: ' . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NOT SET') . '<br>
                DATABASE_URL: ' . (getenv('DATABASE_URL') ? 'SET' : 'NOT SET') . '<br>
                CI_ENVIRONMENT: ' . (getenv('CI_ENVIRONMENT') ? getenv('CI_ENVIRONMENT') : 'NOT SET') . '<br>
                <br><strong>Available Extensions:</strong><br>
                pgsql: ' . (extension_loaded('pgsql') ? 'YES' : 'NO') . '<br>
                pdo_pgsql: ' . (extension_loaded('pdo_pgsql') ? 'YES' : 'NO') . '<br>
                mysqli: ' . (extension_loaded('mysqli') ? 'YES' : 'NO') . '<br>
                <a href="' . base_url('test') . '">← Back to Test</a>';
    }
    
    public function checkTables()
    {
        try {
            $db = \Config\Database::connect();
            
            echo '<h2>🔍 Database Table Check</h2>';
            
            // Check if tables exist
            $tables = ['users', 'bills', 'notifications'];
            
            foreach ($tables as $table) {
                echo "<h3>Table: {$table}</h3>";
                
                // Check if table exists
                $exists = $db->tableExists($table);
                echo "Exists: " . ($exists ? '✅ YES' : '❌ NO') . "<br>";
                
                if ($exists) {
                    // Get field list
                    $fields = $db->getFieldNames($table);
                    echo "Fields: " . implode(', ', $fields) . "<br>";
                    
                    // Get record count
                    $count = $db->table($table)->countAllResults();
                    echo "Records: {$count}<br>";
                    
                    // Show sample data
                    if ($count > 0) {
                        $sample = $db->table($table)->limit(1)->get()->getRowArray();
                        echo "Sample: <pre>" . json_encode($sample, JSON_PRETTY_PRINT) . "</pre>";
                    }
                }
                echo "<br>";
            }
            
            echo '<br><a href="' . base_url('test') . '">← Back to Test</a>';
            
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage();
            echo '<br><a href="' . base_url('test') . '">← Back to Test</a>';
        }
    }
}
