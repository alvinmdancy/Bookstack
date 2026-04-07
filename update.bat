@echo off
setlocal EnableDelayedExpansion
cd /d %~dp0

echo ========================================
echo        BookStack Production Updater
echo ========================================

set LOG_FILE=updater.log

:: =========================
:: 1. Load CURRENT VERSION (SOURCE OF TRUTH)
:: =========================
set LOCAL_TAG=
if exist VERSION (
    set /p LOCAL_TAG=<VERSION
)

if "%LOCAL_TAG%"=="" set LOCAL_TAG=unknown

echo Current version : %LOCAL_TAG%
echo [%date% %time%] START update from %LOCAL_TAG% >> %LOG_FILE%

:: =========================
:: 2. Ensure git repo is valid
:: =========================
git rev-parse --is-inside-work-tree >nul 2>&1
if errorlevel 1 (
    echo ERROR: Not a git repository
    echo [%date% %time%] ERROR not git repo >> %LOG_FILE%
    pause
    exit /b 1
)

:: =========================
:: 3. Fetch latest tags
:: =========================
echo Checking GitHub for updates...
git fetch --tags origin >nul 2>&1

set LATEST_TAG=
for /f %%i in ('git tag --sort=-v:refname') do (
    set LATEST_TAG=%%i
    goto :found_latest
)

:found_latest

if "%LATEST_TAG%"=="" (
    echo ERROR: No tags found on remote
    echo [%date% %time%] ERROR no remote tags >> %LOG_FILE%
    pause
    exit /b 1
)

echo Latest version  : %LATEST_TAG%

:: =========================
:: 4. Compare versions
:: =========================
if "%LOCAL_TAG%"=="%LATEST_TAG%" (
    echo ========================================
    echo Already up to date.
    echo ========================================
    echo [%date% %time%] SKIP already latest >> %LOG_FILE%
    pause
    exit /b 0
)

echo ========================================
echo Update available: %LOCAL_TAG% ^> %LATEST_TAG%
echo ========================================

echo [%date% %time%] UPDATE %LOCAL_TAG% to %LATEST_TAG% >> %LOG_FILE%

:: =========================
:: 5. Create rollback tag (safety snapshot)
:: =========================
set BACKUP_TAG=backup-%LOCAL_TAG%-%RANDOM%

git tag %BACKUP_TAG% >nul 2>&1
:: echo Backup snapshot: %BACKUP_TAG%

:: =========================
:: 6. Stop Docker safely
:: =========================
echo Stopping BookStack containers...
docker compose down

if errorlevel 1 (
    echo ERROR: Failed to stop containers
    echo [%date% %time%] ERROR docker down >> %LOG_FILE%
    pause
    exit /b 1
)

:: =========================
:: 7. Switch to latest version
:: =========================
echo Switching to %LATEST_TAG%...

git fetch --all --tags >nul 2>&1
git checkout %LATEST_TAG%

if errorlevel 1 (
    echo ERROR: Failed to switch version
    goto :rollback
)

:: =========================
:: 8. Update Docker images
:: =========================
echo Pulling latest Docker images...
docker compose pull

:: =========================
:: 9. Start containers
:: =========================
echo Starting BookStack...
docker compose up -d

if errorlevel 1 (
    echo ERROR: Container startup failed
    goto :rollback
)

:: =========================
:: 10. Save new version (CRITICAL STEP)
:: =========================
echo %LATEST_TAG% > VERSION

echo [%date% %time%] SUCCESS now at %LATEST_TAG% >> %LOG_FILE%

echo ========================================
echo UPDATE SUCCESSFUL
echo Now running version: %LATEST_TAG%
echo ========================================
pause
call control.bat
exit /b 0

:: =========================
:: ROLLBACK HANDLER
:: =========================
:rollback
echo ========================================
echo ERROR DETECTED - STARTING ROLLBACK
echo ========================================

echo [%date% %time%] ROLLBACK triggered >> %LOG_FILE%

git checkout %LOCAL_TAG%
docker compose up -d

echo [%date% %time%] ROLLBACK complete >> %LOG_FILE%

pause
exit /b 1