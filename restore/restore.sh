#!/bin/bash

set -e

echo "=================================="
echo "   BookStack Restore Script"
echo "=================================="
echo ""

# Configuration - adjusted for running from restore/ directory
BACKUP_BASE_DIR="../backups"

# List available backups
echo "Available backups:"
if [ ! -d "$BACKUP_BASE_DIR" ] || [ -z "$(ls -A $BACKUP_BASE_DIR/*.tar.gz 2>/dev/null)" ]; then
    echo "✗ No backups found in $BACKUP_BASE_DIR"
    exit 1
fi

ls -1t "$BACKUP_BASE_DIR"/*.tar.gz 2>/dev/null | nl

echo ""
read -p "Enter backup number to restore (1 = latest): " BACKUP_NUM

# Get the selected backup
BACKUP_FILE=$(ls -1t "$BACKUP_BASE_DIR"/*.tar.gz | sed -n "${BACKUP_NUM}p")

if [ -z "$BACKUP_FILE" ]; then
    echo "✗ Invalid backup number"
    exit 1
fi

echo ""
echo "Restoring from: $BACKUP_FILE"
read -p "This will overwrite current data. Continue? (yes/no): " CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "Restore cancelled"
    exit 0
fi

echo ""
echo "[1/3] Extracting backup..."
TEMP_DIR=$(mktemp -d)
tar -xzf "$BACKUP_FILE" -C "$TEMP_DIR"
BACKUP_NAME=$(basename "$BACKUP_FILE" .tar.gz)

echo "[2/3] Restoring database..."
docker exec -i mariadb mysql -uroot -psecretrootpass bookstackapp < "$TEMP_DIR/$BACKUP_NAME/bookstack.sql"
echo "✓ Database restored"

echo ""
echo "[3/3] Restoring images..."
if [ -d "$TEMP_DIR/$BACKUP_NAME/images" ]; then
    rm -rf ../storage/images
    cp -r "$TEMP_DIR/$BACKUP_NAME/images" ../storage/images
    echo "✓ Images restored"
else
    echo "⚠ No images found in backup"
fi
echo ""
echo "[4/4] Updating db/bookstack.sql..."

docker exec mariadb sh -c "mysqldump -uroot -psecretrootpass bookstackapp" > ../db/bookstack.sql

if [ $? -ne 0 ]; then
    echo "[ERROR] Failed to export database"
else
    echo "[OK] db/bookstack.sql updated"
fi
# Cleanup
rm -rf "$TEMP_DIR"

echo ""
echo "=================================="
echo "Restore complete!"
echo "Restart containers if needed:"
echo "  docker compose restart"
echo "=================================="
