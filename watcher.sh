#!/bin/bash

echo "[watcher] Starting PHP app with polling..."

start_server() {
    php app.php &
    SERVER_PID=$!
    echo "[watcher] Started php app.php with PID $SERVER_PID"
}

stop_server() {
    echo "[watcher] Stopping PID $SERVER_PID"
    kill $SERVER_PID 2>/dev/null
    wait $SERVER_PID 2>/dev/null
}

# Save initial checksum
last_hash=""

start_server

while true; do
    new_hash=$(find /app -type f -name "*.php" -exec stat -c "%Y" {} \; | md5sum)

    if [[ "$new_hash" != "$last_hash" ]]; then
        echo "[watcher] PHP code changed - restarting..."
        stop_server
        start_server
        last_hash=$new_hash
    fi

    sleep 1
done
