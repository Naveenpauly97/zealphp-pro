#!/bin/bash

echo "[watcher] Starting PHP websocket_server with polling..."

start_server() {
    php websocket_server.php &
    SERVER_PID=$!
    echo "[watcher] Started php websocket_server.php with PID $SERVER_PID"
}

stop_server() {
    echo "[watcher] Stopping PID $SERVER_PID"
    kill $SERVER_PID 2>/dev/null
    wait $SERVER_PID 2>/dev/null
}

last_hash=""

start_server

while true; do
    # Detect code changes
    new_hash=$(find /app/src/WebSocket -type f -name "*.php" -exec stat -c "%Y" {} \; | md5sum)
    if [[ "$new_hash" != "$last_hash" ]]; then
        echo "[watcher] PHP code changed - restarting..."
        stop_server
        start_server
        last_hash=$new_hash
    fi

    # Detect if WS port is not listening, restart if needed
    if ! lsof -i :9502 >/dev/null 2>&1; then
        echo "[watcher] WebSocket server not running - restarting..."
        stop_server
        start_server
    fi

    sleep 1
done
