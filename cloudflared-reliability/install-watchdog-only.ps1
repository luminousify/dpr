# Installs the Cloudflared-Tunnel-Watchdog scheduled task.
# Run this as Administrator. (Re-runnable; -Force overwrites cleanly.)
$ErrorActionPreference = 'Stop'

$action = New-ScheduledTaskAction `
    -Execute 'powershell.exe' `
    -Argument '-NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File "C:\wamp\www\dpr\cloudflared-reliability\cloudflared-watchdog.ps1"'

$trigger = New-ScheduledTaskTrigger -Once -At ((Get-Date).AddMinutes(1)) `
    -RepetitionInterval (New-TimeSpan -Minutes 10)

$principal = New-ScheduledTaskPrincipal `
    -UserId 'SYSTEM' -RunLevel Highest -LogonType ServiceAccount

$settings = New-ScheduledTaskSettingsSet `
    -StartWhenAvailable -DontStopOnIdleEnd -ExecutionTimeLimit (New-TimeSpan -Minutes 5)

Register-ScheduledTask `
    -TaskName 'Cloudflared-Tunnel-Watchdog' `
    -Action $action -Trigger $trigger -Principal $principal -Settings $settings `
    -Force | Out-Null

if (Get-ScheduledTask -TaskName 'Cloudflared-Tunnel-Watchdog' -ErrorAction SilentlyContinue) {
    Write-Output 'OK: watchdog task registered (runs every 10 min as SYSTEM).'
} else {
    Write-Output 'ERROR: task registration failed.'
    exit 1
}
