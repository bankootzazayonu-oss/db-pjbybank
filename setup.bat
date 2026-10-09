@echo off
title Setup - Movie Review Web App

echo ===================================================
echo     Auto Setup - Movie Review Web App
echo ===================================================
echo.

where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not found!
    echo Please install Laravel Herd: https://herd.laravel.com
    echo or install PHP and add it to your PATH.
    pause
    exit /b
)

echo [1/5] Setting up .env file...
if not exist .env (
    copy .env.example .env >nul
    echo Created .env file.
) else (
    echo .env already exists.
)
call php artisan key:generate
echo.

echo [2/5] Installing Composer Dependencies...
where composer >nul 2>nul
if %errorlevel% equ 0 (
    call composer install
) else (
    echo [SKIP] composer not found, using existing vendor directory.
)
echo.

echo [3/5] Preparing SQLite Database and Tables...
if not exist database\database.sqlite (
    type NUL > database\database.sqlite
    echo Created database.sqlite.
)
call php artisan migrate:fresh --seed
echo.

echo [4/5] Linking Storage...
call php artisan storage:link
echo.

echo [5/5] Building Frontend Assets...
where npm >nul 2>nul
if %errorlevel% equ 0 (
    call npm install
    call npm run build
) else (
    echo [SKIP] npm not found, using existing build.
)
echo.

echo ===================================================
echo   Setup Complete!
echo ===================================================
echo Test Accounts:
echo - Admin: admin@admin.com / password
echo - User:  user@user.com  / password
echo.
echo Starting server and opening browser...
start http://localhost:8000
call php artisan serve --port=8000
pause
