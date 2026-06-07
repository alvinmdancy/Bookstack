#!/bin/bash
set -e

echo "=================================="
echo "   BookStack Restore Script"
echo "=================================="
echo ""

# =========================
# BASE PATH (LOCKED TO YOUR STRUCTURE)
# =========================
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BASE_DIR="$(dirname "$SCRIPT_DIR")"
BACKUP_BASE_DIR="$BASE_DIR/backup/backups"
DB_CONTAINER="mariadb"
DB_NAME="${MARIADB_DATABASE:-bookstackapp}"
DB_USER="root"
DB_PASS="${MARIADB_ROOT_PASSWORD:-rootpass}"
TEMP_DIR="$BASE_DIR/restore/temp_restore"

# =========================
# CHECK BACKUPS OR AUTO MODE
# =========================
AUTO_FILE="$1"

if [ -n "$AUTO_FILE" ]; then
    if [ -f "$AUTO_FILE" ]; then
        BACKUP_FILE="$AUTO_FILE"
        echo "Auto-selected backup:"
        echo "$BACKUP_FILE"
    else
        echo "ERROR: Provided backup file not found"
        echo "$AUTO_FILE"
        exit 1
    fi
else
    if [ ! -d "$BACKUP_BASE_DIR" ] || [ -z "$(ls "$BACKUP_BASE_DIR"/*.zip 2>/dev/null)" ]; then
        echo "No backups found in:"
        echo "$BACKUP_BASE_DIR"
        exit 1
    fi

    echo "Available backups:"
    echo ""

    COUNT=0
    declare -a BACKUP_LIST
    while IFS= read -r FILE; do
        COUNT=$((COUNT + 1))
        BACKUP_LIST[$COUNT]="$FILE"
        echo "$COUNT. $(basename "$FILE")"
    done < <(ls -1t "$BACKUP_BASE_DIR"/*.zip)

    if [ "$COUNT" -eq 0 ]; then
        echo "No backups found"
        exit 1
    fi

    echo ""
    read -rp "Select backup number to restore (1 = latest): " BACKUP_NUM

    if [ -z "${BACKUP_LIST[$BACKUP_NUM]}" ]; then
        echo "Invalid selection"
        exit 1
    fi

    BACKUP_FILE="${BACKUP_LIST[$BACKUP_NUM]}"
fi

# =========================
# CONFIRM
# =========================
echo ""
if [ -n "$AUTO_FILE" ]; then
    echo "Auto mode detected - skipping confirmation"
else
    echo "Selected: $BACKUP_FILE"
    read -rp "THIS WILL OVERWRITE CURRENT DATA. Type YES to continue: " CONFIRM
    if [ "$CONFIRM" != "YES" ]; then
        echo "Restore cancelled"
        exit 0
    fi
fi

# =========================
# EXTRACT
# =========================
echo ""
echo "[1/3] Extracting backup..."
[ -d "$TEMP_DIR" ] && rm -rf "$TEMP_DIR"
mkdir -p "$TEMP_DIR"

unzip -q "$BACKUP_FILE" -d "$TEMP_DIR"
echo "[OK] Extracted"
echo ""

# Find extracted folder
RESTORE_FOLDER=$(find "$TEMP_DIR" -mindepth 1 -maxdepth 1 -type d | head -1)

if [ -z "$RESTORE_FOLDER" ]; then
    echo "[ERROR] Could not find extracted folder"
    rm -rf "$TEMP_DIR"
    exit 1
fi

# =========================
# DATABASE
# =========================
echo "[2/3] Restoring database..."
docker exec -i "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$RESTORE_FOLDER/bookstack.sql"
if [ $? -ne 0 ]; then
    echo "[ERROR] Database restore failed"
    rm -rf "$TEMP_DIR"
    exit 1
fi
echo "[OK] Database restored"
echo ""

# =========================
# IMAGES
# =========================
echo "[3/3] Restoring images..."
if [ -d "$RESTORE_FOLDER/images" ]; then
    rm -rf "$BASE_DIR/storage/images"
    cp -r "$RESTORE_FOLDER/images" "$BASE_DIR/storage/images"
    echo "[OK] Images restored"
else
    echo "[WARNING] No images found in backup"
fi

# =========================
# CLEANUP
# =========================
rm -rf "$TEMP_DIR"

echo ""
echo "=================================="
echo "RESTORE COMPLETE"
echo "=================================="
echo ""
echo "Restart containers:"
cd "$BASE_DIR" && docker compose restart