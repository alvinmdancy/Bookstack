@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

echo ==================================
echo   BookStack Updater (Stable)
echo ==================================
echo.

:: =====================================================
:: GIT - TAG BASED DEPLOYMENT (SOURCE OF TRUTH)
:: =====================================================
echo [0/7] Fetching latest release tags...

git fetch --tags >nul 2>&1
if %errorlevel% neq 0 (
    echo WARNING: Git fetch failed (continuing with local tags)
)

for /f "delims=" %%i in ('git describe --tags --abbrev=0 2^>nul') do set LATEST_TAG=%%i

if not defined LATEST_TAG (
    echo ERROR: Could not determine latest version tag
    pause
    exit /b 1
)

echo Latest version found: !LATEST_TAG!

git checkout !LATEST_TAG! >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Failed to checkout !LATEST_TAG!
    pause
    exit /b 1
)

echo OK Running version !LATEST_TAG!

:: Sync VERSION file
<nul set /p "=!LATEST_TAG!">"VERSION"
echo Version file synced
echo.

:: =====================================================
:: DOCKER CHECK
:: =====================================================
echo [1/7] Checking Docker...

docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Docker is not running
    pause
    exit /b 1
)

echo OK Docker running
echo.

:: =====================================================
:: START CONTAINERS
:: =====================================================
echo [2/7] Starting containers...

docker compose up -d >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Failed to start containers
    pause
    exit /b 1
)

echo OK Containers started
echo.

:: =====================================================
:: WAIT FOR DATABASE
:: =====================================================
echo [3/7] Waiting for MariaDB...

set /a count=0

:db_wait
set /a count+=1

docker exec mariadb mysqladmin ping -uroot -prootpass --silent >nul 2>&1

if %errorlevel% neq 0 (
    if !count! GEQ 20 (
        echo ERROR: MariaDB failed to start
        pause
        exit /b 1
    )
    timeout /t 3 >nul
    goto db_wait
)

echo OK Database ready
echo.

:: =====================================================
:: AUTO BACKUP RESTORE (SINGLE CLEAN FLOW)
:: =====================================================
echo [4/7] Checking for latest backup...

set "BACKUP_DIR=%cd%\backup\backups"
set "LATEST_BACKUP="

for /f "delims=" %%F in ('dir /b /o-d "%BACKUP_DIR%\*.zip" 2^>nul') do (
    set "LATEST_BACKUP=%BACKUP_DIR%\%%F"
    goto found_backup
)

echo WARNING: No backups found
goto skip_restore

:found_backup
echo Latest backup detected:
echo !LATEST_BACKUP!

echo Restoring backup...

call "%~dp0restore\restore.bat" "!LATEST_BACKUP!"

if %errorlevel% neq 0 (
    echo ERROR: Restore failed
    pause
    exit /b 1
)

echo OK Backup restored

:skip_restore
echo.

:: =====================================================
:: VERIFY CONTAINER
:: =====================================================
echo [5/7] Verifying BookStack...

docker ps | findstr bookstack >nul
if %errorlevel% neq 0 (
    echo ERROR: BookStack container not running
    pause
    exit /b 1
)

echo OK BookStack running
echo.

:: =====================================================
:: SHORTCUT CREATION
:: =====================================================
echo [6/7] Creating desktop shortcut...

set "ICON_PATH=%cd%\assets\bookstack.ico"
set "TARGET_PATH=%cd%\control.bat"
set "SHORTCUT_NAME=BookStack.lnk"

powershell -NoProfile -ExecutionPolicy Bypass ^
"$WshShell = New-Object -ComObject WScript.Shell; ^
$Desktop = [Environment]::GetFolderPath('Desktop'); ^
$Shortcut = $WshShell.CreateShortcut((Join-Path $Desktop '%SHORTCUT_NAME%')); ^
$Shortcut.TargetPath = '%TARGET_PATH%'; ^
$Shortcut.WorkingDirectory = '%cd%'; ^
$Shortcut.IconLocation = '%ICON_PATH%'; ^
$Shortcut.Save()"

echo OK Shortcut created
echo.

:: =====================================================
:: FINAL HEALTH CHECK
:: =====================================================
echo [7/7] Final validation...

timeout /t 5 >nul

curl -s http://localhost:8085 >nul 2>&1
if %errorlevel% neq 0 (
    echo WARNING: Web UI not responding yet
) else (
    echo OK Web UI reachable
)

echo.
echo ==================================
echo UPDATE COMPLETE
echo Version: !LATEST_TAG!
echo ==================================
echo Access: http://localhost:8085
echo.

if exist "control.bat" (
    call control.bat
)

pause
exit /b 0