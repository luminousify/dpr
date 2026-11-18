CLOUDFLARED RELIABILITY FIX for dpr.prastu.dev
===============================================

WHY THIS EXISTS
---------------
dpr.prastu.dev was going down ~daily. Root cause (confirmed from the
tunnel's own metrics before the fix):

  cloudflared_tunnel_ha_connections          = 4   (looks fine)
  cloudflared_tunnel_tunnel_register_fail
      {error="server_error"}                 = 9   <-- 9 silent reg failures
  rpc_client_failures
      {method="unregister_connection"}       = 316 <-- connections churning
  Public URL                                 = HTTP 530 (origin unreachable)

The cloudflared.exe process stayed "RUNNING" the whole time (since Jun 23,
nearly 2 months without a restart), but its QUIC/UDP connections to the
Cloudflare edge silently died and never recovered. Windows' built-in
service recovery only triggers when the process fully EXITS -- useless for
this alive-but-stuck state. So the tunnel slowly degraded and produced
HTTP 530s every day until someone manually restarted it.


WHAT THE FIX DOES
-----------------
1. Switches the tunnel transport: QUIC/UDP  ->  HTTP2/TCP.
   TCP recovers cleanly from network/NAT/ISP drops. QUIC wedges.

2. Sets proper service crash-recovery: restart after 20s, 60s, 120s,
   with the counter resetting daily. Auto-start (delayed) on reboot.

3. Adds a watchdog scheduled task that runs every 10 minutes:
     - hits http://127.0.0.1:20241/ready
     - if readyConnections < 2, restarts the cloudflared service
     - logs to watchdog.log
   This is what actually kills the daily downtime: it catches the
   stuck-but-alive state that Windows recovery misses.


FILES
-----
install-reliability-fixes.bat   <-- RUN THIS ONCE (as Administrator)
uninstall-reliability-fixes.bat <-- reverts everything if ever needed
cloudflared-watchdog.ps1        <-- the health-check script (called by task)
README.txt                      <-- this file


HOW TO INSTALL
--------------
1. Right-click  install-reliability-fixes.bat
2. Choose  "Run as administrator"
3. Watch the output. The service restarts in ~30s.
4. After ~1 minute, the site is live again at https://dpr.prastu.dev

That's it. No reboot needed.


HOW TO VERIFY IT'S WORKING
--------------------------
After install, in any terminal:

  curl -s http://127.0.0.1:20241/ready
    -> should print {"status":200,"readyConnections":4,...}

  curl -sI https://dpr.prastu.dev/
    -> should print HTTP/1.1 200 OK

  schtasks /query /tn "Cloudflared-Tunnel-Watchdog"
    -> should show the task, Last Run Time recent, Last Result 0x0

  sc qc cloudflared | findstr http2
    -> BINARY_PATH_NAME should contain "--protocol http2"

The watchdog logs to:
  C:\wamp\www\dpr\cloudflared-reliability\watchdog.log


TROUBLESHOOTING
---------------
* If the install script says "[ERROR] This script must be run as
  Administrator" -> you didn't right-click -> Run as administrator.

* If the site is still 530 after install, the env var TUNNEL_TRANSPORT_PROTOCOL
  may have been picked up before the service started. Reboot once, or run:
     sc stop cloudflared && sc start cloudflared

* If you ever want to go back to the original QUIC setup, run
  uninstall-reliability-fixes.bat (also as Administrator).
