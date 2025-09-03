<?php

namespace ZealPHP\Models;

use ZealPHP\Database\Connection;
use PDO;
use function ZealPHP\elog;

class Lead
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getMySQL();
    }

    public function create(array $data): ?int
    {
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO leads (name, email, phone, message,image, created_at, updated_at) 
                 VALUES (?, ?, ?, ?, ?, NOW(), NOW())'
            );

            $result = $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['message'] ?? null,
                $data['image'] ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'
            ]);

            if ($result) {
                $id = $this->db->lastInsertId();
                elog("Lead created with ID: $id", "info");
                return (int) $id;
            }
        } catch (\PDOException $e) {
            elog("Failed to create lead: " . $e->getMessage(), "error");
        }

        return null;
    }

    public function emailExists(string $email): bool
    {
        try {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM leads WHERE email = ?');
            $stmt->execute([$email]);
            return $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            elog("Error checking email existence: " . $e->getMessage(), "error");
            return false;
        }
    }

    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM leads WHERE id = ?');
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (\PDOException $e) {
            elog("Error finding lead by ID: " . $e->getMessage(), "error");
            return null;
        }
    }

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        try {
            $stmt = $this->db->prepare(
                'SELECT * FROM leads ORDER BY created_at DESC LIMIT ? OFFSET ?'
            );
            $stmt->execute([$limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            elog("Error fetching leads: " . $e->getMessage(), "error");
            return [];
        }
    }

    public function getStats(): array
    {
        try {
            $stmt = $this->db->prepare(
                'SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today,
                    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as this_week,
                    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as this_month
                 FROM leads'
            );
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            elog("Error fetching lead stats: " . $e->getMessage(), "error");
            return [];
        }
    }
}