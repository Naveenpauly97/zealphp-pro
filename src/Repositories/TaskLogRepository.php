<?php

namespace ZealPHP\Repositories;

use PDO;
use ZealPHP\Database\Connection;
use ZealPHP\Models\Task;
use ZealPHP\Models\TaskLog;

use function ZealPHP\elog;

class TaskLogRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getMySQL();
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function insert(string $table, array $data): int
    {
        //elog"Inserting Task table: {$table} with id : " . ($data['id'] ?? 'N/A'), "debug");
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);

        return (int) $this->db->lastInsertId();
    }

    public function getRecentLogs(int $limit = 10): array
    {
        $stmt = $this->db->prepare('SELECT * FROM task_logs ORDER BY created_at DESC LIMIT ?');
        $stmt->execute([$limit]);
        $logs = [];
        while ($data = $stmt->fetch()) {
            $logs[] = new TaskLog($data);
        }
        return $logs;
    }

    public function getLogsByAction(string $action, int $limit = 10): array
    {
        $stmt = $this->db->prepare('SELECT * FROM task_logs WHERE action = ? ORDER BY created_at DESC LIMIT ?');
        $stmt->execute([$action, $limit]);
        $logs = [];
        while ($data = $stmt->fetch()) {
            $logs[] = new TaskLog($data);
        }
        return $logs;
    }


    public function createTaskLog(array $data): int
    {
        // Ensure old_values and new_values are JSON strings
        if (isset($data['old_values']) && is_array($data['old_values'])) {
            $data['old_values'] = json_encode($data['old_values']);
        }
        if (isset($data['new_values']) && is_array($data['new_values'])) {
            $data['new_values'] = json_encode($data['new_values']);
        }
        return $this->insert('task_logs', $data);
    }

    // Read a TaskLog by ID
    public function findTaskLogById(int $id): ?TaskLog
    {
        $stmt = $this->db->prepare('SELECT * FROM task_logs WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? new TaskLog($data) : null;
    }
}