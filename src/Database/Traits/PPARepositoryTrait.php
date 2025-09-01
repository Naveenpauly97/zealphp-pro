<?php

namespace ZealPHP\Database\Traits;

use BadMethodCallException;
use Exception;
use PDO;

trait PPARepositoryTrait
{
    protected string $tableName = '';
    protected string $primaryKey = 'id';
    protected $dbConnection; // Provided by the repository

    public function setTableName(string $name): void
    {
        $this->tableName = $name;
    }

    public function setPrimaryKey(string $key): void
    {
        $this->primaryKey = $key;
    }

    public function setDbConnection($connection): void
    {
        $this->dbConnection = $connection;
    }

    public function __call($name, $arguments)
    {
        if (preg_match('/^(find|get|delete|update|insert)By(.+)$/', $name, $matches)) {
            $action = strtolower($matches[1]);
            $conditionsPart = $matches[2];

            $conditions = preg_split('/And/', $conditionsPart);
            $conditions = array_map(fn($cond) => lcfirst($cond), $conditions);

            return $this->handleDynamicQuery($action, $conditions, $arguments);
        }

        throw new BadMethodCallException("Method {$name} not found");
    }

    protected function handleDynamicQuery(string $action, array $conditions, array $arguments)
    {
        if (empty($this->tableName)) {
            throw new Exception("Table name not set for " . static::class);
        }
        if (!$this->dbConnection) {
            throw new Exception("DB connection not set for " . static::class);
        }

        $where = [];
        foreach ($conditions as $index => $column) {
            if (array_key_exists($index, $arguments)) {
                $where[$this->toSnakeCase($column)] = $arguments[$index];
            }
        }

        // Detect DB type based on connection class
        if ($this->dbConnection instanceof PDO) {
            return $this->handleSqlQuery($action, $where, $arguments);
        } elseif ($this->dbConnection instanceof MongoDB\Database) {
            return $this->handleMongoQuery($action, $where, $arguments);
        } else {
            throw new Exception("Unsupported DB connection type: " . get_class($this->dbConnection));
        }
    }

    protected function handleSqlQuery(string $action, array $where, array $arguments)
    {
        $conn = $this->dbConnection;
        $sql = '';
        $params = [];

        switch ($action) {
            case 'find':
            case 'get':
                $sql = "SELECT * FROM {$this->tableName}";
                if (!empty($where)) {
                    $sql .= " WHERE " . implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
                    $params = array_values($where);
                }
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            case 'delete':
                $sql = "DELETE FROM {$this->tableName}";
                if (!empty($where)) {
                    $sql .= " WHERE " . implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
                    $params = array_values($where);
                }
                $stmt = $conn->prepare($sql);
                return $stmt->execute($params);

            case 'update':
                $data = $arguments[0] ?? [];
                $setPart = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
                $params = array_values($data);

                if (!empty($where)) {
                    $wherePart = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
                    $params = array_merge($params, array_values($where));
                    $sql = "UPDATE {$this->tableName} SET $setPart WHERE $wherePart";
                } else {
                    $sql = "UPDATE {$this->tableName} SET $setPart";
                }

                $stmt = $conn->prepare($sql);
                return $stmt->execute($params);

            case 'insert':
                $data = $arguments[0] ?? [];
                $columns = implode(', ', array_keys($data));
                $placeholders = implode(', ', array_fill(0, count($data), '?'));
                $sql = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";
                $stmt = $conn->prepare($sql);
                return $stmt->execute(array_values($data));
        }

        throw new Exception("Unsupported SQL action: {$action}");
    }

    protected function handleMongoQuery(string $action, array $where, array $arguments)
    {
        $collection = $this->dbConnection->selectCollection($this->tableName);

        switch ($action) {
            case 'find':
            case 'get':
                return $collection->find($where)->toArray();

            case 'delete':
                return $collection->deleteMany($where);

            case 'update':
                $data = $arguments[0] ?? [];
                return $collection->updateMany($where, ['$set' => $data]);

            case 'insert':
                $data = $arguments[0] ?? [];
                return $collection->insertOne($data);
        }

        throw new Exception("Unsupported MongoDB action: {$action}");
    }

    protected function toSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
}
