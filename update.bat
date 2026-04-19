@echo off
setlocal enabledelayedexpansion

:: Always run from script directory
cd /d "%~dp0"

echo ==================================
echo   BookStack Updater
echo ==================================
echo.

:: =========================
:: PHASE 0 - GIT UPDATE (SAFE)
:: =========================
echo [0/7] Fetching latest version...

git fetch --tags >nul 2>&1
if !errorlevel! neq 0 (
    echo WARNING: Git fetch failed
    goto skip_git
)

:: Get latest tag
for /f "delims=" %%i in ('git describe --tags --abbrev=0') do set LATEST_TAG=%%i

if not defined LATEST_TAG (
    echo ERROR: Could not determine latest version
    goto skip_git
)

echo Latest version found: !LATEST_TAG!

:: Checkout latest tag (detached HEAD is expected)
git checkout !LATEST_TAG! >nul 2>&1
if !errorlevel! neq 0 (
    echo ERROR: Failed to checkout latest version
    goto skip_git
)

echo OK Running version !LATEST_TAG!

:: Sync VERSION file
<nul set /p "=!LATEST_TAG!">"VERSION"

:skip_git
echo.

:: =========================
:: PHASE 1 - DOCKER CHECK
:: =========================
echo [1/7] Checking Docker...

docker info >nul 2>&1
if !errorlevel! neq 0 (
    echo ERROR: Docker is not running
    pause
    exit /b 1
)

echo OK Docker running
echo.

:: =========================
:: PHASE 2 - START CONTAINERS
:: =========================
echo [2/7] Starting containers...

docker compose up -d >nul 2>&1
if !errorlevel! neq 0 (
    echo ERROR: Failed to start containers
    pause
    exit /b 1
)

echo OK Containers started
echo.

:: =========================
:: PHASE 3 - WAIT FOR DATABASE
:: =========================
echo [3/7] Waiting for MariaDB...

set /a count=0

:db_wait
set /a count+=1

docker exec mariadb mysqladmin ping -uroot -prootpass --silent >nul 2>&1

if !errorlevel! neq 0 (
    if !count! GEQ 20 (
        echo ERROR: MariaDB not ready
        pause
        exit /b 1
    )
    timeout /t 3 >nul
    goto db_wait
)

echo OK Database ready
echo.

:: =========================
:: PHASE 4 - FIND LATEST BACKUP
:: =========================
echo [4/7] Locating latest backup...

set "BACKUP_DIR=%cd%\backup\backups"
set "LATEST_BACKUP="

for /f "delims=" %%F in ('dir /b /o-d "%BACKUP_DIR%\*.zip" 2^>nul') do (
    set "LATEST_BACKUP=%BACKUP_DIR%\%%F"
    goto backup_found
)

echo WARNING: No backup found
goto skip_restore

:backup_found
echo Found backup:
echo !LATEST_BACKUP!
echo.

:: =========================
:: RESTORE BACKUP
:: =========================
echo Restoring backup...

set "RESTORE_SCRIPT=%~dp0restore\restore.bat"

if not exist "!RESTORE_SCRIPT!" (
    echo ERROR: restore.bat not found
    pause
    exit /b 1
)

call "!RESTORE_SCRIPT!" "!LATEST_BACKUP!" >nul 2>&1

if !errorlevel! neq 0 (
    echo ERROR: Restore failed
    pause
    exit /b 1
)

echo OK Backup restored

:skip_restore
echo.

:: =========================
:: PHASE 5 - VERIFY CONTAINER
:: =========================
echo [5/7] Verifying BookStack container...

docker ps | findstr bookstack >nul
if !errorlevel! neq 0 (
    echo ERROR: BookStack container not running
    pause
    exit /b 1
)

echo OK BookStack running
echo.

:: =========================
:: PHASE 6 - SHORTCUT
:: =========================
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

:: =========================
:: PHASE 7 - FINAL CHECK
:: =========================
echo [7/7] Final validation...

timeout /t 5 >nul
curl -s http://localhost:8085 >nul 2>&1

if !errorlevel! neq 0 (
    echo WARNING: Web UI not responding yet (still booting)
) else (
    echo OK Web UI reachable
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
    echo WARNING: control.bat not found
)

pause
exit /b 0