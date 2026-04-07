# Backup Scripts

These scripts create compressed backups of your BookStack database and images.

## Features

- Backs up MariaDB database
- Backs up uploaded images
- Compresses into .tar.gz format
- Automatically keeps only the 3 most recent backups
- Cross-platform (Linux and Windows)

## Usage

### Linux/Mac
```bash
cd backup
chmod +x backup.sh
./backup.sh
```

### Windows
```cmd
cd backup
backup.bat
```

## Backup Location

Backups are saved to: `../backups/`

Format: `YYYYMMDD_HHMMSS.tar.gz`

Example: `20250405_143022.tar.gz`

## Automatic Cleanup

The script automatically deletes backups older than the 3 most recent ones.

## Scheduled Backups

### Linux (Cron)
```bash
# Edit crontab
crontab -e

# Add daily backup at 2 AM
0 2 * * * cd /path/to/bookstack-git/backup && ./backup.sh >> ../backups/backup.log 2>&1
```

### Windows (Task Scheduler)

1. Open Task Scheduler
2. Create Basic Task
3. Name: "BookStack Backup"
4. Trigger: Daily at 2:00 AM
5. Action: `C:\path\to\bookstack-git\backup\backup.bat`

## What Gets Backed Up

- Database: Complete MySQL dump
- Images: All files in `storage/images/`

## Troubleshooting

**Error: Docker not running**
- Ensure Docker Desktop is running

**Error: Database backup failed**
- Verify MariaDB container is running: `docker ps`
- Check database password in `docker-compose.yml`

**Warning: No images found**
- This is normal if no images have been uploaded yet
