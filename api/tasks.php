<?php

// use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;
use ZealPHP\G;

$tasks = function () {

    //elog'Fetching task details', 'info : TaskService getAllTasks');
    try {

        $Status = G::instance()->get['status'] ?? '';
        $userId = G::instance()->session['user_id'] ?? '';

        if (!$userId) {
            //elog"Unauthorized access attempt by user ID: $userId", "error");
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $taskModel = new TaskService();
        $tasks = empty($gettt = G::instance()->get) ? $taskModel->getAllTasks($userId) :
            $taskModel->getTasksByStatus($userId, $Status);
        $stats = $taskModel->getTaskStats($userId);
        $overdue_tasks = $taskModel->getOverdueTasks($userId);
        // Check if task exists
        if (!$tasks) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Request Data Not Found'
            ]), 404);
            return;
        }

        $response = [
            'task' => $tasks,
            'stats' => $stats,
            'overdue_tasks' => $overdue_tasks,
            'current_status' => $Status,
        ];

        $this->response($this->json([
            'success' => true,
            'data' => $response
        ]), 200);
    } catch (\Exception $e) {
        //elog'Error fetching task: ' . $e->getMessage(), 'error : TaskService getAllTasks');
        $this->response($this->json([
            'success' => false,
            'message' => $e->getMessage()
        ]), $e->getMessage() === 'Authentication required' ? 401 : 500);
    }
};
