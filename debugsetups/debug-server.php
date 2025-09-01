<?php
/**
 * Debug version of app.php with enhanced error reporting and Xdebug support
 */

require_once __DIR__ . '/vendor/autoload.php';

// Enable all error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Force Xdebug session for every request
if (extension_loaded('xdebug')) {
    ini_set('xdebug.start_with_request', 'yes');
    ini_set('xdebug.mode', 'debug');
    
    // Set session cookie to ensure Xdebug triggers on every request
    if (!isset($_COOKIE['XDEBUG_SESSION'])) {
        setcookie('XDEBUG_SESSION', 'VSCODE', 0, '/');
        $_COOKIE['XDEBUG_SESSION'] = 'VSCODE';
    }
}

// Set timezone
date_default_timezone_set('Asia/Kolkata');

use OpenSwoole\Core\Psr\Response;
use OpenSwoole\Coroutine as co;
use OpenSwoole\Coroutine\Channel;
use ZealPHP\App;
use ZealPHP\G;

use function ZealPHP\elog;
use function ZealPHP\response_add_header;
use function ZealPHP\response_set_status;
use function ZealPHP\zlog;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ZealPHP\Database\Connection;

// Debug middleware for better error handling
class DebugMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Force Xdebug session for this request
        if (extension_loaded('xdebug') && function_exists('xdebug_start_trace')) {
            $g = G::instance();
            // Set Xdebug session in cookies and headers
            $g->cookie['XDEBUG_SESSION'] = 'VSCODE';
            if (!isset($_COOKIE['XDEBUG_SESSION'])) {
                $_COOKIE['XDEBUG_SESSION'] = 'VSCODE';
            }
        }
        
        //elog"DebugMiddleware: Processing request to " . $request->getUri()->getPath());
        
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            //elog"DebugMiddleware: Exception caught - " . $e->getMessage(), "error");
            
            // Return detailed error for debugging
            $errorData = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            
            return new Response(
                json_encode($errorData, JSON_PRETTY_PRINT),
                500,
                'Internal Server Error',
                ['Content-Type' => 'application/json']
            );
        }
    }
}

// Enable superglobals for easier debugging
App::superglobals(true);

// Initialize database connection
$dbConfig = require __DIR__ . '/config/database.php';
Connection::init($dbConfig);

// Initialize app with debug settings
$app = App::init('0.0.0.0', 8080);

// Add debug middleware
$app->addMiddleware(new DebugMiddleware());

// Add a debug route
$app->route('/debug/info', function() {
    phpinfo();
});

$app->route('/debug/xdebug', function() {
    $xdebugInfo = [
        'xdebug_loaded' => extension_loaded('xdebug'),
        'xdebug_version' => extension_loaded('xdebug') ? phpversion('xdebug') : 'Not loaded',
        'xdebug_mode' => ini_get('xdebug.mode'),
        'client_host' => ini_get('xdebug.client_host'),
        'client_port' => ini_get('xdebug.client_port'),
        'idekey' => ini_get('xdebug.idekey'),
        'start_with_request' => ini_get('xdebug.start_with_request')
    ];
    
    header('Content-Type: application/json');
    echo json_encode($xdebugInfo, JSON_PRETTY_PRINT);
});

// Include existing routes
include_once __DIR__ . '/app.php';

echo "Debug server starting with Xdebug support...\n";
echo "Xdebug loaded: " . (extension_loaded('xdebug') ? 'Yes' : 'No') . "\n";
echo "Debug info available at: http://localhost:8080/debug/xdebug\n";