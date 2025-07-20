<?php
// Simple debug script
echo "PHP is working!<br>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Current directory: " . __DIR__ . "<br>";

// Check if CodeIgniter bootstrap exists
if (file_exists(__DIR__ . '/../../system/bootstrap.php')) {
    echo "CodeIgniter system found<br>";
} else {
    echo "CodeIgniter system NOT found<br>";
}

// Check if app directory exists
if (file_exists(__DIR__ . '/../../app')) {
    echo "App directory found<br>";
} else {
    echo "App directory NOT found<br>";
}

// Check environment variables
echo "Environment variables:<br>";
echo "CI_ENVIRONMENT: " . (getenv('CI_ENVIRONMENT') ?: 'not set') . "<br>";
echo "DATABASE_URL: " . (getenv('DATABASE_URL') ? 'set' : 'not set') . "<br>";

// Check if we can include CodeIgniter
try {
    echo "Attempting to load CodeIgniter...<br>";
    require_once __DIR__ . '/../../system/bootstrap.php';
    echo "CodeIgniter loaded successfully!<br>";
} catch (Exception $e) {
    echo "CodeIgniter load failed: " . $e->getMessage() . "<br>";
}
?>
