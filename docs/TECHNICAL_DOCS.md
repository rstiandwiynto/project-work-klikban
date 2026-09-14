# Dokumentasi Teknis & Arsitektur (KlikBan)

Dokumen ini berisi penjelasan teknis mengenai struktur aplikasi, skema database, rute (routes), dan arsitektur kode **KlikBan**.

---

## 1. Skema Database & Relasi

Aplikasi ini menggunakan skema relasional dengan tabel-tabel utama berikut:

# a. `users`
Menyimpan data pengguna.
- `id`: PK (BigInt, Auto Increment)
- `name`: String
- `email`: String (Unique)
- `password`: String (Hashed)
- `avatar`: String (Nullable)
- `google_id`: String (Nullable, untuk OAuth Google)

# b. `workspaces`
Menyimpan data ruang kerja kolaboratif.
- `id`: PK
- `name`: String
- `slug`: String
- `description`: Text (Nullable)
- `owner_id`: FK -> `users.id`
- `invite_code`: String (Unique, 8 karakter)

# c. `workspace_user` (Pivot Table)
Hubungan Many-to-Many antara User dan Workspace beserta Role.
- `workspace_id`: FK -> `workspaces.id`
- `user_id`: FK -> `users.id`
- `role`: Enum (`owner`, `admin`, `member`)

# d. `projects`
Menyimpan data proyek di dalam workspace.
- `id`: PK
- `workspace_id`: FK -> `workspaces.id`
- `name`: String
- `slug`: String
- `description`: Text (Nullable)
- `created_by`: FK -> `users.id`

# e. `tasks`
Menyimpan tugas Kanban.
- `id`: PK
- `workspace_id`: FK -> `workspaces.id`
- `project_id`: FK -> `projects.id`
- `title`: String
- `description`: Text (Nullable)
- `priority`: Enum (`didahulukan`, `perlu_diperhatikan`, `eksternal`, `biasa`)
- `status`: Enum (`todo`, `in_progress`, `review`, `done`)
- `due_date`: Date (Nullable)
- `assignee_name`: String (Nullable)
- `assigned_to`: FK -> `users.id` (Nullable)
- `order`: Integer (Default 0)
- `created_by`: FK -> `users.id`

# f. `activity_logs`
Catatan aktivitas workspace.
- `id`: PK
- `workspace_id`: FK -> `workspaces.id`
- `project_id`: FK -> `projects.id` (Nullable)
- `user_id`: FK -> `users.id` (Nullable)
- `user_name`: String
- `action_type`: String (`new_task`, `status_update`, `priority_change`, `member_joined`, dll)
- `badge_text`: String
- `badge_color`: String (`green`, `blue`, `orange`, `red`, dll)
- `description`: Text

---

## 2. Daftar Route Utama (`routes/web.php`)

| Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/` | `HomeController@index` | Landing Page Utama |
| `GET` | `/login` | `AuthController@showLogin` | Halaman Form Login |
| `POST` | `/login` | `AuthController@login` | Proses Login |
| `GET` | `/register` | `AuthController@showRegister` | Halaman Form Registrasi |
| `POST` | `/register` | `AuthController@register` | Proses Registrasi |
| `GET` | `/demo-login` | `AuthController@demoLogin` | Auto-login Akun Demo (Budi Santoso) |
| `GET` | `/auth/google/redirect` | `AuthController@googleRedirect` | Redirect ke Google OAuth |
| `GET` | `/auth/google/callback` | `AuthController@googleCallback` | Callback Login Google |
| `GET` | `/board` | `TaskController@board` | Tampilan Utama Papan Kanban |
| `POST` | `/workspaces` | `WorkspaceController@store` | Membuat Workspace Baru |
| `GET` | `/workspaces/switch/{id}` | `WorkspaceController@switchWorkspace` | Berpindah Workspace Aktif |
| `POST` | `/workspaces/join` | `WorkspaceController@join` | Gabung Workspace via Kode |
| `GET` | `/workspaces/join-link/{code}` | `WorkspaceController@joinLink` | Gabung Workspace via Direct Link |
| `POST` | `/projects` | `ProjectController@store` | Membuat Proyek Baru |
| `GET` | `/projects/switch/{id}` | `ProjectController@switchProject` | Berpindah Proyek Aktif |
| `POST` | `/tasks` | `TaskController@store` | Membuat Tugas Baru |
| `PUT` | `/tasks/{id}` | `TaskController@update` | Mengubah Data Tugas |
| `POST` | `/tasks/{id}/status` | `TaskController@updateStatus` | Memperbarui Status Tugas |
| `POST` | `/tasks/{id}/priority` | `TaskController@updatePriority` | Memperbarui Prioritas Tugas |
| `DELETE` | `/tasks/{id}` | `TaskController@destroy` | Menghapus Tugas |
| `GET` | `/activity-log` | `ActivityLogController@index` | Halaman Log Aktivitas |
| `GET` | `/progress` | `ProgressController@index` | Halaman Dashboard Progres |

---

## 3. Struktur Direktori Utama

```
project-work/
├── app/
│   ├── Http/Controllers/    # Controller penangan logika bisnis
│   └── Models/              # Model Eloquent (User, Workspace, Project, Task, ActivityLog)
├── database/
│   ├── migrations/          # File migrasi database
│   └── seeders/             # DatabaseSeeder (Data awal demo)
├── docs/                    # Folder Dokumentasi Project
│   ├── INSTALLATION.md      # Panduan Instalasi
│   ├── USER_GUIDE.md        # Panduan Penggunaan Fitur
│   └── TECHNICAL_DOCS.md    # Dokumentasi Arsitektur & Database
├── public/                  # Public asset & index.php
├── resources/
│   ├── css/app.css          # Styling kustom & Tailwind CSS
│   └── views/               # Template Blade (layout, board, auth, dll)
├── routes/
│   └── web.php              # Definisi Rute Aplikasi
├── tailwind.config.js       # Konfigurasi Tailwind CSS
└── vite.config.js           # Konfigurasi Vite Bundler
```
