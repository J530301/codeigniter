<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DiagnosticController extends Controller
{
    public function notificationDiagnostic()
    {
        echo '<h1>🔧 Notification System Diagnostic</h1>';
        echo '<style>body{font-family:Arial,sans-serif;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>';
        
        $db = \Config\Database::connect();
        
        try {
            // 1. Check database connection
            echo '<h2>1. Database Connection</h2>';
            if ($db->connect()) {
                echo '<span class="success">✅ Database connection successful</span><br>';
            } else {
                echo '<span class="error">❌ Database connection failed</span><br>';
                return;
            }
            
            // 2. Check notifications table structure
            echo '<h2>2. Notifications Table Structure</h2>';
            if ($db->tableExists('notifications')) {
                echo '<span class="success">✅ Notifications table exists</span><br>';
                
                $fields = $db->getFieldData('notifications');
                echo '<table border="1" style="border-collapse:collapse;margin:10px 0;">';
                echo '<tr><th>Column</th><th>Type</th><th>Nullable</th><th>Default</th></tr>';
                foreach ($fields as $field) {
                    echo '<tr>';
                    echo '<td>' . $field->name . '</td>';
                    echo '<td>' . $field->type . '</td>';
                    echo '<td>' . ($field->nullable ? 'YES' : 'NO') . '</td>';
                    echo '<td>' . ($field->default ?? 'NULL') . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<span class="error">❌ Notifications table does not exist</span><br>';
                return;
            }
            
            // 3. Test raw SQL insert
            echo '<h2>3. Raw SQL Insert Test</h2>';
            try {
                $sql = "INSERT INTO notifications (user_id, title, message, type, is_read, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
                $result = $db->query($sql, [1, 'Diagnostic Test', 'Raw SQL test at ' . date('Y-m-d H:i:s'), 'diagnostic', 0]);
                
                if ($result) {
                    $insertId = $db->insertID();
                    echo '<span class="success">✅ Raw SQL insert successful, ID: ' . $insertId . '</span><br>';
                    
                    // Clean up the test record
                    $db->query("DELETE FROM notifications WHERE id = ?", [$insertId]);
                    echo '<span class="info">🧹 Test record cleaned up</span><br>';
                } else {
                    echo '<span class="error">❌ Raw SQL insert failed</span><br>';
                    $error = $db->error();
                    echo '<pre>Error: ' . json_encode($error, JSON_PRETTY_PRINT) . '</pre>';
                }
            } catch (\Exception $e) {
                echo '<span class="error">❌ Raw SQL exception: ' . $e->getMessage() . '</span><br>';
            }
            
            // 4. Test NotificationModel
            echo '<h2>4. NotificationModel Test</h2>';
            try {
                $notificationModel = new \App\Models\NotificationModel();
                
                // Check validation rules
                echo '<h3>Validation Rules:</h3>';
                echo '<pre>' . json_encode($notificationModel->getValidationRules(), JSON_PRETTY_PRINT) . '</pre>';
                
                // Test 1: Minimal data without is_read
                echo '<h3>Test 1: Minimal Data (no is_read)</h3>';
                $testData1 = [
                    'user_id' => 1,
                    'title' => 'Test 1',
                    'message' => 'Minimal test',
                    'type' => 'test'
                ];
                
                echo '<pre>Data: ' . json_encode($testData1, JSON_PRETTY_PRINT) . '</pre>';
                
                $model1 = new \App\Models\NotificationModel();
                $result1 = $model1->insert($testData1);
                
                if ($result1) {
                    echo '<span class="success">✅ Test 1 successful, ID: ' . $result1 . '</span><br>';
                    // Clean up
                    $model1->delete($result1);
                    echo '<span class="info">🧹 Test 1 record cleaned up</span><br>';
                } else {
                    echo '<span class="error">❌ Test 1 failed</span><br>';
                    echo '<pre>Errors: ' . json_encode($model1->errors(), JSON_PRETTY_PRINT) . '</pre>';
                }
                
                // Test 2: Full data with is_read
                echo '<h3>Test 2: Full Data (with is_read)</h3>';
                $testData2 = [
                    'user_id' => 1,
                    'title' => 'Test 2',
                    'message' => 'Full test with is_read',
                    'type' => 'test',
                    'is_read' => 0
                ];
                
                echo '<pre>Data: ' . json_encode($testData2, JSON_PRETTY_PRINT) . '</pre>';
                
                $model2 = new \App\Models\NotificationModel();
                $result2 = $model2->insert($testData2);
                
                if ($result2) {
                    echo '<span class="success">✅ Test 2 successful, ID: ' . $result2 . '</span><br>';
                    // Clean up
                    $model2->delete($result2);
                    echo '<span class="info">🧹 Test 2 record cleaned up</span><br>';
                } else {
                    echo '<span class="error">❌ Test 2 failed</span><br>';
                    echo '<pre>Errors: ' . json_encode($model2->errors(), JSON_PRETTY_PRINT) . '</pre>';
                }
                
                // Test 3: With skipValidation
                echo '<h3>Test 3: Skip Validation</h3>';
                $testData3 = [
                    'user_id' => 1,
                    'title' => 'Test 3',
                    'message' => 'Skip validation test',
                    'type' => 'test',
                    'is_read' => 0
                ];
                
                echo '<pre>Data: ' . json_encode($testData3, JSON_PRETTY_PRINT) . '</pre>';
                
                $model3 = new \App\Models\NotificationModel();
                $model3->skipValidation(true);
                $result3 = $model3->insert($testData3);
                
                if ($result3) {
                    echo '<span class="success">✅ Test 3 successful, ID: ' . $result3 . '</span><br>';
                    // Clean up
                    $model3->delete($result3);
                    echo '<span class="info">🧹 Test 3 record cleaned up</span><br>';
                } else {
                    echo '<span class="error">❌ Test 3 failed</span><br>';
                    echo '<pre>Errors: ' . json_encode($model3->errors(), JSON_PRETTY_PRINT) . '</pre>';
                }
                
            } catch (\Exception $e) {
                echo '<span class="error">❌ NotificationModel exception: ' . $e->getMessage() . '</span><br>';
            }
            
            // 5. Check existing data
            echo '<h2>5. Current Database State</h2>';
            
            // Count notifications
            $notificationCount = $db->query("SELECT COUNT(*) as count FROM notifications")->getRow()->count;
            echo '<span class="info">📊 Total notifications: ' . $notificationCount . '</span><br>';
            
            // Show recent notifications
            $recent = $db->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5")->getResult();
            if ($recent) {
                echo '<h3>Recent Notifications:</h3>';
                echo '<table border="1" style="border-collapse:collapse;margin:10px 0;">';
                echo '<tr><th>ID</th><th>User ID</th><th>Title</th><th>Type</th><th>Created</th></tr>';
                foreach ($recent as $notification) {
                    echo '<tr>';
                    echo '<td>' . $notification->id . '</td>';
                    echo '<td>' . $notification->user_id . '</td>';
                    echo '<td>' . $notification->title . '</td>';
                    echo '<td>' . $notification->type . '</td>';
                    echo '<td>' . $notification->created_at . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            
            // Check admin users
            $adminCount = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")->getRow()->count;
            echo '<span class="info">👤 Admin users: ' . $adminCount . '</span><br>';
            
            echo '<h2>✅ Diagnostic Complete</h2>';
            
        } catch (\Exception $e) {
            echo '<span class="error">❌ Diagnostic failed: ' . $e->getMessage() . '</span><br>';
        }
    }
}
