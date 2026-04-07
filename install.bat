@echo off
setlocal enabledelayedexpansion

echo ==================================
echo   BookStack Installer
echo ==================================
echo.

:: =========================
:: CONFIG
:: =========================
set CONTAINER_DB=mariadb
set CONTAINER_APP=bookstack

set DB_NAME=bookstackapp
set DB_FILE=db\bookstack.sql

:: IMPORTANT: MUST MATCH docker-compose.yml
set DB_ROOT_PASS=rootpass

set MAX_RETRIES=20

:: =========================
:: INIT VERSION FILE
:: =========================
echo.

if not exist VERSION (
    echo Creating VERSION file...
    
    :: Try to derive version from git tag
    for /f %%i in ('git describe --tags --abbrev=0 2^>nul') do (
        <nul set /p "=%%i" > VERSION
        goto version_done
    )

    :: fallback if git not available
    <nul set /p "=v1.0.0" > VERSION
)

:version_done

echo OK Version initialized
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

docker exec mariadb mysqladmin ping -uroot -p%DB_ROOT_PASS% --silent >nul 2>&1

if %errorlevel% neq 0 (
    if !count! GEQ %MAX_RETRIES% (
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

docker exec mariadb mysql -u root -p%DB_ROOT_PASS% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME%;"

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

docker exec -i mariadb mysql -uroot -p%DB_ROOT_PASS% %DB_NAME% < %DB_FILE%

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
:: PHASE 6 - CREATING SHORTCUT
:: =========================
echo [6/7] Creating desktop shortcut...
echo Creating desktop shortcut...

set ICON_PATH=%cd%\assets\bookstack.ico
set TARGET_PATH=%cd%\control.bat
set SHORTCUT_NAME=BookStack.lnk

powershell -NoProfile -ExecutionPolicy Bypass ^
"$WshShell = New-Object -ComObject WScript.Shell; ^
$Desktop = [Environment]::GetFolderPath('Desktop'); ^
$Shortcut = $WshShell.CreateShortcut(\"$Desktop\%SHORTCUT_NAME%\"); ^
$Shortcut.TargetPath = \"%TARGET_PATH%\"; ^
$Shortcut.WorkingDirectory = \"%cd%\"; ^
$Shortcut.IconLocation = \"%ICON_PATH%\"; ^
$Shortcut.Save()"

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
echo INSTALL COMPLETE - STABLE MODE
echo ==================================
echo Access: http://localhost:8085
echo.
call control.bat
pause
