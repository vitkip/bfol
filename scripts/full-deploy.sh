#!/bin/bash
# Full automated deploy — triggered by PostToolUse Bash hook
# Handles: git push → build + upload all changed files
#          npm run build → upload build/ assets

FTP_HOST="ftp.csfa.la"
FTP_PORT="21"
FTP_USER="csfangko"
FTP_PASS="yUs2Ly6Ld9sT"
FTP_REMOTE_BASE="/home/csfangko/bfol"
FTP_REMOTE_BUILD="/home/csfangko/public_html/build"
PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/bfol"
BUILD_DIR="$PROJECT_DIR/public/build"
LOG_FILE="$PROJECT_DIR/storage/logs/deploy.log"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

ftp_upload() {
    local local_file="$1"
    local remote_path="$2"
    local attempt=1
    local max_attempts=3
    while [ $attempt -le $max_attempts ]; do
        curl -s \
            --ftp-create-dirs \
            --connect-timeout 15 \
            --max-time 90 \
            -u "$FTP_USER:$FTP_PASS" \
            -T "$local_file" \
            "ftp://$FTP_HOST:$FTP_PORT/$remote_path"
        STATUS=$?
        [ $STATUS -eq 0 ] && return 0
        log "  [retry $attempt/$max_attempts] $remote_path (err $STATUS)"
        attempt=$((attempt + 1))
        sleep 2
    done
    return $STATUS
}

upload_build_folder() {
    log ">>> Uploading build/ assets ..."
    local count=0
    find "$BUILD_DIR" -type f | while read -r file; do
        REL="${file#$BUILD_DIR/}"
        ftp_upload "$file" "$FTP_REMOTE_BUILD/$REL"
        count=$((count + 1))
        echo "  [build] OK: $REL"
    done
    log ">>> build/ upload complete"
}

upload_changed_files() {
    log ">>> Uploading changed project files ..."
    local files="$1"
    local any=0

    while IFS= read -r rel; do
        [ -z "$rel" ] && continue

        # Skip files that should not be deployed
        if echo "$rel" | grep -qE "^(vendor/|node_modules/|\.env|storage/framework/|bootstrap/cache/|scripts/|public/build/)"; then
            continue
        fi

        local full_path="$PROJECT_DIR/$rel"
        [ ! -f "$full_path" ] && continue   # skip deleted files

        ftp_upload "$full_path" "$FTP_REMOTE_BASE/$rel"
        STATUS=$?
        if [ $STATUS -eq 0 ]; then
            log "  [file] OK: $rel"
        else
            log "  [file] FAIL ($STATUS): $rel"
        fi
        any=1
    done <<< "$files"

    [ "$any" -eq 0 ] && log "  (no project files to upload)"
}

# ─── Read hook JSON from stdin ───────────────────────────────────────────────
INPUT=$(cat)

COMMAND=$(printf '%s' "$INPUT" | python3 -c "
import sys, json
try:
    data = json.load(sys.stdin)
    print((data.get('tool_input') or {}).get('command', ''))
except:
    pass
" 2>/dev/null || echo "")

# ─── Case 1: npm run build ───────────────────────────────────────────────────
if echo "$COMMAND" | grep -q "npm run build"; then
    [ ! -d "$BUILD_DIR" ] && exit 0
    log "=== Deploy triggered: npm run build ==="
    upload_build_folder
    log "=== Done ==="
    exit 0
fi

# ─── Case 2: git push ────────────────────────────────────────────────────────
if echo "$COMMAND" | grep -qE "git push"; then
    log "=== Deploy triggered: git push ==="

    # Get files changed in latest commit
    CHANGED=$(git -C "$PROJECT_DIR" diff HEAD~1 HEAD --name-only 2>/dev/null)

    if [ -z "$CHANGED" ]; then
        log "  No changed files found in last commit"
        log "=== Done ==="
        exit 0
    fi

    log "Changed files:"
    echo "$CHANGED" | while read -r f; do log "  - $f"; done

    # Check if frontend source files changed → need rebuild
    NEED_BUILD=$(echo "$CHANGED" | grep -E "^(resources/js/|resources/css/|resources/react/|frontend/)" | head -1)

    if [ -n "$NEED_BUILD" ]; then
        log ">>> Frontend changed — running npm run build ..."
        npm run build --prefix "$PROJECT_DIR" >> "$LOG_FILE" 2>&1
        upload_build_folder
    else
        # Upload existing build/ if build assets changed directly
        BUILD_CHANGED=$(echo "$CHANGED" | grep "^public/build/" | head -1)
        [ -n "$BUILD_CHANGED" ] && upload_build_folder
    fi

    # Upload all other changed files
    upload_changed_files "$CHANGED"

    log "=== Deploy complete ==="
    exit 0
fi

# Not a relevant command — exit silently
exit 0