#!/usr/bin/env bash
# PSI-344: snapshot \\192.168.1.201\www\dpr -> C:/laragon/www/dpr-prod201
# Excludes = psi289-tooling exclude.txt (canonical, governs snapshots AND deploys).
set -uo pipefail
DEST=C:/laragon/www/dpr-prod201
cd "$DEST" || exit 1

# Empty the worktree except .git so the branch holds ONLY the 201 tree.
find . -mindepth 1 -maxdepth 1 -not -name .git -exec rm -rf {} +

MSYS_NO_PATHCONV=1 robocopy '\\192.168.1.201\www\dpr' 'C:\laragon\www\dpr-prod201' /E /R:1 /W:1 /MT:8 /NFL /NDL /NP \
  /XF sshd-go* .env* '*.key' 'host_*' '.backpass*' '*.bak*' '*.log' '*.sql' '*.zip' '*.rar' '*.7z' '*.mdb' CLAUDE.md AGENTS.md CONTEXT.md \
  /XD node_modules .git '*_dev' '*- Copy*' '*bak*' backups .claude .cursor .factory .kilo under user_guide
rc=$?
echo "ROBOCOPY_EXIT=$rc (0-7 = success variants)"
[ "$rc" -lt 8 ] || exit "$rc"
exit 0
