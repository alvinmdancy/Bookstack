@echo off
setlocal enabledelayedexpansion

echo ==================================
echo    BookStack Backup Script
echo ==================================
echo.

:: =========================
:: CONFIG (MATCHING YOUR SYSTEM)
:: =========================
set "BACKUP_BASE_DIR=backups"
set "MAX_BACKUPS=3"

set "DB_CONTAINER=mariadb"
set "DB_NAME=bookstackapp"
set "DB_USER=root"
set "DB_PASS=rootpass"

set "IMAGES_PATH=storage\images"

:: =========================
:: TIMESTAMP
:: =========================
for /f %%i in ('powershell -NoProfile -Command "Get-Date -Format yyyyMMdd_HHmmss"') do set "DATE=%%i"

set "BACKUP_DIR=%BACKUP_BASE_DIR%\%DATE%"

:: =========================
:: CREATE FOLDERS
:: =========================
if not exist "%BACKUP_BASE_DIR%" mkdir "%BACKUP_BASE_DIR%"
mkdir "%BACKUP_DIR%"

echo [1/4] Checking Docker container...

docker ps | findstr "%DB_CONTAINER%" >nul
if errorlevel 1 (
    echo [ERROR] MariaDB container not found: %DB_CONTAINER%
    echo Run: docker ps
    pause
    exit /b 1
)

echo [OK] Container found
echo.

echo [2/4] Dumping database (bookstackapp)...

docker exec -i %DB_CONTAINER% mysqldump --protocol=TCP -u%DB_USER% -p%DB_PASS% %DB_NAME% > "%BACKUP_DIR%\bookstack.sql"

if errorlevel 1 (
    echo [ERROR] Database backup failed
    echo.
    echo CHECK:
    echo - DB name: %DB_NAME%
    echo - Container: %DB_CONTAINER%
    echo - Password: %DB_PASS%
    pause
    exit /b 1
)

echo [OK] Database backup complete
echo.

echo [3/4] Backing up images...

if exist "%IMAGES_PATH%\" (
    xcopy "%IMAGES_PATH%\*" "%BACKUP_DIR%\images\" /E /I /Y /Q >nul
    echo [OK] Images backed up
) else (
    echo [WARNING] No images folder found at %IMAGES_PATH%
)

echo.

echo [4/4] Compressing backup...

powershell -NoProfile -Command ^
"Compress-Archive -Path '%BACKUP_DIR%' -DestinationPath '%BACKUP_BASE_DIR%\%DATE%.zip' -Force"

rmdir /S /Q "%BACKUP_DIR%"

echo [OK] Backup created: %DATE%.zip
echo.

echo [5/5] Cleaning old backups...

set /a COUNT=0

for /f "delims=" %%F in ('dir /b /o-d "%BACKUP_BASE_DIR%\*.zip"') do (
    set /a COUNT+=1
    if !COUNT! GTR %MAX_BACKUPS% (
        del "%BACKUP_BASE_DIR%\%%F"
        echo Deleted old backup: %%F
    )
)

echo.
echo ==================================
echo BACKUP COMPLETE
echo Location: %BACKUP_BASE_DIR%
echo Database: %DB_NAME%
echo ==================================
echo.

pause