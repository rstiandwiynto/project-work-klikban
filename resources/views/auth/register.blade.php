<!DOCTYPE html>
<html lang="id" class="h-full bg-bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - KlikBan Workspace</title>
    <meta name="description" content="Daftar akun KlikBan gratis dan buat workspace tim Anda untuk mengelola tugas dan proyek secara cepat.">
    
    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Pre-compiled & Minified Production CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
    <style>
        input:focus {
            border-color: #3B82F6;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }
    </style>
</head>
<body class="min-h-full flex flex-col items-center justify-center p-4 antialiased text-textmain bg-bg">

    <div class="w-full max-w-[420px] bg-white rounded-[4px] p-8 border border-borderc space-y-5 shadow-xs">
        
        <!-- Brand Header -->
        <div class="text-center space-y-1">
            <div class="inline-flex items-center justify-center gap-2 mb-1.5">
                <svg class="w-6 h-6" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="8" width="32" height="24" rx="4" stroke="#3B82F6" stroke-width="2.5" fill="white"/>
                    <line x1="4" y1="15" x2="36" y2="15" stroke="#3B82F6" stroke-width="2"/>
                    <line x1="10" y1="19" x2="24" y2="19" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
                    <line x1="10" y1="24" x2="18" y2="24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                    <path d="M22 20L31 29L26.5 30.5L30 37L27 38.5L23.5 32L19.5 35.5L22 20Z" fill="#3B82F6" stroke="white" stroke-width="1.5"/>
                </svg>
                <span class="text-xs font-bold text-primary">KlikBan</span>
            </div>
            
            <h1 class="text-[22px] font-bold text-textmain tracking-tight">Daftar Akun Baru</h1>
            <p class="text-[12px] text-textsub">Buat workspace Anda dan kelola tugas cepat</p>
        </div>

        @if($errors->any())
            <div class="p-2.5 bg-red-50 border border-red-200 rounded-[4px] text-[12px] text-accent font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Register -->
        <form method="POST" action="{{ route('register.submit') }}" class="space-y-3.5">
            @csrf

            <!-- Nama Lengkap -->
            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider">NAMA LENGKAP</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-textsub absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    <input 
                        type="text" 
                        name="name" 
                        required 
                        value="{{ old('name') }}"
                        placeholder="Contoh: Budi Santoso" 
                        class="w-full pl-9 pr-3 py-2 bg-white border border-borderc rounded-[4px] text-[13px] focus:outline-none transition-shadow"
                    >
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider">EMAIL</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-textsub absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        value="{{ old('email') }}"
                        placeholder="nama@gmail.com" 
                        class="w-full pl-9 pr-3 py-2 bg-white border border-borderc rounded-[4px] text-[13px] focus:outline-none transition-shadow"
                    >
                </div>
            </div>

            <!-- Workspace Name (Otomatis Admin!) -->
            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider">
                    NAMA WORKSPACE <span class="text-primary">(Otomatis Jadi Admin)</span>
                </label>
                <div class="relative">
                    <svg class="w-4 h-4 text-textsub absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>
                    </svg>
                    <input 
                        type="text" 
                        name="workspace_name" 
                        value="{{ old('workspace_name') }}"
                        placeholder="Contoh: PT WUNAWAN / Tim Proyek RPL" 
                        class="w-full pl-9 pr-3 py-2 bg-white border border-borderc rounded-[4px] text-[13px] focus:outline-none transition-shadow"
                    >
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-textmain uppercase tracking-wider">PASSWORD</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-textsub absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        placeholder="Minimal 6 karakter" 
                        class="w-full pl-9 pr-3 py-2 bg-white border border-borderc rounded-[4px] text-[13px] focus:outline-none transition-shadow"
                    >
                </div>
            </div>

            <!-- Button Submit -->
            <div class="pt-1">
                <button type="submit" class="w-full py-2.5 bg-primary hover:bg-blue-600 text-white font-bold rounded-[4px] text-[13px] transition flex items-center justify-center gap-2 shadow-sm">
                    <span>Daftar & Buat Workspace</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </form>

        <p class="text-center text-[12px] text-textsub">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">Masuk di sini</a>
        </p>

    </div>

    <div class="mt-6 flex items-center justify-center gap-6 text-[11px] text-textsub">
        <a href="{{ route('home') }}" class="hover:text-textmain transition">Home</a>
        <a href="{{ route('demo.login') }}" class="hover:text-primary transition font-medium">Masuk Demo</a>
    </div>

</body>
</html>
