<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login() {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = User::findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];

                if ($user['role'] == 'admin') {
                    header("Location: index.php?page=admin-dashboard");
                } else {
                    header("Location: index.php?page=employee-dashboard");
                }
                exit();
            } else {
                $message = "Invalid username or password.";
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
