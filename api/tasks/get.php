<?php

use ZealPHP\Services\TaskService;
use function ZealPHP\elog;
use ZealPHP\G;

$get = function () {

    elog('Fetching task details', 'info : TaskService getAllTasks');
    try {
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
        // TODO : replace with actual user ID from authentication
        $task = $taskModel->getAllTasks(1);

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