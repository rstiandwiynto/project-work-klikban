<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KlikBan — Manajemen Tugas Tercepat untuk Tim Modern</title>
<meta name="description" content="KlikBan menghapus hambatan kognitif dalam kolaborasi tim. Kelola tugas cepat, visualisasikan alur kerja dengan Kanban board modern super cepat.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Pre-compiled & Minified Production CSS -->
<link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
</head>
<body class="antialiased">

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

      <!-- Desktop nav with sliding indicator -->
      <nav id="desktopNav" class="hidden md:flex items-center gap-8 relative h-16">
        <div id="navSlider" class="nav-slider" style="opacity:0"></div>
        <a href="#hero" data-nav="home" class="nav-link active text-body-md font-medium text-textsub h-full flex items-center relative overflow-hidden px-1">Home</a>
        <a href="#showcase" data-nav="showcase" class="nav-link text-body-md font-medium text-textsub hover:text-primary h-full flex items-center relative overflow-hidden px-1">Fitur Kami</a>
      </nav>

      <div class="hidden md:flex items-center gap-3">
        @auth
          <a href="{{ route('board') }}" class="focus-ring bg-primary hover:bg-blue-600 transition-colors text-white text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Buka Workspace</a>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="focus-ring text-body-md font-medium text-accent hover:text-red-700 px-3 py-2 transition flex items-center gap-1.5" title="Keluar dari akun">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              Keluar
            </button>
          </form>
          <div class="w-9 h-9 rounded-[4px] bg-blue-100 text-primary flex items-center justify-center font-bold text-[12px]">{{ auth()->user()->initials ?? 'Me' }}</div>
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
        <a href="#hero" data-mobile-nav class="text-body-md font-medium text-textsub hover:text-primary">Home</a>
        <a href="#showcase" data-mobile-nav class="text-body-md font-medium text-textsub hover:text-primary">Fitur Kami</a>
        @auth
          <a href="{{ route('board') }}" class="focus-ring w-full text-center bg-primary text-white text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Buka Workspace</a>
          <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="focus-ring w-full text-center bg-red-50 border border-red-200 text-accent text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Keluar Akun</button>
          </form>
        @else
          <a href="{{ route('register') }}" class="focus-ring w-full text-center bg-primary text-white text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Daftar Gratis</a>
          <a href="{{ route('login') }}" class="focus-ring w-full text-center bg-surface border border-borderc text-textmain text-body-md font-semibold px-5 py-2.5 rounded-[4px]">Masuk</a>
        @endauth
      </div>
    </div>
  </div>
</header>

