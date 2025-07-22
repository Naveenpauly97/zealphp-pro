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

}