# monitor-queue.ps1
$process = Get-Process -Name php -ErrorAction SilentlyContinue | Where-Object { $_.Path -like "*queue:work*" }

if (-not $process) {
    Write-Output "$(Get-Date): Queue not running. Restarting..." >> "C:\wamp64\www\Projects\pmgs-app\storage\logs\queue-monitor.log"
    Start-Process "php" -ArgumentList "artisan queue:work --tries=3 --timeout=90" -WorkingDirectory "C:\wamp64\www\Projects\pmgs-app" -WindowStyle Hidden
} else {
    Write-Output "$(Get-Date): Queue running OK." >> "C:\wamp64\www\Projects\pmgs-app\storage\logs\queue-monitor.log"
}
