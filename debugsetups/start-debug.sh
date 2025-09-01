#!/bin/bash

echo "Starting ZealPHP in debug mode..."

# Check if Xdebug is loaded
if php -m | grep -q xdebug; then
    echo "✓ Xdebug is loaded"
else
    echo "✗ Xdebug is not loaded. Please install and configure Xdebug first."
    exit 1
fi

# Set Xdebug environment variables
export XDEBUG_MODE=debug
export XDEBUG_SESSION=VSCODE
export XDEBUG_TRIGGER=VSCODE
export XDEBUG_SESSION_START=VSCODE
export XDEBUG_CONFIG="idekey=VSCODE"

# Start the debug server
echo "Starting server on http://localhost:8080"
echo "Xdebug listening on port 9003"
echo "Press Ctrl+C to stop"

php debug-server.php