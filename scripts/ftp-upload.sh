#!/bin/bash
# Auto-upload edited file to production server via FTP

FTP_HOST="ftp.csfa.la"
FTP_PORT="21"
FTP_USER="csfangko"
FTP_PASS="yUs2Ly6Ld9sT"
FTP_REMOTE_BASE="/home/csfangko/bfol"
LOCAL_BASE="/Applications/XAMPP/xamppfiles/htdocs/bfol"

# Read tool input JSON from stdin (Claude Code PostToolUse hook)
INPUT=$(cat)

FILE_PATH=$(echo "$INPUT" | python3 -c "
import sys, json
try:
    data = json.load(sys.stdin)
    fp = (data.get('tool_input') or {}).get('file_path', '')
    print(fp)
except:
    pass
" 2>/dev/null || echo "")

if [ -z "$FILE_PATH" ] || [ ! -f "$FILE_PATH" ]; then
    exit 0
fi

# Only handle files inside project
REL_PATH="${FILE_PATH#$LOCAL_BASE/}"
if [ "$REL_PATH" = "$FILE_PATH" ]; then
    exit 0
fi

# Skip files that should never be deployed
if echo "$REL_PATH" | grep -qE "^(vendor/|node_modules/|\.env|storage/framework/|bootstrap/cache/|scripts/)"; then
    exit 0
fi

REMOTE_PATH="$FTP_REMOTE_BASE/$REL_PATH"

curl -s \
    --ftp-create-dirs \
    --connect-timeout 10 \
    --max-time 30 \
    -u "$FTP_USER:$FTP_PASS" \
    -T "$FILE_PATH" \
    "ftp://$FTP_HOST:$FTP_PORT/$REMOTE_PATH"

STATUS=$?
if [ $STATUS -eq 0 ]; then
    echo "[deploy] OK: $REL_PATH"
else
    echo "[deploy] FAIL ($STATUS): $REL_PATH" >&2
fi
