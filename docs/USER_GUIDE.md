# Panduan Penggunaan Fitur KlikBan

Dokumen ini menjelaskan alur kerja dan cara menggunakan seluruh fitur yang tersedia di sistem kolaborasi tugas **KlikBan**.

---

# Daftar Fitur Utama

1. [Autentikasi & Login](#1-autentikasi--login)
2. [Manajemen Workspace (Ruang Kerja)](#2-manajemen-workspace-ruang-kerja)
3. [Manajemen Proyek](#3-manajemen-proyek)
4. [Papan Kanban (Kanban Board Task)](#4-papan-kanban-kanban-board-task)
5. [Log Aktivitas (Activity Log)](#5-log-aktivitas-activity-log)
6. [Dashboard Progres (Progress Overview)](#6-dashboard-progres-progress-overview)

---

## 1. Autentikasi & Login

Aplikasi menyediakan 3 metode akses:
- **Login Biasa**: Masuk dengan Email dan Password yang terdaftar.
- **Login Demo (Instant)**: Fitur praktis untuk mencoba aplikasi secara instan tanpa perlu mengetik kredensial (masuk langsung sebagai *Budi Santoso*).
- **Google OAuth Login**: Masuk menggunakan akun Google (membutuhkan konfigurasi OAuth Google).
- **Registrasi Akun Baru**: Pengguna baru dapat mendaftar akun dengan mengisi Nama, Email, dan Password.

---

## 2. Manajemen Workspace (Ruang Kerja)

Workspace merupakan wadah utama tempat tim berkolaborasi.

# a. Berpindah Workspace
- Klik dropdown nama Workspace di bagian kiri atas / header.
- Pilih workspace yang ingin diakses.

# b. Membuat Workspace Baru
- Klik **+ Tambah Workspace**.
- Isi **Nama Workspace** dan **Deskripsi**.
- Pembuat otomatis menjadi **Owner** workspace tersebut.

# c. Mengundang & Menambahkan Anggota
- **Via Kode Undangan / Link**: Setiap workspace memiliki **Kode Undangan** unik (contoh: `WUNAWAN1`). Anggota lain dapat masuk menggunakan menu **Gabung Workspace** dengan menginput kode tersebut atau mengklik link tautan undangan.
- **Via Form Tambah Anggota (Admin/Owner)**: Admin/Owner dapat memasukkan email anggota baru secara langsung.

# d. Keluar Sendiri dari Workspace (Leave Workspace)
- Jika anggota atau admin sudah tidak membutuhkan ruang kerja tersebut, mereka dapat keluar secara mandiri.
- Tombol **Keluar dari Workspace** tersedia di:
  1. Menu **Kelola Anggota** pada baris profil pengguna atau di bagian bawah modal.
  2. Modal **Ganti / Kelola Workspace** pada kartu workspace terkait.
  3. Sidebar pada panel peran anggota.
- *Catatan*: Pemilik utama (*Owner*) tidak dapat keluar dari workspace miliknya kecuali kepemilikan dialihkan atau workspace dihapus.

# e. Mengeluarkan Anggota oleh Admin (Remove Member)
- Admin atau Pemilik Utama (*Owner*) memiliki hak untuk mengeluarkan anggota tim yang sudah tidak aktif atau tidak relevan.
- Buka modal **Kelola Anggota**, lalu klik tombol **Keluarkan** di samping nama anggota yang dituju.
- Setiap tugas yang sebelumnya ditugaskan ke anggota tersebut akan otomatis di-unassign dan tercatat dalam Log Aktivitas (*Activity Log*).

---

## 3. Manajemen Proyek

Setiap Workspace dapat memiliki beberapa Proyek.

- **Membuat Proyek**: Klik tombol **+ Proyek Baru** pada sidebar/header.
- **Berpindah Proyek**: Pilih proyek aktif melalui menu navigasi proyek.
- **Menghapus Proyek**: Proyek dapat dihapus oleh Admin/Owner workspace.

---

## 4. Papan Kanban (Kanban Board Task)

Papan Kanban adalah pusat pengelolaan tugas harian tim.

# a. Kolom Status Tugas
Tugas terbagi ke dalam 4 kolom status:
1. **Belum Dimulai (To Do)**
2. **Sedang Dikerjakan (In Progress)**
3. **Dalam Peninjauan (Review)**
4. **Selesai (Done)**

# b. Tingkat Prioritas Tugas
Tugas dikelompokkan dengan label prioritas dan warna khas:
- 🔴 **Didahulukan** (*High Priority*) - Badge Merah
- 🟡 **Perlu Diperhatikan** (*Medium Priority*) - Badge Oranye/Kuning
- 🔵 **Eksternal** (*External Dependency*) - Badge Biru
- 🟢 **Biasa** (*Low/Normal Priority*) - Badge Hijau

# c. Menambah & Memperbarui Tugas
- **Tambah Tugas**: Klik tombol **+ Tambah Tugas** pada kolom yang diinginkan. Isi Judul, Deskripsi, Prioritas, Tenggat Waktu (*Due Date*), dan Penanggung Jawab (*Assignee*).
- **Ubah Status**: Geser/pindahkan tugas atau ubah status melalui menu cepat pada kartu tugas.
- **Ubah Prioritas**: Klik pemilih prioritas pada tugas untuk menyesuaikan tingkat urgensi.
- **Hapus Tugas**: Klik opsi hapus pada kartu tugas jika tugas sudah tidak relevan.

---

## 5. Log Aktivitas (Activity Log)

Menu **Activity Log** mencatat jejak audit (*audit trail*) setiap perubahan yang terjadi di dalam workspace:
- Penambahan tugas baru
- Perubahan status & prioritas tugas
- Anggota baru yang bergabung
- Waktu dan nama pengguna yang melakukan aksi

Log ditampilkan secara kronologis dengan penanda warna badge aksi (*NEW TASK*, *STATUS UPDATE*, *MEMBER JOINED*, *PRIORITY CHANGE*).

---

## 6. Dashboard Progres (Progress Overview)

Menu **Progress Overview** menampilkan ringkasan visual kesiapan dan kesehatan proyek:
- Persentase penyelesaian tugas (*Completion Rate*).
- Diagram/grafik distribusi status tugas.
- Rincian jumlah tugas berdasarkan prioritas dan anggota tim.
