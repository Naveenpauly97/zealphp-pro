<?php

use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

$list = function () {
    try {
        $taskModel = new TaskService();

        $tasks = $taskModel->getAllTasks(1);
        $stats = $taskModel->getTaskStats(1);
        $overdue = $taskModel->getOverdueTasks(1);

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