<!-- ===================== HERO ===================== -->
<section id="hero" class="relative overflow-hidden border-b border-borderc scroll-mt-16">
  <div class="grid-fade absolute inset-0 pointer-events-none"></div>
  <div class="relative max-w-[1280px] mx-auto px-6 lg:px-8 py-16 md:py-24 grid lg:grid-cols-2 gap-14 items-center">
    <div>
      <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary text-label-sm uppercase tracking-wide px-3 py-1.5 rounded-[4px]">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h7l-1 8 11-14h-8l1-6z"/></svg>
        Velocity Tasking
      </span>
      <h1 class="mt-5 text-[34px] sm:text-[42px] lg:text-[50px] font-extrabold leading-[1.08] tracking-tight text-textmain">
        Manajemen Tugas <span class="text-primary">Tercepat</span> untuk Tim Modern.
      </h1>
      <p class="mt-5 text-body-md sm:text-[15px] text-textsub max-w-[480px] leading-relaxed">
        KlikBan menghapus hambatan kognitif dalam kolaborasi. Visualisasikan alur kerja, tetapkan prioritas, dan selesaikan proyek lebih cepat dengan cockpit data berkinerja tinggi.
      </p>
      <div class="mt-8 flex flex-wrap items-center gap-3">
        @auth
          <a href="{{ route('board') }}" class="focus-ring inline-flex items-center gap-2 bg-primary hover:bg-blue-600 transition-colors text-white text-body-md font-semibold px-6 py-3 rounded-[4px]">
            Buka Workspace <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        @else
          <a href="{{ route('register') }}" class="focus-ring inline-flex items-center gap-2 bg-primary hover:bg-blue-600 transition-colors text-white text-body-md font-semibold px-6 py-3 rounded-[4px]">
            Mulai Sekarang <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <a href="{{ route('demo.login') }}" class="focus-ring inline-flex items-center gap-2 bg-surface border border-borderc hover:border-primary hover:text-primary transition-colors text-textmain text-body-md font-semibold px-6 py-3 rounded-[4px]">Lihat Demo</a>
        @endauth
      </div>
      <div class="mt-9 flex items-center gap-3">
        <div class="flex -space-x-2.5">
          <div class="w-8 h-8 rounded-full bg-primary text-white text-[11px] font-bold flex items-center justify-center ring-2 ring-bg">RN</div>
          <div class="w-8 h-8 rounded-full bg-secondary text-white text-[11px] font-bold flex items-center justify-center ring-2 ring-bg">DA</div>
          <div class="w-8 h-8 rounded-full bg-textsub text-white text-[11px] font-bold flex items-center justify-center ring-2 ring-bg">+</div>
        </div>
        <p class="text-label-sm text-textsub font-medium">Digunakan oleh 500+ tim di Indonesia</p>
      </div>
    </div>
    <!-- Mockup card -->
    <div class="relative flex justify-center lg:justify-end">
      <div class="float-card drag-tilt transition-transform duration-500 bg-surface border border-borderc rounded-card shadow-[0_20px_50px_-15px_rgba(27,27,31,0.15)] w-full max-w-[420px] p-5">
        <div class="flex items-center justify-between mb-4">
          <span class="text-label-sm text-textsub font-semibold uppercase tracking-wide">Papan Proyek</span>
          <span class="inline-flex items-center gap-1.5 bg-red-50 text-accent text-label-sm font-semibold px-2.5 py-1 rounded-[4px]">
            <span class="w-1.5 h-1.5 rounded-[2px] bg-accent"></span>Prioritas: Didahulukan
          </span>
        </div>
        <div class="space-y-3">
          <div class="border border-borderc rounded-[4px] p-3.5 border-l-[4px] border-l-accent bg-white">
            <p class="text-[11px] text-accent font-bold uppercase">DIDAHULUKAN</p>
            <p class="text-[15px] font-bold text-textmain mt-1">Finalize API Design</p>
            <p class="text-[12.5px] text-textsub mt-1">Pastikan dokumentasi selalu terbaru.</p>
          </div>
          <div class="border border-borderc rounded-[4px] p-3.5 border-l-[4px] border-l-primary bg-white">
            <p class="text-[11px] text-primary font-bold uppercase">In Progress</p>
            <p class="text-[15px] font-bold text-textmain mt-1">UI Component Audit</p>
            <p class="text-[12.5px] text-textsub mt-1">Meninjau kelas Tailwind.</p>
          </div>
        </div>
        <div class="mt-4 flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-[4px] px-3.5 py-2.5">
          <span class="text-[13px] font-bold text-emerald-700">Tugas Diselesaikan</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="#047857" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== WHY KLIKBAN ===================== -->
