<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Project;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    /**
     * Helper to get active workspace for the current session (restricted to memberships)
     */
    public static function getActiveWorkspace(): ?Workspace
    {
        $user = auth()->user();
        if (!$user) return null;

        $workspaceId = session('active_workspace_id');
        if ($workspaceId) {
            $workspace = Workspace::with(['members', 'projects'])->find($workspaceId);
            if ($workspace && $workspace->hasMember($user)) {
                return $workspace;
            }
        }

        // Default to user's first owned or joined workspace
        $workspace = $user->ownedWorkspaces()->first() ?? $user->workspaces()->first();
        
        if (!$workspace) {
            // If user has no workspace yet, check if there is an open demo workspace or create their own
            $workspace = Workspace::where('owner_id', $user->id)->first();
            if (!$workspace) {
                $wsName = $user->name . ' Workspace';
                $workspace = Workspace::create([
                    'name' => $wsName,
                    'slug' => Str::slug($wsName) . '-' . Str::random(4),
                    'owner_id' => $user->id,
                    'description' => 'Workspace pribadi ' . $user->name,
                    'invite_code' => strtoupper(Str::random(8)),
                ]);
                $workspace->members()->attach($user->id, ['role' => 'owner']);
            }
        }

        if ($workspace) {
            session(['active_workspace_id' => $workspace->id]);
        }
        return $workspace;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();

        // 1. Create workspace
        $workspace = Workspace::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'owner_id' => $user->id, // Creator is automatically Owner / Admin!
            'description' => $request->description,
            'invite_code' => strtoupper(Str::random(8)),
        ]);

        // 2. Attach creator as Owner in pivot
        $workspace->members()->attach($user->id, ['role' => 'owner']);

        // 3. Create default project
        $project = Project::create([
            'workspace_id' => $workspace->id,
            'name' => 'Proyek Utama',
            'slug' => 'proyek-utama',
            'description' => 'Proyek utama di workspace ' . $workspace->name,
            'created_by' => $user->id,
        ]);

        // 4. Log activity
        ActivityLog::log(
            workspaceId: $workspace->id,
            description: $user->name . ' membuat workspace baru "' . $workspace->name . '" (Admin)',
            badgeText: 'MEMBER JOINED',
            badgeColor: 'orange',
            actionType: 'member_joined',
            projectId: $project->id,
            userId: $user->id,
            userName: $user->name
        );

        session([
            'active_workspace_id' => $workspace->id,
            'active_project_id' => $project->id,
        ]);

        return redirect()->route('board')->with('success', 'Workspace "' . $workspace->name . '" berhasil dibuat! Anda adalah Admin workspace ini.');
    }

    public function switchWorkspace(Request $request, $id)
    {
        $user = auth()->user();
        $workspace = Workspace::findOrFail($id);

        // Strict Membership Check: ONLY members or owner can switch to workspace
        if (!$workspace->hasMember($user)) {
            return back()->with('error', 'Akses ditolak. Workspace ini hanya dapat diakses oleh anggota yang telah diundang oleh Admin.');
        }

        session(['active_workspace_id' => $workspace->id]);

        $firstProject = $workspace->projects()->first();
        if (!$firstProject) {
            $firstProject = Project::create([
                'workspace_id' => $workspace->id,
                'name' => 'Proyek Utama',
                'slug' => 'proyek-utama-' . Str::random(4),
                'description' => 'Proyek utama di workspace ' . $workspace->name,
                'created_by' => $user->id,
            ]);
        }
        session(['active_project_id' => $firstProject->id]);

        return redirect()->route('board', ['project_id' => $firstProject->id])->with('success', 'Beralih ke workspace ' . $workspace->name);
    }

    /**
     * Join workspace via direct invitation link shared by admin
     */
    public function joinLink($code)
    {
        $workspace = Workspace::where('invite_code', strtoupper(trim($code)))->first();

        if (!$workspace) {
            return redirect()->route('home')->with('error', 'Link undangan workspace tidak valid atau sudah kadaluarsa.');
        }

        if (!auth()->check()) {
            session(['pending_invite_code' => $workspace->invite_code]);
            return redirect()->route('login')->with('info', 'Silakan masuk atau daftar akun untuk bergabung ke workspace ' . $workspace->name);
        }

        $user = auth()->user();
        if (!$workspace->hasMember($user)) {
            $workspace->members()->attach($user->id, ['role' => 'member']);

            ActivityLog::log(
                workspaceId: $workspace->id,
                description: $user->name . ' bergabung ke workspace melalui link undangan dari Admin',
                badgeText: 'MEMBER JOINED',
                badgeColor: 'orange',
                actionType: 'member_joined',
                userId: $user->id,
                userName: $user->name
            );
        }

        session(['active_workspace_id' => $workspace->id]);
        $project = $workspace->projects()->first();
        if ($project) {
            session(['active_project_id' => $project->id]);
        }

        return redirect()->route('board')->with('success', 'Selamat datang! Anda telah berhasil bergabung ke workspace ' . $workspace->name);
    }

    /**
     * Join workspace using invite code input
     */
    public function join(Request $request)
    {
        $request->validate([
            'invite_code' => ['required', 'string'],
        ]);

        $workspace = Workspace::where('invite_code', strtoupper(trim($request->invite_code)))->first();

        if (!$workspace) {
            return back()->with('error', 'Kode undangan tidak valid.');
        }

        $user = auth()->user();
        if (!$workspace->hasMember($user)) {
            $workspace->members()->attach($user->id, ['role' => 'member']);

            ActivityLog::log(
                workspaceId: $workspace->id,
                description: $user->name . ' bergabung ke workspace via kode undangan',
                badgeText: 'MEMBER JOINED',
                badgeColor: 'orange',
                actionType: 'member_joined',
                userId: $user->id,
                userName: $user->name
            );
        }

        session(['active_workspace_id' => $workspace->id]);
        return redirect()->route('board')->with('success', 'Berhasil bergabung ke workspace ' . $workspace->name);
    }

    /**
     * Add member directly - ONLY ADMIN / OWNER CAN DO THIS!
     */
    public function addMember(Request $request)
    {
        $workspace = self::getActiveWorkspace();
        if (!$workspace) return back()->with('error', 'Workspace tidak ditemukan.');

        $user = auth()->user();

        // STRICT ACCESS CHECK: Only Workspace Admin/Owner can invite members!
        if (!$workspace->isAdmin($user)) {
            return back()->with('error', 'Akses ditolak. Hanya Admin workspace yang berhak mengundang atau menambahkan anggota baru.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'role' => ['nullable', 'in:admin,member'],
        ]);

        $email = $request->email ?: (Str::slug($request->name) . '@klikban.local');
        $newUser = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $request->name,
                'password' => bcrypt('password'),
            ]
        );

        $role = $request->role ?: 'member';
        $workspace->members()->syncWithoutDetaching([$newUser->id => ['role' => $role]]);

        ActivityLog::log(
            workspaceId: $workspace->id,
            description: (auth()->user()?->name ?? 'Admin') . ' mengundang ' . $newUser->name . ' ke dalam workspace',
            badgeText: 'MEMBER JOINED',
            badgeColor: 'orange',
            actionType: 'member_joined',
            userId: auth()->id(),
            userName: auth()->user()?->name ?? 'Admin'
        );

        return back()->with('success', $newUser->name . ' berhasil ditambahkan ke workspace oleh Admin.');
    }

    /**
     * Leave workspace (for members or non-owner admins)
     */
    public function leave(Request $request, $id)
    {
        $user = auth()->user();
        $workspace = Workspace::findOrFail($id);

        if (!$workspace->hasMember($user)) {
            return back()->with('error', 'Anda bukan anggota dari workspace ini.');
        }

        // Owner cannot leave their own workspace
        if ($workspace->owner_id === $user->id) {
            return back()->with('error', 'Sebagai Pemilik Utama (Owner), Anda tidak dapat meninggalkan workspace ini. Anda dapat menghapus workspace atau mengalihkan kepemilikan terlebih dahulu.');
        }

        // Detach user
        $workspace->members()->detach($user->id);

        // Unassign tasks assigned to this user in this workspace
        $workspace->tasks()->where('assigned_to', $user->id)->update(['assigned_to' => null]);

        // Log activity
        ActivityLog::log(
            workspaceId: $workspace->id,
            description: $user->name . ' telah keluar dari workspace',
            badgeText: 'MEMBER LEFT',
            badgeColor: 'red',
            actionType: 'member_left',
            userId: $user->id,
            userName: $user->name
        );

        // If leaving the currently active workspace in session, clear active session
        if (session('active_workspace_id') == $workspace->id) {
            session()->forget(['active_workspace_id', 'active_project_id']);
        }

        return redirect()->route('board')->with('success', 'Anda telah berhasil keluar dari workspace ' . $workspace->name . '.');
    }

    /**
     * Remove / kick a member from workspace (Admin or Owner only)
     */
    public function removeMember(Request $request, $id, $userId)
    {
        $currentUser = auth()->user();
        $workspace = Workspace::findOrFail($id);

        // Check permission: Current user must be Admin or Owner
        if (!$workspace->isAdmin($currentUser)) {
            return back()->with('error', 'Akses ditolak. Hanya Admin atau Pemilik workspace yang berhak mengeluarkan anggota.');
        }

        $targetUser = User::findOrFail($userId);

        // Cannot remove the workspace owner
        if ($workspace->owner_id === $targetUser->id) {
            return back()->with('error', 'Pemilik Utama (Owner) workspace tidak dapat dikeluarkan.');
        }

        // Cannot remove oneself (use leave instead)
        if ($currentUser->id === $targetUser->id) {
            return back()->with('error', 'Gunakan tombol "Keluar Workspace" jika Anda ingin keluar sendiri.');
        }

        // Check if target user is actually a member
        if (!$workspace->members()->where('user_id', $targetUser->id)->exists()) {
            return back()->with('error', 'Pengguna tersebut bukan anggota dari workspace ini.');
        }

        // If current user is an Admin (not the primary Owner), they cannot remove another Admin
        if ($workspace->owner_id !== $currentUser->id) {
            $targetMember = $workspace->members()->where('user_id', $targetUser->id)->first();
            if ($targetMember && in_array($targetMember->pivot->role, ['owner', 'admin'])) {
                return back()->with('error', 'Admin tidak dapat mengeluarkan sesama Admin. Hanya Pemilik Utama yang memiliki wewenang ini.');
            }
        }

        // Detach member
        $workspace->members()->detach($targetUser->id);

        // Unassign tasks assigned to target user in this workspace
        $workspace->tasks()->where('assigned_to', $targetUser->id)->update(['assigned_to' => null]);

        // Log activity
        ActivityLog::log(
            workspaceId: $workspace->id,
            description: ($currentUser->name ?? 'Admin') . ' mengeluarkan ' . $targetUser->name . ' dari workspace',
            badgeText: 'MEMBER REMOVED',
            badgeColor: 'red',
            actionType: 'member_removed',
            userId: $currentUser->id,
            userName: $currentUser->name ?? 'Admin'
        );

        return back()->with('success', 'Anggota ' . $targetUser->name . ' berhasil dikeluarkan dari workspace.');
    }
}
