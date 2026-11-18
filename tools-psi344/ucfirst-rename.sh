#!/usr/bin/env bash
# PSI-344: rename controllers/models to pocketarc ucfirst convention.
# Files + class declarations only; $this->m_xxx properties stay lowercase
# (Loader names the property from the requested argument, not the class).
set -euo pipefail
cd "$(dirname "$0")/.."
PHP=C:/laragon/bin/php/php-8.3.36-Win32-vs16-x64/php.exe

rename_tree() { # $1 = dir
  local dir="$1"
  for f in "$dir"/*.php; do
    [ -e "$f" ] || continue
    base=$(basename "$f")
    case "$base" in index.html) continue ;; esac
    ufirst="$(echo "${base:0:1}" | tr '[:lower:]' '[:upper:]')${base:1}"
    if [ "$base" != "$ufirst" ]; then
      git mv "$f" "$dir/$ufirst" && echo "RENAMED $dir/$base -> $ufirst"
    fi
    # Fix class declaration to match the ucfirst name.
    cname="${ufirst%.php}"
    if grep -qE "class ${base%.php}\b" "$dir/$ufirst"; then
      sed -i "s/class ${base%.php}\b/class $cname/" "$dir/$ufirst"
      echo "RECLASSED $dir/$ufirst: ${base%.php} -> $cname"
    fi
  done
}

rename_tree application/controllers
rename_tree application/controllers/api
rename_tree application/models

# Subdir controllers (api/Sync.php) - already ucfirst, just verify class decls
for f in application/controllers/api/*.php; do
  [ -e "$f" ] || continue
  base=$(basename "$f" .php)
  if grep -qE "class [^ ]" "$f"; then :; fi
done

# Libraries: ucfirst-check every php file
for f in application/libraries/*.php; do
  [ -e "$f" ] || continue
  base=$(basename "$f")
  case "$base" in index.html) continue ;; esac
  ufirst="$(echo "${base:0:1}" | tr '[:lower:]' '[:upper:]')${base:1}"
  if [ "$base" != "$ufirst" ]; then
    git mv "$f" "application/libraries/$ufirst" && echo "RENAMED libraries/$base -> $ufirst"
  fi
  cname="${ufirst%.php}"
  if grep -qE "class ${base%.php}\b" "application/libraries/$ufirst"; then
    sed -i "s/class ${base%.php}\b/class $cname/" "application/libraries/$ufirst"
    echo "RECLASSED libraries/$ufirst"
  fi
done

echo ===VERIFY
grep -rn "^class [a-z]" application/controllers application/models application/libraries --include=*.php | head -10 || true
echo DONE
