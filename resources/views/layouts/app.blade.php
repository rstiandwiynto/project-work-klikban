<!DOCTYPE html>
<html lang="id" class="h-full bg-bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Workspace — KlikBan')</title>
    
    <meta name="description" content="KlikBan - Aplikasi manajemen tugas kolaboratif dan Kanban modern super cepat.">
    
    <!-- DNS Prefetch & Preconnect untuk mempercepat koneksi ke Google Fonts -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts dengan display=swap (teks langsung tampil pakai font fallback) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Pre-compiled & Minified Production CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
</head>
<body class="antialiased text-textmain bg-bg min-h-screen flex flex-col">

    <!-- ===================== TOP HEADER ===================== -->
    <header class="sticky top-0 z-40 bg-surface/95 backdrop-blur border-b border-borderc">
      <div class="h-16 px-4 sm:px-6 flex items-center justify-between">
        
        <!-- Left: Mobile Toggle & Brand Logo -->
        <div class="flex items-center gap-3">
          <button id="sidebarToggle" class="lg:hidden focus-ring w-9 h-9 flex items-center justify-center text-textmain hover:bg-bg rounded transition-colors" aria-label="Buka menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
          </button>
          
          <a href="{{ route('home') }}" class="flex items-center gap-2">
            <svg width="28" height="28" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 shrink-0">
                <rect x="4" y="8" width="32" height="24" rx="4" stroke="#3B82F6" stroke-width="2.5" fill="white"/>
                <line x1="4" y1="15" x2="36" y2="15" stroke="#3B82F6" stroke-width="2"/>
                <line x1="10" y1="19" x2="24" y2="19" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="10" y1="24" x2="18" y2="24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                <path d="M22 20L31 29L26.5 30.5L30 37L27 38.5L23.5 32L19.5 35.5L22 20Z" fill="#3B82F6" stroke="white" stroke-width="1.5"/>
            </svg>
            <span class="text-[17px] font-extrabold tracking-tight text-primary">KlikBan</span>
          </a>
        </div>

        <!-- Center Nav & Active Project Switcher -->
        <nav class="hidden md:flex items-center gap-7 absolute left-1/2 -translate-x-1/2">
          <a href="{{ route('home') }}" class="text-body-md font-medium {{ request()->routeIs('home') ? 'text-primary font-semibold' : 'text-textsub hover:text-textmain' }} transition-colors">Home</a>
          
          <div class="relative group" id="project-dropdown-container">
            <button id="project-dropdown-btn" class="flex items-center gap-1.5 text-body-md font-semibold text-primary border-b-2 border-primary pb-[22px] -mb-[22px] focus:outline-none cursor-pointer">
              <span class="max-w-[200px] truncate">{{ $project->name ?? 'Pilih Proyek' }}</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="text-primary transition-transform group-hover:translate-y-0.5"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>

            <!-- Dropdown Menu Proyek -->
            <div id="project-dropdown-menu" class="hidden absolute left-1/2 -translate-x-1/2 top-full mt-3 w-72 bg-surface rounded-card border border-borderc py-2 z-50 shadow-xl">
              <div class="px-3 py-1.5 flex items-center justify-between border-b border-borderc mb-1">
                <span class="text-[11px] font-bold text-textsub uppercase tracking-wider">Proyek di Workspace</span>
                <span class="text-[11px] font-bold text-primary truncate max-w-[120px]">{{ $workspace->name ?? '' }}</span>
              </div>
              
              <div class="max-h-56 overflow-y-auto py-1">
                @if(isset($projects) && $projects->isNotEmpty())
                    @foreach($projects as $p)
                        @php $isCurrentProj = (isset($project) && $project->id == $p->id); @endphp
                        <div class="group/proj flex items-center justify-between px-3 py-2 text-body-md hover:bg-blue-50 transition-colors {{ $isCurrentProj ? 'bg-blue-50 font-semibold text-primary' : 'text-textmain' }}">
                            <a href="{{ route('projects.switch', $p->id) }}" class="flex-1 truncate pr-2 {{ $isCurrentProj ? 'text-primary' : 'text-textmain hover:text-primary' }}">
                                {{ $p->name }}
                            </a>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $isCurrentProj ? 'bg-primary text-white font-bold' : 'bg-slate-100 text-textsub' }}">
                                    {{ $p->tasks_count ?? $p->tasks()->count() }}
                                </span>
                                @if(isset($workspace) && ($workspace->isAdmin(auth()->user()) || (auth()->check() && $p->created_by === auth()->id())))
                                    <button type="button" onclick="event.preventDefault(); event.stopPropagation(); deleteProject('{{ $p->id }}', '{{ addslashes($p->name) }}')" class="p-1 text-textsub hover:text-red-500 rounded transition-colors" title="Hapus Proyek" aria-label="Hapus proyek">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/></svg>
                                    </button>
                                @endif
                                @if($isCurrentProj)
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="px-3 py-2 text-[12px] text-textsub italic">Belum ada proyek.</p>
                @endif
              </div>

              <div class="border-t border-borderc my-1"></div>
              <button onclick="openModal('modal-new-project')" class="w-full text-left px-3 py-2 text-[12px] font-semibold text-primary hover:bg-blue-50 flex items-center gap-1.5 transition-colors">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                  Tambah Proyek Baru
              </button>
              <button onclick="openModal('modal-switch-workspace')" class="w-full text-left px-3 py-2 text-[12px] font-medium text-textsub hover:bg-slate-50 flex items-center gap-1.5 transition-colors">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                  Ganti / Kelola Workspace...
              </button>
            </div>
          </div>
        </nav>

        <!-- Right Actions: "Buat Tugas Baru" button & Account Dropdown -->
        <div class="flex items-center gap-3">
          <button id="openAddTaskBtn" onclick="openCreateTaskModal()" class="focus-ring hidden sm:inline-flex items-center gap-2 bg-primary hover:bg-blue-600 transition-colors text-white text-body-md font-semibold px-4 sm:px-5 py-2.5 rounded shadow-sm">
            Buat Tugas Baru
          </button>
          
          <button id="openAddTaskBtnMobile" onclick="openCreateTaskModal()" class="focus-ring sm:hidden w-9 h-9 flex items-center justify-center bg-primary text-white rounded shadow-sm" aria-label="Buat tugas baru">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </button>

          <!-- User Profile Dropdown -->
          <div class="relative" id="profile-dropdown-container">
            <button id="profile-dropdown-btn" class="focus-ring w-9 h-9 flex items-center justify-center rounded-full text-textsub hover:text-textmain transition-colors" aria-label="Akun">
                @auth
                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[12px]">
                        {{ auth()->user()->initials }}
                    </div>
                @else
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="10" r="3.2" stroke="currentColor" stroke-width="1.5"/><path d="M5.5 19c1.2-2.5 3.6-4 6.5-4s5.3 1.5 6.5 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                @endauth
            </button>

            <!-- Dropdown Menu -->
            <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2 w-56 bg-surface rounded-card border border-borderc py-2 z-50 shadow-md">
                @auth
                    <div class="px-4 py-2 border-b border-borderc">
                        <p class="text-body-md font-semibold text-textmain">{{ auth()->user()->name }}</p>
                        <p class="text-[12px] text-textsub truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('board') }}" class="flex items-center gap-2 px-4 py-2 text-body-md text-textmain hover:bg-bg transition-colors">
                        Board
                    </a>
                    <a href="{{ route('progress') }}" class="flex items-center gap-2 px-4 py-2 text-body-md text-textmain hover:bg-bg transition-colors">
                        Progress Overview
                    </a>
                    <a href="{{ route('activity-log') }}" class="flex items-center gap-2 px-4 py-2 text-body-md text-textmain hover:bg-bg transition-colors">
                        Activity Log
                    </a>
                    <button onclick="openModal('modal-switch-workspace')" class="w-full text-left flex items-center gap-2 px-4 py-2 text-body-md text-textmain hover:bg-bg transition-colors">
                        Kelola Workspace
                    </button>
                    <a href="{{ route('about') }}" target="_blank" class="flex items-center gap-2 px-4 py-2 text-body-md text-textmain hover:bg-bg transition-colors">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8"/><path d="M12 16v-4M12 8h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        Tentang & Panduan
                    </a>
                    <div class="border-t border-borderc my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-body-md text-accent hover:bg-red-50 transition-colors font-medium">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Keluar Akun
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-2 text-body-md text-textmain hover:bg-bg transition-colors">
                        Masuk Akun
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center gap-2 px-4 py-2 text-body-md text-textmain hover:bg-bg transition-colors">
                        Buat Akun Baru
                    </a>
                    <div class="border-t border-borderc my-1"></div>
                    <a href="{{ route('demo.login') }}" class="flex items-center gap-2 px-4 py-2 text-[12px] font-semibold text-primary hover:bg-blue-50 transition-colors">
                        Masuk Cepat Demo
                    </a>
                @endauth
            </div>
          </div>

        </div>
      </div>
    </header>

    <div class="flex flex-1 min-h-[calc(100vh-64px)]">

      <!-- ===================== SIDEBAR OVERLAY (mobile) ===================== -->
      <div id="sidebarBackdrop" class="backdrop-panel hidden fixed inset-0 bg-black/30 z-50 lg:hidden opacity-0"></div>

      <!-- ===================== SIDEBAR ===================== -->
      <aside id="sidebar" class="sidebar w-[255px] shrink-0 bg-surface border-r border-borderc flex flex-col pt-5 px-4 overflow-y-auto">
        <!-- Mobile Sidebar Close Header -->
        <div class="flex items-center justify-between pb-3 mb-2 border-b border-borderc lg:hidden shrink-0">
          <span class="text-[12px] font-bold text-textsub uppercase tracking-wider">Menu Navigasi</span>
          <button id="sidebarClose" type="button" class="w-8 h-8 flex items-center justify-center rounded text-textsub hover:text-textmain hover:bg-bg transition-colors" aria-label="Tutup menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </button>
        </div>

        <!-- Workspace Header Card (Clickable to switch workspace) -->
        <div onclick="openModal('modal-switch-workspace')" role="button" tabindex="0" class="group flex items-center gap-3 p-2 -mx-1 rounded-card hover:bg-blue-50/60 border border-transparent hover:border-blue-200 transition-all cursor-pointer mb-4" title="Klik untuk ganti workspace">
          <div class="w-10 h-10 rounded bg-primary text-white flex items-center justify-center font-extrabold text-[15px] shrink-0 shadow-sm">
            {{ isset($workspace) ? $workspace->initials : 'W' }}
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between">
              <p class="text-title-md text-[14px] font-bold text-textmain leading-tight truncate group-hover:text-primary transition-colors">
                  {{ isset($workspace) ? $workspace->name : 'PT WUNAWAN' }}
              </p>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="text-textsub group-hover:text-primary transition-colors shrink-0 ml-1"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="flex items-center gap-1.5 mt-0.5">
                <span class="text-[10px] text-textsub font-bold tracking-wide uppercase">WORKSPACE</span>
                @if(isset($workspace))
                    <span class="text-[10px] text-borderc">•</span>
                    <span class="text-[10px] font-semibold {{ $workspace->isAdmin(auth()->user()) ? 'text-primary' : 'text-textsub' }}">
                        {{ $workspace->isAdmin(auth()->user()) ? 'ADMIN' : 'MEMBER' }}
                    </span>
                @endif
            </div>
          </div>
        </div>

        <nav class="space-y-1">
          <!-- 1. Board -->
          <a href="{{ route('board') }}" class="nav-item focus-ring w-full flex items-center gap-3 px-3 py-2.5 rounded transition-colors {{ request()->routeIs('board') ? 'bg-primary text-white font-semibold text-body-md shadow-sm' : 'text-textsub hover:bg-bg hover:text-textmain text-body-md font-medium' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.7"/><rect x="14" y="3" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.7"/><rect x="3" y="14" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.7"/><rect x="14" y="14" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.7"/></svg>
            Board
          </a>

          <!-- 2. Progress Overview -->
          <a href="{{ route('progress') }}" class="nav-item focus-ring w-full flex items-center gap-3 px-3 py-2.5 rounded transition-colors {{ request()->routeIs('progress') ? 'bg-primary text-white font-semibold text-body-md shadow-sm' : 'text-textsub hover:bg-bg hover:text-textmain text-body-md font-medium' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M7 15v-3M12 15V8M17 15v-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
            Progress Overview
          </a>

          <!-- 3. Activity Log -->
          <a href="{{ route('activity-log') }}" class="nav-item focus-ring w-full flex items-center gap-3 px-3 py-2.5 rounded transition-colors {{ request()->routeIs('activity-log') ? 'bg-primary text-white font-semibold text-body-md shadow-sm' : 'text-textsub hover:bg-bg hover:text-textmain text-body-md font-medium' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 7v5l3.5 2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Activity Log
          </a>

          <!-- 4. Panduan & Bantuan -->
          <a href="{{ route('about') }}" target="_blank" class="nav-item focus-ring w-full flex items-center gap-3 px-3 py-2.5 rounded transition-colors text-textsub hover:bg-bg hover:text-textmain text-body-md font-medium">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.7"/><path d="M12 16v-4M12 8h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Panduan & Tentang
          </a>
        </nav>

        <!-- Dedicated Project List in Sidebar -->
        <div class="mt-5 pt-4 border-t border-borderc">
          <div class="flex items-center justify-between px-2 mb-2">
            <span class="text-[11px] font-bold text-textsub uppercase tracking-wider">PROYEK ({{ isset($projects) ? $projects->count() : 0 }})</span>
            <button onclick="openModal('modal-new-project')" title="Tambah Proyek Baru" class="text-primary hover:text-blue-700 text-[11px] font-bold flex items-center gap-1 transition-colors">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                Tambah
            </button>
          </div>
          
          <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
            @if(isset($projects) && $projects->isNotEmpty())
                @foreach($projects as $p)
                    @php $isCurrentProj = (isset($project) && $project->id == $p->id); @endphp
                    <div class="flex items-center justify-between px-2.5 py-1.5 rounded transition-colors group {{ $isCurrentProj ? 'bg-blue-50 border-l-2 border-primary' : 'hover:bg-bg' }}" title="{{ $p->name }}">
                        <a href="{{ route('projects.switch', $p->id) }}" class="flex-1 truncate pr-2 text-[13px] {{ $isCurrentProj ? 'font-bold text-primary' : 'text-textsub group-hover:text-textmain font-medium' }}">
                            {{ $p->name }}
                        </a>
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $isCurrentProj ? 'bg-primary text-white font-bold' : 'bg-slate-100 text-textsub' }}">
                                {{ $p->tasks_count ?? $p->tasks()->count() }}
                            </span>
                            @if(isset($workspace) && ($workspace->isAdmin(auth()->user()) || (auth()->check() && $p->created_by === auth()->id())))
                                <button type="button" onclick="deleteProject('{{ $p->id }}', '{{ addslashes($p->name) }}')" class="p-1 text-textsub hover:text-red-500 rounded transition-colors" title="Hapus Proyek" aria-label="Hapus proyek">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="px-2 py-2 text-[12px] text-textsub italic">
                    Belum ada proyek.
                </div>
            @endif
          </div>
        </div>

        <!-- Member / Admin Section in Sidebar -->
        @if(isset($workspace) && $workspace->isAdmin(auth()->user()))
            <div class="mt-5 p-3 bg-blue-50/70 border border-blue-100 rounded-card space-y-2">
                <div class="flex items-center justify-between text-[11px] text-textsub">
                    <span class="font-bold text-primary flex items-center gap-1">
                        Admin Akses
                    </span>
                    <button onclick="copyShareableInviteLink('{{ $workspace->invite_url }}')" class="text-primary hover:underline font-semibold text-[11px]" title="Salin Link">
                        Salin Link
                    </button>
                </div>
                <button onclick="openModal('modal-add-member')" class="w-full text-center py-2 text-[12px] font-bold text-white bg-primary hover:bg-blue-600 rounded transition-colors flex items-center justify-center gap-1.5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M16 3.13a4 4 0 010 7.75M23 21v-2a4 4 0 00-3-3.87M9 11a4 4 0 100-8 4 4 0 000 8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Kelola Anggota ({{ $workspace->members_count ?? $workspace->members()->count() }})
                </button>
            </div>
        @elseif(isset($workspace))
            <!-- Non-admin Member Section in Sidebar -->
            <div class="mt-5 p-3 bg-surface border border-borderc rounded-card space-y-2">
                <div class="flex items-center justify-between text-[11px]">
                    <span class="font-semibold text-textsub flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Anggota Tim
                    </span>
                    <button onclick="openModal('modal-add-member')" class="text-primary hover:underline font-semibold text-[11px]">
                        Lihat Anggota
                    </button>
                </div>
                @if($workspace->owner_id !== auth()->id())
                    <button type="button" onclick="confirmLeaveWorkspace({{ $workspace->id }}, '{{ addslashes($workspace->name) }}')" class="w-full text-center py-1.5 text-[12px] font-bold text-accent hover:bg-red-50 rounded border border-red-200 transition-colors flex items-center justify-center gap-1">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                        Keluar dari Workspace
                    </button>
                @endif
            </div>
        @endif

        <div class="mt-auto pt-5 pb-4 space-y-3">
          <button id="newProjectBtn" onclick="openModal('modal-new-project')" class="focus-ring w-full inline-flex items-center justify-center gap-2 bg-primary hover:bg-blue-600 transition-colors text-white text-body-md font-semibold py-2.5 rounded shadow-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            New Project
          </button>

          @auth
            <!-- Sidebar User Profile & Quick Logout -->
            <div class="pt-2 border-t border-borderc flex items-center justify-between px-1">
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-7 h-7 rounded-full bg-blue-100 text-primary flex items-center justify-center font-bold text-[11px] shrink-0">
                  {{ auth()->user()->initials }}
                </div>
                <span class="text-[12px] font-semibold text-textmain truncate">{{ auth()->user()->name }}</span>
              </div>
              <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="focus-ring p-1 text-textsub hover:text-accent hover:bg-red-50 rounded transition-colors" title="Keluar / Logout">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
              </form>
            </div>
          @endauth
        </div>
      </aside>

      <!-- ===================== MAIN CONTENT ===================== -->
      <main class="flex-1 min-w-0 flex flex-col overflow-y-auto">

        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 p-3.5 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 text-body-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-[18px] leading-none px-1">×</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 p-3.5 rounded bg-red-50 border border-red-200 text-accent text-body-md flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-accent text-[18px] leading-none px-1">×</button>
            </div>
        @endif

        @yield('content')
      </main>
    </div>

    <!-- ===================== MODALS ===================== -->

    <!-- 1. Modal Buat Tugas Baru -->
    <div id="modal-create-task" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-3 sm:p-4 bg-black/50 overflow-y-auto">
        <div class="modal-panel bg-surface rounded-card max-w-lg w-full border border-borderc overflow-hidden shadow-2xl my-auto max-h-[92vh] flex flex-col">
            <!-- Header (Sticky) -->
            <div class="px-4 sm:px-5 py-3.5 border-b border-borderc flex items-center justify-between bg-bg shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded bg-blue-50 text-primary flex items-center justify-center font-bold">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <h3 class="text-title-md text-[15px] sm:text-[16px] text-textmain">Buat Tugas Baru</h3>
                </div>
                <button type="button" onclick="closeModal('modal-create-task')" class="text-textsub hover:text-textmain text-[22px] leading-none p-1">×</button>
            </div>

            <!-- Form with scrollable body and sticky footer -->
            <form id="form-create-task" method="POST" action="{{ route('tasks.store') }}" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <input type="hidden" name="project_id" id="create-task-project-id" value="{{ $project->id ?? '' }}">

                <!-- Scrollable Form Body -->
                <div class="overflow-y-auto p-4 sm:p-5 space-y-3.5 flex-1 max-h-[calc(92vh-125px)]">
                    <!-- Judul Tugas -->
                    <div>
                        <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Judul Tugas <span class="text-accent">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Finalisasi Arsitektur Sistem" class="focus-ring w-full px-3 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none transition-colors">
                    </div>

                    <!-- Deskripsi Tugas -->
                    <div>
                        <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Deskripsi Tugas</label>
                        <textarea name="description" rows="2" placeholder="Jelaskan detail yang perlu dikerjakan..." class="focus-ring w-full px-3 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none transition-colors resize-y"></textarea>
                    </div>

                    <!-- Pemilih Warna Prioritas -->
                    <div>
                        <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1.5">Tingkat Prioritas & Warna <span class="text-accent">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            
                            <label class="relative flex items-center p-2 rounded border border-borderc hover:border-red-400 bg-surface cursor-pointer transition select-none has-[:checked]:border-accent has-[:checked]:bg-red-50/50">
                                <input type="radio" name="priority" value="didahulukan" class="sr-only" checked>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-[2px] bg-accent shrink-0"></span>
                                    <div class="min-w-0">
                                        <p class="text-[12px] font-bold text-accent truncate">Critical / Utama</p>
                                        <p class="text-[10px] text-textsub truncate">Prioritas utama</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-2 rounded border border-borderc hover:border-amber-400 bg-surface cursor-pointer transition select-none has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50/50">
                                <input type="radio" name="priority" value="perlu_diperhatikan" class="sr-only">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-[2px] bg-amber-600 shrink-0"></span>
                                    <div class="min-w-0">
                                        <p class="text-[12px] font-bold text-amber-700 truncate">High / Perhatian</p>
                                        <p class="text-[10px] text-textsub truncate">Perlu perhatian</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-2 rounded border border-borderc hover:border-blue-400 bg-surface cursor-pointer transition select-none has-[:checked]:border-primary has-[:checked]:bg-blue-50/50">
                                <input type="radio" name="priority" value="eksternal" class="sr-only">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-[2px] bg-primary shrink-0"></span>
                                    <div class="min-w-0">
                                        <p class="text-[12px] font-bold text-primary truncate">Medium / Eksternal</p>
                                        <p class="text-[10px] text-textsub truncate">Tugas tambahan</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-2 rounded border border-borderc hover:border-emerald-400 bg-surface cursor-pointer transition select-none has-[:checked]:border-success has-[:checked]:bg-emerald-50/50">
                                <input type="radio" name="priority" value="biasa" class="sr-only">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-[2px] bg-success shrink-0"></span>
                                    <div class="min-w-0">
                                        <p class="text-[12px] font-bold text-success truncate">Low / Biasa</p>
                                        <p class="text-[10px] text-textsub truncate">Tugas reguler</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Status, Deadline & Assignee -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-0.5">
                        <div>
                            <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Status Kolom</label>
                            <select name="status" id="create-task-status" class="focus-ring w-full px-2.5 py-2 bg-surface border border-borderc rounded text-[12px] text-textmain focus:border-primary outline-none">
                                <option value="todo">To-Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="done">Done</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Tenggat Waktu</label>
                            <input type="date" name="due_date" value="{{ date('Y-m-d') }}" class="focus-ring w-full px-2.5 py-2 bg-surface border border-borderc rounded text-[12px] text-textmain focus:border-primary outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Penanggung Jawab</label>
                            <input type="text" name="assignee_name" placeholder="Nama assignee" value="{{ auth()->user()->name ?? 'Budi Santoso' }}" class="focus-ring w-full px-2.5 py-2 bg-surface border border-borderc rounded text-[12px] text-textmain focus:border-primary outline-none">
                        </div>
                    </div>
                </div>

                <!-- Actions (Sticky at bottom) -->
                <div class="px-4 sm:px-5 py-3 border-t border-borderc flex items-center justify-end gap-2 bg-bg shrink-0">
                    <button type="button" onclick="closeModal('modal-create-task')" class="focus-ring px-4 py-2 rounded border border-borderc text-textmain text-body-md font-medium hover:bg-surface transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="focus-ring px-5 py-2 rounded bg-primary hover:bg-blue-600 text-white text-body-md font-bold transition-colors flex items-center gap-1.5 shadow-sm">
                        Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Modal Quick Status & Task Detail Changer (1-Klik) -->
    <div id="modal-quick-status" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/40">
        <div class="modal-panel bg-surface rounded-card max-w-md w-full border border-borderc overflow-hidden shadow-xl max-h-[95vh] flex flex-col">
            <div class="px-5 py-4 border-b border-borderc flex items-center justify-between bg-bg shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded bg-blue-50 text-primary flex items-center justify-center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M4 8h13M17 8l-3-3M17 8l-3 3M20 16H7M7 16l3-3M7 16l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="text-title-md text-[15px] text-textmain">Pindahkan / Ubah Status</h3>
                </div>
                <button onclick="closeModal('modal-quick-status')" class="text-textsub hover:text-textmain text-[20px] leading-none px-1">×</button>
            </div>

            <div class="p-5 overflow-y-auto flex-1 space-y-4">
                <div>
                    <span id="quick-status-badge" class="inline-block text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded mb-2"></span>
                    <h4 id="quick-status-task-title" class="text-title-md text-[16px] text-textmain leading-snug"></h4>
                    <p id="quick-status-task-desc" class="text-body-md text-[13px] text-textsub mt-1.5 leading-relaxed whitespace-pre-wrap"></p>
                </div>

                <div class="space-y-2 pt-3 border-t border-borderc">
                    <p class="text-[11px] font-bold text-textsub uppercase tracking-wider">Pilih Status Baru (1-Klik):</p>
                    
                    <div class="grid grid-cols-1 gap-2">
                        <button onclick="executeQuickStatus('todo')" class="status-option w-full text-left flex items-center justify-between p-3 rounded border border-borderc hover:border-primary hover:bg-blue-50 font-semibold text-body-md text-textmain transition-colors">
                            <span class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-[2px] bg-slate-400"></span>
                                To-Do
                            </span>
                            <span class="text-textsub text-[12px]">Pindahkan</span>
                        </button>

                        <button onclick="executeQuickStatus('in_progress')" class="status-option w-full text-left flex items-center justify-between p-3 rounded border border-blue-200 bg-blue-50/40 hover:bg-blue-50 font-semibold text-body-md text-primary transition-colors">
                            <span class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-[2px] bg-primary"></span>
                                In Progress
                            </span>
                            <span class="text-primary text-[12px]">Pindahkan</span>
                        </button>

                        <button onclick="executeQuickStatus('review')" class="status-option w-full text-left flex items-center justify-between p-3 rounded border border-amber-200 bg-amber-50/40 hover:bg-amber-50 font-semibold text-body-md text-amber-700 transition-colors">
                            <span class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-[2px] bg-amber-600"></span>
                                Review
                            </span>
                            <span class="text-amber-700 text-[12px]">Pindahkan</span>
                        </button>

                        <button onclick="executeQuickStatus('done')" class="status-option w-full text-left flex items-center justify-between p-3 rounded border border-emerald-200 bg-emerald-50/40 hover:bg-emerald-50 font-semibold text-body-md text-success transition-colors">
                            <span class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-[2px] bg-success"></span>
                                Done (Selesai)
                            </span>
                            <span class="text-success text-[12px]">Selesaikan</span>
                        </button>
                    </div>
                </div>

                <div class="pt-3 border-t border-borderc flex justify-between items-center text-label-sm">
                    <span id="quick-status-assignee" class="text-textsub font-medium truncate max-w-[200px]"></span>
                    <button type="button" onclick="deleteCurrentTask()" class="text-accent hover:underline font-semibold flex items-center gap-1">
                        Hapus Tugas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Modal Switch / Manage Workspace -->
    <div id="modal-switch-workspace" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-3 sm:p-4 bg-black/50 overflow-y-auto">
        <div class="modal-panel bg-surface rounded-card max-w-lg w-full border border-borderc overflow-hidden shadow-2xl my-auto max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-5 py-4 border-b border-borderc flex items-center justify-between bg-bg shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded bg-blue-50 text-primary flex items-center justify-center font-bold">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.8"/><rect x="14" y="3" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="14" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.8"/><rect x="14" y="14" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.8"/></svg>
                    </div>
                    <div>
                        <h3 class="text-title-md text-[16px] text-textmain">Kelola & Ganti Workspace</h3>
                        <p class="text-[12px] text-textsub">Pilih atau buat workspace untuk tim dan proyek Anda</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-switch-workspace')" class="text-textsub hover:text-textmain text-[22px] leading-none p-1">×</button>
            </div>

            <!-- Modal Navigation Tabs -->
            <div class="flex border-b border-borderc bg-surface px-5 pt-1 shrink-0 gap-4">
                <button id="btn-ws-tab-list" type="button" onclick="switchWorkspaceModalTab('ws-tab-list')" class="ws-modal-tab-btn pb-2.5 pt-2 border-b-2 border-primary text-primary font-bold text-[13px] transition-colors flex items-center gap-1.5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Workspace Saya ({{ isset($userWorkspaces) ? $userWorkspaces->count() : 0 }})
                </button>
                <button id="btn-ws-tab-create" type="button" onclick="switchWorkspaceModalTab('ws-tab-create')" class="ws-modal-tab-btn pb-2.5 pt-2 border-b-2 border-transparent text-textsub hover:text-textmain font-medium text-[13px] transition-colors flex items-center gap-1.5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Buat Baru
                </button>
                <button id="btn-ws-tab-join" type="button" onclick="switchWorkspaceModalTab('ws-tab-join')" class="ws-modal-tab-btn pb-2.5 pt-2 border-b-2 border-transparent text-textsub hover:text-textmain font-medium text-[13px] transition-colors flex items-center gap-1.5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M15 7h3a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h3M12 3v10M8 7l4-4 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Gabung Tim
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="overflow-y-auto p-5 flex-1 max-h-[calc(90vh-140px)]">
                <!-- Tab 1: Workspace List -->
                <div id="ws-tab-list" class="ws-modal-tab-content space-y-2.5">
                    @if(isset($userWorkspaces) && $userWorkspaces->isNotEmpty())
                        <div class="space-y-2">
                            @foreach($userWorkspaces as $ws)
                                @php
                                    $isCurrent = (isset($workspace) && $workspace->id === $ws->id);
                                    $isAdmin = $ws->isAdmin(auth()->user());
                                @endphp
                                <div class="flex items-center justify-between p-3.5 rounded-card border transition-colors {{ $isCurrent ? 'border-primary bg-blue-50/50 shadow-sm' : 'border-borderc hover:border-slate-400 bg-surface' }}">
                                    <div class="flex items-center gap-3 min-w-0 flex-1 pr-3">
                                        <div class="w-10 h-10 rounded bg-primary text-white flex items-center justify-center font-bold text-[14px] shrink-0">
                                            {{ $ws->initials }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-[14px] font-bold text-textmain truncate">{{ $ws->name }}</h4>
                                                @if($isCurrent)
                                                    <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-2 py-0.5 rounded-full">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-0.5 text-[11px] text-textsub">
                                                <span class="font-semibold {{ $isAdmin ? 'text-primary' : 'text-textsub' }}">
                                                    {{ $isAdmin ? 'Admin / Owner' : 'Member' }}
                                                </span>
                                                <span>•</span>
                                                <span>{{ $ws->projects_count ?? $ws->projects()->count() }} Proyek</span>
                                                <span>•</span>
                                                <span>{{ $ws->members_count ?? $ws->members()->count() }} Anggota</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        @if($isCurrent)
                                            <span class="text-[12px] font-bold text-primary flex items-center gap-1">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                Dipilih
                                            </span>
                                        @else
                                            <a href="{{ route('workspaces.switch', $ws->id) }}" class="focus-ring px-3.5 py-1.5 bg-primary hover:bg-blue-600 text-white text-[12px] font-bold rounded transition-colors inline-flex items-center gap-1 shadow-sm">
                                                Beralih
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </a>
                                        @endif

                                        @if($ws->owner_id !== auth()->id())
                                            <button type="button" onclick="confirmLeaveWorkspace({{ $ws->id }}, '{{ addslashes($ws->name) }}')" class="focus-ring text-[11px] font-bold text-textsub hover:text-accent hover:bg-red-50 px-2 py-1.5 rounded border border-borderc hover:border-red-200 transition-colors" title="Keluar dari workspace '{{ $ws->name }}'">
                                                Keluar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-textsub">
                            <p class="text-body-md">Belum ada workspace.</p>
                        </div>
                    @endif
                </div>

                <!-- Tab 2: Create Workspace -->
                <div id="ws-tab-create" class="ws-modal-tab-content hidden">
                    <form method="POST" action="{{ route('workspaces.store') }}" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Nama Workspace <span class="text-accent">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: PT Inovasi Digital" class="focus-ring w-full px-3.5 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                            <textarea name="description" rows="2" placeholder="Deskripsi tim atau workspace..." class="focus-ring w-full px-3.5 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none"></textarea>
                        </div>
                        <div class="p-3 bg-blue-50/60 rounded border border-blue-100 text-[12px] text-textsub">
                            <p>Anda akan otomatis menjadi <strong class="text-primary font-bold">Admin/Owner</strong> di workspace ini dan dapat mengundang anggota tim lainnya.</p>
                        </div>
                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" onclick="switchWorkspaceModalTab('ws-tab-list')" class="focus-ring px-3.5 py-2 border border-borderc text-textmain text-body-md font-medium rounded hover:bg-bg transition-colors">Batal</button>
                            <button type="submit" class="focus-ring px-4 py-2 bg-primary hover:bg-blue-600 text-white text-body-md font-bold rounded transition-colors shadow-sm">+ Buat Workspace</button>
                        </div>
                    </form>
                </div>

                <!-- Tab 3: Join Workspace -->
                <div id="ws-tab-join" class="ws-modal-tab-content hidden">
                    <form method="POST" action="{{ route('workspaces.join') }}" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Kode Undangan <span class="text-accent">*</span></label>
                            <input type="text" name="invite_code" required placeholder="Contoh: 8 karakter kode (cth: AB12CD34)" class="focus-ring w-full px-3.5 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none font-mono uppercase">
                            <p class="text-[11px] text-textsub mt-1">Dapatkan kode undangan dari Admin workspace yang ingin Anda ikuti.</p>
                        </div>
                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" onclick="switchWorkspaceModalTab('ws-tab-list')" class="focus-ring px-3.5 py-2 border border-borderc text-textmain text-body-md font-medium rounded hover:bg-bg transition-colors">Batal</button>
                            <button type="submit" class="focus-ring px-4 py-2 bg-textmain hover:bg-black text-white text-body-md font-bold rounded transition-colors shadow-sm">Gabung Workspace</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Modal Tambah Proyek -->
    <div id="modal-new-project" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/40">
        <div class="modal-panel bg-surface rounded-card max-w-md w-full border border-borderc overflow-hidden shadow-xl">
            <div class="px-5 py-4 border-b border-borderc flex items-center justify-between bg-bg">
                <h3 class="text-title-md text-[15px] text-textmain">Tambah Proyek Baru</h3>
                <button onclick="closeModal('modal-new-project')" class="text-textsub hover:text-textmain text-[20px] leading-none px-1">×</button>
            </div>

            <form method="POST" action="{{ route('projects.store') }}" class="p-5 space-y-3.5">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Nama Proyek <span class="text-accent">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Proyek Akhir RPL" class="focus-ring w-full px-3.5 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi tujuan proyek..." class="focus-ring w-full px-3.5 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-new-project')" class="focus-ring px-3.5 py-2 border border-borderc text-textmain text-body-md font-medium rounded hover:bg-bg transition-colors">Batal</button>
                    <button type="submit" class="focus-ring px-4 py-2 bg-primary hover:bg-blue-600 text-white text-body-md font-bold rounded transition-colors">Simpan Proyek</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Modal Kelola & Anggota Tim -->
    @if(isset($workspace))
    <div id="modal-add-member" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-3 sm:p-4 bg-black/50 overflow-y-auto">
        <div class="modal-panel bg-surface rounded-card max-w-lg w-full border border-borderc overflow-hidden shadow-2xl my-auto max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-5 py-3.5 border-b border-borderc flex items-center justify-between bg-bg shrink-0">
                <div class="min-w-0 pr-3">
                    <h3 class="text-title-md text-[15px] text-textmain font-bold truncate">
                        {{ $workspace->isAdmin(auth()->user()) ? 'Kelola & Anggota Tim' : 'Anggota Workspace' }}
                    </h3>
                    <p class="text-[11px] text-textsub truncate">{{ $workspace->name }}</p>
                </div>
                <button type="button" onclick="closeModal('modal-add-member')" class="text-textsub hover:text-textmain text-[22px] leading-none p-1">×</button>
            </div>

            @if($workspace->isAdmin(auth()->user()))
            <!-- Modal Tabs (Admin Only) -->
            <div class="px-5 pt-2 border-b border-borderc bg-surface flex items-center gap-5 shrink-0">
                <button id="btn-member-tab-list" type="button" onclick="switchMemberModalTab('member-tab-list')" class="member-modal-tab-btn pb-2.5 pt-1.5 border-b-2 border-primary text-primary font-bold text-[13px] transition-colors flex items-center gap-1.5">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M16 3.13a4 4 0 010 7.75M23 21v-2a4 4 0 00-3-3.87M9 11a4 4 0 100-8 4 4 0 000 8z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Anggota ({{ $workspace->members->count() }})
                </button>
                <button id="btn-member-tab-invite" type="button" onclick="switchMemberModalTab('member-tab-invite')" class="member-modal-tab-btn pb-2.5 pt-1.5 border-b-2 border-transparent text-textsub hover:text-textmain font-medium text-[13px] transition-colors flex items-center gap-1.5">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M20 8v6M23 11h-6M9 11a4 4 0 100-8 4 4 0 000 8z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Undang Anggota
                </button>
            </div>
            @endif

            <!-- Modal Content (Scrollable) -->
            <div class="p-5 overflow-y-auto flex-1 max-h-[calc(90vh-140px)] space-y-4">
                
                <!-- Tab 1: Member List -->
                <div id="member-tab-list" class="member-modal-tab-content space-y-3">
                    <div class="flex items-center justify-between text-[11px] text-textsub pb-1">
                        <span class="font-bold uppercase tracking-wider">Daftar Anggota Tim</span>
                        <span>{{ $workspace->members->count() }} orang</span>
                    </div>

                    <div class="space-y-2">
                        @foreach($workspace->members as $m)
                            @php
                                $isSelf = (auth()->id() === $m->id);
                                $isOwner = ($workspace->owner_id === $m->id);
                                $isTargetAdmin = ($m->pivot->role === 'admin' || $isOwner);
                                $canKick = false;
                                if (!$isOwner && !$isSelf) {
                                    if ($workspace->owner_id === auth()->id()) {
                                        $canKick = true;
                                    } elseif ($workspace->isAdmin(auth()->user()) && !$isTargetAdmin) {
                                        $canKick = true;
                                    }
                                }
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-bg border border-borderc rounded-card hover:border-slate-300 transition-colors">
                                <div class="flex items-center gap-3 min-w-0 flex-1 pr-2">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-primary flex items-center justify-center font-bold text-[12px] shrink-0">
                                        {{ $m->initials ?? strtoupper(substr($m->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[13px] font-bold text-textmain truncate">{{ $m->name }}</span>
                                            @if($isSelf)
                                                <span class="text-[10px] bg-blue-100 text-primary font-bold px-1.5 py-0.2 rounded">Anda</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-textsub truncate block">{{ $m->email }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($isOwner)
                                        <span class="text-[10.5px] font-extrabold px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 uppercase tracking-wide">
                                            Owner
                                        </span>
                                    @elseif($m->pivot->role === 'admin')
                                        <span class="text-[10.5px] font-bold px-2 py-0.5 rounded bg-blue-50 text-primary border border-blue-200 uppercase tracking-wide">
                                            Admin
                                        </span>
                                    @else
                                        <span class="text-[10.5px] font-medium px-2 py-0.5 rounded bg-slate-100 text-textsub uppercase tracking-wide">
                                            Member
                                        </span>
                                    @endif

                                    @if($canKick)
                                        <button type="button" onclick="confirmRemoveMember({{ $workspace->id }}, {{ $m->id }}, '{{ addslashes($m->name) }}')" class="focus-ring text-[11px] font-bold text-accent hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded border border-red-200 transition-colors inline-flex items-center gap-1" title="Keluarkan dari workspace">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M17 11h6"/></svg>
                                            Keluarkan
                                        </button>
                                    @endif


                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($workspace->owner_id !== auth()->id())
                        <div class="mt-4 pt-3 border-t border-borderc flex items-center justify-between bg-red-50/50 p-3 rounded border border-red-100">
                            <div>
                                <p class="text-[12px] font-bold text-accent">Keluar dari Workspace</p>
                            </div>
                            <button type="button" onclick="confirmLeaveWorkspace({{ $workspace->id }}, '{{ addslashes($workspace->name) }}')" class="focus-ring text-[12px] font-bold text-white bg-accent hover:bg-red-700 px-3 py-1.5 rounded transition-colors shrink-0">
                                Keluar Sekarang
                            </button>
                        </div>
                    @endif
                </div>

                @if($workspace->isAdmin(auth()->user()))
                <!-- Tab 2: Invite / Add Member (Admin Only) -->
                <div id="member-tab-invite" class="member-modal-tab-content hidden space-y-4">
                    <!-- Direct Shareable Invitation Link & Code -->
                    <div class="p-3.5 bg-blue-50/60 border border-blue-200 rounded-card space-y-3">
                        <!-- Kode Pendek -->
                        <div>
                            <p class="text-[11px] font-bold text-primary uppercase tracking-wider mb-1.5">Kode Undangan:</p>
                            <div class="flex items-center gap-2">
                                <input type="text" readonly value="{{ $workspace->invite_code }}" class="flex-1 px-2.5 py-1.5 bg-surface border border-blue-200 rounded text-[14px] font-bold text-textmain tracking-widest font-mono text-center select-all">
                                <button onclick="copyShareableInviteLink('{{ $workspace->invite_code }}')" class="focus-ring px-3 py-1.5 bg-primary hover:bg-blue-600 text-white font-bold text-[12px] rounded transition-colors shrink-0">
                                    Salin
                                </button>
                            </div>
                        </div>
                        
                        <!-- Link Lengkap -->
                        <div>
                            <p class="text-[11px] font-bold text-primary uppercase tracking-wider mb-1.5">Link Undangan Langsung:</p>
                            <div class="flex items-center gap-2">
                                <input type="text" readonly value="{{ $workspace->invite_url }}" class="flex-1 px-2.5 py-1.5 bg-surface border border-blue-200 rounded text-[12px] text-textmain font-mono select-all">
                                <button onclick="copyShareableInviteLink('{{ $workspace->invite_url }}')" class="focus-ring px-3 py-1.5 border border-primary text-primary hover:bg-blue-50 font-bold text-[12px] rounded transition-colors shrink-0">
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah Langsung -->
                    <form method="POST" action="{{ route('workspaces.add-member') }}" class="space-y-3 pt-2 border-t border-borderc">
                        @csrf
                        <p class="text-[11px] font-bold text-textsub uppercase tracking-wider">Atau Tambahkan Manual ke Tim:</p>
                        <div>
                            <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Nama Anggota <span class="text-accent">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: Rina Novita" class="focus-ring w-full px-3 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider mb-1">Email (Opsional)</label>
                            <input type="email" name="email" placeholder="rina@example.com" class="focus-ring w-full px-3 py-2 bg-surface border border-borderc rounded text-body-md text-textmain focus:border-primary outline-none">
                        </div>
                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" onclick="switchMemberModalTab('member-tab-list')" class="focus-ring px-3.5 py-2 border border-borderc text-textmain text-body-md font-medium rounded hover:bg-bg transition-colors">Lihat Daftar Anggota</button>
                            <button type="submit" class="focus-ring px-4 py-2 bg-primary hover:bg-blue-600 text-white text-body-md font-bold rounded transition-colors">Tambahkan</button>
                        </div>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endif

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initDropdowns();
            initMobileSidebar();
        });

        function initDropdowns() {
            const projectBtn = document.getElementById('project-dropdown-btn');
            const projectMenu = document.getElementById('project-dropdown-menu');
            if (projectBtn && projectMenu) {
                projectBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    projectMenu.classList.toggle('hidden');
                });
            }

            const profileBtn = document.getElementById('profile-dropdown-btn');
            const profileMenu = document.getElementById('profile-dropdown-menu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                });
            }

            document.addEventListener('click', () => {
                if (projectMenu) projectMenu.classList.add('hidden');
                if (profileMenu) profileMenu.classList.add('hidden');
            });
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) {
                sidebar.classList.remove('open');
            }
            if (backdrop) {
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 200);
            }
        }

        function initMobileSidebar() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (toggleBtn && sidebar && backdrop) {
                const openSidebar = () => {
                    sidebar.classList.add('open');
                    backdrop.classList.remove('hidden');
                    setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
                };

                toggleBtn.addEventListener('click', openSidebar);
                if (closeBtn) closeBtn.addEventListener('click', closeMobileSidebar);
                backdrop.addEventListener('click', closeMobileSidebar);
            }
        }

        function openModal(id) {
            // Tutup sidebar mobile otomatis jika sedang terbuka agar tidak menutupi modal
            closeMobileSidebar();

            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('hidden');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.add('hidden');
        }

        // Tutup modal otomatis saat pengguna menyentuh area gelap (backdrop) di luar modal
        document.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('fixed') && e.target.id && e.target.id.startsWith('modal-')) {
                closeModal(e.target.id);
            }
        });

        // Tutup modal atau sidebar saat tombol Escape ditekan
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMobileSidebar();
                document.querySelectorAll('[id^="modal-"]:not(.hidden)').forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        });

        function switchWorkspaceModalTab(tabId) {
            document.querySelectorAll('.ws-modal-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.ws-modal-tab-btn').forEach(el => {
                el.classList.remove('text-primary', 'border-primary', 'font-bold');
                el.classList.add('text-textsub', 'border-transparent', 'font-medium');
            });
            
            const targetContent = document.getElementById(tabId);
            if (targetContent) targetContent.classList.remove('hidden');
            
            const activeBtn = document.getElementById('btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.remove('text-textsub', 'border-transparent', 'font-medium');
                activeBtn.classList.add('text-primary', 'border-primary', 'font-bold');
            }
        }

        function openCreateTaskModal(status = 'todo') {
            const statusSelect = document.getElementById('create-task-status');
            if (statusSelect) statusSelect.value = status;
            openModal('modal-create-task');
        }

        // Quick Status State & Card Click Handler
        let currentQuickTaskId = null;

        function openQuickStatusModal(taskId, taskTitle, taskDesc = '', badgeText = '', badgeClass = '', assignee = '', currentStatus = '') {
            currentQuickTaskId = taskId;
            
            const titleEl = document.getElementById('quick-status-task-title');
            if (titleEl) titleEl.innerText = taskTitle;

            const descEl = document.getElementById('quick-status-task-desc');
            if (descEl) descEl.innerText = taskDesc || '';

            const badgeEl = document.getElementById('quick-status-badge');
            if (badgeEl) {
                badgeEl.innerText = badgeText || '';
                badgeEl.className = 'inline-block text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded mb-2 ' + (badgeClass || 'bg-slate-100 text-textsub');
            }

            const assigneeEl = document.getElementById('quick-status-assignee');
            if (assigneeEl) assigneeEl.innerText = assignee ? ('Ditugaskan ke: ' + assignee) : '';

            openModal('modal-quick-status');
        }

        async function executeQuickStatus(newStatus) {
            if (!currentQuickTaskId) return;

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`{{ url('/tasks') }}/${currentQuickTaskId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                if (response.ok) {
                    closeModal('modal-quick-status');
                    window.location.reload();
                } else {
                    alert('Gagal memperbarui status tugas.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            }
        }

        async function deleteCurrentTask() {
            if (!currentQuickTaskId) return;
            if (!confirm('Apakah Anda yakin ingin menghapus tugas ini?')) return;

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`{{ url('/tasks') }}/${currentQuickTaskId}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    }
                });

                if (response.ok) {
                    closeModal('modal-quick-status');
                    window.location.reload();
                } else {
                    alert('Gagal menghapus tugas.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            }
        }

        async function deleteCurrentTaskFromCard(taskId) {
            if (!confirm('Apakah Anda yakin ingin menghapus tugas ini?')) return;

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`{{ url('/tasks') }}/${taskId}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Gagal menghapus tugas.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            }
        }

        function copyShareableInviteLink(link) {
            navigator.clipboard.writeText(link).then(() => {
                alert('Link undangan workspace disalin:\n' + link);
            });
        }

        async function deleteProject(projectId, projectName) {
            if (!confirm(`Apakah Anda yakin ingin menghapus proyek "${projectName}" beserta semua tugas di dalamnya? Tindakan ini tidak dapat dibatalkan.`)) return;

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`{{ url('/projects') }}/${projectId}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    }
                });

                if (response.ok) {
                    window.location.href = "{{ route('board') }}";
                } else {
                    const data = await response.json().catch(() => ({}));
                    alert(data.message || 'Gagal menghapus proyek.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            }
        }

        function confirmLeaveWorkspace(workspaceId, workspaceName) {
            if (!confirm(`Apakah Anda yakin ingin keluar dari workspace "${workspaceName}"?\n\nAnda tidak akan dapat mengakses proyek dan tugas di workspace ini lagi kecuali diundang kembali oleh Admin.`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('/workspaces') }}/${workspaceId}/leave`;

            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(token);

            document.body.appendChild(form);
            form.submit();
        }

        function confirmRemoveMember(workspaceId, userId, userName) {
            if (!confirm(`Keluarkan "${userName}" dari workspace ini?\n\nTugas yang sebelumnya ditugaskan ke anggota ini akan dilepaskan (unassigned).`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('/workspaces') }}/${workspaceId}/remove-member/${userId}`;

            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(token);

            document.body.appendChild(form);
            form.submit();
        }

        function switchMemberModalTab(tabId) {
            document.querySelectorAll('.member-modal-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.member-modal-tab-btn').forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary', 'font-bold');
                btn.classList.add('border-transparent', 'text-textsub', 'font-medium');
            });

            const activeTab = document.getElementById(tabId);
            if (activeTab) activeTab.classList.remove('hidden');

            const activeBtn = document.getElementById('btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.remove('border-transparent', 'text-textsub', 'font-medium');
                activeBtn.classList.add('border-primary', 'text-primary', 'font-bold');
            }
        }
    </script>
</body>
</html>
