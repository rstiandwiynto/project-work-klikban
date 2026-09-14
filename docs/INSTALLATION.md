# Panduan Instalasi & Konfigurasi (KlikBan)

Dokumen ini berisi panduan langkah demi langkah untuk menginstal, mengonfigurasi, dan menjalankan aplikasi KlikBan (Laravel Task Management System) di lingkungan lokal (seperti Laragon, XAMPP, atau PHP CLI native).

---

# Prasyarat Sistem

Sebelum menginstal, pastikan perangkat Anda sudah terpasang:
- PHP: Versi `^8.2` atau lebih baru (dengan ekstensi `pdo`, `mbstring`, `openssl`, `curl`, `sqlite3` jika menggunakan SQLite).
- Composer: Versi `^2.0` atau lebih baru.
- Node.js: Versi `18.x` atau `20.x` (disertai `npm`).
- Database Server: MySQL / MariaDB (misal via Laragon / XAMPP) atau SQLite.

---

# Langkah-Langkah Instalasi

# 1. Clone / Siapkan Project
Jika Anda mendownload repository ini, pastikan seluruh file sudah berada di direktori web server (contoh di Laragon: `C:\laragon\www\project-work`).

# 2. Install Dependency PHP (Composer)
Buka terminal di root folder project, lalu jalankan:
```bash
composer install
```

# 3. Install Dependency Frontend (NPM)
Jalankan perintah berikut untuk menginstal package Tailwind CSS, Vite, dan dependency terkait:
```bash
npm install
```

# 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env` jika belum ada:
```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi database dan aplikasi:

# a. Konfigurasi Database (MySQL)
```env
APP_NAME=KlikBan
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost/project-work/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_work
DB_USERNAME=root
DB_PASSWORD=
```
*Catatan: Pastikan database `project_work` sudah dibuat di phpMyAdmin / MySQL sebelum menjalankan migrasi.*

# b. Konfigurasi Google OAuth (Opsional untuk Login Google)
```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost/project-work/public/auth/google/callback
```

# 5. Generate Application Key
Jalankan perintah berikut untuk menghasilkan `APP_KEY` Laravel:
```bash
php artisan key:generate
```

# 6. Migrasi & Seeder Database
Jalankan migrasi tabel beserta seeder data awal (User demo, Workspace `PT WUNAWAN`, Project, Task, dan Activity Log):
```bash
php artisan migrate:fresh --seed
```

---

## Menjalankan Aplikasi

Jalankan aplikasi menggunakan salah satu dari metode di bawah ini:

## Metode A: Menggunakan `npm run dev` / `composer run dev` (Rekomendasi)
Jalankan dev server secara simultan (PHP Artisan Serve + Vite Asset Compiler):
```bash
npm run dev
```
atau
```bash
composer run dev
```

Akses aplikasi di browser pada alamat:
`http://127.0.0.1:8000` atau `http://localhost:8000`

## Metode B: Menggunakan Web Server Laragon / Apache
Jika menggunakan Laragon, pastikan Apache dan MySQL aktif.
Akses aplikasi melalui URL Laragon:
`http://localhost/project-work/public`

Untuk mengompilasi asset CSS & JS saat menggunakan Laragon:
```bash
npm run dev
# atau untuk build produksi:
npm run build
```

---

## Akun Demo bawaan (Seeder)

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda dapat langsung menggunakan akun demo berikut:

| Nama User | Email | Password | Role Workspace |
| **Budi Santoso** | `budi@klikban.com` | `password` | Owner |
| **Siti Aminah** | `siti@klikban.com` | `password` | Admin |
| **Rizky Pratama** | `rizky@klikban.com` | `password` | Member |
| **Dewi Lestari** | `dewi@klikban.com` | `password` | Member |

---