<section class="max-w-[1280px] mx-auto px-6 lg:px-8 py-16 md:py-24">
  <div class="max-w-[640px] mx-auto text-center">
    <h2 class="text-[26px] sm:text-headline-lg text-textmain font-extrabold">Mengapa Memilih KlikBan?</h2>
    <p class="mt-3 text-body-md text-textsub">Dirancang untuk kecepatan dan akurasi, memberikan tim Anda keunggulan dalam setiap eksekusi proyek.</p>
  </div>
  <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
    <div class="bg-surface border border-borderc rounded-[4px] p-6 hover:border-primary/40 transition-colors">
      <div class="w-10 h-10 rounded-[4px] bg-blue-50 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 7v5l3.5 2M21 12a9 9 0 11-4.2-7.6" stroke="#3B82F6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <h3 class="mt-4 text-title-md text-textmain">Quick Status Update</h3>
      <p class="mt-2 text-body-md text-textsub">Perbarui status tugas dalam satu klik. Tidak ada form panjang, tidak ada hambatan. Hanya aliran data yang mulus.</p>
    </div>
    <div class="bg-surface border border-borderc rounded-[4px] p-6 hover:border-primary/40 transition-colors">
      <div class="w-10 h-10 rounded-[4px] bg-emerald-50 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3l9 4-9 4-9-4 9-4z" stroke="#059669" stroke-width="1.8" stroke-linejoin="round"/><path d="M3 11l9 4 9-4M3 15l9 4 9-4" stroke="#059669" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round"/></svg>
      </div>
      <h3 class="mt-4 text-title-md text-textmain">Visual Priority</h3>
      <p class="mt-2 text-body-md text-textsub">Hierarki visual yang cerdas menggunakan kode warna untuk memastikan tugas "Didahulukan" selalu mendapat perhatian utama tim.</p>
    </div>
    <div class="bg-surface border border-borderc rounded-[4px] p-6 hover:border-primary/40 transition-colors sm:col-span-2 lg:col-span-1">
      <div class="w-10 h-10 rounded-[4px] bg-orange-50 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3" stroke="#EA580C" stroke-width="1.8"/><path d="M3 20c0-3 2.7-5 6-5s6 2 6 5" stroke="#EA580C" stroke-width="1.8" stroke-linecap="round"/><path d="M16 8a3 3 0 010 6M19 20c0-2.5-1.7-4.3-4-4.9" stroke="#EA580C" stroke-width="1.8" stroke-linecap="round"/></svg>
      </div>
      <h3 class="mt-4 text-title-md text-textmain">Accurate Collaboration</h3>
      <p class="mt-2 text-body-md text-textsub">Akses privat workspace yang aman, khusus untuk anggota. Setiap perubahan terlihat seketika lewat Activity Log yang mendetail.</p>
    </div>
  </div>
</section>

