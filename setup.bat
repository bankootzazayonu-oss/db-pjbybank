@echo off
chcp 65001 >nul
echo ===================================================
echo      ระบบติดตั้งอัตโนมัติ - Movie Review Web App
echo ===================================================
echo.

echo [1/7] กำลังติดตั้ง PHP Dependencies (Composer)...
call composer install
echo.

echo [2/7] กำลังติดตั้ง Node.js Dependencies (NPM)...
call npm install
echo.

echo [3/7] กำลังตั้งค่าไฟล์ Environment (.env)...
IF NOT EXIST .env (
    copy .env.example .env
    echo คัดลอก .env.example เป็น .env เรียบร้อย
) ELSE (
    echo พบไฟล์ .env อยู่แล้ว ข้ามขั้นตอนนี้
)
echo.

echo [4/7] กำลังสร้าง App Key...
call php artisan key:generate
echo.

echo [5/7] กำลังเตรียมฐานข้อมูล SQLite และตาราง...
IF NOT EXIST database\database.sqlite (
    type NUL > database\database.sqlite
    echo สร้างไฟล์ database.sqlite เรียบร้อย
)
call php artisan migrate:fresh --seed
echo.

echo [6/7] กำลังเชื่อมต่อโฟลเดอร์รูปภาพ (Storage Link)...
call php artisan storage:link
echo.

echo [7/7] กำลังคอมไพล์ CSS/JS (Tailwind)...
call npm run build
echo.

echo ===================================================
echo   ติดตั้งสำเร็จ! โปรเจกต์พร้อมใช้งานแล้ว 🎉
echo ===================================================
echo.
echo *** สิ่งที่ต้องทำต่อไป ***
echo พิมพ์คำสั่ง: php artisan serve เพื่อเปิดเว็บ
echo.
pause
