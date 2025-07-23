<?php

use ZealPHP\Services\TaskService;
use ZealPHP\G;
use function ZealPHP\elog;

$delete = function () {
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
        $task = $taskModel->getTask($taskId, 1);

        if (!$task) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Task not found'
            ]), 404);
            return;
        }

        $success = $taskModel->delete($taskId);

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
        elog("Task deletion error: " . $e->getMessage(), "error");
        $this->response($this->json([
            'success' => false,
            'message' => $e->getMessage()
        ]), $e->getMessage() === 'Authentication required' ? 401 : 500);
    }
};