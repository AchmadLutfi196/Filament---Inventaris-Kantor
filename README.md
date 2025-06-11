# 📦 Inventaris Kantor dengan Laravel Filament

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)

Aplikasi manajemen inventaris kantor berbasis web yang dibangun menggunakan Laravel dan Filament Admin Panel dengan arsitektur multi-domain.

## 📝 Deskripsi

Aplikasi ini dikembangkan untuk membantu pengelolaan barang inventaris kantor, termasuk:
- 📥 Pendataan barang masuk
- 📤 Peminjaman barang
- 🔄 Pengembalian barang
- 🏷️ Pengelolaan kategori barang
- 👥 Manajemen pengguna dengan berbagai peran
- 💬 Sistem chat real-time antara user dan admin

## 🏗️ Arsitektur Multi-Domain

Aplikasi ini menggunakan arsitektur multi-domain untuk memisahkan antarmuka user dan admin:

- **User Domain:** `user.inventaris.local` - Frontend untuk pengguna
- **Admin Domain:** `admin.inventaris.local:8080` - Panel admin untuk administrator

## ⚙️ Persyaratan Sistem

### Untuk Instalasi Docker (Recommended)
- Docker Desktop
- PowerShell (Windows)
- 4GB RAM minimum
- 10GB storage space

### Untuk Instalasi Manual
- PHP 8.2 atau lebih baru
- Composer
- Node.js dan NPM
- MySQL (database yang digunakan dalam proyek ini)
- Web server (Apache atau Nginx)

## 🐳 Instalasi dengan Docker (Recommended)

### 1. Clone Repositori

```bash
git clone https://github.com/Hritss28/Filament---Inventaris-Kantor.git inventaris-kantor
cd inventaris-kantor
```

### 2. Setup Multi-Domain Environment

```bat
# Run setup script (akan menghandle semua konfigurasi)
setup-docker.bat
```

### 3. Tambahkan Hosts Entries (Administrator Required)

```powershell
# Jalankan PowerShell sebagai Administrator
PowerShell -ExecutionPolicy Bypass -File add-hosts.ps1
```

### 4. Akses Aplikasi

- **User Frontend:** http://user.inventaris.local
- **Admin Panel:** http://admin.inventaris.local:8080

### 5. Management Commands

```bat
# Start services
start-docker.bat

# Stop services
stop-docker.bat

# View logs
docker-compose logs -f

# Rebuild containers
docker-compose build --no-cache
docker-compose up -d --force-recreate
```

## 🔧 Instalasi Manual

### 1. Clone Repositori

```bash
git clone https://github.com/Hritss28/Filament---Inventaris-Kantor.git inventaris-kantor
cd inventaris-kantor
```

### 2. Instal Dependensi PHP

```bash
composer install
```

### 3. Konfigurasi Lingkungan

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Edit file .env

Hapus isi env dan ganti ke isi yang saya kasih di bawah ini, pengaturan pada file `.env`:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:k4jbt6wttNOWeJLq24uvtfQvkOcb5c9SDnouBNoazfQ=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_kantor
DB_USERNAME=root
DB_PASSWORD=

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

```

### 5. Buat Database MySQL

Buat database MySQL baru dengan nama yang sesuai dengan konfigurasi di file `.env`:

```bash
mysql -u root -p
```

Setelah masuk ke MySQL, buat database:

```sql
CREATE DATABASE inventaris_kantor;
EXIT;
```

### 6. Migrasi Database

```bash
php artisan migrate
```

### 7. Tambahkan Data Awal (Opsional)

```bash
php artisan db:seed
```

### 8. Instal Dependensi Frontend

```bash
npm install
npm run build
```

### 9. Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi untuk "User Biasa" akan berjalan di `http://localhost:8000`
Aplikasi untuk "Admin" akan berjalan di `http://localhost:8000/admin`

### 10. Akses Panel Admin Filament

Buka browser dan akses:

```
http://localhost:8000/admin
Email : admin@gmail.com
Password : admin123
```

Jika ingin bikin akun admin sendiri, buat terlebih dahulu dengan perintah:

```bash
php artisan make:filament-user
```

## 🏗️ Struktur Aplikasi

Aplikasi ini terdiri dari beberapa modul utama:

- **📋 Manajemen Barang**: Pengelolaan data barang inventaris
- **🏷️ Kategori**: Pengaturan kategori untuk barang
- **📤 Peminjaman**: Pencatatan peminjaman dan pengembalian barang
- **👥 Pengguna**: Manajemen pengguna aplikasi dengan peran berbeda (admin, staff, dll)

## ✨ Fitur Utama

- 📊 Dashboard dengan ringkasan data inventaris
- 🖼️ Manajemen barang dengan upload foto
- 📝 Sistem peminjaman dan pengembalian barang
- 🔍 Pencarian dan filter data
- 🔐 Manajemen pengguna dengan sistem peran (role-based access control)

## 💻 Pengembangan Lebih Lanjut

Untuk pengembangan lebih lanjut, Anda dapat menjalankan aplikasi dalam mode pengembangan:

```bash
npm run dev
```

atau menggunakan perintah gabungan yang telah dikonfigurasi:

```bash
composer dev
```

## 🛠️ Teknologi yang Digunakan

- ![Laravel](https://img.shields.io/badge/Laravel-v12-FF2D20)
- ![Filament](https://img.shields.io/badge/Filament-v3.3-6875F5)
- ![Breeze](https://img.shields.io/badge/Laravel_Breeze-v2.3-6875F5)
- ![MySQL](https://img.shields.io/badge/MySQL-8.0-blue)


⏱️ Terakhir diperbarui: 2025-05-30
👤 Dibuat oleh: [Hritss28](https://github.com/Hritss28)
