<?php

use ZealPHP\App;
use ZealPHP\Services\AuthService;

$app = App::instance();

// Login page
$app->route('/login', ['methods' => ['GET', 'POST']], function() {
    $authService = new AuthService();
    
    if ($authService->isAuthenticated()) {
        header('Location: /tasks');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
            $user = $authService->login($username, $password);
            
            if ($user) {
                header('Location: /tasks');
                exit;
            } else {
                $error = 'Invalid credentials';
            }
        } else {
            $error = 'Username and password are required';
        }
    }

    App::render('/auth/login', [
        'title' => 'Login',
        'error' => $error ?? null
    ]);
});

// Register page
$app->route('/register', ['methods' => ['GET', 'POST']], function() {
    $authService = new AuthService();
    
    if ($authService->isAuthenticated()) {
        header('Location: /tasks');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required';
        }
        if (empty($email)) {
            $errors[] = 'Email is required';
        }
        if (empty($password)) {
            $errors[] = 'Password is required';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (empty($errors)) {
            $user = $authService->register([
                'username' => $username,
                'email' => $email,
                'password' => $password
            ]);
            
            if ($user) {
                header('Location: /tasks');
                exit;
            } else {
                $errors[] = 'Registration failed. Username or email may already exist.';
            }
        }
    }

    App::render('/auth/register', [
        'title' => 'Register',
        'errors' => $errors ?? []
    ]);
});

// Logout
$app->route('/logout', ['methods' => ['GET']], function() {
    $authService = new AuthService();
    $authService->logout();
    header('Location: /login');
    exit;
});