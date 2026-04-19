@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"

:menu
cls
set "LOCAL_TAG=unknown"
set "LATEST_TAG=unknown"
set "UPDATE_AVAILABLE=0"

:: =========================
:: Read LOCAL version (SOURCE OF TRUTH)
:: =========================
if exist "VERSION" (
    rem Read the raw content of the VERSION file
    set /p "LOCAL_TAG_RAW="<"VERSION"

    rem --- Robustly trim leading and trailing whitespace ---
    rem 1. Trim leading whitespace
    for /f "tokens=* delims= " %%a in ("!LOCAL_TAG_RAW!") do set "LOCAL_TAG=%%a"

    rem 2. Trim trailing whitespace recursively
    :trim_trailing
    if defined LOCAL_TAG (
        if "!LOCAL_TAG:~-1!"==" " ( 
            set "LOCAL_TAG=!LOCAL_TAG:~0,-1!"
            goto :trim_trailing
        )
    )
    
) else (
    echo WARNING: VERSION file not found
)

:: =========================
:: Fetch latest tags
:: =========================
git fetch --tags origin >nul 2>&1

:: Get the latest tag
for /f "delims=" %%i in ('git tag --sort^=-v:refname 2^>nul') do (
    set "LATEST_TAG=%%i"
    goto :latest_done
)

:latest_done

rem Trim whitespace from LATEST_TAG
for /f "tokens=* delims= " %%a in ("!LATEST_TAG!") do set "LATEST_TAG=%%a"

:: =========================
:: Compare versions
:: =========================

echo ================================
echo     BookStack Control Panel
echo ================================
echo.
echo Go to http://localhost:8085 in your browser to access BookStack
echo.

echo Current Version: !LOCAL_TAG!
echo Latest Version : !LATEST_TAG!
echo.

if not "!LOCAL_TAG!"=="unknown" (
    if not "!LATEST_TAG!"=="unknown" (
        if not "!LOCAL_TAG!"=="!LATEST_TAG!" (
            set "UPDATE_AVAILABLE=1"
        )
    )
)

if "!UPDATE_AVAILABLE!"=="1" (
    echo.
    echo *** UPDATE AVAILABLE ***
    echo.
)
echo ================================
echo Please select an option:
echo ================================
echo.
echo 1. Start BookStack
echo 2. Stop BookStack
echo 3. Update BookStack
echo 4. Restart BookStack
echo 5. Exit
echo.
set /p choice=Select an option: 

if "!choice!"=="1" goto start
if "!choice!"=="2" goto stop
if "!choice!"=="3" goto update
if "!choice!"=="4" goto restart
if "!choice!"=="5" exit

goto menu

:start
echo ================================
echo     Starting BookStack....
echo ================================

docker compose up -d
echo.
echo Go to http://localhost:8085 in your browser to access BookStack
echo.
pause
call control.bat
goto menu

:stop
echo ================================
echo     Stopping BookStack....
echo ================================

docker compose down
pause
call control.bat
goto menu

:update
call update.bat
goto menu

:restart
echo ================================
echo     Restarting BookStack....
echo ================================

docker compose down
docker compose up -d
pause
call control.bat
goto menu