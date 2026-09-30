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
  .spinner {
    width: 44px; height: 44px;
    border: 4px solid rgba(59,130,246,0.15);
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10 antialiased">
  <div class="w-full max-w-[380px] bg-white border border-slate-200/80 rounded-2xl shadow-[0_20px_50px_-15px_rgba(27,27,31,0.12)] p-8 text-center">

    <!-- Icon -->
    <div class="relative w-20 h-20 mx-auto mb-5 flex items-center justify-center">
      <div class="absolute inset-0 bg-blue-500/10 rounded-full animate-ping opacity-60"></div>
      <div class="w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-500/30 flex items-center justify-center text-white">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    <!-- Title -->
    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Login Berhasil!</h1>
    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
      Halo <span class="font-semibold text-slate-700">{{ $user->name ?? 'Pengguna' }}</span>, 
      Anda sedang diarahkan ke KlikBan...
    </p>

    <!-- Spinner -->
    <div class="mt-7 flex flex-col items-center gap-3">
      <div class="spinner"></div>
      <p id="countdownText" class="text-sm text-slate-400">Mengalihkan dalam <span id="sec">2</span> detik...</p>
    </div>

    <!-- Manual button -->
    <div class="mt-6">
      <a id="btnBoard" href="{{ route('board') }}"
        class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] transition-all text-white font-semibold py-3 px-5 rounded-xl shadow-md shadow-blue-600/20 text-sm">
        Buka KlikBan Sekarang
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
        </svg>
      </a>
    </div>

    <!-- Web fallback -->
    <div class="mt-5 text-xs text-slate-400">
      Jika tidak otomatis, sentuh tombol di atas.
    </div>
  </div>

<script>
  // Auto redirect ke board setelah 2 detik (server session sudah dibuat)
  const boardUrl = @json(route('board'));
  let secs = 2;

  const secEl = document.getElementById('sec');
  const interval = setInterval(() => {
    secs--;
    if (secEl) secEl.textContent = secs;
    if (secs <= 0) {
      clearInterval(interval);
      window.location.replace(boardUrl);
    }
  }, 1000);

  // Tombol manual juga langsung ke board
  const btn = document.getElementById('btnBoard');
  if (btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      clearInterval(interval);
      window.location.replace(boardUrl);
    });
  }
</script>
</body>
</html>
