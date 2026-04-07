@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

echo ==================================
echo   BookStack Updater
echo ==================================
echo.

:: =========================
:: SYNC VERSION FILE
:: =========================
echo Syncing VERSION file with latest git tag...

git describe --tags --abbrev=0 > temp_tag.txt 2>nul

if %errorlevel% equ 0 (
    set /p NEW_GIT_TAG=<temp_tag.txt
    del temp_tag.txt

    <nul set /p "=!NEW_GIT_TAG!">"VERSION"
    echo OK Version synced: !NEW_GIT_TAG!
) else (
    if exist temp_tag.txt del temp_tag.txt
    echo WARNING: Git tag not found. VERSION file not updated.
)
echo.

:: =========================
:: PHASE 1 - DOCKER CHECK
:: =========================
echo [1/7] Checking Docker...
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Docker is not running or not installed
    pause
    exit /b 1
)
echo OK Docker running
echo.

:: =========================
:: PHASE 2 - START CONTAINERS
:: =========================
echo [2/7] Starting containers...
docker compose up -d
if %errorlevel% neq 0 (
    echo ERROR: Failed to start containers
    pause
    exit /b 1
)
echo OK Containers started
echo.

:: =========================
:: PHASE 3 - WAIT FOR MARIADB
:: =========================
echo [3/7] Waiting for MariaDB...
set /a count=0

:db_wait
set /a count+=1
docker exec mariadb mysqladmin ping -uroot -prootpass --silent >nul 2>&1

if %errorlevel% neq 0 (
    if !count! GEQ 20 (
        echo ERROR: MariaDB failed to become ready
        pause
        exit /b 1
    )
    timeout /t 3 >nul
    goto db_wait
)

echo OK Database ready
echo.

:: =========================
:: PHASE 4 - CREATE DB + RESTORE
:: =========================
echo [4/7] Preparing database...

docker exec mariadb mysql -u root -prootpass -e "CREATE DATABASE IF NOT EXISTS bookstackapp;"
if %errorlevel% neq 0 (
    echo ERROR: Failed to create database
    pause
    exit /b 1
)

echo OK Database exists
echo.
echo Restoring database...

set /a count=0

:restore_db
set /a count+=1

docker exec -i mariadb mysql -uroot -prootpass bookstackapp < "db\bookstack.sql"

if %errorlevel% neq 0 (
    echo WARNING: Restore attempt !count! failed
    if !count! GEQ 5 (
        echo ERROR: DB restore failed permanently
        pause
        exit /b 1
    )
    timeout /t 3 >nul
    goto restore_db
)

echo OK Database restored
echo.

:: =========================
:: PHASE 5 - VERIFY BOOKSTACK
:: =========================
echo [5/7] Verifying BookStack container...

docker ps | findstr bookstack >nul
if %errorlevel% neq 0 (
    echo ERROR: BookStack container not running
    pause
    exit /b 1
)

echo OK BookStack running
echo.

:: =========================
:: PHASE 6 - CREATING SHORTCUT (FIXED)
:: =========================
echo [6/7] Creating desktop shortcut...

set "ICON_PATH=%cd%\assets\bookstack.ico"
set "TARGET_PATH=%cd%\control.bat"
set "SHORTCUT_NAME=BookStack.lnk"

powershell -NoProfile -ExecutionPolicy Bypass -Command "$WshShell = New-Object -ComObject WScript.Shell; $Desktop = [Environment]::GetFolderPath('Desktop'); $Shortcut = $WshShell.CreateShortcut((Join-Path $Desktop '%SHORTCUT_NAME%')); $Shortcut.TargetPath = '%TARGET_PATH%'; $Shortcut.WorkingDirectory = '%cd%'; $Shortcut.IconLocation = '%ICON_PATH%'; $Shortcut.Save()"

echo Shortcut created on desktop
echo.

:: =========================
:: PHASE 7 - FINAL CHECK
:: =========================
echo [7/7] Final validation...

timeout /t 5 >nul
curl -s http://localhost:8085 >nul 2>&1

if %errorlevel% neq 0 (
    echo WARNING: Web UI not responding yet (still booting)
)

echo.
echo ==================================
echo UPDATE COMPLETE
echo ==================================
echo Access: http://localhost:8085
echo.

if exist "control.bat" (
    call control.bat
) else (
    echo WARNING: control.bat not found to launch dashboard.
)

pause
exit /b 0