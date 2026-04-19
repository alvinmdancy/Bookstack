@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

echo ==================================
echo   BookStack Updater (Stable)
echo ==================================
echo.

:: =========================
:: SYNC FROM GITHUB
:: =========================
echo [SYNC] Pulling latest from GitHub...

git checkout main >nul 2>&1
git pull origin main >nul 2>&1

if %errorlevel% neq 0 (
    echo ERROR: Git pull failed
    pause
    exit /b 1
)

echo OK Repo updated
echo.

:: =========================
:: GET LATEST TAG
:: =========================
for /f "delims=" %%i in ('git describe --tags --abbrev=0 2^>nul') do set "LATEST_TAG=%%i"

if not defined LATEST_TAG (
    echo ERROR: No git tag found
    pause
    exit /b 1
)

echo Latest tag detected: !LATEST_TAG!
echo.

:: =========================
:: WRITE VERSION (FIXED PATH)
:: =========================
set "VERSION_FILE=%~dp0VERSION"

echo !LATEST_TAG!>"%VERSION_FILE%"

echo OK VERSION updated: !LATEST_TAG!
echo.

:: =========================
:: DOCKER CHECK
:: =========================
echo [1/5] Checking Docker...

docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Docker not running
    pause
    exit /b 1
)

echo OK Docker running
echo.

:: =========================
:: START CONTAINERS
:: =========================
echo [2/5] Starting containers...

docker compose up -d >nul 2>&1

if %errorlevel% neq 0 (
    echo ERROR: Failed to start containers
    pause
    exit /b 1
)

echo OK Containers running
echo.

:: =========================
:: WAIT FOR DB
:: =========================
echo [3/5] Waiting for MariaDB...

set /a count=0

:dbwait
set /a count+=1

docker exec mariadb mysqladmin ping -uroot -prootpass --silent >nul 2>&1

if %errorlevel% neq 0 (
    if !count! GEQ 20 (
        echo ERROR: DB not ready
        pause
        exit /b 1
    )
    timeout /t 3 >nul
    goto dbwait
)

echo OK Database ready
echo.

:: =========================
:: FIND LATEST BACKUP
:: =========================
echo [4/5] Finding latest backup...

set "BACKUP_DIR=%~dp0backup\backups"
set "LATEST_BACKUP="

for /f "delims=" %%F in ('dir /b /o-d "%BACKUP_DIR%\*.zip" 2^>nul') do (
    set "LATEST_BACKUP=%BACKUP_DIR%\%%F"
    goto found
)

echo WARNING: No backups found
goto skip_restore

:found
echo Latest backup:
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

:: =========================
:: FINAL CHECK
:: =========================
echo [5/5] Final validation...

timeout /t 5 >nul
curl -s http://localhost:8085 >nul 2>&1

echo.
echo ==================================
echo UPDATE COMPLETE
echo Version: !LATEST_TAG!
echo ==================================
echo.

pause
exit /b 0