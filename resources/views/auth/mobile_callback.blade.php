<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kembali ke Aplikasi KlikBan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
<style>
  body {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(ellipse 80% 60% at 30% 0%, #eef0fb 0%, #f2f3fb 45%, #e7e9f5 100%);
  }
  .pulse-ring {
    animation: pulse 1.8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }
  @keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.08); }
  }
</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-8 antialiased">
  <div class="w-full max-w-[400px] bg-white border border-slate-200/90 rounded-2xl shadow-[0_20px_50px_-15px_rgba(27,27,31,0.12)] p-7 sm:p-8 text-center">

    <!-- Icon -->
    <div class="relative w-20 h-20 mx-auto mb-5 flex items-center justify-center">
      <div class="absolute inset-0 bg-blue-500/15 rounded-full pulse-ring"></div>
      <div class="w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-500/30 flex items-center justify-center text-white">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    <!-- Title -->
    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Login Berhasil!</h1>
    <p class="mt-2 text-sm text-slate-600 leading-relaxed">
      Halo <span class="font-bold text-slate-800">{{ $user->name ?? 'Pengguna' }}</span>, akun Google Anda sudah terhubung.
    </p>

    @php
      $token = $token ?? '';
      $encodedToken = urlencode($token);
      $webFallback = urlencode(url('/board?token=' . $encodedToken));
      $intentUrl = 'intent://klikban.site.je/board?token=' . $encodedToken . '#Intent;scheme=https;package=com.klikban.app;S.browser_fallback_url=' . $webFallback . ';end';
      $schemeUrl = 'com.klikban.app://board?token=' . $encodedToken;
      $directUrl = url('/board?token=' . $encodedToken);
    @endphp

    <!-- Action buttons -->
    <div class="mt-6 space-y-3">
      <!-- Tombol Utama Buka Aplikasi -->
      <a id="btnOpenApp" href="{{ $intentUrl }}"
        class="w-full inline-flex items-center justify-center gap-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] transition-all text-white font-bold py-3.5 px-5 rounded-xl shadow-lg shadow-blue-600/25 text-base">
        <span>Buka Aplikasi KlikBan</span>
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
        </svg>
      </a>

      <!-- Fallback custom scheme -->
      <div class="pt-2">
        <a id="btnScheme" href="{{ $schemeUrl }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 underline">
          Aplikasi belum terbuka? Sentuh di sini (Buka Langsung)
        </a>
      </div>

      <p class="text-[12px] text-slate-400">
        Ketuk tombol biru di atas untuk kembali ke aplikasi KlikBan di HP Anda.
      </p>
    </div>

    <!-- Web fallback link -->
    <div class="mt-8 pt-4 border-t border-slate-100 text-xs text-slate-400">
      <a href="{{ $directUrl }}" class="hover:text-blue-600 hover:underline transition-colors font-medium">
        Atau lanjut buka di browser Chrome ini
      </a>
    </div>
  </div>

<script>
  const intentUrl = @json($intentUrl);
  const schemeUrl = @json($schemeUrl);

  function launchApp() {
    try {
      window.location.href = intentUrl;
    } catch(e) {}
  }

  // Trigger otomatis pembukaan aplikasi saat halaman termuat
  window.addEventListener('load', function() {
    setTimeout(launchApp, 400);
  });
</script>
</body>
</html>
