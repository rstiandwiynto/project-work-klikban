<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Berhasil — KlikBan</title>
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'><rect x='4' y='8' width='32' height='24' rx='4' stroke='%233B82F6' stroke-width='2.5' fill='white'/><line x1='4' y1='15' x2='36' y2='15' stroke='%233B82F6' stroke-width='2'/><path d='M22 20L31 29L26.5 30.5L30 37L27 38.5L23.5 32L19.5 35.5L22 20Z' fill='%233B82F6'/></svg>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
<style>
  body {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(ellipse 80% 60% at 30% 0%, #eef0fb 0%, #f2f3fb 45%, #e7e9f5 100%);
  }
</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10 antialiased">
  <div class="w-full max-w-[420px] bg-white border border-slate-200/80 rounded-2xl shadow-[0_20px_50px_-15px_rgba(27,27,31,0.12)] p-7 sm:p-9 text-center">
    
    <!-- Logo & Status Icon -->
    <div class="relative w-20 h-20 mx-auto mb-5 flex items-center justify-center">
      <div class="absolute inset-0 bg-blue-500/10 rounded-full animate-ping opacity-60"></div>
      <div class="w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-500/30 flex items-center justify-center text-white">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    <!-- Title & Description -->
    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Login Berhasil!</h1>
    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
      Halo <span class="font-semibold text-slate-700">{{ $user->name ?? 'Pengguna' }}</span>, kami sedang mengalihkan Anda kembali ke aplikasi KlikBan di HP Anda...
    </p>

    <!-- Main Button -->
    <div class="mt-7 space-y-3">
      <a id="btnOpenApp" href="#"
        class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] transition-all text-white font-semibold py-3.5 px-5 rounded-xl shadow-md shadow-blue-600/20 text-sm">
        <span>Buka Aplikasi KlikBan</span>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
        </svg>
      </a>

      <p class="text-[12px] text-slate-400">
        Jika aplikasi tidak terbuka otomatis, silakan sentuh tombol di atas.
      </p>
    </div>

    <!-- Web fallback link -->
    <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-center gap-4 text-xs text-slate-400">
      <a href="{{ route('board') }}" class="hover:text-blue-600 transition-colors">Lanjutkan di Web Browser</a>
      <span>•</span>
      <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Beranda</a>
    </div>
  </div>

<script>
  const token = @json($token);
  const encodedToken = encodeURIComponent(token);
  
  // Standard Custom Scheme for KlikBan
  const schemeUrl = "com.klikban.app://auth/callback?token=" + encodedToken;
  
  // Android Intent URI (best compatibility across Android Chrome)
  const intentUrl = "intent://auth/callback?token=" + encodedToken + "#Intent;scheme=com.klikban.app;package=com.klikban.app;end";

  // Bind to button
  const btn = document.getElementById('btnOpenApp');
  if (btn) {
    btn.href = intentUrl;
    btn.addEventListener('click', function(e) {
      // Fallback if intent fails on some browsers
      setTimeout(function() {
        window.location.href = schemeUrl;
      }, 500);
    });
  }

  // Automatic redirect attempt
  try {
    window.location.href = intentUrl;
  } catch (err) {
    window.location.href = schemeUrl;
  }

  // Second attempt fallback with scheme after 500ms
  setTimeout(function() {
    window.location.href = schemeUrl;
  }, 500);
</script>
</body>
</html>
