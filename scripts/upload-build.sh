#!/bin/bash
# Upload compiled build/ assets to production server after npm run build

FTP_HOST="ftp.csfa.la"
FTP_PORT="21"
FTP_USER="csfangko"
FTP_PASS="yUs2Ly6Ld9sT"
REMOTE_BUILD="/home/csfangko/public_html/build"
PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/bfol"
BUILD_DIR="$PROJECT_DIR/public/build"

# Read hook JSON from stdin
INPUT=$(cat)

# Check if this was triggered by npm run build
COMMAND=$(printf '%s' "$INPUT" | python3 -c "
import sys, json
try:
    data = json.load(sys.stdin)
    cmd = (data.get('tool_input') or {}).get('command', '')
    print(cmd)
except:
    pass
" 2>/dev/null || echo "")

if ! echo "$COMMAND" | grep -q "npm run build"; then
    exit 0
fi

if [ ! -d "$BUILD_DIR" ]; then
    echo "[deploy-build] ERROR: $BUILD_DIR not found" >&2
    exit 1
fi

echo "[deploy-build] Uploading build/ -> $REMOTE_BUILD ..."

find "$BUILD_DIR" -type f | while read -r file; do
    REL="${file#$BUILD_DIR/}"
    REMOTE_PATH="$REMOTE_BUILD/$REL"

    curl -s \
        --ftp-create-dirs \
        --connect-timeout 10 \
        --max-time 60 \
        -u "$FTP_USER:$FTP_PASS" \
        -T "$file" \
        "ftp://$FTP_HOST:$FTP_PORT/$REMOTE_PATH"

    STATUS=$?
    if [ $STATUS -eq 0 ]; then
        echo "[deploy-build] OK: build/$REL"
    else
        echo "[deploy-build] FAIL ($STATUS): build/$REL" >&2
    fi
done

echo "[deploy-build] Done!"
