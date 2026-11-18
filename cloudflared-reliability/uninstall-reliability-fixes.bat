@echo off
:: =====================================================================
::  Reverts the cloudflared reliability changes:
::   - Re-registers cloudflared WITHOUT --protocol http2 (back to QUIC)
::   - Removes the watchdog scheduled task
::  MUST be run as Administrator.
:: =====================================================================
setlocal enabledelayedexpansion

net session >nul 2>&1
if errorlevel 1 (
  echo [ERROR] Run as Administrator.
  pause
  exit /b 1
)

set "CFAUTH=C:\Program Files (x86)\cloudflared\cloudflared.exe"

echo [1/3] Removing watchdog scheduled task...
schtasks /delete /tn "Cloudflared-Tunnel-Watchdog" /f >nul 2>&1 && echo     OK. || echo     (task not found)

echo [2/3] Re-reading token and reverting to default protocol (QUIC)...
set "SVCBIN="
for /f "tokens=1,*" %%A in ('sc qc cloudflared ^| findstr /i "BINARY_PATH_NAME"') do set "SVCBIN=%%B"
set "TOKEN=" & set "PREV="
for %%A in (!SVCBIN!) do (
  if /i "!PREV!"=="--token" set "TOKEN=%%A"
  set "PREV=%%A"
)

sc stop cloudflared >nul 2>&1
timeout /t 3 /nobreak >nul
sc delete cloudflared >nul 2>&1
timeout /t 2 /nobreak >nul

if not "!TOKEN!"=="" (
  sc create cloudflared binpath= "\"!CFAUTH!\" tunnel run --token !TOKEN!" start= auto DisplayName= "Cloudflared agent" >nul 2>&1
  sc failure cloudflared reset= 86400 actions= restart/20000/restart/60000/restart/120000 >nul 2>&1
  sc start cloudflared >nul 2>&1
  echo     OK.
) else (
  echo [WARN] Could not read token. Reinstall cloudflared manually with:
  echo   "!CFAUTH!" service install --token ^<TOKEN^>
)

echo [3/3] Clearing TUNNEL_TRANSPORT_PROTOCOL env var...
setx TUNNEL_TRANSPORT_PROTOCOL "" /M >nul 2>&1

echo.
echo === Reverted ===
echo Tunnel is back on QUIC/UDP and the watchdog is removed.
echo.
pause
endlocal
goto :eof
