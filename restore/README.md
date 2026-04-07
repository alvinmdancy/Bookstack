# Restore Scripts

These scripts restore BookStack from compressed backups.

## Features

- Lists available backups
- Interactive backup selection
- Restores database and images
- Confirms before overwriting data
- Cross-platform (Linux and Windows)

## Usage

### Linux/Mac
```bash
cd restore
chmod +x restore.sh
./restore.sh
```

### Windows
```cmd
cd restore
restore.bat
```

## Restore Process

1. Script lists all available backups (newest first)
2. Enter the backup number (1 = most recent)
3. Confirm the restore operation
4. Database and images are restored automatically

## What Gets Restored

- Database: Complete MySQL data
- Images: All uploaded images

## After Restore

Restart BookStack containers to apply changes:
```bash
docker compose restart
```

Or from the project root:
```bash
cd ..
docker compose restart
```

## Troubleshooting

**Error: No backups found**
- Run the backup script first: `cd ../backup && ./backup.sh`

**Error: Docker not running**
- Ensure Docker Desktop is running

**Error: Invalid backup number**
- Enter a number from the list (1 for newest)

## Safety

The restore script:
- Asks for confirmation before overwriting
- Can be safely cancelled by entering "no"
- Creates a temporary extraction directory
- Cleans up after completion
