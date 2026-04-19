@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

echo ==================================
echo   BookStack Updater (Stable)
echo ==================================
echo.

:: =====================================================
:: [0] SYNC FROM GITHUB (CODE + BACKUPS)
:: =====================================================
echo [SYNC] Pulling latest from GitHub...

git checkout main >nul 2>&1
git pull origin main >nul 2>&1

if %errorlevel% neq 0 (
    echo ERROR: Failed to pull from GitHub
    pause
    exit /b 1
)

echo OK Repo updated from GitHub
echo.

:: =====================================================
:: VERSION (DISPLAY ONLY - NO LOGIC DEPENDENCY)
:: =====================================================
echo [VERSION] Reading version...

set "VERSION_FILE=%cd%\VERSION"

if exist "%VERSION_FILE%" (
    set /p CURRENT_VERSION=<"%VERSION_FILE%"
) else (
    set "CURRENT_VERSION=unknown"
)

echo Current Version: %CURRENT_VERSION%
echo.

:: =====================================================
:: [1/6] DOCKER CHECK
:: =====================================================
echo [1/6] Checking Docker...

docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Docker is not running
    pause
    exit /b 1
)

echo OK Docker running
echo.

:: =====================================================
:: [2/6] START CONTAINERS
:: =====================================================
echo [2/6] Starting containers...

docker compose up -d >nul 2>&1

if %errorlevel% neq 0 (
    echo ERROR: Failed to start containers
    pause
    exit /b 1
)

echo OK Containers running
echo.

:: =====================================================
:: [3/6] WAIT FOR DATABASE
:: =====================================================
echo [3/6] Waiting for MariaDB...

set /a count=0

:db_wait
set /a count+=1

docker exec mariadb mysqladmin ping -uroot -prootpass --silent >nul 2>&1

if %errorlevel% neq 0 (
    if !count! GEQ 20 (
        echo ERROR: Database not ready
        pause
        exit /b 1
    )
    timeout /t 3 >nul
    goto db_wait
)

echo OK Database ready
echo.

:: =====================================================
:: [4/6] SELECT LATEST BACKUP (LOCAL ONLY)
:: =====================================================
echo [4/6] Finding latest backup...

set "BACKUP_DIR=%cd%\backup\backups"
set "LATEST_BACKUP="

if not exist "%BACKUP_DIR%" (
    echo WARNING: Backup folder not found
    goto skip_restore
)

for /f "delims=" %%F in ('dir /b /o-d "%BACKUP_DIR%\*.zip" 2^>nul') do (
    set "LATEST_BACKUP=%BACKUP_DIR%\%%F"
    goto found_backup
)

echo WARNING: No backups found
goto skip_restore

:found_backup
echo Latest backup detected:
echo %LATEST_BACKUP%

echo Restoring backup...

call "%~dp0restore\restore.bat" "%LATEST_BACKUP%"

if %errorlevel% neq 0 (
    echo ERROR: Backup restore failed
    pause
    exit /b 1
)

echo OK Backup restored

:skip_restore
echo.

:: =====================================================
:: [5/6] VERIFY CONTAINER
:: =====================================================
echo [5/6] Verifying BookStack...

docker ps | findstr bookstack >nul
if %errorlevel% neq 0 (
    echo ERROR: BookStack not running
    pause
    exit /b 1
)

echo OK BookStack running
echo.

:: =====================================================
:: [6/6] FINAL HEALTH CHECK
:: =====================================================
echo [6/6] Final validation...

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
echo Version: %CURRENT_VERSION%
echo ==================================
echo Access: http://localhost:8085
echo.

if exist "control.bat" (
    call control.bat
)

pause
exit /b 0