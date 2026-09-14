# 📌 KlikBan - Sistem Manajemen Tugas & Kolaborasi Tim

**KlikBan** adalah aplikasi web berbasis **Laravel 12** & **Tailwind CSS** yang dirancang untuk membantu tim mengelola tugas harian (*task management*), ruang kerja (*workspace*), proyek, dan log aktivitas secara real-time dengan tampilan Papan Kanban (*Kanban Board*) yang modern dan intuitif.

---

## ✨ Fitur Utama

- 📋 **Papan Kanban Interaktif**: Kelola tugas berdasarkan kolom status (*To Do*, *In Progress*, *Review*, *Done*) dan tingkat prioritas (*Didahulukan*, *Perlu Diperhatikan*, *Eksternal*, *Biasa*).
- 🏢 **Multi-Workspace & Proyek**: Buat dan kelola beberapa ruang kerja kolaboratif serta proyek terpisah dalam satu akun.
- 👥 **Sistem Undangan Anggota**: Undang anggota tim menggunakan kode unik workspace atau tautan (*shareable invite link*).
- 🔐 **Autentikasi Fleksibel**: Menerima Login biasa, Registrasi, **Google OAuth Login**, dan **Demo Login 1-Klik** tanpa perlu registrasi ulang.
- 📜 **Log Aktivitas (Audit Trail)**: Melacak setiap aksi anggota tim (penambahan tugas, ubah status, undang anggota, dll) secara transparan.
- 📊 **Progress Dashboard**: Ringkasan kesiapan proyek, persentase penyelesaian tugas, dan statistik kerja tim.

---

## 🚀 Cara Cepat Menjalankan Proyek (Quick Start)

### 1. Prasyarat
- **PHP** `>= 8.2`
- **Composer** `>= 2.0`
- **Node.js** `>= 18.x` & **npm**
- **MySQL** / **SQLite**

### 2. Langkah Instalasi

```bash
# 1. Install dependency PHP & Node.js
composer install
npm install

# 2. Salin environment file
cp .env.example .env

# 3. Generate APP_KEY Laravel
php artisan key:generate

# 4. Konfigurasi database di file .env lalu jalankan migrasi & seeder
php artisan migrate:fresh --seed

# 5. Jalankan server lokal & Vite
npm run dev
# atau: composer run dev
```

Buka browser Anda di `http://127.0.0.1:8000` atau `http://localhost/project-work/public`.

---

## 🔑 Akun Demo (Bawaan Seeder)

Untuk mencoba aplikasi secara langsung, Anda dapat mengklik tombol **"Demo Login"** di halaman login atau menggunakan kredensial berikut:

| Email | Password | Role Workspace |
|---|---|---|
| `budi@klikban.com` | `password` | Owner (`PT WUNAWAN`) |
| `siti@klikban.com` | `password` | Admin (`PT WUNAWAN`) |
| `rizky@klikban.com` | `password` | Member (`PT WUNAWAN`) |
| `dewi@klikban.com` | `password` | Member (`PT WUNAWAN`) |

---

## 📚 Folder Dokumentasi (Documentation Hub)

Dokumentasi lengkap proyek ini tersimpan di dalam folder [`docs/`](./docs/):

- 🛠️ [**Panduan Instalasi Lengkap (`docs/INSTALLATION.md`)**](./docs/INSTALLATION.md): Panduan setup detail untuk Laragon, XAMPP, environment variables, dan Google OAuth.
- 📖 [**Panduan Penggunaan Fitur (`docs/USER_GUIDE.md`)**](./docs/USER_GUIDE.md): Panduan lengkap penggunaan Papan Kanban, Workspace, Invitation Code, Activity Log, dan Progress Overview.
- 🏗️ [**Dokumentasi Teknis (`docs/TECHNICAL_DOCS.md`)**](./docs/TECHNICAL_DOCS.md): Penjelasan skema database, relasi tabel, rute (*routes*), dan arsitektur kode.

---

## 🛠️ Teknologi yang Digunakan

- **Backend Framework**: Laravel 12 (PHP 8.2+)
- **Frontend Stack**: Blade Templating, Tailwind CSS v3/v4, Vite Bundler
- **Authentication**: Laravel Session Auth & Laravel Socialite (Google OAuth)
- **Database**: MySQL / SQLite
- **Tooling**: Composer, NPM, Concurrently
