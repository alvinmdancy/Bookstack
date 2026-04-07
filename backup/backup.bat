@echo off
setlocal enabledelayedexpansion

echo ==================================
echo    BookStack Backup Script
echo ==================================
echo.

:: Configuration - adjusted for running from backup\ directory
set BACKUP_BASE_DIR=..\backups
set MAX_BACKUPS=3

:: Generate timestamp
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /format:list') do set datetime=%%I
set DATE=%datetime:~0,8%_%datetime:~8,6%

set BACKUP_DIR=%BACKUP_BASE_DIR%\%DATE%

:: Create backup directory
if not exist "%BACKUP_BASE_DIR%" mkdir "%BACKUP_BASE_DIR%"
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

echo [1/4] Dumping database...
docker exec mariadb mysqldump -uroot -psecretrootpass bookstackapp > "%BACKUP_DIR%\bookstack.sql"

if %errorlevel% equ 0 (
    echo [OK] Database backup complete
) else (
    echo [ERROR] Database backup failed
    pause
    exit /b 1
)

echo.
echo [2/4] Backing up images...
if exist "..\storage\images\" (
    xcopy /E /I /Y /Q "..\storage\images" "%BACKUP_DIR%\images" >nul
    echo [OK] Images backed up
) else (
    echo [WARNING] No images directory found, skipping
)

echo.
echo [3/4] Compressing backup...
tar -czf "%BACKUP_BASE_DIR%\%DATE%.tar.gz" -C "%BACKUP_BASE_DIR%" "%DATE%"
rmdir /S /Q "%BACKUP_DIR%"
echo [OK] Backup compressed: %BACKUP_BASE_DIR%\%DATE%.tar.gz

echo.
echo [4/4] Cleaning old backups (keeping latest %MAX_BACKUPS%)...

:: Count backups
set /a BACKUP_COUNT=0
for %%F in ("%BACKUP_BASE_DIR%\*.tar.gz") do set /a BACKUP_COUNT+=1

if !BACKUP_COUNT! GTR %MAX_BACKUPS% (
    :: Get list of backups sorted by date (oldest first)
    set /a TO_DELETE=!BACKUP_COUNT! - %MAX_BACKUPS%
    set /a DELETED=0
    
    for /f "delims=" %%F in ('dir /B /O:D "%BACKUP_BASE_DIR%\*.tar.gz"') do (
        if !DELETED! LSS !TO_DELETE! (
            del "%BACKUP_BASE_DIR%\%%F"
            set /a DELETED+=1
        )
    )
    echo [OK] Removed !DELETED! old backup(s)
) else (
    echo [OK] No cleanup needed (total backups: !BACKUP_COUNT!)
)

echo.
echo ==================================
echo Backup complete!
echo Location: %BACKUP_BASE_DIR%\%DATE%.tar.gz
echo.
:: Count final backups
set /a FINAL_COUNT=0
for %%F in ("%BACKUP_BASE_DIR%\*.tar.gz") do set /a FINAL_COUNT+=1
echo Total backups: !FINAL_COUNT!
echo ==================================
echo.

pause
