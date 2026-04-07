#!/bin/bash

set -e

echo "=================================="
echo "   BookStack Backup Script"
echo "=================================="
echo ""

# Configuration - adjusted for running from backup/ directory
BACKUP_BASE_DIR="../backups"
DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="$BACKUP_BASE_DIR/$DATE"
MAX_BACKUPS=3

# Create backup directory
mkdir -p "$BACKUP_DIR"

echo "[1/4] Dumping database..."
docker exec mariadb mysqldump -uroot -psecretrootpass bookstackapp > "$BACKUP_DIR/bookstack.sql"

if [ $? -eq 0 ]; then
    echo "✓ Database backup complete"
else
    echo "✗ Database backup failed"
    exit 1
fi

echo ""
echo "[2/4] Backing up images..."
if [ -d "../storage/images" ]; then
    cp -r ../storage/images "$BACKUP_DIR/"
    echo "✓ Images backed up"
else
    echo "⚠ No images directory found, skipping"
fi

echo ""
echo "[3/4] Compressing backup..."
tar -czf "$BACKUP_DIR.tar.gz" -C "$BACKUP_BASE_DIR" "$DATE"
rm -rf "$BACKUP_DIR"
echo "✓ Backup compressed: $BACKUP_DIR.tar.gz"

echo ""
echo "[4/4] Cleaning old backups (keeping latest $MAX_BACKUPS)..."

# Count backups
BACKUP_COUNT=$(ls -1 "$BACKUP_BASE_DIR"/*.tar.gz 2>/dev/null | wc -l)

if [ "$BACKUP_COUNT" -gt "$MAX_BACKUPS" ]; then
    # Delete oldest backups, keep only MAX_BACKUPS
    ls -1t "$BACKUP_BASE_DIR"/*.tar.gz | tail -n +$((MAX_BACKUPS + 1)) | xargs rm -f
    DELETED=$((BACKUP_COUNT - MAX_BACKUPS))
    echo "✓ Removed $DELETED old backup(s)"
else
    echo "✓ No cleanup needed (total backups: $BACKUP_COUNT)"
fi

echo ""
echo "=================================="
echo "Backup complete!"
echo "Location: $BACKUP_DIR.tar.gz"
echo "Total backups: $(ls -1 "$BACKUP_BASE_DIR"/*.tar.gz 2>/dev/null | wc -l)"
echo "=================================="
