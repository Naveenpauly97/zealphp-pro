<?php

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST') ?: 'mysql.selfmade.ninja',
            'port' => (int)(getenv('DB_PORT') ?: 3306),
            'database' => getenv('DB_DATABASE') ?: 'project_demo_zealproject',
            'username' => getenv('DB_USERNAME') ?: 'project_demo',
            'password' => getenv('DB_PASSWORD') ?: 'Mysql@1234',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ],
    ],
];