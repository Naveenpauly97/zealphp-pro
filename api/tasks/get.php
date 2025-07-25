<?php

use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;
use ZealPHP\G;

$get = function () {

    elog('Fetching task details', 'info : TaskService getAllTasks');
    try {
        $g = G::instance();
        $taskId = (int) ($g->get['id'] ?? 0);

        $authService = new AuthService();
        $userId = $authService->getCurrentUser()->id;
        $isValidUser = $userId ? $authService->validateUserOwnership($userId) : false;

        if (!$isValidUser) {
            elog("Unauthorized access attempt by user ID: $userId", "error");
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        if (!$taskId) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Task ID is required'
            ]), 400);
            return;
        }

        $taskModel = new TaskService();
        $task = $taskModel->getTask($taskId, $userId);

        // Check if task exists
        if (!$task) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Task not found'
            ]), 404);
            return;
        }

        $this->response($this->json([
            'success' => true,
            'data' => $task
        ]), 200);

    } catch (\Exception $e) {
        elog('Error fetching task: ' . $e->getMessage(), 'error : TaskService getAllTasks');
        $this->response($this->json([
            'success' => false,
            'message' => $e->getMessage()
        ]), $e->getMessage() === 'Authentication required' ? 401 : 500);
    }
};