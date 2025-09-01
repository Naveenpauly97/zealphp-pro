<?php

namespace ZealPHP\Database;

use ZealPHP\App;
use PDO;
use PDOException;
use MongoDB\Client as MongoClient;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use function ZealPHP\elog;

// Load all traits
use ZealPHP\Database\Traits\MySQLTrait;
use ZealPHP\Database\Traits\PostgresTrait;
use ZealPHP\Database\Traits\MongoDBTrait;
use ZealPHP\Database\Traits\RabbitMQTrait;

class Connection
{
    use MySQLTrait, PostgresTrait, MongoDBTrait, RabbitMQTrait;

    private static array $config = [];

    public static function init(array $config): void
    {
        self::$config = $config;
    }

    public static function getConfig(string $service): array
    {
        if (!isset(self::$config['connections'][$service])) {
            throw new \RuntimeException("Configuration for service '{$service}' not found.");
        }
        return self::$config['connections'][$service];
    }

    public static function loadEnv(): void
    {
        $homePath = !empty(App::$cwd) ? App::$cwd : '/app';

        $envPath = $homePath . '/.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                putenv(trim($name) . '=' . trim($value));
            }
        }
    }
}
