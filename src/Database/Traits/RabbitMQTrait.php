<?php

namespace ZealPHP\Database\Traits;

use PhpAmqpLib\Connection\AMQPStreamConnection;

trait RabbitMQTrait
{
    private static ?AMQPStreamConnection $rabbitInstance = null;

    public static function getRabbit(): AMQPStreamConnection
    {
        if (self::$rabbitInstance === null) {
            self::connectRabbit();
        }
        return self::$rabbitInstance;
    }

    private static function connectRabbit(): void
    {
        $config = self::getConfig('rabbitmq');

        self::$rabbitInstance = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['username'],
            $config['password'],
            $config['vhost'] ?? '/'
        );
    }

    public static function closeRabbit(): void
    {
        if (self::$rabbitInstance !== null) {
            self::$rabbitInstance->close();
            self::$rabbitInstance = null;
        }
    }
}
