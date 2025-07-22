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

}