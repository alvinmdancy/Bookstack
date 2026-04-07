@echo off
cd /d %~dp0

set LOCAL_TAG=unknown
set LATEST_TAG=
set UPDATE_AVAILABLE=0

:: =========================
:: Read LOCAL version (SOURCE OF TRUTH)
:: =========================
if exist VERSION (
    set /p LOCAL_TAG=<VERSION
)

:: =========================
:: Fetch latest tags
:: =========================
git fetch --tags origin >nul 2>&1

for /f %%i in ('git tag --sort=-v:refname') do (
    set LATEST_TAG=%%i
    goto :latest_done
)

:latest_done

:: =========================
:: Compare versions
:: =========================
if not "%LOCAL_TAG%"=="%LATEST_TAG%" (
    set UPDATE_AVAILABLE=1
)
:menu
cls
echo ================================
echo     BookStack Control Panel
echo ================================
echo.
echo Go to http://localhost:8085 in your browser to access BookStack
echo.
echo Current Version : %LOCAL_TAG%
echo Latest Version  : %LATEST_TAG%

if "%UPDATE_AVAILABLE%"=="1" (
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

if "%choice%"=="1" goto start
if "%choice%"=="2" goto stop
if "%choice%"=="3" goto update
if "%choice%"=="4" goto restart
if "%choice%"=="5" exit

goto menu

:start
echo ================================
echo     Starting BookStack....
echo ================================

docker compose up -d
pause
echo go to http://localhost:8085 in your browser to access BookStack
goto menu

:stop
echo ================================
echo     Stopping BookStack....
echo ================================

docker compose down
pause
goto menu

:update
call update.bat
:: reload version after update
if exist VERSION (
    set /p LOCAL_TAG=<VERSION
)

set UPDATE_AVAILABLE=0

goto menu

:restart
echo ================================
echo     Restarting BookStack....
echo ================================

docker compose down
docker compose up -d
pause
goto menu
