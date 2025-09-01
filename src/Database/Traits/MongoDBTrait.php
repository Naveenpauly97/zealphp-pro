<?php

namespace ZealPHP\Database\Traits;

use MongoDB\Client as MongoClient;

trait MongoDBTrait
{
    private static ?MongoClient $mongoInstance = null;

    public static function getMongo(): MongoClient
    {
        if (self::$mongoInstance === null) {
            self::connectMongo();
        }
        return self::$mongoInstance;
    }

    private static function connectMongo(): void
    {
        $config = self::getConfig('mongodb');

        $uri = sprintf(
            "mongodb://%s:%s@%s:%d/%s",
            $config['username'],
            $config['password'],
            $config['host'],
            $config['port'],
            $config['database']
        );

        self::$mongoInstance = new MongoClient($uri, $config['options'] ?? []);
    }

    public static function closeMongo(): void
    {
        self::$mongoInstance = null;
    }
}
