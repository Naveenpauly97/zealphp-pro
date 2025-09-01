<?php

use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

$create = function () {
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

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $input = $this->_request; // Fallback to POST data if JSON is not provided
        }

        if (!$input || empty($input['title'])) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Title is required'
            ]), 400);
            return;
        }

        $taskData = [
            'user_id' => $userId,
            'title' => $input['title'],
            'description' => $input['description'] ?? '',
            'status' => $input['status'] ?? 'pending',
            'priority' => $input['priority'] ?? 'medium',
            'due_date' => $input['due_date'] ?? null
        ];

        // Validate status
        $validStatuses = ['pending', 'in_progress', 'completed'];
        if (!in_array($taskData['status'], $validStatuses)) {
            $taskData['status'] = 'pending';
        }

        // Validate priority
        $validPriorities = ['low', 'medium', 'high'];
        if (!in_array($taskData['priority'], $validPriorities)) {
            $taskData['priority'] = 'medium';
        }

        $taskModel = new TaskService();
        $taskId = $taskModel->create($taskData);
        $task = $taskModel->getTask($taskId, $userId);

        $this->response($this->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ]), 201);

    } catch (\Exception $e) {
        //elog"Task creation error: " . $e->getMessage(), "error");
        $this->response($this->json([
            'success' => false,
            'message' => $e->getMessage()
        ]), $e->getMessage() === 'Authentication required' ? 401 : 500);
    }
};