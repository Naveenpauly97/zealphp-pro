<?php

namespace ZealPHP\Repositories;

use PDO;
use ZealPHP\Database\Connection;
use ZealPHP\Models\Task;
use ZealPHP\Services\TaskLogService;
use ZealPHP\Session;

use function ZealPHP\elog;

class TaskRepository
{
    private PDO $db;

    private TaskLogService $taskLogService;

    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->taskLogService = new TaskLogService();
    }

    public function findById(int $id): ?Task
    {
        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE id = ?');
        $stmt->execute([$id]);

        $data = $stmt->fetch();
        return $data ? new Task($data) : null;
    }

    public function findByUserId(int $userId, array $filters = []): array
    {
        $sql = 'SELECT * FROM tasks WHERE user_id = ?';
        $params = [$userId];

        if (!empty($filters['status'])) {
            $sql .= ' AND status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['priority'])) {
            $sql .= ' AND priority = ?';
            $params[] = $filters['priority'];
        }

        $sql .= ' ORDER BY created_at DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ?';
            $params[] = (int) $filters['limit'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $tasks = [];
        while ($data = $stmt->fetch()) {
            $tasks[] = new Task($data);
        }

        return $tasks;
    }

    public function getTasksByStatus(int $userId, string $status): array
    {
        return $this->findByUserId($userId, ['status' => $status]);
    }

    public function getOverdueTasks(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM tasks 
             WHERE user_id = ? AND due_date < CURDATE() AND status != "completed"
             ORDER BY due_date ASC'
        );
        $stmt->execute([$userId]);

        $tasks = [];
        while ($data = $stmt->fetch()) {
            $tasks[] = new Task($data);
        }

        return $tasks;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getTaskStats(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT 
                status,
                COUNT(*) as count
             FROM tasks 
             WHERE user_id = ?
             GROUP BY status'
        );
        $stmt->execute([$userId]);

        $stats = [
            'pending' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'total' => 0
        ];

        while ($row = $stmt->fetch()) {
            $stats[$row['status']] = (int) $row['count'];
            $stats['total'] += (int) $row['count'];
        }

        return $stats;
    }
    public function insert(string $table, array $data): int
    {
        elog("Inserting Task table: {$table} with id : " . ($data['id'] ?? 'N/A'), "debug");
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);

        $insertedId = (int) $this->db->lastInsertId();
        if ($insertedId > 0) {
            try {
                //TODO: Need to implemet Code reusability for logging
                elog("Task inserted with ID: {$insertedId}", "debug");

                $newValues = json_encode($this->findById($insertedId));
                $logData = [
                    'task_id' => $insertedId,
                    'user_id' => Session::get('user_id') ?? null,
                    'action' => 'insert',
                    'new_values' => $newValues
                ];
                $this->taskLogService->create($logData);
                elog("Task log created for deletion of task ID: " . $insertedId, "debug");
            } catch (\Exception $e) {
                elog("Error creating task log for insertion: " . $e->getMessage(), "error");
            }
            return $insertedId;
        }
        return 0;
    }

    public function update(string $table, array $data, array $where): bool
    {
        elog("Updating Task table: {$table} with id : " . $where['id'], "debug");
        $oldValues = json_encode($this->findById($where['id']));

        $setClause = implode(', ', array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        $whereClause = implode(' AND ', array_map(fn($key) => "{$key} = :where_{$key}", array_keys($where)));

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";

        $params = $data;
        foreach ($where as $key => $value) {
            $params["where_{$key}"] = $value;
        }

        $stmt = $this->query($sql, $params);

        if ($stmt->rowCount() > 0) {
            elog("Updated Task data table: {$table} with id : " . $where['id'], "debug");
            try {
                $newValues = json_encode($this->findById($where['id']));
                $logData = [
                    'task_id' => $where['id'],
                    'user_id' => Session::get('user_id') ?? null,
                    'action' => 'update',
                    'new_values' => $newValues,
                    'old_values' => $oldValues
                ];
                $this->taskLogService->create($logData);
                elog("Task log created for updation of task ID: " . $where['id'], "debug");
            } catch (\Exception $e) {
                elog("Error creating task log for updation: " . $e->getMessage(), "error");
            }
            return true;
        }
        return false;


    }

    public function delete(string $table, array $where): bool
    {
        elog("Deleting Task table: {$table} with id : " . $where['id'], "debug");
        $whereClause = implode(' AND ', array_map(fn($key) => "{$key} = :{$key}", array_keys($where)));
        $oldValues = json_encode($this->findById($where['id']));
        $sql = "DELETE FROM {$table} WHERE {$whereClause}";
        $stmt = $this->query($sql, $where);
        elog("Deleted Task table: {$table} with id : " . $where['id'], "debug");
        if ($stmt->rowCount() > 0) {
            try {
                $logData = [
                    'task_id' => $where['id'],
                    'user_id' => Session::get('user_id') ?? null,
                    'action' => 'delete',
                    'old_values' => $oldValues
                ];
                $this->taskLogService->create($logData);
                elog("Task log created for deletion of task ID: " . $where['id'], "debug");
            } catch (\Exception $e) {
                elog("Error creating task log for deletion: " . $e->getMessage(), "error");
            }
            return true;
        }
        return false;
    }

}