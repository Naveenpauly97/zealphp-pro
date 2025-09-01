<?php
/**
 * Xdebug trigger helper for ZealPHP
 * This ensures Xdebug sessions persist across requests
 */

// Force Xdebug to start debugging
if (extension_loaded('xdebug')) {
    // Set environment variables
    putenv('XDEBUG_SESSION=VSCODE');
    putenv('XDEBUG_TRIGGER=VSCODE');
    
    // Set session cookie
    if (!isset($_COOKIE['XDEBUG_SESSION'])) {
        setcookie('XDEBUG_SESSION', 'VSCODE', 0, '/');
        $_COOKIE['XDEBUG_SESSION'] = 'VSCODE';
    }
    
    // Set session in superglobals
    $_GET['XDEBUG_SESSION_START'] = 'VSCODE';
    $_REQUEST['XDEBUG_SESSION_START'] = 'VSCODE';
    
    // Force trigger debugging
    if (function_exists('xdebug_start_trace')) {
        @xdebug_start_trace();
    }
}

// Include this at the top of your main app.php
echo "Xdebug trigger activated for session: VSCODE\n";