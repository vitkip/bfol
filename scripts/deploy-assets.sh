#!/bin/bash
set -e

FTP_HOST="ftp.csfa.la"
FTP_PORT="21"
FTP_USER="csfangko"
FTP_PASS="yUs2Ly6Ld9sT"
REMOTE_BUILD="/home/csfangko/public_html/build"
PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/bfol"
BUILD_DIR="$PROJECT_DIR/public/build"

echo ">>> npm run build ..."
cd "$PROJECT_DIR"
npm run build

echo ""
echo ">>> Upload build/ -> public_html/build/ ..."

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

    echo "  OK: $REL"
done

echo ""
echo ">>> Deploy assets complete!"
