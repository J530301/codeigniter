<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>CodeIgniter 4 Test Page</h1>
    <p>If you can see this, your CodeIgniter app is working!</p>
    <p>Environment: <?= ENVIRONMENT ?></p>
    <p>Base URL: <?= base_url() ?></p>
    
    <h2>Test Links:</h2>
    <ul>
        <li><a href="<?= base_url('test/dbTest') ?>">Test Database Connection</a></li>
        <li><a href="<?= base_url('test/phpInfo') ?>">PHP Info</a></li>
        <li><a href="<?= base_url('login') ?>">Go to Login</a></li>
    </ul>
</body>
</html>
