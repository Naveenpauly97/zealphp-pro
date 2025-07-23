<?php

namespace ZealPHP\Services;

use ZealPHP\Models\Task;
use ZealPHP\Repositories\TaskRepository;

class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
    }

    public function getAllTasks(int $userId, array $filters = []): array
    {
        return $this->taskRepository->findByUserId($userId, $filters);
    }

    public function getTask(int $id, int $userId): ?Task
    {
        $task = $this->taskRepository->findById($id);

        if ($task && $task->user_id === $userId) {
            return $task;
        }

        return null;
    }

    public function getTaskStats(int $userId): array
    {
        return $this->taskRepository->getTaskStats($userId);
    }

    public function getOverdueTasks(int $userId): array
    {
        return $this->taskRepository->getOverdueTasks($userId);
    }

    public function getTasksByStatus(int $userId, string $status): array
    {
        return $this->taskRepository->getTasksByStatus($userId, $status);
    }

    public function create(array $taskData): int
    {
        $taskData['created_at'] = date('Y-m-d H:i:s');
        $taskData['updated_at'] = date('Y-m-d H:i:s');

        return $this->taskRepository->insert('tasks', $taskData);
    }
}