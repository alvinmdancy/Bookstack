@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

:menu
cls

set "BASE_DIR=%~dp0"
set "VERSION_FILE=%BASE_DIR%VERSION"

set "LOCAL_TAG=unknown"
set "LATEST_TAG=unknown"
set "UPDATE_AVAILABLE=0"

:: =====================================================
:: READ LOCAL VERSION (ABSOLUTE PATH FIX)
:: =====================================================
if exist "%VERSION_FILE%" (
    set /p "LOCAL_TAG="<"%VERSION_FILE%"
)

for /f "tokens=* delims= " %%a in ("!LOCAL_TAG!") do set "LOCAL_TAG=%%a"

:: =====================================================
:: GET LATEST GIT TAG (SAFE PATH)
:: =====================================================
for /f "delims=" %%i in ('git -C "%BASE_DIR%" describe --tags --abbrev=0 2^>nul') do (
    set "LATEST_TAG=%%i"
)

for /f "tokens=* delims= " %%a in ("!LATEST_TAG!") do set "LATEST_TAG=%%a"

:: =====================================================
:: COMPARE VERSIONS
:: =====================================================
if not "!LOCAL_TAG!"=="unknown" (
    if not "!LATEST_TAG!"=="unknown" (
        if not "!LOCAL_TAG!"=="!LATEST_TAG!" (
            set "UPDATE_AVAILABLE=1"
        )
    )
)

:: =====================================================
:: UI
:: =====================================================
echo ================================
echo     BookStack Control Panel
echo ================================
echo.
echo Go to http://localhost:8085 in your browser
echo.

echo Current Version: !LOCAL_TAG!
echo Latest Version : !LATEST_TAG!
echo.

if "!UPDATE_AVAILABLE!"=="1" (
    echo *** UPDATE AVAILABLE ***
    echo.
)

echo ================================
echo 1. Start
echo 2. Stop
echo 3. Update
echo 4. Restart
echo 5. Exit
echo ================================
echo.
set /p choice=Select:

if "!choice!"=="1" goto start
if "!choice!"=="2" goto stop
if "!choice!"=="3" goto update
if "!choice!"=="4" goto restart
if "!choice!"=="5" exit

goto menu

:: =====================================================
:: START
:: =====================================================
:start
echo Starting BookStack...
docker compose up -d
pause
goto menu

:: =====================================================
:: STOP
:: =====================================================
:stop
echo Stopping BookStack...
docker compose down
pause
goto menu

:: =====================================================
:: UPDATE
:: =====================================================
:update
call update.bat
goto menu

:: =====================================================
:: RESTART
:: =====================================================
:restart
echo Restarting BookStack...
docker compose down
docker compose up -d
pause
goto menu