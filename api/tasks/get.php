<?php

// use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;
use ZealPHP\G;

$get = function () {
    // $authService = new AuthService();
    $taskService = new TaskService();

    // $user = $authService->requireAuth();
    $g = G::instance();

    // Extract task ID from URL path
    $uri = $g->server['REQUEST_URI'];
    elog("Request URI: $uri", "info---------------------------------------------");
    if (preg_match('/\/api\/tasks\/(\d+)/', $uri, $matches)) {
        $taskId = (int) $matches[1];
        elog("Request taskId: $taskId", "info---------------------------------------------");
    } else {
        $this->response($this->json(['error' => 'Invalid task ID']), 400);
        return;
    }

    $task = $taskService->getTask($taskId, 1);

    if ($task) {
        $this->response($this->json([
            'success' => true,
            'data' => $task->toArray()
        ]), 200);
    } else {
        $this->response($this->json(['error' => 'Task not found']), 404);
    }
};