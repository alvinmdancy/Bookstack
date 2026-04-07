@echo off
setlocal enabledelayedexpansion

echo ==================================
echo    BookStack Restore Script
echo ==================================
echo.

:: Configuration - adjusted for running from restore\ directory
set BACKUP_BASE_DIR=..\backups

:: Check if backups exist
if not exist "%BACKUP_BASE_DIR%\*.tar.gz" (
    echo No backups found in %BACKUP_BASE_DIR%
    pause
    exit /b 1
)

:: List available backups
echo Available backups:
set /a count=0
for /f "delims=" %%F in ('dir /B /O:-D "%BACKUP_BASE_DIR%\*.tar.gz"') do (
    set /a count+=1
    echo !count!. %%F
    set "backup!count!=%%F"
)

if !count! equ 0 (
    echo No backups found
    pause
    exit /b 1
)

echo.
set /p BACKUP_NUM="Enter backup number to restore (1 = latest): "

if not defined backup!BACKUP_NUM! (
    echo Invalid backup number
    pause
    exit /b 1
)

set BACKUP_FILE=%BACKUP_BASE_DIR%\!backup%BACKUP_NUM%!

echo.
echo Restoring from: !BACKUP_FILE!
set /p CONFIRM="This will overwrite current data. Continue? (yes/no): "

if /i not "%CONFIRM%"=="yes" (
    echo Restore cancelled
    pause
    exit /b 0
)

echo.
echo [1/3] Extracting backup...
set TEMP_DIR=temp_restore
if exist "%TEMP_DIR%" rmdir /S /Q "%TEMP_DIR%"
mkdir "%TEMP_DIR%"

tar -xzf "!BACKUP_FILE!" -C "%TEMP_DIR%"

:: Get backup folder name (without .tar.gz)
for %%F in ("!BACKUP_FILE!") do set BACKUP_NAME=%%~nF

echo [2/3] Restoring database...
docker exec -i mariadb mysql -uroot -psecretrootpass bookstackapp < "%TEMP_DIR%\%BACKUP_NAME%\bookstack.sql"
echo [OK] Database restored

echo.
echo [3/3] Restoring images...
if exist "%TEMP_DIR%\%BACKUP_NAME%\images\" (
    if exist "..\storage\images\" rmdir /S /Q "..\storage\images"
    xcopy /E /I /Y /Q "%TEMP_DIR%\%BACKUP_NAME%\images" "..\storage\images" >nul
    echo [OK] Images restored
) else (
    echo [WARNING] No images found in backup
)

:: Cleanup
rmdir /S /Q "%TEMP_DIR%"

echo.
echo ==================================
echo Restore complete!
echo Restart containers if needed:
echo   docker compose restart
echo ==================================
echo.

pause
