# 🎬 CineReview - Movie Review & Tier List Web Application

เว็บแอปพลิเคชันรีวิวภาพยนตร์ ชุมชนคอหนัง และระบบจัดอันดับภาพยนตร์แบบโต้ตอบ (Interactive Tier List)  
พัฒนาด้วย **Laravel 11**, **Tailwind CSS v4**, **SQLite** และเชื่อมต่อ **TMDB API**

---

## 💻 สิ่งที่ต้องติดตั้งในเครื่องก่อนเริ่ม (Prerequisites)

ก่อนเริ่มรันโปรเจกต์ ตรวจสอบว่าเครื่องของคุณมีเครื่องมือเหล่านี้แล้ว:

1. **PHP:** เวอร์ชัน `>= 8.2` (แนะนำ PHP 8.3)
2. **Composer:** สำหรับจัดการแพ็กเกจ PHP
3. **Node.js:** เวอร์ชัน `>= 18.x` หรือ `20.x+` และ **npm**
4. **Git:** สำหรับดึงโค้ด

> 💡 **แนะนำ:** หากใช้ Windows แนะนำให้ติดตั้ง **[Laravel Herd](https://herd.laravel.com/windows)** ซึ่งจะติดตั้งทั้ง PHP, Composer, Node.js ให้ครบในตัวเดียว ไม่ต้องตั้งค่า PATH เองให้ปวดหัว

---

## 🚀 ขั้นตอนการติดตั้งและรันโปรเจกต์ (Step-by-Step)

ทำตามขั้นตอนด้านล่างนี้ทีละคำสั่งใน **Terminal / PowerShell / Command Prompt**:

### ขั้นตอนที่ 1: ดึงโค้ดและเปิดโฟลเดอร์โปรเจกต์
```bash
git clone <URL_ของ_GIT_REPOSITORY>
cd db-pjbybank
```

---

### ขั้นตอนที่ 2: คัดลอกไฟล์ Environment (.env)
ทำการคัดลอกไฟล์ `.env.example` ให้เป็น `.env`:
* **บน Windows (PowerShell / CMD):**
  ```powershell
  copy .env.example .env
  ```
* **บน Mac / Linux / Git Bash:**
  ```bash
  cp .env.example .env
  ```

---

### ขั้นตอนที่ 3: ติดตั้ง Dependencies ฝั่ง PHP (Composer)
```bash
composer install
```
*(หากพบเตือนเรื่อง Platform ให้เพิ่ม `--ignore-platform-reqs` ได้)*

---

### ขั้นตอนที่ 4: ติดตั้ง Dependencies ฝั่ง JavaScript/CSS (Node.js)
```bash
npm install
```

---

### ขั้นตอนที่ 5: สร้าง Key ความปลอดภัยของระบบ (APP_KEY)
```bash
php artisan key:generate
```

---

### ขั้นตอนที่ 6: สร้างฐานข้อมูลและรัน Migration
1. ตรวจสอบว่ามีไฟล์ `database/database.sqlite` หรือไม่ หากไม่มี ให้สร้างไฟล์ว่างเปล่าขึ้นมา:
   * **Windows (PowerShell):**
     ```powershell
     New-Item -ItemType File -Path database/database.sqlite -Force
     ```
   * **Mac / Linux / Git Bash:**
     ```bash
     touch database/database.sqlite
     ```
2. รันคำสั่งสร้างตารางในฐานข้อมูล:
   ```bash
   php artisan migrate
   ```

---

### ขั้นตอนที่ 7: เชื่อมต่อโฟลเดอร์สำหรับเก็บรูปภาพ (Storage Link)
จำเป็นต้องรันคำสั่งนี้เพื่อให้ภาพโปสเตอร์ที่อัปโหลดแสดงผลได้:
```bash
php artisan storage:link
```

---

### ขั้นตอนที่ 8: Build สไตล์ CSS & สคริปต์หน้าเว็บ
รันคำสั่ง Compile ไฟล์ CSS / JS ให้พร้อมทำงาน:
```bash
npm run build
```
*(หรือหากกำลังแก้โค้ดหน้าเว็บอยู่ สามารถรัน `npm run dev` เพื่อให้รีเฟรชหน้าเว็บอัตโนมัติได้)*

---

### ขั้นตอนที่ 9: เริ่มรันเซิร์ฟเวอร์ (Start Server)
```bash
php artisan serve
```

เมื่อขึ้นข้อความ `Server running on [http://127.0.0.1:8000]` ให้เปิด Browser ไปที่:
👉 **[http://localhost:8000](http://localhost:8000)** หรือ **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 👑 วิธีสร้างบัญชี Admin (สำหรับทดสอบหลังบ้าน)

โดยเริ่มต้น ผู้ใช้ที่กดสมัครสมาชิก (Register) จะได้สถานะเป็นผู้ใช้ทั่วไป (`user`)  
หากต้องการทดสอบฟีเจอร์ของ **Admin** (อนุมัติหนัง, นำเข้า TMDB, จัดการหมวดหมู่/ผู้กำกับ):

1. สมัครสมาชิกผ่านหน้าเว็บตามปกติ เช่น อีเมล `admin@test.com`
2. เปิด Terminal แล้วพิมพ์คำสั่ง:
   ```bash
   php artisan tinker
   ```
3. พิมพ์โค้ดต่อไปนี้แล้วกด Enter:
   ```php
   $user = App\Models\User::where('email', 'admin@test.com')->first();
   $user->role = 'admin';
   $user->save();
   exit;
   ```
4. รีเฟรชหน้าเว็บ บัญชีดังกล่าวจะมีเมนูสำหรับ Admin ขึ้นมาทันที

---

## 🚨 คู่มือแก้ปัญหา "หากจอแดงต้องทำยังไง?" (Troubleshooting)

### 🔴 1. Error: `No application encryption key has been specified`
* **สาเหตุ:** ยังไม่ได้สุ่ม Key ความปลอดภัยลงในไฟล์ `.env`
* **วิธีแก้:** รันคำสั่ง:
  ```bash
  php artisan key:generate
  ```

---

### 🔴 2. Error: `Database file at path [...] does not exist`
* **สาเหตุ:** ไม่มีไฟล์ `database.sqlite` ในโฟลเดอร์ `database/`
* **วิธีแก้:**
  1. สร้างไฟล์ชื่อ `database.sqlite` ไว้ในโฟลเดอร์ `database/`
  2. รันคำสั่ง `php artisan migrate` ใหม่อีกครั้ง

---

### 🔴 3. Error: `Vite manifest not found at [...]` หรือหน้าเว็บไม่มี CSS / เละ
* **สาเหตุ:** ยังไม่ได้ build ไฟล์ CSS และ JS ของ Tailwind
* **วิธีแก้:** รันคำสั่ง:
  ```bash
  npm run build
  ```
  หรือเปิดอีก Terminal หนึ่งแล้วรัน `npm run dev` ทิ้งไว้

---

### 🔴 4. รูปภาพโปสเตอร์ที่อัปโหลดไม่ยอมแสดงผล (ขึ้นรูปแตก 404)
* **สาเหตุ:** ยังไม่ได้เชื่อม Symlink จาก storage ไปยัง public
* **วิธีแก้:** รันคำสั่ง:
  ```bash
  php artisan storage:link
  ```

---

### 🔴 5. Error: `could not find driver` หรือ `pdo_sqlite` หายไป
* **สาเหตุ:** ใน PHP ของเครื่องคุณยังไม่ได้เปิดการใช้งาน SQLite Extension
* **วิธีแก้:**
  1. เปิดไฟล์ `php.ini` ของเครื่อง (ถ้าใช้ Herd จะเปิดให้อัตโนมัติแล้ว)
  2. ค้นหาและลบเครื่องหมาย `;` ด้านหน้าบรรทัด:
     ```ini
     extension=pdo_sqlite
     extension=sqlite3
     ```
  3. บันทึกไฟล์แล้วรันเซิร์ฟเวอร์ใหม่

---

### 🔴 6. ค้นหาหนังจาก TMDB ไม่ได้ / ขึ้น Error เชื่อมต่อ API
* **สาเหตุ:** ไฟล์ `.env` ไม่มีค่า `TMDB_API_KEY`
* **วิธีแก้:** ตรวจสอบไฟล์ `.env` ว่ามีบรรทัดนี้หรือไม่:
  ```env
  TMDB_API_KEY=176ba27a57b132784892dc6b4c517753
  ```

---

### 🔴 7. Error: `Failed to listen on 127.0.0.1:8000 (reason: ...)` (Port ชน)
* **สาเหตุ:** มีโปรแกรมอื่นเปิดค้างอยู่ที่ Port 8000
* **วิธีแก้:** สั่งรันโดยระบุ Port อื่น เช่น:
  ```bash
  php artisan serve --port=8080
  ```
  แล้วเปิดเข้าเว็บที่ `http://localhost:8080` แทน

---

## 📁 โครงสร้างโปรเจกต์คร่าวๆ (Project Structure)
* `app/Http/Controllers/` - ตัวควบคุม Logic การทำงาน (Activity, Review, Reply, Collection, Admin)
* `app/Models/` - ฐานข้อมูลและ Eloquent Models (Activity, Review, Collection, Type, Director)
* `database/migrations/` - โครงสร้างตารางทั้งหมด
* `resources/views/` - หน้าจอ Blade Templates ทั้งหมด
* `routes/web.php` - เส้นทาง URL ทั้งหมดของระบบ
