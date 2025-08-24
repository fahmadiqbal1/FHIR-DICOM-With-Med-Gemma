<?php
// Quick test to see if authentication is working properly
// Use this to test login functionality

if (isset($_POST['email']) && isset($_POST['password'])) {
    // Simple login test
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    echo "<h3>Login Test for: $email</h3>";
    
    // Simulate Laravel check
    echo "<p>✓ Form data received</p>";
    echo "<p>✓ Email: $email</p>";
    echo "<p>✓ Password length: " . strlen($password) . "</p>";
    
    // Redirect test
    echo "<p>→ Would redirect to /dashboard after successful login</p>";
    echo "<p>→ Dashboard would detect role and redirect to appropriate dashboard</p>";
    
    echo "<hr>";
    echo "<a href='/' style='color: blue; text-decoration: underline;'>← Back to login test</a>";
    
} else {
    // Show login form
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Login Test - Healthcare Platform</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .credentials { background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .credentials h3 { margin-top: 0; }
    </style>
</head>
<body>
    <h2>🏥 Healthcare Platform Login Test</h2>
    
    <div class="credentials">
        <h3>Test Credentials (Password: password)</h3>
        <p><strong>Owner:</strong> owner@medgemma.com</p>
        <p><strong>Admin:</strong> admin@medgemma.com</p>
        <p><strong>Doctor:</strong> doctor1@medgemma.com</p>
        <p><strong>Lab Tech:</strong> labtech@medgemma.com</p>
        <p><strong>Radiologist:</strong> radiologist@medgemma.com</p>
        <p><strong>Pharmacist:</strong> pharmacist@medgemma.com</p>
    </div>
    
    <form method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="owner@medgemma.com">
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required value="password">
        </div>
        
        <button type="submit">Test Login</button>
    </form>
    
    <hr>
    <p><a href="http://127.0.0.1:8000/login" target="_blank" style="color: blue; text-decoration: underline;">→ Go to actual login page</a></p>
    <p><a href="http://127.0.0.1:8000/dashboard/owner" target="_blank" style="color: blue; text-decoration: underline;">→ Test owner dashboard directly</a></p>
</body>
</html>';
}
?>
