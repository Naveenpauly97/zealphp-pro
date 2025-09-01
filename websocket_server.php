<?php

require_once __DIR__ . '/vendor/autoload.php';

use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use ZealPHP\WebSocket\TaskWebSocketHandler;
use function ZealPHP\elog;

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

// Initialize database connection
$dbConfig = require __DIR__ . '/config/database.php';
\ZealPHP\Database\Connection::init($dbConfig);

$server = new Server("0.0.0.0", 9502);
$handler = new TaskWebSocketHandler();

$server->set([
    'worker_num' => 4,
    // 'task_worker_num' => 4,
    'enable_static_handler' => false,
    'document_root' => __DIR__ . '/public',
]);

$server->on("start", function (Server $server) {
    echo "WebSocket Server started at ws://0.0.0.0:9502\n";
});

$server->on("open", function (Server $server, Request $request) use ($handler) {
    $handler->onOpen($server, $request);
});

$server->on("message", function (Server $server, Frame $frame) use ($handler) {
    echo "Received message: {$frame->data}\n";
    $handler->onMessage($server, $frame);
});

$server->on("close", function (Server $server, int $fd) use ($handler) {
    $handler->onClose($server, $fd);
});

$server->start();