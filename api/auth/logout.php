<?php

use ZealPHP\Services\AuthService;

$logout = function() {
    $authService = new AuthService();
    $authService->logout();
    
    $this->response($this->json([
        'success' => true,
        'message' => 'Logout successful'
    ]), 200);
};