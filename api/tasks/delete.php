<?php

use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use ZealPHP\G;
use function ZealPHP\elog;

$delete = function () {
    try {
        $authService = new AuthService();
        $userId = $authService->getCurrentUser()->id;
        $isValidUser = $userId ? $authService->validateUserOwnership($userId) : false;

        if (!$isValidUser) {
            //elog"Unauthorized access attempt by user ID: $userId", "error");
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $g = G::instance();
        $taskId = (int) ($g->get['id'] ?? 0);

        if (!$taskId) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Task ID is required'
            ]), 400);
            return;
        }

        $taskModel = new TaskService();
        $task = $taskModel->getTask($taskId, $userId);

        if (!$task) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Task not found'
            ]), 404);
            return;
        }

        $success = $taskModel->deleteTaskByUserId($taskId,$userId );

        if ($success) {
            $this->response($this->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]), 200);
        } else {
            $this->response($this->json([
                'success' => false,
                'message' => 'Failed to delete task'
            ]), 500);
        }

    } catch (\Exception $e) {
        //elog"Task deletion error: " . $e->getMessage(), "error");
        $this->response($this->json([
            'success' => false,
            'message' => $e->getMessage()
        ]), $e->getMessage() === 'Authentication required' ? 401 : 500);
    }
};