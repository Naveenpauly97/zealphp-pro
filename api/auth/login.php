<?php

use ZealPHP\Services\AuthService;
use function ZealPHP\elog;

$login = function() {
    $authService = new AuthService();
    
    if ($this->get_request_method() !== 'POST') {
        $this->response($this->json(['error' => 'Method not allowed']), 405);
        return;
    }

    $username = $this->_request['username'] ?? '';
    $password = $this->_request['password'] ?? '';

    if (empty($username) || empty($password)) {
        $this->response($this->json(['error' => 'Username and password are required']), 400);
        return;
    }

    $user = $authService->login($username, $password);

    if ($user) {
        $this->response($this->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user->toArray()
        ]), 200);
    } else {
        $this->response($this->json(['error' => 'Invalid credentials']), 401);
    }
};