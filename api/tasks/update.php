<?php

use ZealPHP\Services\TaskService;
use ZealPHP\G;
use function ZealPHP\elog;

$update = function () {
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

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Invalid JSON input'
            ]), 400);
            return;
        }

        $taskModel = new TaskService();
        $existingTask = $taskModel->getTask($taskId, 1);

        if (!$existingTask) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Task not found'
            ]), 404);
            return;
        }

        // Prepare update data
        $updateData = [];
        $allowedFields = ['title', 'description', 'status', 'priority', 'due_date'];

        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updateData[$field] = $input[$field];
            }
        }

        // Validate status
        if (isset($updateData['status'])) {
            $validStatuses = ['pending', 'in_progress', 'completed'];
            if (!in_array($updateData['status'], $validStatuses)) {
                $this->response($this->json([
                    'success' => false,
                    'message' => 'Invalid status. Must be: pending, in_progress, or completed'
                ]), 400);
                return;
            }
        }

        // Validate priority
        if (isset($updateData['priority'])) {
            $validPriorities = ['low', 'medium', 'high'];
            if (!in_array($updateData['priority'], $validPriorities)) {
                $this->response($this->json([
                    'success' => false,
                    'message' => 'Invalid priority. Must be: low, medium, or high'
                ]), 400);
                return;
            }
        }

        if (empty($updateData)) {
            $this->response($this->json([
                'success' => false,
                'message' => 'No valid fields to update'
            ]), 400);
            return;
        }

        $success = $taskModel->update($taskId, $updateData);

        if ($success) {
            $updatedTask = $taskModel->getTask($taskId, 1);
            $this->response($this->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => $updatedTask
            ]), 200);
        } else {
            $this->response($this->json([
                'success' => false,
                'message' => 'Failed to update task'
            ]), 500);
        }

    } catch (\Exception $e) {
        elog("Task update error: " . $e->getMessage(), "error");
        $this->response($this->json([
            'success' => false,
            'message' => $e->getMessage()
        ]), $e->getMessage() === 'Authentication required' ? 401 : 500);
    }
};