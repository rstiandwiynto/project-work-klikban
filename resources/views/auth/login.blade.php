<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk — KlikBan</title>
<meta name="description" content="Masuk ke akun KlikBan Anda untuk mengelola tugas dan proyek tim Anda secara cepat.">
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'><rect x='4' y='8' width='32' height='24' rx='4' stroke='%233B82F6' stroke-width='2.5' fill='white'/><line x1='4' y1='15' x2='36' y2='15' stroke='%233B82F6' stroke-width='2'/><path d='M22 20L31 29L26.5 30.5L30 37L27 38.5L23.5 32L19.5 35.5L22 20Z' fill='%233B82F6'/></svg>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
<style>
  body { font-family: 'Inter', sans-serif; }
  .auth-bg {
    background: radial-gradient(ellipse 80% 60% at 30% 0%, #eef0fb 0%, #f2f3fb 45%, #e7e9f5 100%);
  }
  .focus-ring:focus-visible { outline: 2px solid #3B82F6; outline-offset: 2px; }
  input:focus {
    border-color: #3B82F6;
    background-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
  }
  @media (prefers-reduced-motion: reduce) {
    * { animation: none !important; transition: none !important; }
  }
</style>
</head>
<body class="antialiased auth-bg min-h-screen flex items-center justify-center px-4 py-10 sm:py-16">

  <div class="w-full max-w-[400px] flex flex-col items-center">

    <!-- ===================== CARD ===================== -->
    <div class="w-full bg-surface border border-borderc rounded-card shadow-[0_25px_60px_-20px_rgba(27,27,31,0.18)] px-6 sm:px-9 py-8 sm:py-10">

      <!-- Logo + brand -->
      <div class="flex flex-col items-center text-center">
        <a href="{{ route('home') }}" class="flex flex-col items-center focus-ring rounded">
          <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8">
            <rect x="4" y="8" width="32" height="24" rx="4" stroke="#3B82F6" stroke-width="2.5" fill="white"/>
            <line x1="4" y1="15" x2="36" y2="15" stroke="#3B82F6" stroke-width="2"/>
            <line x1="10" y1="19" x2="24" y2="19" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="10" y1="24" x2="18" y2="24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
            <path d="M22 20L31 29L26.5 30.5L30 37L27 38.5L23.5 32L19.5 35.5L22 20Z" fill="#3B82F6" stroke="white" stroke-width="1.5"/>
          </svg>
          <p class="mt-1 text-[13px] font-bold text-primary tracking-tight">KlikBan</p>
        </a>

        <h1 class="mt-5 text-[22px] font-extrabold text-textmain tracking-tight">KlikBan</h1>
        <p class="mt-1.5 text-body-md text-textsub">Workspace yang nyaman</p>
      </div>

      <!-- Flash / Validation Errors -->
      @if($errors->any())
        <div class="mt-5 p-3 bg-red-50 border border-red-200 rounded text-label-sm text-accent font-medium">
          {{ $errors->first() }}
        </div>
      @endif

      @if(session('status'))
        <div class="mt-5 p-3 bg-emerald-50 border border-emerald-200 rounded text-label-sm text-success font-medium">
          {{ session('status') }}
        </div>
      @endif

      <!-- Form -->
      <form id="loginForm" method="POST" action="{{ route('login.submit') }}" class="mt-6 space-y-4" novalidate>
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-label-sm uppercase tracking-wide text-textmain font-bold mb-2">Email</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-3.5 flex items-center text-textsub pointer-events-none">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <input 
              id="email" 
              name="email" 
              type="email" 
              autocomplete="email" 
              required
              value="{{ old('email', 'budi@klikban.com') }}"
              placeholder="nama@gmail.com"
              class="focus-ring w-full bg-bg border border-borderc rounded text-body-md text-textmain placeholder:text-textsub/70 pl-11 pr-4 py-3 outline-none transition-colors"
            >
          </div>
        </div>

        <!-- Password -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="text-label-sm uppercase tracking-wide text-textmain font-bold">Password</label>
            <a href="#" class="text-label-sm font-semibold text-primary hover:text-blue-600 transition-colors">Lupa Password?</a>
          </div>
          <div class="relative">
            <span class="absolute inset-y-0 left-3.5 flex items-center text-textsub pointer-events-none">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><rect x="4" y="10" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </span>
            <input 
              id="password" 
              name="password" 
              type="password" 
              autocomplete="current-password" 
              required
              value="password"
              placeholder="••••••••"
              class="focus-ring w-full bg-bg border border-borderc rounded text-body-md text-textmain placeholder:text-textsub/70 pl-11 pr-11 py-3 outline-none transition-colors"
            >
            <button type="button" id="togglePassword" aria-label="Tampilkan password"
              class="focus-ring absolute inset-y-0 right-3.5 flex items-center text-textsub hover:text-textmain transition-colors">
              <svg id="eyeOpen" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
              <svg id="eyeClosed" width="18" height="18" viewBox="0 0 24 24" fill="none" class="hidden"><path d="M3 3l18 18M10.6 10.6a3 3 0 004.24 4.24M9.9 5.1A10.6 10.6 0 0112 5c6.5 0 10 7 10 7a13.2 13.2 0 01-3.1 3.9M6.2 6.2C4 7.7 2.4 10 2 12c0 0 3.5 7 10 7 1.4 0 2.6-.3 3.7-.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
          </div>
        </div>

        <!-- Remember -->
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
          <input type="checkbox" name="remember" id="remember" class="focus-ring w-4 h-4 rounded border-borderc text-primary accent-primary cursor-pointer">
          <span class="text-body-md text-textmain">Tetap masuk di perangkat ini</span>
        </label>

        <!-- Submit -->
        <button type="submit"
          class="focus-ring w-full inline-flex items-center justify-center gap-2 bg-primary hover:bg-blue-600 transition-colors text-white text-body-md font-semibold py-3 rounded mt-2">
          Masuk
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>

      <!-- Divider -->
      <div class="mt-6 flex items-center gap-3">
        <span class="h-px flex-1 bg-borderc"></span>
        <span class="text-body-md text-textsub whitespace-nowrap">Atau masuk dengan</span>
        <span class="h-px flex-1 bg-borderc"></span>
      </div>

      @if(session('error'))
        <div class="mt-5 p-3 bg-red-50 border border-red-200 rounded text-label-sm text-accent font-medium">
          {{ session('error') }}
        </div>
      @endif

      @php
        $isFromMobileApp = str_contains(request()->header('User-Agent', ''), 'KlikBan') || request()->has('is_app');
      @endphp

      <!-- Google OAuth Button -->
      <a href="{{ route('auth.google', $isFromMobileApp ? ['source' => 'app'] : []) }}"
        id="googleLoginBtn"
        class="focus-ring mt-5 w-full inline-flex items-center justify-center gap-2.5 bg-bg border border-borderc hover:border-primary/50 transition-colors text-textmain text-body-md font-semibold py-3 rounded">
        <svg width="18" height="18" viewBox="0 0 48 48">
          <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3c-1.6 4.6-6 8-11.3 8-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.1 29.6 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5z"/>
          <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 15.9 18.9 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.1 29.6 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/>
          <path fill="#4CAF50" d="M24 44c5.5 0 10.4-1.9 14.2-5.1l-6.6-5.4c-2 1.5-4.6 2.5-7.6 2.5-5.3 0-9.7-3.4-11.3-8.1l-6.5 5C9.6 39.6 16.2 44 24 44z"/>
          <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.2 4.2-4.1 5.5l6.6 5.4C41.5 35.8 44 30.4 44 24c0-1.3-.1-2.7-.4-3.5z"/>
        </svg>
        Masuk dengan Google
      </a>

      <!-- Register Link -->
      <div class="mt-6 text-center">
        <p class="text-body-md text-textsub">
          Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-blue-600 transition-colors">Daftar sekarang</a>
        </p>
      </div>
    </div>

    <!-- Footer links -->
    <div class="mt-6 flex items-center gap-5">
      <a href="#" class="text-body-md text-textsub hover:text-primary transition-colors">Bantuan</a>
      <a href="#" class="text-body-md text-textsub hover:text-primary transition-colors">Privasi</a>
      <a href="#" class="text-body-md text-textsub hover:text-primary transition-colors">Ketentuan</a>
    </div>
  </div>

<script>
  const toggleBtn = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const eyeOpen = document.getElementById('eyeOpen');
  const eyeClosed = document.getElementById('eyeClosed');

  if (toggleBtn && passwordInput) {
    toggleBtn.addEventListener('click', () => {
      const isPassword = passwordInput.type === 'password';
      passwordInput.type = isPassword ? 'text' : 'password';
      eyeOpen.classList.toggle('hidden', isPassword);
      eyeClosed.classList.toggle('hidden', !isPassword);
      toggleBtn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
    });
  }

  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function() {
      const submitBtn = this.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80');
        submitBtn.innerHTML = `
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Memproses...
        `;
      }
    });
  }

  // Deteksi jika dibuka dari aplikasi mobile Android KlikBan
  if (navigator.userAgent.includes('KlikBan') || window.Capacitor !== undefined) {
    const googleBtn = document.getElementById('googleLoginBtn');
    if (googleBtn) {
      googleBtn.href = "{{ route('auth.google', ['source' => 'app']) }}";
    }
  }
</script>
</body>
</html>
