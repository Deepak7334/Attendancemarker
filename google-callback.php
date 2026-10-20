<?php
// google-callback.php
session_start();
require_once 'google-config.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/config/database.php';

if (isset($_GET['code'])) {
    // Exchange the auth code for a token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if(!isset($token['error'])){
        $client->setAccessToken($token['access_token']);
    
        // Get User Profile Data from Google
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        
        $email = $google_account_info->email;
        $name = $google_account_info->name;
        $google_id = $google_account_info->id;
    
        // 1. Check if user already exists with this Google ID
        // Note: You need to add findByGoogleId method to your User model
        // Or write raw SQL here if you prefer.
        $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
        $stmt->execute([$google_id]);
        $user = $stmt->fetch();
    
        if ($user) {
            // User exists, log them in
            $_SESSION['user'] = $user;
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: index.php?page=admin-dashboard");
            } else {
                header("Location: index.php?page=employee-dashboard");
            }
            exit;
        } else {
            // 2. User doesn't exist by Google ID. Check if Email exists?
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $existingEmailUser = $stmt->fetch();
            
            if ($existingEmailUser) {
                // Link Google ID to existing email account
                $update = $pdo->prepare("UPDATE users SET google_id = ? WHERE email = ?");
                $update->execute([$google_id, $email]);
                
                $_SESSION['user'] = $existingEmailUser;
                header("Location: index.php?page=employee-dashboard"); // Default redirect
                exit;
            } else {
                // 3. New User - Create account
                // Adjust columns based on your specific table structure
                $insert = $pdo->prepare("INSERT INTO users (username, email, google_id, role, password) VALUES (?, ?, ?, 'employee', NULL)");
                
                // Using email as username for google users, or use $name
                $insert->execute([$email, $email, $google_id]); 
                
                // Fetch the new user to set session
                $newUserStmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $newUserStmt->execute([$email]);
                $_SESSION['user'] = $newUserStmt->fetch();
                
                header("Location: index.php?page=employee-dashboard");
                exit;
            }
        }
    }
}

// If something went wrong
header("Location: index.php?error=google_login_failed");
exit;
?>