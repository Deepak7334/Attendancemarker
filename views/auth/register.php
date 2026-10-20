<?php
session_start();
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../models/User.php';
    require_once __DIR__ . '/../../config/database.php';

    $data = [
        'username' => trim($_POST['username']),
        'email'    => trim($_POST['email']),
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'role'     => 'employee'
    ];

    if (User::create($data)) {
        $success = true;
        $message = "Registration successful! You can login now.";
    } else {
        $message = "Registration failed. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    margin: 0;
    height: 100vh;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    text-align: center;
}

.welcome-text {
    color: #fff;
    font-size: 34px;
    font-weight: 600;
    margin-bottom: 30px;
}

.register-box {
    width: 400px;
    background: #fff;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 25px 45px rgba(0,0,0,0.25);
}

.register-box h3 {
    margin-bottom: 20px;
    color: #333;
}

.register-box input {
    width: 100%;
    padding: 12px;
    margin-bottom: 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.register-box input:focus {
    outline: none;
    border-color: #2ecc71;
}

.register-btn {
    width: 100%;
    padding: 12px;
    background: #2ecc71;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
}

.register-btn:hover {
    background: #27ae60;
}

.success {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}

.error {
    background: #ffecec;
    color: #c62828;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}

.login-text {
    margin-top: 16px;
    font-size: 14px;
}

.login-text a {
    color: #2ecc71;
    text-decoration: none;
    font-weight: 600;
}

.login-text a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="container">
    <div class="welcome-text">Create Your Account</div>

    <div class="register-box">
        <h3>Register</h3>

        <?php if (!empty($message)): ?>
            <div class="<?= $success ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="index.php?page=register">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="register-btn" type="submit">Register</button>
        </form>
        <?php endif; ?>

        <div class="login-text">
            Already have an account?
            <a href="index.php?page=login">Login here</a>
        </div>
    </div>
</div>

</body>
</html>
