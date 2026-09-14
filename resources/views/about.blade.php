<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Aplikasi & Petunjuk Penggunaan — KlikBan</title>
    <meta name="description" content="Panduan lengkap petunjuk penggunaan aplikasi KlikBan: Mulai dari autentikasi, manajemen workspace, proyek, papan Kanban, log aktivitas, hingga dashboard progres.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Pre-compiled & Minified Production CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
</head>
<body class="antialiased bg-bg text-textmain">

<!-- ===================== HEADER ===================== -->
<header class="sticky top-0 z-50 bg-surface/90 backdrop-blur border-b border-borderc">
  <div class="max-w-[1280px] mx-auto px-6 lg:px-8">
    <div class="h-16 flex items-center justify-between">
      <!-- Logo -->
      <a href="{{ route('home') }}" class="flex items-center gap-2 focus-ring rounded">
        <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="4" y="8" width="32" height="24" rx="4" stroke="#3B82F6" stroke-width="2.5" fill="white"/>
          <line x1="4" y1="15" x2="36" y2="15" stroke="#3B82F6" stroke-width="2"/>
          <line x1="10" y1="19" x2="24" y2="19" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
          <line x1="10" y1="24" x2="18" y2="24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
          <path d="M22 20L31 29L26.5 30.5L30 37L27 38.5L23.5 32L19.5 35.5L22 20Z" fill="#3B82F6" stroke="white" stroke-width="1.5"/>
        </svg>
        <span class="text-[19px] font-extrabold tracking-tight text-primary">KlikBan</span>
      </a>

      <!-- Desktop nav -->
      <nav class="hidden md:flex items-center gap-8 h-16">
        <a href="{{ route('home') }}" class="text-body-md font-medium text-textsub hover:text-primary transition-colors">Home</a>
        <a href="{{ route('home') }}#showcase" class="text-body-md font-medium text-textsub hover:text-primary transition-colors">Fitur Kami</a>
      </nav>

      <div class="hidden md:flex items-center gap-3">
        @auth
          <a href="{{ route('board') }}" class="focus-ring bg-primary hover:bg-blue-600 transition-colors text-white text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Buka Workspace</a>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="focus-ring text-body-md font-medium text-accent hover:text-red-700 px-3 py-2 transition flex items-center gap-1.5" title="Keluar">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              Keluar
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="focus-ring text-body-md font-medium text-textsub hover:text-textmain px-3 py-2 transition">Masuk</a>
          <a href="{{ route('register') }}" class="focus-ring bg-primary hover:bg-blue-600 transition-colors text-white text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Daftar Gratis</a>
        @endauth
      </div>

      <!-- Mobile toggle -->
      <button id="menuBtn" class="md:hidden focus-ring w-9 h-9 flex items-center justify-center text-textmain" aria-label="Buka menu" aria-expanded="false">
        <svg id="iconOpen" width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        <svg id="iconClose" width="22" height="22" viewBox="0 0 24 24" fill="none" class="hidden"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
      </button>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="mobile-menu md:hidden overflow-hidden max-h-0 opacity-0">
      <div class="pb-5 flex flex-col gap-4 border-t border-borderc pt-4">
        <a href="{{ route('home') }}" class="text-body-md font-medium text-textsub hover:text-primary">Home</a>
        <a href="{{ route('home') }}#showcase" class="text-body-md font-medium text-textsub hover:text-primary">Fitur Kami</a>
        @auth
          <a href="{{ route('board') }}" class="focus-ring w-full text-center bg-primary text-white text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Buka Workspace</a>
        @else
          <a href="{{ route('register') }}" class="focus-ring w-full text-center bg-primary text-white text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Daftar Gratis</a>
          <a href="{{ route('login') }}" class="focus-ring w-full text-center bg-surface border border-borderc text-textmain text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Masuk</a>
        @endauth
      </div>
    </div>
  </div>
</header>

