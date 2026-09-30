<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workspace;
use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('board');
        }

        // Auto-login jika datang dari mobile app (berdasarkan token atau handshake IP)
        $token = $request->query('token') ?: $request->query('mt');
        $userToLogin = null;
        if ($token) {
            $userToLogin = User::where('remember_token', $token)->first();
        }

        if (!$userToLogin) {
            $ip = $request->ip();
            $handshakeFile = storage_path('framework/cache/app_auth_' . md5($ip) . '.json');
            if (file_exists($handshakeFile)) {
                $content = @json_decode(file_get_contents($handshakeFile), true);
                if ($content && !empty($content['user_id']) && (time() - $content['time'] < 300)) {
                    $userToLogin = User::find($content['user_id']);
                }
            }

            if (!$userToLogin) {
                try {
                    $cached = \Illuminate\Support\Facades\Cache::get('pending_app_login_' . md5($ip));
                    if ($cached && !empty($cached['user_id'])) {
                        $userToLogin = User::find($cached['user_id']);
                    }
                } catch (\Throwable $e) {}
            }
        }

        if ($userToLogin) {
            Auth::login($userToLogin, true);
            $request->session()->regenerate();
            return redirect()->route('board');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('board'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('board');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'workspace_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Automatically create initial workspace where the user is Owner / Admin!
        $wsName = $request->workspace_name ?: ($request->name . ' Workspace');
        $workspace = Workspace::create([
            'name' => $wsName,
            'slug' => Str::slug($wsName) . '-' . Str::random(4),
            'owner_id' => $user->id,
            'description' => 'Workspace utama ' . $request->name,
            'invite_code' => strtoupper(Str::random(8)),
        ]);

        $workspace->members()->attach($user->id, ['role' => 'owner']);

        // Create default initial project
        $project = Project::create([
            'workspace_id' => $workspace->id,
            'name' => 'Proyek Perdana',
            'slug' => 'proyek-perdana',
            'description' => 'Proyek pertama di workspace ' . $workspace->name,
            'created_by' => $user->id,
        ]);

        // Log initial activity
        ActivityLog::log(
            workspaceId: $workspace->id,
            description: $user->name . ' membuat workspace baru "' . $workspace->name . '"',
            badgeText: 'MEMBER JOINED',
            badgeColor: 'orange',
            actionType: 'member_joined',
            projectId: $project->id,
            userId: $user->id,
            userName: $user->name
        );

        Auth::login($user);
        session(['active_workspace_id' => $workspace->id, 'active_project_id' => $project->id]);

        return redirect()->route('board')->with('success', 'Selamat datang di KlikBan! Workspace Anda telah siap.');
    }

    /**
     * One-click demo sign-in — safely provisions a dedicated demo account.
     * Never falls back to an arbitrary user to avoid logging visitors
     * into the real admin account.
     */
    public function demoLogin(Request $request)
    {
        // Cari atau buat akun demo secara otomatis
        $user = User::firstOrCreate(
            ['email' => 'budi@klikban.com'],
            [
                'name'     => 'Budi Demo',
                'password' => Hash::make(Str::random(32)),
            ]
        );

        Auth::login($user);

        // Ambil workspace pertama milik akun demo, atau buat jika belum ada
        $workspace = $user->workspaces()->first();

        if (!$workspace) {
            $workspace = Workspace::create([
                'name'        => 'Demo Workspace',
                'slug'        => 'demo-workspace-' . Str::random(4),
                'owner_id'    => $user->id,
                'description' => 'Workspace demo untuk mencoba fitur KlikBan.',
                'invite_code' => strtoupper(Str::random(8)),
            ]);

            $workspace->members()->attach($user->id, ['role' => 'owner']);
        }

        // Ambil project pertama di workspace, atau buat jika belum ada
        $project = $workspace->projects()->first();

        if (!$project) {
            $project = Project::create([
                'workspace_id' => $workspace->id,
                'name'         => 'Proyek Demo',
                'slug'         => 'proyek-demo-' . Str::random(4),
                'description'  => 'Proyek contoh untuk eksplorasi fitur.',
                'created_by'   => $user->id,
            ]);
        }

        // Simpan ke session dan redirect ke board
        session([
            'active_workspace_id' => $workspace->id,
            'active_project_id'   => $project->id,
        ]);

        return redirect()->route('board')->with('success', 'Masuk sebagai ' . $user->name);
    }

    /**
     * Redirect pengguna ke halaman login Google.
     */
    public function googleRedirect(Request $request)
    {
        $ua = $request->header('User-Agent', '');
        $isApp = $request->query('source') === 'app' 
            || str_contains($ua, 'KlikBan')
            || str_contains($ua, 'wv')
            || $request->cookie('is_klikban_app') === '1';

        $driver = Socialite::driver('google')->stateless();
        if ($isApp) {
            $driver->with(['state' => 'app']);
        }

        return $driver->redirect();
    }

    /**
     * Terima callback dari Google dan proses login / registrasi akun.
     */
    public function googleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login Google gagal: ' . $e->getMessage());
        }

        try {
            $hasGoogleId = \Illuminate\Support\Facades\Schema::hasColumn('users', 'google_id');
            $hasAvatar   = \Illuminate\Support\Facades\Schema::hasColumn('users', 'avatar');

            // Cari user berdasarkan google_id, atau email, atau buat akun baru
            $user = null;
            if ($hasGoogleId) {
                $user = User::where('google_id', $googleUser->getId())->first();
            }
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
            }

            if ($user) {
                $updateData = [];
                if ($hasGoogleId && empty($user->google_id)) {
                    $updateData['google_id'] = $googleUser->getId();
                }
                if ($hasAvatar && empty($user->avatar)) {
                    $updateData['avatar'] = $googleUser->getAvatar();
                }
                if (!empty($updateData)) {
                    $user->update($updateData);
                }
            } else {
                // Buat akun baru dari data Google
                $createData = [
                    'name'     => $googleUser->getName() ?: 'Pengguna Google',
                    'email'    => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(32)), // Aman dari kolom password NOT NULL
                ];
                if ($hasGoogleId) {
                    $createData['google_id'] = $googleUser->getId();
                }
                if ($hasAvatar) {
                    $createData['avatar'] = $googleUser->getAvatar();
                }

                $user = User::create($createData);

                // Buat Workspace awal
                $wsName    = ($user->name ?: 'My') . ' Workspace';
                $workspace = Workspace::create([
                    'name'        => $wsName,
                    'slug'        => Str::slug($wsName) . '-' . Str::random(4),
                    'owner_id'    => $user->id,
                    'description' => 'Workspace utama ' . $user->name,
                    'invite_code' => strtoupper(Str::random(8)),
                ]);
                $workspace->members()->attach($user->id, ['role' => 'owner']);

                // Buat Project awal
                $project = Project::create([
                    'workspace_id' => $workspace->id,
                    'name'         => 'Proyek Perdana',
                    'slug'         => 'proyek-perdana-' . Str::random(4),
                    'description'  => 'Proyek pertama di workspace ' . $workspace->name,
                    'created_by'   => $user->id,
                ]);

                ActivityLog::log(
                    workspaceId: $workspace->id,
                    description: $user->name . ' bergabung via Google dan membuat workspace "' . $workspace->name . '"',
                    badgeText: 'MEMBER JOINED',
                    badgeColor: 'orange',
                    actionType: 'member_joined',
                    projectId: $project->id,
                    userId: $user->id,
                    userName: $user->name
                );

                session([
                    'active_workspace_id' => $workspace->id,
                    'active_project_id'   => $project->id,
                ]);
            }

            // Jika user lama, ambil workspace & project aktifnya
            if (!session('active_workspace_id')) {
                $workspace = $user->workspaces()->first();
                if ($workspace) {
                    $project = $workspace->projects()->first();
                    session([
                        'active_workspace_id' => $workspace->id,
                        'active_project_id'   => $project?->id,
                    ]);
                }
            }

            // Buat token handshake untuk aplikasi mobile
            $token = Str::random(60);
            $user->forceFill(['remember_token' => $token])->save();

            // Simpan handshake IP untuk aplikasi mobile
            $ip = $request->ip();
            $cacheData = [
                'user_id' => $user->id,
                'token'   => $token,
                'time'    => time(),
            ];

            try {
                \Illuminate\Support\Facades\Cache::put('pending_app_login_' . md5($ip), $cacheData, 180);
            } catch (\Throwable $e) {}

            $handshakeDir = storage_path('framework/cache');
            if (!is_dir($handshakeDir)) {
                @mkdir($handshakeDir, 0777, true);
            }
            @file_put_contents($handshakeDir . '/app_auth_' . md5($ip) . '.json', json_encode($cacheData));

            $state = $request->query('state');
            $isFromApp = ($state === 'app' 
                || str_contains($state ?? '', 'app')
                || $request->cookie('is_klikban_app') === '1');

            Auth::login($user, true);
            $request->session()->regenerate();

            if ($isFromApp) {
                return view('auth.mobile_callback', [
                    'user'  => $user,
                    'token' => $token,
                ]);
            }

            return redirect()->route('board')->with('success', 'Selamat datang, ' . $user->name . '!');

        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Terjadi kendala saat login Google: ' . $e->getMessage());
        }
    }

    /**
     * Terima login dari deep link aplikasi Android (Capacitor).
     */
    public function mobileLogin(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'Token login tidak valid.');
        }

        try {
            $payload = json_decode(Crypt::decryptString($token), true);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak valid atau rusak.');
        }

        if (empty($payload['user_id']) || empty($payload['exp']) || time() > $payload['exp']) {
            return redirect()->route('login')->with('error', 'Sesi login telah kedaluwarsa. Silakan coba lagi.');
        }

        $user = User::find($payload['user_id']);
        if (!$user) {
            return redirect()->route('login')->with('error', 'Pengguna tidak ditemukan.');
        }

        if (!session('active_workspace_id')) {
            $workspace = $user->workspaces()->first();
            if ($workspace) {
                $project = $workspace->projects()->first();
                session([
                    'active_workspace_id' => $workspace->id,
                    'active_project_id'   => $project?->id,
                ]);
            }
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('board')->with('success', 'Selamat datang di Aplikasi KlikBan, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