<!-- ===================== SHOWCASE ===================== -->
<section id="showcase" class="bg-white border-y border-borderc scroll-mt-16">
  <div class="max-w-[1280px] mx-auto px-6 lg:px-8 py-16 md:py-24 grid lg:grid-cols-2 gap-14 items-center">
    <div class="grid sm:grid-cols-2 gap-4 order-2 lg:order-1">
      <div class="border border-borderc border-l-[4px] border-l-accent rounded-[4px] p-4 bg-bg">
        <div class="flex items-center justify-between">
          <span class="text-[11px] text-accent font-bold uppercase">Didahulukan</span>
          <button class="text-textsub" aria-label="Opsi lainnya">⋯</button>
        </div>
        <p class="text-[15px] font-bold text-textmain mt-2">Finalize API Design</p>
        <p class="text-[12.5px] text-textsub mt-1">Pastikan dokumentasi selalu terbaru.</p>
        <div class="mt-3 flex items-center justify-between">
          <span class="w-6 h-6 rounded-[4px] bg-primary text-white text-[10px] font-bold flex items-center justify-center">RN</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="text-textsub"><path d="M21 12.5V7a3 3 0 00-3-3H8a3 3 0 00-3 3v13l7-3 7 3v-4.5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
        </div>
      </div>
      <div class="border border-borderc border-l-[4px] border-l-primary rounded-[4px] p-4 bg-bg">
        <div class="flex items-center justify-between">
          <span class="text-[11px] text-primary font-bold uppercase">Eksternal</span>
          <button class="text-textsub" aria-label="Opsi lainnya">⋯</button>
        </div>
        <p class="text-[15px] font-bold text-textmain mt-2">UI Component Audit</p>
        <p class="text-[12.5px] text-textsub mt-1">Meninjau kelas Tailwind.</p>
        <div class="mt-3 flex items-center justify-between">
          <span class="w-6 h-6 rounded-[4px] bg-secondary text-white text-[10px] font-bold flex items-center justify-center">DA</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="text-textsub"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v10z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
        </div>
      </div>
      <div class="border border-borderc border-l-[4px] border-l-emerald-500 rounded-[4px] p-4 bg-bg sm:col-span-2">
        <div class="flex items-center justify-between">
          <span class="text-[11px] text-emerald-600 font-bold uppercase">Selesai</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#059669" stroke-width="1.6"/><path d="M8 12.5l2.5 2.5L16 9.5" stroke="#059669" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <p class="text-[15px] font-bold text-textmain mt-2">Landing Page Prototype</p>
        <p class="text-[12.5px] text-textsub mt-1">Divalidasi bersama stakeholder utama.</p>
      </div>
    </div>
    <div class="order-1 lg:order-2">
      <h2 class="text-[26px] sm:text-headline-lg text-textmain font-extrabold">Efisiensi Maksimal,<br><span class="text-emerald-600">Tanpa Kompromi.</span></h2>
      <p class="mt-4 text-body-md text-textsub leading-relaxed">Antarmuka kami menggunakan pendekatan "Tonal Layering" untuk membedakan antara informasi penting dan data pendukung. Setiap elemen UI di KlikBan dirancang untuk mengurangi beban kognitif pengguna.</p>
      <div class="mt-7 space-y-5">
        <div class="flex gap-3">
          <div class="w-9 h-9 shrink-0 rounded-[4px] bg-blue-50 flex items-center justify-center">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M3 7l4 4 5-6M3 15l4 4 5-6M14 8h7M14 16h7" stroke="#3B82F6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <p class="text-[15px] font-bold text-textmain">Smart Filtering</p>
            <p class="text-[13px] text-textsub mt-0.5">Pantau status prioritas via Dashboard Progress sirkular.</p>
          </div>
        </div>
        <div class="flex gap-3">
          <div class="w-9 h-9 shrink-0 rounded-[4px] bg-blue-50 flex items-center justify-center">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="12" rx="2" stroke="#3B82F6" stroke-width="1.8"/><path d="M7 10h.01M11 10h.01M15 10h.01M9 14h6" stroke="#3B82F6" stroke-width="1.8" stroke-linecap="round"/></svg>
          </div>
          <div>
            <p class="text-[15px] font-bold text-textmain">Fast Interaction</p>
            <p class="text-[13px] text-textsub mt-0.5">Ubah status tugas dan delegasi tim dengan interaksi 1-Klik.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="max-w-[1280px] mx-auto px-6 lg:px-8 py-16 md:py-24">
  <div class="relative overflow-hidden rounded-[4px] bg-gradient-to-br from-primary to-secondary px-6 sm:px-12 py-14 sm:py-16 text-center shadow-lg">
    <div class="grid-fade absolute inset-0 opacity-20 pointer-events-none"></div>
    <div class="relative">
      <h2 class="text-[24px] sm:text-headline-lg text-white font-extrabold">Ingin Mengakselerasi Proyek Anda?</h2>
      <p class="mt-3 text-[14px] text-blue-50 max-w-[520px] mx-auto">Bergabunglah dengan ribuan tim yang telah berpindah ke KlikBan untuk manajemen tugas yang lebih bersih dan cepat.</p>
      @auth
        <a href="{{ route('board') }}" class="focus-ring mt-7 inline-flex items-center gap-2 bg-white text-primary text-[14px] font-bold px-6 py-3 rounded-[4px] hover:bg-blue-50 transition-colors">Buka Workspace</a>
      @else
        <a href="{{ route('register') }}" class="focus-ring mt-7 inline-flex items-center gap-2 bg-white text-primary text-[14px] font-bold px-6 py-3 rounded-[4px] hover:bg-blue-50 transition-colors">Buat Workspace Gratis</a>
      @endauth
    </div>
  </div>
