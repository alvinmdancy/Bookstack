#!/bin/bash
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

echo "=================================="
echo "  BookStack Updater (Stable)"
echo "=================================="
echo ""

# =========================
# SYNC FROM GITHUB
# =========================
echo "[SYNC] Pulling latest from GitHub..."
git checkout main > /dev/null 2>&1
git pull origin main > /dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "ERROR: Git pull failed"
    exit 1
fi
echo "[OK] Repo updated"
echo ""

# =========================
# GET LATEST TAG
# =========================
LATEST_TAG=$(git describe --tags --abbrev=0 2>/dev/null | tr -d '[:space:]')
if [ -z "$LATEST_TAG" ]; then
    echo "ERROR: No git tag found"
    exit 1
fi
echo "Latest tag detected: $LATEST_TAG"
echo ""

# =========================
# WRITE VERSION FILE
# =========================
echo "$LATEST_TAG" > "$SCRIPT_DIR/VERSION"
echo "[OK] VERSION updated: $LATEST_TAG"
echo ""

# =========================
# DOCKER CHECK
# =========================
echo "[1/5] Checking Docker..."
if ! docker info > /dev/null 2>&1; then
    echo "ERROR: Docker not running"
    exit 1
fi
echo "[OK] Docker running"
echo ""

# =========================
# START CONTAINERS
# =========================
echo "[2/5] Starting containers..."
if ! docker compose up -d > /dev/null 2>&1; then
    echo "ERROR: Failed to start containers"
    exit 1
fi
echo "[OK] Containers running"
echo ""

# =========================
# WAIT FOR DB
# =========================
echo "[3/5] Waiting for MariaDB..."
DB_PASS="${MARIADB_ROOT_PASSWORD:-rootpass}"
COUNT=0
until docker exec mariadb mysqladmin ping -uroot -p"$DB_PASS" --silent > /dev/null 2>&1; do
    COUNT=$((COUNT + 1))
    if [ "$COUNT" -ge 20 ]; then
        echo "ERROR: DB not ready after 60 seconds"
        exit 1
    fi
    sleep 3
done
echo "[OK] Database ready"
echo ""

# =========================
# FIND LATEST BACKUP
# =========================
echo "[4/5] Finding latest backup..."
BACKUP_DIR="$SCRIPT_DIR/backup/backups"
LATEST_BACKUP=$(ls -1t "$BACKUP_DIR"/*.zip 2>/dev/null | head -1)

if [ -z "$LATEST_BACKUP" ]; then
    echo "[WARNING] No backups found, skipping restore"
else
    echo "Latest backup: $LATEST_BACKUP"
    echo "Restoring backup..."
    bash "$SCRIPT_DIR/restore/restore.sh" "$LATEST_BACKUP"
    if [ $? -ne 0 ]; then
        echo "ERROR: Restore failed"
        exit 1
    fi
    echo "[OK] Backup restored"
fi
echo ""

# =========================
# FINAL CHECK
# =========================
echo "[5/5] Final validation..."
sleep 5
APP_URL="${APP_URL:-http://localhost:8085}"
curl -s "$APP_URL" > /dev/null 2>&1 && echo "[OK] BookStack is reachable at $APP_URL" || echo "[WARNING] Could not reach $APP_URL — containers may still be starting"

echo ""
echo "=================================="
echo "UPDATE COMPLETE"
echo "Version: $LATEST_TAG"
echo "=================================="