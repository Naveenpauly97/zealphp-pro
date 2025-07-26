<?php

namespace ZealPHP\Services;

use ZealPHP\Models\Task;
use ZealPHP\Models\TaskLog;
use ZealPHP\Repositories\TaskLogRepository;
use ZealPHP\Repositories\TaskRepository;

class TaskLogService
{
    private TaskLogRepository $taskLogRepository;

    public function __construct()
    {
        $this->taskLogRepository = new TaskLogRepository();
    }

    // Get recent logs with a limit
    public function getRecentLogs(int $limit = 10): array
    {
        return $this->taskLogRepository->getRecentLogs($limit);
    }

    // Get logs by action with a limit
    public function getLogsByAction(string $action, int $limit = 10): array
    {
        return $this->taskLogRepository->getLogsByAction($action, $limit);
    }

    // Get a single log by ID
    public function findTaskLogById(int $id): ?TaskLog
    {
        return $this->taskLogRepository->findTaskLogById($id);
    }

    public function create(array $taskData): int
    {
        $taskData['created_at'] = date('Y-m-d H:i:s');

        return $this->taskLogRepository->insert('task_logs', $taskData);
    }

}