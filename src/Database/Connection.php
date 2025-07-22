<?php

namespace ZealPHP\Database;

use PDO;
use PDOException;
use ZealPHP\App;
use function ZealPHP\elog;

class Connection
{
    private static ?PDO $instance = null;
    private static array $config = [];

    public static function init(array $config): void
    {
        self::$config = $config;
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::connect();
        }
        
        return self::$instance;
    }

    private static function connect(): void
    {
        $config = self::$config['connections']['mysql'];
        
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );
        // Load environment variables from .env file
        if (file_exists(App::$cwd . '/.env')) {
            $lines = file(App::$cwd . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                putenv(trim($name) . '=' . trim($value));
            }
        }

        try {
            self::$instance = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
            
            elog("Database connected successfully");
        } catch (PDOException $e) {
            elog("Database connection failed: " . $e->getMessage(), "error");
            throw $e;
        }
    }

    public static function reconnect(): void
    {
        self::$instance = null;
        self::connect();
    }

    public static function close(): void
    {
        self::$instance = null;
    }
}