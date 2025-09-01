#!/bin/bash

echo "Testing debug persistence..."

# Start the debug server in background
./start-debug.sh &
SERVER_PID=$!

# Wait for server to start
sleep 3

echo "Making test requests to verify debug persistence..."

# Make multiple requests with Xdebug session
for i in {1..5}; do
    echo "Request $i:"
    curl -H "Cookie: XDEBUG_SESSION=VSCODE" \
         -H "X-Debug-Session: VSCODE" \
         http://localhost:8080/debug-page \
         -s -o /dev/null -w "Status: %{http_code}, Time: %{time_total}s\n"
    sleep 1
done

# Test API endpoint
echo "Testing API endpoint:"
curl -H "Cookie: XDEBUG_SESSION=VSCODE" \
     -H "X-Debug-Session: VSCODE" \
     http://localhost:8080/api/test \
     -s -o /dev/null -w "Status: %{http_code}, Time: %{time_total}s\n"

# Stop the server
kill $SERVER_PID

echo "Debug persistence test completed!"