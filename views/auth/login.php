<?php
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../models/User.php';
    require_once __DIR__ . '/../../config/database.php';

    $user = User::findByUsername($_POST['username']);

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user;

        if ($user['role'] === 'admin') {
            header("Location: index.php?page=admin-dashboard");
        } else {
            header("Location: index.php?page=employee-dashboard");
        }
        exit;
    } else {
        $message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

.login-box {
    width: 380px;
    background: #fff;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 25px 45px rgba(0,0,0,0.25);
}

.login-box h3 {
    margin-bottom: 20px;
    color: #333;
}

.login-box input {
    width: 100%;
    padding: 12px;
    margin-bottom: 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.login-box input:focus {
    outline: none;
    border-color: #2ecc71;
}

.login-btn {
    width: 100%;
    padding: 12px;
    background: #2ecc71;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
}

.login-btn:hover {
    background: #27ae60;
}

.error {
    background: #ffecec;
    color: #c62828;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 13px;
}

.divider {
    margin: 18px 0;
    color: #888;
    font-size: 13px;
}

.social-btn {
    width: 100%;
    padding: 11px;
    border-radius: 8px;
    border: none;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 12px;
}

.google {
    background: #ffffff;
    color: #444;
    border: 1px solid #ddd;
}

.google i {
    color: #db4437;
}

.facebook {
    background: #1877f2;
    color: #fff;
}

.register-text {
    margin-top: 16px;
    font-size: 14px;
}

.register-text a {
    color: #2ecc71;
    text-decoration: none;
    font-weight: 600;
}

.register-text a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="container">
    <div class="welcome-text">Kajal Electronics</div>

    <div class="login-box">
        <h3>Login</h3>

        <?php if (!empty($message)): ?>
            <div class="error"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <button class="login-btn" type="submit">Login</button>
        </form>

        <div class="divider">OR</div>

        <!-- Social Login Buttons -->
      <a href="google-login.php" class="social-btn google">
    <i class="fa-brands fa-google"></i> Continue with Google
</a>



        <button class="social-btn facebook">
            <i class="fa-brands fa-facebook-f"></i> Continue with Facebook
        </button>

        <!-- Register Redirect -->
        <div class="register-text">
            Don’t have an account?
            <a href="index.php?page=register">Register here</a>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>
