@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

:menu
cls

set "LOCAL_TAG=unknown"
set "LATEST_TAG=unknown"
set "VERSION_FILE=%~dp0VERSION"

:: =========================
:: READ LOCAL VERSION (FIXED PATH)
:: =========================
if exist "%VERSION_FILE%" (
    set /p "LOCAL_TAG="<"%VERSION_FILE%"
)

:: clean whitespace
for /f "tokens=* delims= " %%a in ("!LOCAL_TAG!") do set "LOCAL_TAG=%%a"

:: =========================
:: GET LATEST TAG
:: =========================
for /f "delims=" %%i in ('git describe --tags --abbrev=0 2^>nul') do set "LATEST_TAG=%%i"

:: =========================
:: DISPLAY
:: =========================
echo ================================
echo     BookStack Control Panel
echo ================================
echo.
echo Current Version: !LOCAL_TAG!
echo Latest Version : !LATEST_TAG!
echo.

if not "!LOCAL_TAG!"=="!LATEST_TAG!" (
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
set /p choice=Select: 

if "!choice!"=="1" goto start
if "!choice!"=="2" goto stop
if "!choice!"=="3" goto update
if "!choice!"=="4" goto restart
if "!choice!"=="5" exit

goto menu

:start
docker compose up -d
pause
goto menu

:stop
docker compose down
pause
goto menu

:update
call update.bat
goto menu

:restart
docker compose down
docker compose up -d
pause
goto menu