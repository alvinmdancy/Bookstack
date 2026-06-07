#!/bin/bash
set -e

echo "=================================="
echo "   BookStack Backup Script"
echo "=================================="
echo ""

# =========================
# CONFIG (MATCHING YOUR SYSTEM)
# =========================
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BASE_DIR="$(dirname "$SCRIPT_DIR")"
BACKUP_BASE_DIR="$SCRIPT_DIR/backups"
MAX_BACKUPS=3
DB_CONTAINER="mariadb"
DB_NAME="${MARIADB_DATABASE:-bookstackapp}"
DB_USER="root"
DB_PASS="${MARIADB_ROOT_PASSWORD:-rootpass}"
IMAGES_PATH="$BASE_DIR/storage/images"

# =========================
# TIMESTAMP
# =========================
DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="$BACKUP_BASE_DIR/$DATE"

# =========================
# CREATE FOLDERS
# =========================
mkdir -p "$BACKUP_BASE_DIR"
mkdir -p "$BACKUP_DIR"

# =========================
# CHECK CONTAINER
# =========================
echo "[1/5] Checking Docker container..."
if ! docker ps | grep -q "$DB_CONTAINER"; then
    echo "[ERROR] MariaDB container not found: $DB_CONTAINER"
    echo "Run: docker ps"
    exit 1
fi
echo "[OK] Container found"
echo ""

# =========================
# DATABASE
# =========================
echo "[2/5] Dumping database ($DB_NAME)..."
docker exec -i "$DB_CONTAINER" mysqldump --protocol=TCP -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_DIR/bookstack.sql"
if [ $? -ne 0 ]; then
    echo "[ERROR] Database backup failed"
    echo ""
    echo "CHECK:"
    echo "- DB name: $DB_NAME"
    echo "- Container: $DB_CONTAINER"
    rm -rf "$BACKUP_DIR"
    exit 1
fi
echo "[OK] Database backup complete"
echo ""

# =========================
# IMAGES
# =========================
echo "[3/5] Backing up images..."
if [ -d "$IMAGES_PATH" ]; then
    cp -r "$IMAGES_PATH" "$BACKUP_DIR/images"
    echo "[OK] Images backed up"
else
    echo "[WARNING] No images folder found at $IMAGES_PATH"
fi
echo ""

# =========================
# COMPRESS
# =========================
echo "[4/5] Compressing backup..."
cd "$BACKUP_BASE_DIR"
zip -qr "$DATE.zip" "$DATE/"
rm -rf "$BACKUP_DIR"
echo "[OK] Backup created: $DATE.zip"
echo ""

# =========================
# CLEANUP OLD BACKUPS
# =========================
echo "[5/5] Cleaning old backups..."
COUNT=0
while IFS= read -r FILE; do
    COUNT=$((COUNT + 1))
    if [ "$COUNT" -gt "$MAX_BACKUPS" ]; then
        rm -f "$FILE"
        echo "Deleted old backup: $(basename "$FILE")"
    fi
done < <(ls -1t "$BACKUP_BASE_DIR"/*.zip 2>/dev/null)

echo ""
echo "=================================="
echo "BACKUP COMPLETE"
echo "Location: $BACKUP_BASE_DIR"
echo "Database: $DB_NAME"
echo "Total backups: $(ls -1 "$BACKUP_BASE_DIR"/*.zip 2>/dev/null | wc -l)"
echo "=================================="