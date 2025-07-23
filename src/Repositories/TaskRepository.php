<?php

namespace ZealPHP\Repositories;

use PDO;
use ZealPHP\Database\Connection;
use ZealPHP\Models\Task;
use function ZealPHP\elog;

class TaskRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
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
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);

        return (int) $this->db->lastInsertId();
    }

    public function update(string $table, array $data, array $where): bool
    {
        $setClause = implode(', ', array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        $whereClause = implode(' AND ', array_map(fn($key) => "{$key} = :where_{$key}", array_keys($where)));

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";

        $params = $data;
        foreach ($where as $key => $value) {
            $params["where_{$key}"] = $value;
        }

        $stmt = $this->query($sql, $params);
        return $stmt->rowCount() > 0;
    }

}