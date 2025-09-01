#!/bin/bash
echo "Starting ZealPHP WebSocket Server..."
touch Debug/log/app.log
touch Debug/log/ws.log
touch Debug/log/last_app_ps_id.txt
touch Debug/log/last_ws_ps_id.txt
nohup php app.php > app.log 2>&1 & echo $! > Debug/log/last_app_ps_id.txt
nohup php websocket_server.php  > ws.log 2>&1 & echo $! > Debug/log/last_ws_ps_id.txt
wait
tail -f Debug/log/ws.log