</section>

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
          <li><a href="#showcase" class="hover:text-primary transition-colors">Fitur</a></li>
          <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors font-medium">Tentang Aplikasi</a></li>
          <li><a href="{{ route('guide') }}#panduan" class="hover:text-primary transition-colors">Petunjuk Penggunaan</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-[14px] font-bold text-textmain mb-4 uppercase tracking-wider">Sumber Daya</h4>
        <ul class="space-y-3 text-[13px] text-textsub">
          <li><a href="{{ route('about') }}#faq" class="hover:text-primary transition-colors">Pusat Bantuan & FAQ</a></li>
          <li><a href="{{ route('guide') }}#panduan" class="hover:text-primary transition-colors">Dokumentasi Alur Kerja</a></li>
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
        <a href="#" class="text-textsub hover:text-textmain transition-colors" aria-label="Twitter"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
        <a href="https://github.com/rstiandwiynto" class="text-textsub hover:text-textmain transition-colors" aria-label="GitHub"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg></a>
      </div>
    </div>
  </div>
</footer>

<script>
(function() {
  // ---- Mobile menu toggle ----
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const iconOpen = document.getElementById('iconOpen');
  const iconClose = document.getElementById('iconClose');

  function closeMobileMenu() {
    if (mobileMenu) { mobileMenu.style.maxHeight = '0px'; mobileMenu.style.opacity = '0'; }
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
    if (iconOpen)  iconOpen.classList.remove('hidden');
    if (iconClose) iconClose.classList.add('hidden');
  }

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
        closeMobileMenu();
      }
    });
  }

  // Mobile nav links: close menu on click
  document.querySelectorAll('[data-mobile-nav]').forEach(function(el) {
    el.addEventListener('click', closeMobileMenu);
  });

  // ---- Ripple effect ----
  function createRipple(e) {
    var link = e.currentTarget;
    link.querySelectorAll('.ripple').forEach(function(r) { r.remove(); });
    var ripple = document.createElement('span');
    ripple.classList.add('ripple');
    var rect = link.getBoundingClientRect();
    ripple.style.left = (e.clientX - rect.left - 4) + 'px';
    ripple.style.top  = (e.clientY - rect.top  - 4) + 'px';
    link.appendChild(ripple);
    ripple.addEventListener('animationend', function() { ripple.remove(); });
  }

  // ---- Desktop sliding indicator ----
  var navSlider  = document.getElementById('navSlider');
  var desktopNav = document.getElementById('desktopNav');
  var navLinks   = desktopNav ? desktopNav.querySelectorAll('.nav-link') : [];

  function moveSliderTo(link) {
    if (!navSlider || !desktopNav) return;
    var navRect  = desktopNav.getBoundingClientRect();
    var linkRect = link.getBoundingClientRect();
    navSlider.style.opacity = '1';
    navSlider.style.left    = (linkRect.left - navRect.left) + 'px';
    navSlider.style.width   = linkRect.width + 'px';
  }

  function setActiveLink(clickedLink) {
    navLinks.forEach(function(l) {
      l.classList.remove('active');
      l.style.color = '';
      l.style.fontWeight = '';
    });
    clickedLink.classList.add('active');
    moveSliderTo(clickedLink);
  }

  navLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
      createRipple(e);
      setActiveLink(link);
    });
  });

  // Init slider position on load
  window.addEventListener('load', function() {
    var activeLink = desktopNav ? desktopNav.querySelector('.nav-link.active') : null;
    if (activeLink) moveSliderTo(activeLink);
  });

  // Reposition on resize
  window.addEventListener('resize', function() {
    var activeLink = desktopNav ? desktopNav.querySelector('.nav-link.active') : null;
    if (activeLink) moveSliderTo(activeLink);
  });

  // ---- Scroll spy: update active link based on scroll position ----
  var sections = [];
  navLinks.forEach(function(link) {
    var href = link.getAttribute('href');
    if (href && href.startsWith('#')) {
      var section = document.getElementById(href.substring(1));
      if (section) sections.push({ link: link, section: section });
    }
  });

  function onScroll() {
    var scrollY = window.scrollY + 100;
    var current = null;
    sections.forEach(function(item) {
      if (item.section.offsetTop <= scrollY) current = item;
    });
    if (current && !current.link.classList.contains('active')) {
      setActiveLink(current.link);
    }
  }

  if (sections.length > 0) {
    window.addEventListener('scroll', onScroll, { passive: true });
  }
})();
</script>
</body>
</html>
