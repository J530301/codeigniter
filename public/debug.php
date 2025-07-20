<?php
// Simple debug script
echo "PHP is working!<br>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Current directory: " . __DIR__ . "<br>";

// Check if vendor autoload exists (CodeIgniter 4 way)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "Vendor/autoload.php found<br>";
} else {
    echo "Vendor/autoload.php NOT found<br>";
}

// Check if app directory exists
if (file_exists(__DIR__ . '/../app')) {
    echo "App directory found<br>";
} else {
    echo "App directory NOT found<br>";
}

// Check if CodeIgniter framework exists in vendor
if (file_exists(__DIR__ . '/../vendor/codeigniter4')) {
    echo "CodeIgniter 4 framework found in vendor<br>";
} else {
    echo "CodeIgniter 4 framework NOT found in vendor<br>";
}

// Check environment variables
echo "Environment variables:<br>";
echo "CI_ENVIRONMENT: " . (getenv('CI_ENVIRONMENT') ?: 'not set') . "<br>";
echo "DATABASE_URL: " . (getenv('DATABASE_URL') ? 'set' : 'not set') . "<br>";

// List files in root directory
echo "<br>Files in project root:<br>";
$files = scandir(__DIR__ . '/..');
foreach($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "- $file<br>";
    }
}

// Check if we can include CodeIgniter bootstrap
try {
    echo "<br>Attempting to load CodeIgniter via index.php bootstrap...<br>";
    
    // Define paths like in index.php
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    
    // Check if Paths config exists
    if (file_exists(__DIR__ . '/../app/Config/Paths.php')) {
        echo "Paths config found<br>";
        require_once __DIR__ . '/../app/Config/Paths.php';
        $paths = new Config\Paths();
        echo "System path: " . $paths->systemDirectory . "<br>";
        echo "App path: " . $paths->appDirectory . "<br>";
    } else {
        echo "Paths config NOT found<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "Fatal Error: " . $e->getMessage() . "<br>";
}
?>
