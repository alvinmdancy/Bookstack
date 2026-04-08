@echo off
setlocal enabledelayedexpansion

echo ==================================
echo    BookStack Restore Script
echo ==================================
echo.

:: =========================
:: BASE PATH (LOCKED TO YOUR STRUCTURE)
:: =========================
set "BASE_DIR=%USERPROFILE%\Bookstack"
set "BACKUP_BASE_DIR=%BASE_DIR%\backup\backups"

set "DB_CONTAINER=mariadb"
set "DB_NAME=bookstackapp"
set "DB_USER=root"
set "DB_PASS=rootpass"

set "TEMP_DIR=%BASE_DIR%\temp_restore"

:: =========================
:: CHECK BACKUPS OR AUTO MODE
:: =========================

set "AUTO_FILE=%~1"

if defined AUTO_FILE (
    if exist "%AUTO_FILE%" (
        set "BACKUP_FILE=%AUTO_FILE%"
        echo Auto-selected backup:
        echo !BACKUP_FILE!
        goto :CONFIRM
    ) else (
        echo ERROR: Provided backup file not found
        echo %AUTO_FILE%
        if not defined AUTO_FILE pause
        exit /b 1
    )
)

if not exist "%BACKUP_BASE_DIR%\*.zip" (
    echo No backups found in:
    echo %BACKUP_BASE_DIR%
    if not defined AUTO_FILE pause
    exit /b 1
)

echo Available backups:
echo.

set /a count=0

for /f "delims=" %%F in ('dir /b /o-d "%BACKUP_BASE_DIR%\*.zip"') do (
    set /a count+=1
    echo !count!. %%F
    set "backup!count!=%%F"
)

if !count! equ 0 (
    echo No backups found
    if not defined AUTO_FILE pause
    exit /b 1
)

echo.
set /p BACKUP_NUM="Select backup number to restore (1 = latest): "

if not defined backup%BACKUP_NUM% (
    echo Invalid selection
    if not defined AUTO_FILE pause
    exit /b 1
)

set "BACKUP_FILE=%BACKUP_BASE_DIR%\!backup%BACKUP_NUM%!"

:CONFIRM
echo.

if defined AUTO_FILE (
    echo Auto mode detected - skipping confirmation
) else (
    echo Selected: !BACKUP_FILE!
    set /p CONFIRM="THIS WILL OVERWRITE CURRENT DATA. Type YES to continue: "

    if /i not "!CONFIRM!"=="YES" (
        echo Restore cancelled
        if not defined AUTO_FILE pause
        exit /b 0
    )
)

:: =========================
:: EXTRACT
:: =========================
echo [1/3] Extracting backup...

if exist "%TEMP_DIR%" rmdir /S /Q "%TEMP_DIR%"
mkdir "%TEMP_DIR%"

powershell -NoProfile -Command ^
"Expand-Archive -Path '!BACKUP_FILE!' -DestinationPath '%TEMP_DIR%' -Force"

echo [OK] Extracted
echo.

:: =========================
:: FIND EXTRACTED FOLDER
:: =========================
for /d %%D in ("%TEMP_DIR%\*") do set "RESTORE_FOLDER=%%D"

echo [2/3] Restoring database...

docker exec -i %DB_CONTAINER% mysql -u%DB_USER% -p%DB_PASS% %DB_NAME% < "%RESTORE_FOLDER%\bookstack.sql"

if errorlevel 1 (
    echo [ERROR] Database restore failed
    if not defined AUTO_FILE pause
    exit /b 1
)

echo [OK] Database restored
echo.

echo [3/3] Restoring images...

if exist "%RESTORE_FOLDER%\images\" (
    if exist "%BASE_DIR%\storage\images\" rmdir /S /Q "%BASE_DIR%\storage\images"
    xcopy "%RESTORE_FOLDER%\images\*" "%BASE_DIR%\storage\images\" /E /I /Y /Q >nul
    echo [OK] Images restored
) else (
    echo [WARNING] No images found in backup
)

:: =========================
:: CLEANUP
:: =========================
rmdir /S /Q "%TEMP_DIR%"

echo.
echo ==================================
echo RESTORE COMPLETE
echo ==================================
echo.

echo Restart containers:
echo docker compose restart

if not defined AUTO_FILE pause