<!-- ===================== HERO BANNER ===================== -->
<section class="relative bg-surface border-b border-borderc py-12 md:py-16 overflow-hidden">
  <div class="grid-fade absolute inset-0 pointer-events-none opacity-40"></div>
  <div class="relative max-w-[1280px] mx-auto px-6 lg:px-8">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-primary text-label-sm uppercase tracking-wider px-3 py-1 rounded-[4px] mb-4">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Pusat Bantuan & Panduan Resmi
      </div>
      <h1 class="text-[28px] sm:text-[38px] lg:text-[44px] font-extrabold text-textmain leading-tight">
        Tentang Aplikasi & <span class="text-primary">Petunjuk Penggunaan</span>
      </h1>
      <p class="mt-4 text-body-md sm:text-[15px] text-textsub leading-relaxed">
        Selamat datang di dokumentasi interaktif <strong>KlikBan</strong>. Pelajari cara mengelola tugas tim, mengatur workspace kolaboratif, memperbarui status papan Kanban, dan memonitor produktivitas secara terstruktur.
      </p>

      <!-- Quick Action Buttons -->
      <div class="mt-6 flex flex-wrap gap-3">
        <a href="#panduan" class="inline-flex items-center gap-2 bg-primary text-white text-[13px] font-bold px-4 py-2.5 rounded-[4px] hover:bg-blue-600 transition-colors">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M19 14l-7 7m0 0l-7-7m7 7V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Mulai Baca Petunjuk
        </a>
        <a href="#tentang-aplikasi" class="inline-flex items-center gap-2 bg-bg border border-borderc text-textmain text-[13px] font-semibold px-4 py-2.5 rounded-[4px] hover:bg-surface transition-colors">
          Sekilas Tentang KlikBan
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ===================== MAIN CONTENT & SIDEBAR ===================== -->
<main class="max-w-[1280px] mx-auto px-6 lg:px-8 py-10 md:py-14">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

    <!-- Sticky Desktop Table of Contents (TOC) -->
    <aside class="lg:col-span-3">
      <div class="sticky top-24 bg-surface border border-borderc rounded-[4px] p-5">
        <div class="flex items-center gap-2 pb-3 mb-3 border-b border-borderc">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-primary"><path d="M4 6h16M4 12h10M4 18h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          <span class="text-[13px] font-bold uppercase tracking-wider text-textmain">Daftar Isi</span>
        </div>
        <nav class="space-y-1.5 text-[13px]">
          <a href="#tentang-aplikasi" class="block py-1.5 px-2 rounded text-textsub hover:text-primary hover:bg-blue-50/60 font-medium transition-colors">1. Tentang KlikBan</a>
          <a href="#autentikasi" class="block py-1.5 px-2 rounded text-textsub hover:text-primary hover:bg-blue-50/60 font-medium transition-colors">2. Autentikasi & Login</a>
          <a href="#manajemen-workspace" class="block py-1.5 px-2 rounded text-textsub hover:text-primary hover:bg-blue-50/60 font-medium transition-colors">3. Manajemen Workspace</a>
          <a href="#manajemen-proyek" class="block py-1.5 px-2 rounded text-textsub hover:text-primary hover:bg-blue-50/60 font-medium transition-colors">4. Manajemen Proyek</a>
          <a href="#papan-kanban" class="block py-1.5 px-2 rounded text-textsub hover:text-primary hover:bg-blue-50/60 font-medium transition-colors">5. Papan Kanban & Tugas</a>
          <a href="#log-aktivitas" class="block py-1.5 px-2 rounded text-textsub hover:text-primary hover:bg-blue-50/60 font-medium transition-colors">6. Log Aktivitas (Audit)</a>
          <a href="#dashboard-progres" class="block py-1.5 px-2 rounded text-textsub hover:text-primary hover:bg-blue-50/60 font-medium transition-colors">7. Dashboard Progres</a>
          <a href="#faq" class="block py-1.5 px-2 rounded text-textsub hover:text-primary hover:bg-blue-50/60 font-medium transition-colors">8. Tanya Jawab (FAQ)</a>
        </nav>

        <div class="mt-6 pt-4 border-t border-borderc bg-bg -mx-5 -mb-5 p-4 rounded-b-[4px]">
          <p class="text-[11px] font-semibold text-textsub">Butuh mencoba langsung?</p>
          <a href="{{ route('demo.login') }}" class="mt-2 block text-center w-full bg-primary hover:bg-blue-600 text-white text-[12px] font-bold py-1.5 px-3 rounded-[4px] transition-colors">
            Coba Akun Demo
          </a>
        </div>
      </div>
    </aside>

    <!-- Main Text Content Area -->
    <article class="lg:col-span-9 space-y-12" id="panduan">

      <!-- ================= SECTION 1: TENTANG APLIKASI ================= -->
      <section id="tentang-aplikasi" class="bg-surface border border-borderc rounded-[4px] p-6 sm:p-8 scroll-mt-24 shadow-sm">
        <div class="flex items-center gap-2 text-primary font-bold text-[12px] uppercase tracking-wider mb-2">
          <span>Bagian 01</span>
          <span>&bull;</span>
          <span>Profil Platform</span>
        </div>
        <h2 class="text-[24px] sm:text-[28px] font-extrabold text-textmain">1. Tentang Aplikasi KlikBan</h2>
        <p class="mt-3 text-body-md text-textsub leading-relaxed">
          <strong>KlikBan</strong> adalah platform manajemen tugas kolaboratif modern berbasis web yang mengadopsi metodologi Kanban dan pendekatan desain antarmuka <em>Velocity Tasking</em>. Aplikasi ini dirancang untuk menghapus beban kognitif pengguna melalui penyajian visual yang bersih, navigasi instan, serta pembaruan status tugas 1-klik.
        </p>

        <div class="mt-6 grid sm:grid-cols-3 gap-4">
          <div class="p-4 bg-bg border border-borderc rounded-[4px]">
            <div class="w-8 h-8 rounded-[4px] bg-blue-50 text-primary flex items-center justify-center font-bold mb-3">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M13 2L3 14h7l-1 8 11-14h-8l1-6z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h3 class="font-bold text-[15px] text-textmain">Velocity Tasking</h3>
            <p class="mt-1 text-[12.5px] text-textsub">Eksekusi tugas cepat tanpa dialog rumit, peralihan workspace kilat, dan interaksi responsif.</p>
          </div>

          <div class="p-4 bg-bg border border-borderc rounded-[4px]">
            <div class="w-8 h-8 rounded-[4px] bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold mb-3">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h3 class="font-bold text-[15px] text-textmain">Tonal Layering</h3>
            <p class="mt-1 text-[12.5px] text-textsub">Hirarki visual jelas yang membedakan kartu tugas, indikator prioritas, dan data pendukung.</p>
          </div>

          <div class="p-4 bg-bg border border-borderc rounded-[4px]">
            <div class="w-8 h-8 rounded-[4px] bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold mb-3">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="currentColor" stroke-width="2"/></svg>
            </div>
            <h3 class="font-bold text-[15px] text-textmain">Multi-Workspace</h3>
            <p class="mt-1 text-[12.5px] text-textsub">Satu akun untuk banyak ruang kerja tim terpisah dengan kode undangan instan.</p>
          </div>
        </div>
      </section>

      <!-- ================= SECTION 2: AUTENTIKASI ================= -->
      <section id="autentikasi" class="bg-surface border border-borderc rounded-[4px] p-6 sm:p-8 scroll-mt-24 shadow-sm">
        <div class="flex items-center gap-2 text-primary font-bold text-[12px] uppercase tracking-wider mb-2">
          <span>Bagian 02</span>
          <span>&bull;</span>
          <span>Akses Pengguna</span>
        </div>
        <h2 class="text-[24px] sm:text-[28px] font-extrabold text-textmain">2. Autentikasi & Login</h2>
        <p class="mt-3 text-body-md text-textsub leading-relaxed">
          KlikBan menyediakan beberapa opsi masuk yang fleksibel sesuai kebutuhan pengujian maupun operasional sehari-hari:
        </p>

        <div class="mt-5 space-y-4">
          <div class="p-4 border-l-4 border-l-primary bg-bg rounded-r-[4px]">
            <h3 class="font-bold text-[15px] text-textmain flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-primary text-white text-[11px] flex items-center justify-center font-bold">A</span>
              Login Email & Password Terdaftar
            </h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Buka menu <strong>Masuk</strong>, ketik alamat email dan kata sandi Anda, lalu klik tombol <em>Masuk ke Workspace</em>.
            </p>
          </div>

          <div class="p-4 border-l-4 border-l-emerald-500 bg-bg rounded-r-[4px]">
            <h3 class="font-bold text-[15px] text-textmain flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-emerald-600 text-white text-[11px] flex items-center justify-center font-bold">B</span>
              Login Demo Instan (Budi Santoso)
            </h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Khusus penguji atau demonstrasi: Anda dapat mengklik tombol <strong>Coba Akun Demo</strong> di halaman Login untuk langsung masuk sebagai pengguna contoh tanpa perlu mengetik email dan password.
            </p>
          </div>

          <div class="p-4 border-l-4 border-l-amber-500 bg-bg rounded-r-[4px]">
            <h3 class="font-bold text-[15px] text-textmain flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-amber-500 text-white text-[11px] flex items-center justify-center font-bold">C</span>
              Pendaftaran Akun Baru
            </h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Pilih menu <strong>Daftar Gratis</strong>, lengkapi formulir Nama Lengkap, Alamat Email, dan Password minimal 8 karakter. Setelah mendaftar, sistem otomatis membuatkan <em>Workspace Default</em> untuk Anda.
            </p>
          </div>

          <div class="p-4 border-l-4 border-l-indigo-500 bg-bg rounded-r-[4px]">
            <h3 class="font-bold text-[15px] text-textmain flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 text-[11px] flex items-center justify-center font-bold">D</span>
              Google OAuth (Single Sign-On)
            </h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Tersedia tombol <em>Lanjutkan dengan Google</em> untuk autentikasi satu klik menggunakan kredensial akun Google Anda.
            </p>
          </div>
        </div>
      </section>

      <!-- ================= SECTION 3: WORKSPACE ================= -->
      <section id="manajemen-workspace" class="bg-surface border border-borderc rounded-[4px] p-6 sm:p-8 scroll-mt-24 shadow-sm">
        <div class="flex items-center gap-2 text-primary font-bold text-[12px] uppercase tracking-wider mb-2">
          <span>Bagian 03</span>
          <span>&bull;</span>
          <span>Struktur Organisasi</span>
        </div>
        <h2 class="text-[24px] sm:text-[28px] font-extrabold text-textmain">3. Manajemen Workspace (Ruang Kerja)</h2>
        <p class="mt-3 text-body-md text-textsub leading-relaxed">
          Workspace adalah ruang kolaborasi utama. Anda dapat memiliki beberapa workspace untuk divisi atau tim yang berbeda.
        </p>

        <div class="mt-6 space-y-6">
          <div>
            <h3 class="text-[16px] font-bold text-textmain flex items-center gap-2">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-primary"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke="currentColor" stroke-width="2"/></svg>
              A. Berpindah & Mengelola Workspace
            </h3>
            <ol class="mt-2.5 space-y-2 text-[13px] text-textsub list-decimal list-inside leading-relaxed">
              <li>Klik kartu Workspace di pojok kiri atas pada Sidebar aplikasi.</li>
              <li>Pilih workspace tujuan dari daftar popup. Status proyek dan tugas akan berganti seketika.</li>
            </ol>
          </div>

          <div class="border-t border-borderc pt-4">
            <h3 class="text-[16px] font-bold text-textmain flex items-center gap-2">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-primary"><path d="M12 4v16m8-8H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
              B. Membuat Workspace Baru
            </h3>
            <ol class="mt-2.5 space-y-2 text-[13px] text-textsub list-decimal list-inside leading-relaxed">
              <li>Klik opsi <strong>+ Tambah Workspace</strong> di menu workspace.</li>
              <li>Ketikkan <strong>Nama Workspace</strong> dan <strong>Deskripsi</strong> singkat.</li>
              <li>Klik tombol <em>Simpan</em>. Pembuat otomatis mendapatkan hak istimewa sebagai <strong>Owner/Admin</strong>.</li>
            </ol>
          </div>

          <div class="border-t border-borderc pt-4">
            <h3 class="text-[16px] font-bold text-textmain flex items-center gap-2">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-primary"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M20 8v6M23 11h-6M9 11a4 4 0 100-8 4 4 0 000 8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              C. Mengundang & Menambahkan Anggota Tim
            </h3>
            <p class="mt-1 text-[13px] text-textsub leading-relaxed">
              Admin workspace memiliki dua cara praktis untuk mengundang rekan kerja:
            </p>
            <div class="mt-3 grid sm:grid-cols-2 gap-3">
              <div class="p-3 bg-bg border border-borderc rounded-[4px]">
                <span class="text-[11px] font-bold uppercase text-primary tracking-wider">Metode 1: Kode Undangan Singkat</span>
                <p class="mt-1 text-[12.5px] text-textsub">Setiap workspace memiliki <strong>Kode Undangan unik</strong> (contoh: <code class="px-1.5 py-0.5 bg-blue-100 text-primary font-mono text-[12px] rounded">WUNAWAN1</code>). Bagikan kode ini kepada rekan Anda agar dapat dimasukkan di menu <em>Gabung Workspace</em>.</p>
              </div>
              <div class="p-3 bg-bg border border-borderc rounded-[4px]">
                <span class="text-[11px] font-bold uppercase text-primary tracking-wider">Metode 2: Link Undangan Langsung</span>
                <p class="mt-1 text-[12.5px] text-textsub">Klik tombol <strong>Salin Link</strong> di sidebar atau modal undangan. Rekan tim yang mengklik link tersebut akan langsung bergabung ke workspace tanpa mengetik kode manual.</p>
              </div>
            </div>
          </div>

          <div class="border-t border-borderc pt-4">
            <h3 class="text-[16px] font-bold text-textmain flex items-center gap-2">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-accent"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              D. Fitur Keluar Sendiri dari Workspace (Leave Workspace)
            </h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Jika anggota atau admin sudah selesai berkolaborasi atau workspace tidak lagi dibutuhkan, pengguna dapat <strong>keluar sendiri secara mandiri</strong> melalui tombol <em>Keluar dari Workspace</em> di Sidebar, daftar anggota, atau modal pemilih workspace.
            </p>
          </div>

          <div class="border-t border-borderc pt-4">
            <h3 class="text-[16px] font-bold text-textmain flex items-center gap-2">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-accent"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 11h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              E. Mengeluarkan Anggota oleh Admin (Remove / Kick Member)
            </h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Admin atau Pemilik Utama memiliki otoritas penuh untuk <strong>mengeluarkan anggota</strong> dari tim melalui tombol <em>Keluarkan</em> pada daftar anggota modal <em>Kelola Anggota</em>. Tugas yang ditugaskan ke anggota tersebut akan otomatis dilepaskan (unassigned) dan dicatat pada riwayat audit.
            </p>
          </div>
        </div>
      </section>

      <!-- ================= SECTION 4: PROYEK ================= -->
      <section id="manajemen-proyek" class="bg-surface border border-borderc rounded-[4px] p-6 sm:p-8 scroll-mt-24 shadow-sm">
        <div class="flex items-center gap-2 text-primary font-bold text-[12px] uppercase tracking-wider mb-2">
          <span>Bagian 04</span>
          <span>&bull;</span>
          <span>Struktur Kerja</span>
        </div>
        <h2 class="text-[24px] sm:text-[28px] font-extrabold text-textmain">4. Manajemen Proyek</h2>
        <p class="mt-3 text-body-md text-textsub leading-relaxed">
          Satu workspace dapat menaungi beberapa proyek berbeda untuk memisahkan fokus inisiatif tim.
        </p>

        <div class="mt-5 space-y-4">
          <div class="flex gap-3">
            <div class="w-6 h-6 rounded-full bg-blue-100 text-primary font-bold text-[12px] flex items-center justify-center shrink-0 mt-0.5">1</div>
            <div>
              <h3 class="font-bold text-[14px] text-textmain">Membuat Proyek Baru</h3>
              <p class="text-[13px] text-textsub mt-0.5">Klik tombol <strong>New Project</strong> atau tombol <strong>+ Tambah</strong> di samping label PROYEK pada Sidebar. Masukkan nama proyek dan deskripsi target pekerjaan.</p>
            </div>
          </div>

          <div class="flex gap-3">
            <div class="w-6 h-6 rounded-full bg-blue-100 text-primary font-bold text-[12px] flex items-center justify-center shrink-0 mt-0.5">2</div>
            <div>
              <h3 class="font-bold text-[14px] text-textmain">Berpindah Antar Proyek</h3>
              <p class="text-[13px] text-textsub mt-0.5">Klik salah satu nama proyek di daftar Sidebar atau gunakan dropdown pemilih proyek di Top Header.</p>
            </div>
          </div>

          <div class="flex gap-3">
            <div class="w-6 h-6 rounded-full bg-blue-100 text-primary font-bold text-[12px] flex items-center justify-center shrink-0 mt-0.5">3</div>
            <div>
              <h3 class="font-bold text-[14px] text-textmain">Menghapus Proyek (Admin / Pembuat)</h3>
              <p class="text-[13px] text-textsub mt-0.5">Klik ikon tempat sampah pada baris proyek di Sidebar atau dropdown menu proyek. Tindakan ini akan menghapus proyek dan tugas terkait setelah konfirmasi.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= SECTION 5: KANBAN BOARD ================= -->
      <section id="papan-kanban" class="bg-surface border border-borderc rounded-[4px] p-6 sm:p-8 scroll-mt-24 shadow-sm">
        <div class="flex items-center gap-2 text-primary font-bold text-[12px] uppercase tracking-wider mb-2">
          <span>Bagian 05</span>
          <span>&bull;</span>
          <span>Pusat Operasional</span>
        </div>
        <h2 class="text-[24px] sm:text-[28px] font-extrabold text-textmain">5. Papan Kanban & Pengelolaan Tugas</h2>
        <p class="mt-3 text-body-md text-textsub leading-relaxed">
          Papan Kanban adalah tempat Anda memantau alur tugas secara visual dari awal hingga selesai.
        </p>

        <!-- 4 Status Columns Explanation -->
        <h3 class="mt-6 text-[16px] font-bold text-textmain">A. 4 Kolom Status Tugas</h3>
        <div class="mt-3 grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
          <div class="p-3 bg-bg border border-borderc border-l-[3px] border-l-slate-400 rounded-[4px]">
            <span class="text-[11px] font-bold uppercase text-textsub">1. Belum Dimulai</span>
            <p class="text-[12px] text-textsub mt-1">Tugas baru yang masuk dalam antrean backlog pengerjaan.</p>
          </div>
          <div class="p-3 bg-bg border border-borderc border-l-[3px] border-l-primary rounded-[4px]">
            <span class="text-[11px] font-bold uppercase text-primary">2. Sedang Dikerjakan</span>
            <p class="text-[12px] text-textsub mt-1">Tugas yang aktif sedang diproses oleh penanggung jawab.</p>
          </div>
          <div class="p-3 bg-bg border border-borderc border-l-[3px] border-l-amber-500 rounded-[4px]">
            <span class="text-[11px] font-bold uppercase text-amber-700">3. Dalam Peninjauan</span>
            <p class="text-[12px] text-textsub mt-1">Tugas selesai yang sedang dalam tahap QA atau review tim.</p>
          </div>
          <div class="p-3 bg-bg border border-borderc border-l-[3px] border-l-emerald-500 rounded-[4px]">
            <span class="text-[11px] font-bold uppercase text-emerald-700">4. Selesai</span>
            <p class="text-[12px] text-textsub mt-1">Tugas yang tervalidasi rampung dan memenuhi kriteria.</p>
          </div>
        </div>

        <!-- Priority Badges Explanation -->
        <h3 class="mt-7 text-[16px] font-bold text-textmain">B. Indikator Tingkat Prioritas</h3>
        <p class="mt-1 text-[13px] text-textsub">Setiap tugas ditandai dengan warna badge khusus untuk membedakan urgensi:</p>
        <div class="mt-3 flex flex-wrap gap-2.5">
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[4px] text-[12px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            Didahulukan (High Priority)
          </span>
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[4px] text-[12px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            Perlu Diperhatikan (Medium)
          </span>
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[4px] text-[12px] font-bold bg-blue-50 text-primary border border-blue-200">
            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
            Eksternal (External Dependency)
          </span>
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[4px] text-[12px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Biasa (Normal/Low)
          </span>
        </div>

        <!-- How to Add & Edit Tasks -->
        <h3 class="mt-7 text-[16px] font-bold text-textmain">C. Interaksi Kartu Tugas</h3>
        <ul class="mt-2.5 space-y-2 text-[13px] text-textsub list-disc list-inside leading-relaxed">
          <li><strong>Tambah Tugas</strong>: Klik tombol <em>+ Tambah Tugas</em> di atas kolom manapun atau tombol <em>Buat Tugas Baru</em> di header. Isi Judul, Deskripsi, Prioritas, Tanggal Tenggat, dan Pilih Anggota PIC.</li>
          <li><strong>Pindah Status Cepat (1-Klik)</strong>: Klik tombol panah status pada kartu tugas untuk memindahkannya ke kolom berikutnya seketika tanpa perlu reload halaman.</li>
          <li><strong>Ubah Prioritas Cepat</strong>: Klik badge prioritas pada kartu untuk mengganti tingkat prioritas secara instan.</li>
          <li><strong>Hapus Tugas</strong>: Klik tombol menu atau opsi hapus pada kartu tugas yang ingin diarsipkan/dihilangkan.</li>
        </ul>
      </section>

      <!-- ================= SECTION 6: LOG AKTIVITAS ================= -->
      <section id="log-aktivitas" class="bg-surface border border-borderc rounded-[4px] p-6 sm:p-8 scroll-mt-24 shadow-sm">
        <div class="flex items-center gap-2 text-primary font-bold text-[12px] uppercase tracking-wider mb-2">
          <span>Bagian 06</span>
          <span>&bull;</span>
          <span>Audit & Transparansi</span>
        </div>
        <h2 class="text-[24px] sm:text-[28px] font-extrabold text-textmain">6. Log Aktivitas (Activity Log)</h2>
        <p class="mt-3 text-body-md text-textsub leading-relaxed">
          Menu <strong>Activity Log</strong> berfungsi sebagai buku catatan jejak audit (<em>audit trail</em>) transparan untuk seluruh aksi yang dilakukan oleh anggota di workspace.
        </p>

        <div class="mt-4 p-4 bg-bg border border-borderc rounded-[4px] space-y-2 text-[13px] text-textsub">
          <p class="font-bold text-textmain">Peristiwa yang Tercatat Otomatis:</p>
          <ul class="list-disc list-inside space-y-1">
            <li>Pembuatan tugas baru dengan nama pembuat dan tanggal.</li>
            <li>Perpindahan kolom status tugas (misal: <em>Sedang Dikerjakan &rarr; Selesai</em>).</li>
            <li>Perubahan tingkat urgensi/prioritas tugas.</li>
            <li>Anggota baru yang masuk bergabung ke dalam tim.</li>
          </ul>
        </div>
      </section>

      <!-- ================= SECTION 7: DASHBOARD PROGRES ================= -->
      <section id="dashboard-progres" class="bg-surface border border-borderc rounded-[4px] p-6 sm:p-8 scroll-mt-24 shadow-sm">
        <div class="flex items-center gap-2 text-primary font-bold text-[12px] uppercase tracking-wider mb-2">
          <span>Bagian 07</span>
          <span>&bull;</span>
          <span>Metrik & Ringkasan</span>
        </div>
        <h2 class="text-[24px] sm:text-[28px] font-extrabold text-textmain">7. Dashboard Progres (Progress Overview)</h2>
        <p class="mt-3 text-body-md text-textsub leading-relaxed">
          Menu <strong>Progress Overview</strong> menyajikan visualisasi data kesiapan proyek secara ringkas tanpa perlu menghitung manual:
        </p>

        <div class="mt-5 grid sm:grid-cols-3 gap-4">
          <div class="p-4 bg-bg border border-borderc rounded-[4px] text-center">
            <div class="text-[26px] font-extrabold text-primary">100%</div>
            <p class="text-[12px] font-bold text-textmain mt-1">Completion Rate</p>
            <p class="text-[11.5px] text-textsub mt-0.5">Persentase total tugas selesai dibanding keseluruhan tugas.</p>
          </div>
          <div class="p-4 bg-bg border border-borderc rounded-[4px] text-center">
            <div class="text-[26px] font-extrabold text-amber-600">4 Kolom</div>
            <p class="text-[12px] font-bold text-textmain mt-1">Status Distribution</p>
            <p class="text-[11.5px] text-textsub mt-0.5">Grafik perbandingan tugas belum dimulai, aktif, review, dan selesai.</p>
          </div>
          <div class="p-4 bg-bg border border-borderc rounded-[4px] text-center">
            <div class="text-[26px] font-extrabold text-emerald-600">Workload</div>
            <p class="text-[12px] font-bold text-textmain mt-1">Beban Tugas Tim</p>
            <p class="text-[11.5px] text-textsub mt-0.5">Pantau jumlah tugas yang dipegang masing-masing PIC anggota tim.</p>
          </div>
        </div>
      </section>

      <!-- ================= SECTION 8: FAQ ================= -->
      <section id="faq" class="bg-surface border border-borderc rounded-[4px] p-6 sm:p-8 scroll-mt-24 shadow-sm">
        <div class="flex items-center gap-2 text-primary font-bold text-[12px] uppercase tracking-wider mb-2">
          <span>Bagian 08</span>
          <span>&bull;</span>
          <span>Bantuan Cepat</span>
        </div>
        <h2 class="text-[24px] sm:text-[28px] font-extrabold text-textmain">8. Pertanyaan yang Sering Diajukan (FAQ)</h2>

        <div class="mt-6 space-y-4">
          <div class="p-4 bg-bg border border-borderc rounded-[4px]">
            <h3 class="font-bold text-[14px] text-textmain">Bagaimana jika kode undangan workspace saya hilang?</h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Admin/Owner workspace dapat melihat kode undangan kapan saja dengan mengklik tombol <strong>+ Undang Anggota</strong> pada Sidebar. Kode tertera di kotak abu-abu tebal dan tombol <em>Salin Link</em> langsung siap dibagikan.
            </p>
          </div>

          <div class="p-4 bg-bg border border-borderc rounded-[4px]">
            <h3 class="font-bold text-[14px] text-textmain">Apakah saya bisa berada di lebih dari satu workspace?</h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Ya, sistem KlikBan mendukung multi-workspace. Anda dapat menjadi pemilik di satu workspace dan menjadi anggota kontributor di workspace tim lain. Beralih antar workspace dapat dilakukan dengan 1 klik pada header/sidebar.
            </p>
          </div>

          <div class="p-4 bg-bg border border-borderc rounded-[4px]">
            <h3 class="font-bold text-[14px] text-textmain">Apakah data saya aman dan langsung tersimpan?</h3>
            <p class="mt-1.5 text-[13px] text-textsub leading-relaxed">
              Setiap kali Anda membuat tugas, memindahkan kartu status, atau memperbarui prioritas, data langsung disimpan ke database secara real-time dan tercatat di Log Aktivitas.
            </p>
          </div>
        </div>
      </section>

      <!-- CTA Card in Documentation -->
      <div class="p-8 bg-gradient-to-br from-primary to-secondary text-white rounded-[4px] text-center shadow-lg">
        <h3 class="text-[22px] font-extrabold">Siap Memaksimalkan Alur Kerja Tim Anda?</h3>
        <p class="mt-2 text-[14px] text-blue-50 max-w-xl mx-auto">Masuk ke ruang kerja Anda sekarang atau coba langsung fitur interaktif KlikBan secara gratis.</p>
        <div class="mt-6 flex justify-center gap-3">
          @auth
            <a href="{{ route('board') }}" class="bg-white text-primary font-bold text-[13px] px-6 py-2.5 rounded-[4px] hover:bg-blue-50 transition-colors">Buka Papan Kanban</a>
          @else
            <a href="{{ route('register') }}" class="bg-white text-primary font-bold text-[13px] px-6 py-2.5 rounded-[4px] hover:bg-blue-50 transition-colors">Daftar Akun Baru</a>
            <a href="{{ route('demo.login') }}" class="bg-blue-700/60 border border-white/30 text-white font-semibold text-[13px] px-5 py-2.5 rounded-[4px] hover:bg-blue-700 transition-colors">Coba Demo Cepat</a>
          @endauth
        </div>
      </div>

    </article>
  </div>
</main>

<!-- ===================== FOOTER ===================== -->
<footer class="border-t border-borderc bg-surface">
  <div class="max-w-[1280px] mx-auto px-6 lg:px-8 pt-14 pb-8">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
      <div>
        <div class="flex items-center gap-2">
          <svg class="w-6 h-6" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="4" stroke="#3B82F6" stroke-width="2.5" fill="white"/><line x1="4" y1="15" x2="36" y2="15" stroke="#3B82F6" stroke-width="2"/><line x1="10" y1="19" x2="24" y2="19" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/><line x1="10" y1="24" x2="18" y2="24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/><path d="M22 20L31 29L26.5 30.5L30 37L27 38.5L23.5 32L19.5 35.5L22 20Z" fill="#3B82F6" stroke="white" stroke-width="1.5"/></svg>
          <span class="text-[16px] font-extrabold tracking-tight text-primary">KlikBan</span>
        </div>
        <p class="mt-4 text-[13px] text-textsub leading-relaxed">Platform manajemen tugas yang dirancang khusus untuk meminimalkan friksi dan memaksimalkan output.</p>
      </div>
      <div>
        <h4 class="text-[14px] font-bold text-textmain mb-4 uppercase tracking-wider">Produk</h4>
        <ul class="space-y-3 text-[13px] text-textsub">
          <li><a href="{{ route('home') }}#showcase" class="hover:text-primary transition-colors">Fitur</a></li>
          <li><a href="{{ route('about') }}" class="text-primary font-semibold transition-colors">Tentang Aplikasi</a></li>
          <li><a href="{{ route('guide') }}#panduan" class="hover:text-primary transition-colors">Petunjuk Penggunaan</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-[14px] font-bold text-textmain mb-4 uppercase tracking-wider">Sumber Daya</h4>
        <ul class="space-y-3 text-[13px] text-textsub">
          <li><a href="{{ route('about') }}#faq" class="hover:text-primary transition-colors">Pusat Bantuan & FAQ</a></li>
          <li><a href="{{ route('about') }}#panduan" class="hover:text-primary transition-colors">Dokumentasi Alur Kerja</a></li>
          <li><a href="{{ route('demo.login') }}" class="hover:text-primary transition-colors">Akses Demo Pengguna</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-[14px] font-bold text-textmain mb-4 uppercase tracking-wider">Perusahaan</h4>
        <ul class="space-y-3 text-[13px] text-textsub">
          <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors">Tentang Kami</a></li>
          <li><a href="https://github.com/rstiandwiynto" target="_blank" rel="noopener" class="hover:text-primary transition-colors">Kontak Pengembang</a></li>
          <li><a href="#" class="hover:text-primary transition-colors">Privasi & Syarat</a></li>
        </ul>
      </div>
    </div>
    <div class="mt-14 pt-8 border-t border-borderc flex flex-col md:flex-row items-center justify-between gap-4">
      <p class="text-[12px] text-textsub">&copy; 2026 KlikBan Workspace. Hak Cipta Dilindungi.</p>
      <div class="flex gap-4">
        <a href="https://github.com/rstiandwiynto" class="text-textsub hover:text-textmain transition-colors" aria-label="GitHub"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg></a>
      </div>
    </div>
  </div>
</footer>

<script>
(function() {
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const iconOpen = document.getElementById('iconOpen');
  const iconClose = document.getElementById('iconClose');

  if (menuBtn) {
    menuBtn.addEventListener('click', function() {
      const isExpanded = menuBtn.getAttribute('aria-expanded') === 'true';
      menuBtn.setAttribute('aria-expanded', !isExpanded);
      if (!isExpanded) {
        mobileMenu.style.maxHeight = mobileMenu.scrollHeight + 'px';
        mobileMenu.style.opacity = '1';
        iconOpen.classList.add('hidden');
        iconClose.classList.remove('hidden');
      } else {
        mobileMenu.style.maxHeight = '0px';
        mobileMenu.style.opacity = '0';
        iconOpen.classList.remove('hidden');
        iconClose.classList.add('hidden');
      }
    });
  }
})();
</script>

</body>
</html>
