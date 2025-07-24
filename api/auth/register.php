<?php

use ZealPHP\Services\AuthService;
use function ZealPHP\elog;

$register = function() {
    $authService = new AuthService();
    
    if ($this->get_request_method() !== 'POST') {
        $this->response($this->json(['error' => 'Method not allowed']), 405);
        return;
    }

    $username = $this->_request['username'] ?? '';
    $email = $this->_request['email'] ?? '';
    $password = $this->_request['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $this->response($this->json(['error' => 'Username, email, and password are required']), 400);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->response($this->json(['error' => 'Invalid email format']), 400);
        return;
    }

    $user = $authService->register([
        'username' => $username,
        'email' => $email,
        'password' => $password
    ]);

    if ($user) {
        $this->response($this->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $user->toArray()
        ]), 201);
    } else {
        $this->response($this->json(['error' => 'Registration failed. Username or email may already exist.']), 400);
    }
};