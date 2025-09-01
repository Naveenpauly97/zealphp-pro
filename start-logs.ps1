# start-logs.ps1

Write-Host "🚀 Starting containers in detached mode..."
docker compose up -d --build

Write-Host "📂 Writing logs to log/*.log (press Ctrl+C to stop tailing)..."

Start-Process powershell -ArgumentList "docker logs -f zealphp-app    *> log\app.log" -NoNewWindow
Start-Process powershell -ArgumentList "docker logs -f zealphp-app-ws *> log\ws.log"  -NoNewWindow
Start-Process powershell -ArgumentList "docker logs -f zealphp-db     *> log\db.log"  -NoNewWindow
