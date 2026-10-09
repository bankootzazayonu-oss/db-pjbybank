@echo off
title Movie Review and Tier List Community

echo ===================================================
echo     Movie Review Web App - Quick Starter
echo ===================================================
echo.

:: 1. Check PHP
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not found on your system!
    echo Please install Laravel Herd: https://herd.laravel.com
    echo or install PHP and add it to your PATH.
    echo.
    pause
    exit /b
)

:: 2. Setup .env if missing
if not exist .env (
    echo [*] Creating .env file from .env.example...
    copy .env.example .env >nul
    call php artisan key:generate
)

:: 3. Setup SQLite database if missing
if not exist database\database.sqlite (
    echo [*] Creating database.sqlite...
    type NUL > database\database.sqlite
    echo [*] Running database migrations and seeds...
    call php artisan migrate:fresh --seed
)

:: 4. Storage link if missing
if not exist public\storage (
    call php artisan storage:link >nul 2>nul
)

:: 5. Check vendor folder
if not exist vendor (
    echo [*] Installing Composer dependencies...
    where composer >nul 2>nul
    if %errorlevel% equ 0 (
        call composer install
    ) else (
        echo [WARNING] Composer is not found. Dependencies might be missing.
    )
)

:: 6. Check frontend build
if not exist public\build (
    echo [*] Building frontend assets...
    where npm >nul 2>nul
    if %errorlevel% equ 0 (
        call npm install
        call npm run build
    ) else (
        echo [WARNING] NPM is not found. UI might not look correct.
    )
)

echo.
echo ===================================================
echo   Starting Server...
echo ===================================================
echo URL: http://localhost:8000
echo Admin: admin@admin.com / password
echo User:  user@user.com  / password
echo.
echo Opening browser...
start http://localhost:8000
echo.
call php artisan serve --port=8000
pause
