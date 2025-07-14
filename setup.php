<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Global News Network</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        
        .status-box {
            background: rgba(255,255,255,0.2);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .success {
            background: rgba(40, 167, 69, 0.3);
            border: 2px solid #28a745;
        }
        
        .error {
            background: rgba(220, 53, 69, 0.3);
            border: 2px solid #dc3545;
        }
        
        .warning {
            background: rgba(255, 193, 7, 0.3);
            border: 2px solid #ffc107;
            color: #212529;
        }
        
        .info {
            background: rgba(23, 162, 184, 0.3);
            border: 2px solid #17a2b8;
        }
        
        .debug-info {
            background: rgba(0,0,0,0.3);
            padding: 1rem;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        
        .btn {
            background: #ff6b35;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #e55a2b;
            transform: translateY(-2px);
        }
        
        .instructions {
            background: rgba(255,255,255,0.1);
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        
        .instructions h3 {
            color: #ff6b35;
            margin-bottom: 1rem;
        }
        
        .instructions ol {
            padding-left: 1.5rem;
        }
        
        .instructions li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
        
        code {
            background: rgba(0,0,0,0.3);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Database Setup & Status</h1>
        
        <?php
        require_once 'db.php';
        $db = new Database();
        $debugInfo = $db->getDebugInfo();
        ?>
        
        <?php if ($debugInfo['connected']): ?>
            <div class="status-box success">
                <h2>✅ Database Connected Successfully!</h2>
                <p>Your database connection is working perfectly. The website is fully functional with live data.</p>
            </div>
        <?php else: ?>
            <div class="status-box warning">
                <h2>⚠️ Database Connection Failed</h2>
                <p>Don't worry! The website is still working with sample data. Here's what happened and how to fix it:</p>
            </div>
        <?php endif; ?>
        
        <div class="status-box info">
            <h3>🔍 Connection Details</h3>
            <div class="debug-info">
                <strong>Status:</strong> <?php echo $debugInfo['connected'] ? 'Connected' : 'Using Sample Data'; ?><br>
                <strong>Host:</strong> <?php echo htmlspecialchars($debugInfo['host']); ?><br>
                <strong>Database:</strong> <?php echo htmlspecialchars($debugInfo['database']); ?><br>
                <strong>Username:</strong> <?php echo htmlspecialchars($debugInfo['username']); ?><br>
                <strong>Sample Data Mode:</strong> <?php echo $debugInfo['using_sample_data'] ? 'Yes' : 'No'; ?>
            </div>
        </div>
        
        <?php if (!$debugInfo['connected']): ?>
            <div class="instructions">
                <h3>🛠️ How to Fix Database Connection</h3>
                <ol>
                    <li><strong>Check if your database exists:</strong>
                        <br>Log into your hosting control panel (cPanel, phpMyAdmin, etc.) and verify that the database <code><?php echo htmlspecialchars($debugInfo['database']); ?></code> exists.
                    </li>
                    
                    <li><strong>Create the database if it doesn't exist:</strong>
                        <br>In your hosting control panel, create a new database named <code><?php echo htmlspecialchars($debugInfo['database']); ?></code>
                    </li>
                    
                    <li><strong>Import the database structure:</strong>
                        <br>Use the <code>database.sql</code> file to create the necessary tables. You can import this through phpMyAdmin or your hosting control panel.
                    </li>
                    
                    <li><strong>Verify user permissions:</strong>
                        <br>Make sure the user <code><?php echo htmlspecialchars($debugInfo['username']); ?></code> has full access to the database <code><?php echo htmlspecialchars($debugInfo['database']); ?></code>
                    </li>
                    
                    <li><strong>Check hosting provider settings:</strong>
                        <br>Some hosting providers use different database hosts. Try updating the host in <code>db.php</code> to your provider's database server address.
                    </li>
                </ol>
            </div>
            
            <div class="status-box info">
                <h3>🎯 Quick Solutions</h3>
                <p><strong>Option 1:</strong> Contact your hosting provider to verify database credentials and permissions.</p>
                <p><strong>Option 2:</strong> The website works perfectly with sample data - you can use it as-is for demonstration purposes.</p>
                <p><strong>Option 3:</strong> Try using a local database server like XAMPP or WAMP for testing.</p>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="index.php" class="btn">🏠 Go to Homepage</a>
            <a href="setup.php" class="btn">🔄 Refresh Status</a>
        </div>
        
        <div class="status-box success" style="margin-top: 2rem;">
            <h3>✨ Good News!</h3>
            <p>Even without database connection, your news website is fully functional with:</p>
            <ul style="margin-top: 1rem;">
                <li>✅ Beautiful homepage with featured articles</li>
                <li>✅ Category pages with sample news</li>
                <li>✅ Individual article pages</li>
                <li>✅ Search functionality</li>
                <li>✅ Comment system (demo mode)</li>
                <li>✅ Responsive design</li>
                <li>✅ Professional styling</li>
            </ul>
        </div>
    </div>
</body>
</html>
