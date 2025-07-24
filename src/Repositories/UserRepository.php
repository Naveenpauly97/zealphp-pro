<?php

namespace ZealPHP\Repositories;

use PDO;
use ZealPHP\Database\Connection;
use ZealPHP\Models\User;
use function ZealPHP\elog;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        
        $data = $stmt->fetch();
        return $data ? new User($data) : null;
    }

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        
        $data = $stmt->fetch();
        return $data ? new User($data) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        
        $data = $stmt->fetch();
        return $data ? new User($data) : null;
    }

    public function create(array $userData): ?User
    {
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)'
            );
            
            $result = $stmt->execute([
                $userData['username'],
                $userData['email'],
                User::hashPassword($userData['password'])
            ]);

            if ($result) {
                $id = $this->db->lastInsertId();
                elog("User created with ID: $id");
                return $this->findById((int)$id);
            }
        } catch (\PDOException $e) {
            elog("Failed to create user: " . $e->getMessage(), "error");
        }

        return null;
    }

        public function createUserSession(array $userData): ?User
    {
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO user_sessions (user_id, `data`) VALUES (?, ?)'
            );
            
            $result = $stmt->execute([
                $userData['user_id'],
                $userData['data']
            ]);

            if ($result) {
                $id = $this->db->lastInsertId();
                elog("User Session created with ID: $id");
                return $this->findById((int)$id);
            }
        } catch (\PDOException $e) {
            elog("Failed to create user session: " . $e->getMessage(), "error");
        }

        return null;
    }

    public function update(int $id, array $userData): bool
    {
        try {
            $fields = [];
            $values = [];

            foreach ($userData as $field => $value) {
                if (in_array($field, ['username', 'email', 'password'])) {
                    if ($field === 'password') {
                        $fields[] = 'password_hash = ?';
                        $values[] = User::hashPassword($value);
                    } else {
                        $fields[] = "$field = ?";
                        $values[] = $value;
                    }
                }
            }

            if (empty($fields)) {
                return false;
            }

            $values[] = $id;
            $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (\PDOException $e) {
            elog("Failed to update user: " . $e->getMessage(), "error");
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            elog("Failed to delete user: " . $e->getMessage(), "error");
            return false;
        }
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
}