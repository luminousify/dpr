<#
.SYNOPSIS
    Cloudflared tunnel health watchdog.
.DESCRIPTION
    Hits the local cloudflared metrics/ready endpoint every CHECK_INTERVAL seconds.
    If the tunnel reports fewer than MIN_CONNECTIONS ready HA connections
    (i.e. the process is alive but not actually connected to Cloudflare's edge),
    the cloudflared Windows service is restarted.

    This catches the most common failure mode: the cloudflared.exe process keeps
    running but its QUIC/HTTP2 connections to the edge silently die, producing
    HTTP 530/1033 on the public hostname. The built-in Windows service recovery
    only fires when the process *exits*, so it never triggers for this state.
.NOTES
    Logs to: C:\wamp\www\dpr\cloudflared-reliability\watchdog.log
    Designed to be run by a Windows Scheduled Task every 10 minutes.
#>

# ---------- Config ----------
$ServiceName      = "cloudflared"
$ReadyUrl         = "http://127.0.0.1:20241/ready"
$MinConnections   = 2          # A healthy tunnel holds 4; restart if fewer than 2
$CheckTimeoutSec  = 8          # Don't hang the whole task if it's wedged
$LogPath          = Join-Path $PSScriptRoot "watchdog.log"
# -----------------------------

function Write-Log {
    param([string]$Message)
    $line = "{0} {1}" -f (Get-Date -Format "yyyy-MM-dd HH:mm:ss"), $Message
    Add-Content -Path $LogPath -Value $line -ErrorAction SilentlyContinue
}

function Restart-Tunnel {
    Write-Log "RESTART: service '$ServiceName' is being restarted."
    try {
        Restart-Service -Name $ServiceName -Force -ErrorAction Stop
        Start-Sleep -Seconds 10
        $svc = Get-Service -Name $ServiceName -ErrorAction SilentlyContinue
        if ($svc -and $svc.Status -eq 'Running') {
            Write-Log "RESTART: service now Running. Tunnel will reconnect shortly."
        } else {
            Write-Log "RESTART: service status after restart = $($svc.Status). Will retry next run."
        }
    } catch {
        Write-Log "RESTART FAILED: $($_.Exception.Message)"
    }
}

# ---------- Main ----------
$svc = Get-Service -Name $ServiceName -ErrorAction SilentlyContinue
if (-not $svc) {
    Write-Log "SKIP: service '$ServiceName' not found."
    exit 0
}

# Service not running -> let Windows recovery handle it, but log + kick it.
if ($svc.Status -ne 'Running') {
    Write-Log "UNHEALTHY: service status is $($svc.Status). Attempting start."
    try { Start-Service -Name $ServiceName -ErrorAction Stop } catch { Write-Log "START FAILED: $($_.Exception.Message)" }
    exit 0
}

# Service is running -> probe the real edge connectivity via /ready.
$ready = $null
try {
    $resp = Invoke-WebRequest -Uri $ReadyUrl -UseBasicParsing -TimeoutSec $CheckTimeoutSec -ErrorAction Stop
    $ready = $resp.Content | ConvertFrom-Json
} catch {
    Write-Log "UNHEALTHY: /ready endpoint unreachable ($($_.Exception.Message)). Restarting."
    Restart-Tunnel
    exit 0
}

$conn = [int]$ready.readyConnections
if ($conn -lt $MinConnections) {
    Write-Log "UNHEALTHY: readyConnections=$conn (need >= $MinConnections). Restarting."
    Restart-Tunnel
} else {
    # Healthy. Only log once per hour (every 6th run at 10-min interval) to avoid log bloat.
    if ((Get-Date).Minute -lt 10) {
        Write-Log "OK: readyConnections=$conn, connectorId=$($ready.connectorId)"
    }
}
