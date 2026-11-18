@echo off
:: =====================================================================
::  Cloudflared reliability installer for dpr.prastu.dev
::
::  Fixes the recurring daily downtime by:
::   1. Switching the tunnel from QUIC/UDP -> HTTP2/TCP so connections
::      recover cleanly from network drops (QUIC silently wedges on
::      flaky/residential links, which is what produced the HTTP 530s).
::   2. Setting proper service auto-restart on crash (every 20-120s).
::   3. Installing a 10-minute watchdog that restarts the service if it
::      is alive-but-stuck (Windows' built-in recovery only fires when
::      the process fully exits, so it never catches this failure mode).
::
::  MUST be run as Administrator.
::  Right-click this file -> "Run as administrator".
:: =====================================================================
setlocal enabledelayedexpansion

:: --- bail out if not admin ---
net session >nul 2>&1
if errorlevel 1 (
  echo.
  echo [ERROR] This script must be run as Administrator.
  echo Right-click this .bat and choose "Run as administrator".
  echo.
  pause
  exit /b 1
)

set "CFAUTH=C:\Program Files (x86)\cloudflared\cloudflared.exe"
echo.
echo === Cloudflared reliability installer ===
echo.

:: --- read the current service binpath to recover the existing token ---
echo [1/5] Reading current cloudflared service config...
set "SVCBIN="
for /f "tokens=1,*" %%A in ('sc qc cloudflared ^| findstr /i "BINARY_PATH_NAME"') do (
  set "LINE=%%B"
  set "SVCBIN=!LINE:BINARY_PATH_NAME =!"
)
if "!SVCBIN!"=="" (
  for /f "tokens=1,*" %%A in ('sc qc cloudflared ^| findstr /i "BINARY_PATH_NAME"') do set "SVCBIN=%%B"
)
echo     Current binpath: !SVCBIN!

:: Extract the token (the argument immediately after --token)
set "TOKEN="
set "PREV="
for %%A in (!SVCBIN!) do (
  if /i "!PREV!"=="--token" set "TOKEN=%%A"
  set "PREV=%%A"
)

if "!TOKEN!"=="" (
  echo [ERROR] Could not find --token in the service command line.
  echo Aborting. The service was NOT changed.
  pause
  exit /b 1
)
echo     Token found: !TOKEN:~0,20%!...

:: --- force HTTP2 transport machine-wide as a belt-and-suspenders ---
echo [2/5] Setting TUNNEL_TRANSPORT_PROTOCOL=http2 (machine-wide)...
setx TUNNEL_TRANSPORT_PROTOCOL http2 /M >nul 2>&1
if errorlevel 1 (
  echo     [WARN] Could not set machine env var; relying on --protocol flag only.
) else (
  echo     OK.
)

:: --- stop + delete the old service so we can re-register cleanly ---
echo [3/5] Stopping and removing old service registration...
sc stop cloudflared >nul 2>&1
timeout /t 4 /nobreak >nul
sc delete cloudflared >nul 2>&1
timeout /t 2 /nobreak >nul

:: --- re-register the service with --protocol http2 ---
echo [4/5] Re-registering service with --protocol http2 and crash-recovery...
sc create cloudflared ^
  binpath= "\"!CFAUTH!\" tunnel run --protocol http2 --token !TOKEN!" ^
  start= auto ^
  DisplayName= "Cloudflared agent" >nul 2>&1

if errorlevel 1 (
  echo [ERROR] sc create failed. Service may need manual reinstall.
  pause
  exit /b 1
)

:: delayed autostart so it survives reboot even on slow networks
sc config cloudflared start= auto-delayed >nul 2>&1
:: restart on failure: 20s, 60s, 120s; reset counter after 1 day
sc failure cloudflared reset= 86400 actions= restart/20000/restart/60000/restart/120000 >nul 2>&1
sc start cloudflared >nul 2>&1
echo     OK.

:: --- install the watchdog scheduled task ---
echo [5/5] Installing watchdog scheduled task (every 10 minutes)...
powershell -NoProfile -ExecutionPolicy Bypass -Command ^
  "$a = New-ScheduledTaskAction -Execute 'powershell.exe' -Argument '-NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File \"C:\wamp\www\dpr\cloudflared-reliability\cloudflared-watchdog.ps1\"';" ^
  "$t = New-ScheduledTaskTrigger -Once -At ((Get-Date).AddMinutes(1)) -RepetitionInterval (New-TimeSpan -Minutes 10);" ^
  "$p = New-ScheduledTaskPrincipal -UserId 'SYSTEM' -RunLevel Highest -LogonType ServiceAccount;" ^
  "$s = New-ScheduledTaskSettingsSet -StartWhenAvailable -DontStopOnIdleEnd -ExecutionTimeLimit (New-TimeSpan -Minutes 5);" ^
  "Register-ScheduledTask -TaskName 'Cloudflared-Tunnel-Watchdog' -Action $a -Trigger $t -Principal $p -Settings $s -Force | Out-Null;" ^
  "if (Get-ScheduledTask -TaskName 'Cloudflared-Tunnel-Watchdog' -ErrorAction SilentlyContinue) { '    OK.' } else { '    [ERROR] task not registered' }"

echo.
echo === Done ===
echo.
echo Changes applied:
echo   * Tunnel now uses HTTP2/TCP to Cloudflare (was QUIC/UDP).
echo   * Service auto-restarts on crash (20s/60s/120s) and on reboot.
echo   * Watchdog checks tunnel health every 10 min and restarts if stuck.
echo.
echo The tunnel reconnects within ~30 seconds. Verify with:
echo   curl -s http://127.0.0.1:20241/ready
echo   https://dpr.prastu.dev/
echo.
pause
endlocal
goto :eof
