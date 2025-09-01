<?php

namespace ZealPHP\Database\Traits;

use PDO;
use PDOException;

trait PostgresTrait
{
    private static ?PDO $postgresInstance = null;

    public static function getPostgres(): PDO
    {
        if (self::$postgresInstance === null) {
            self::connectPostgres();
        }
        return self::$postgresInstance;
    }

    private static function connectPostgres(): void
    {
        $config = self::getConfig('postgres');

        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $config['host'],
            $config['port'],
            $config['database']
        );

        try {
            self::$postgresInstance = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public static function closePostgres(): void
    {
        self::$postgresInstance = null;
    }
}
