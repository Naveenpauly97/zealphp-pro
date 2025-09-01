<?php

namespace ZealPHP\Database\Traits;

use PDO;
use PDOException;

trait MySQLTrait
{
    private static ?PDO $mysqlInstance = null;

    public static function getMySQL(): PDO
    {
        if (self::$mysqlInstance === null) {
            self::connectMySQL();
        }
        return self::$mysqlInstance;
    }

    private static function connectMySQL(): void
    {
        $config = self::getConfig('mysql');

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            self::$mysqlInstance = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
            //elog("MySQL connected successfully");
        } catch (PDOException $e) {
            //elog("MySQL connection failed: " . $e->getMessage(), "error");
            throw $e;
        }
    }

    public static function closeMySQL(): void
    {
        self::$mysqlInstance = null;
    }
}
