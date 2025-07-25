<?php

use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

$list = function () {
    try {
        $taskModel = new TaskService();
        $authService = new AuthService();
        $userId = $authService->getCurrentUser()->id;
        $isValidUser = $userId ? $authService->validateUserOwnership($userId) : false;

        if (!$isValidUser) {
            elog("Unauthorized access attempt by user ID: $userId", "error");
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $tasks = $taskModel->getAllTasks($userId);
        $stats = $taskModel->getTaskStats($userId);
        $overdue = $taskModel->getOverdueTasks($userId);

        $this->response($this->json([
            'success' => true,
            'data' => [
                'tasks' => $tasks,
                'stats' => $stats,
                'overdue' => $overdue
            ]
        ]), 200);

    } catch (\Exception $e) {
        elog("Tasks list error: " . $e->getMessage(), "error");
        $this->response($this->json([
            'success' => false,
            'message' => $e->getMessage()
        ]), $e->getMessage() === 'Authentication required' ? 401 : 500);
    }
};