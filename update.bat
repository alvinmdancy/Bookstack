@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

echo ==================================
echo   BookStack Updater
echo ==================================
echo.

rem =========================
rem FETCH LATEST TAGS
rem =========================
echo Fetching latest tags from GitHub...

git fetch --tags origin

if %errorlevel% equ 0 (
    echo OK Tags fetched successfully
) else (
    echo WARNING: Git fetch failed
    echo Continuing with local version...
)
echo.

rem =========================
rem GET LATEST TAG
rem =========================
echo Finding latest version tag...

for /f "delims=" %%i in ('git tag --sort^=-v:refname 2^>nul') do (
    set "LATEST_TAG=%%i"
    goto :found_tag
)

echo ERROR: No tags found in repository
pause
exit /b 1

:found_tag
rem Trim whitespace from tag
for /f "tokens=* delims= " %%a in ("!LATEST_TAG!") do set "LATEST_TAG=%%a"

echo Latest tag found: !LATEST_TAG!
echo.

rem =========================
rem CLEAN LOCAL CHANGES
rem =========================
echo Preparing to switch versions...
echo.

git reset --hard
echo.
git clean -fd -e .env -e data/ -e backup/

echo.
echo OK Local changes cleaned
echo.

rem =========================
rem CHECKOUT LATEST TAG
rem =========================
echo Checking out !LATEST_TAG!...
echo.

git checkout "!LATEST_TAG!"

if %errorlevel% equ 0 (
    echo.
    echo OK Switched to !LATEST_TAG!
) else (
    echo ERROR: Failed to checkout !LATEST_TAG!
    echo.
    echo Troubleshooting info:
    git status
    pause
    exit /b 1
)
echo.

rem =========================
rem UPDATE VERSION FILE
rem =========================
echo Updating VERSION file...

<nul set /p "=!LATEST_TAG!">"VERSION"
echo OK VERSION file updated to !LATEST_TAG!
echo.

rem =========================
rem PHASE 1 - DOCKER CHECK
rem =========================
echo [1/7] Checking Docker...
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Docker is not running or not installed
    pause
    exit /b 1
)
echo OK Docker running
echo.

rem =========================
rem PHASE 2 - START CONTAINERS
rem =========================
echo [2/7] Starting containers...
docker compose up -d
if %errorlevel% neq 0 (
    echo ERROR: Failed to start containers
    pause
    exit /b 1
)
echo OK Containers started
echo.

rem =========================
rem PHASE 3 - WAIT FOR MARIADB
rem =========================
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
rem =========================
rem AUTO SELECT LATEST BACKUP
rem =========================

set "BACKUP_BASE_DIR=%cd%\backup\backups"
set "LATEST_BACKUP="

for /f "delims=" %%F in ('dir /b /o-d "%BACKUP_BASE_DIR%\*.zip" 2^>nul') do (
    set "LATEST_BACKUP=%BACKUP_BASE_DIR%\%%F"
    goto :found_latest
)

echo WARNING: No backups found for auto-restore
goto :skip_restore

:found_latest
echo Latest backup detected:
echo !LATEST_BACKUP!

echo Restoring automatically...
call "%~dp0restore\restore.bat" "%LATEST_BACKUP%" >nul 2>&1

:skip_restore
echo.
rem =========================
rem PHASE 4 - CREATE DB + RESTORE
rem =========================
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

rem =========================
rem PHASE 4 - RESTORE BACKUP
rem =========================
echo [4/7] Restoring latest backup...

set "RESTORE_SCRIPT=%~dp0restore\restore.bat"

if not exist "%RESTORE_SCRIPT%" (
    echo ERROR: restore.bat not found
    pause
    exit /b 1
)

set "RESTORE_SCRIPT=%USERPROFILE%\Bookstack\restore\restore.bat"

if %errorlevel% neq 0 (
    echo ERROR: Restore failed
    pause
    exit /b 1
)

echo OK Backup restored
echo.

rem =========================
rem PHASE 5 - VERIFY BOOKSTACK
rem =========================
echo [5/7] Verifying BookStack container...

docker ps | findstr bookstack >nul
if %errorlevel% neq 0 (
    echo ERROR: BookStack container not running
    pause
    exit /b 1
)

echo OK BookStack running
echo.

rem =========================
rem PHASE 6 - CREATING SHORTCUT (FIXED)
rem =========================
echo [6/7] Creating desktop shortcut...

set "ICON_PATH=%cd%\assets\bookstack.ico"
set "TARGET_PATH=%cd%\control.bat"
set "SHORTCUT_NAME=BookStack.lnk"

powershell -NoProfile -ExecutionPolicy Bypass -Command "$WshShell = New-Object -ComObject WScript.Shell; $Desktop = [Environment]::GetFolderPath('Desktop'); $Shortcut = $WshShell.CreateShortcut((Join-Path $Desktop '%SHORTCUT_NAME%')); $Shortcut.TargetPath = '%TARGET_PATH%'; $Shortcut.WorkingDirectory = '%cd%'; $Shortcut.IconLocation = '%ICON_PATH%'; $Shortcut.Save()"

echo Shortcut created on desktop
echo.

rem =========================
rem PHASE 7 - FINAL CHECK
rem =========================
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