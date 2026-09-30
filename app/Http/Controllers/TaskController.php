<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display Kanban Board
     */
    public function board(Request $request)
    {
        // Auto-login untuk aplikasi mobile Android jika ada token atau handshake IP
        if (!auth()->check()) {
            $userToLogin = null;

            // 1. Cek query parameter token (?token=... atau ?mt=...)
            $token = $request->query('token') ?: $request->query('mt');
            if ($token) {
                $userToLogin = \App\Models\User::where('remember_token', $token)->first();
            }

            // 2. Cek handshake file/cache berdasarkan IP HP
            if (!$userToLogin) {
                $ip = $request->ip();
                $handshakeFile = storage_path('framework/cache/app_auth_' . md5($ip) . '.json');
                if (file_exists($handshakeFile)) {
                    $content = @json_decode(file_get_contents($handshakeFile), true);
                    if ($content && !empty($content['user_id']) && (time() - $content['time'] < 180)) {
                        $userToLogin = \App\Models\User::find($content['user_id']);
                    }
                    @unlink($handshakeFile);
                }

                if (!$userToLogin) {
                    try {
                        $cached = \Illuminate\Support\Facades\Cache::get('pending_app_login_' . md5($ip));
                        if ($cached && !empty($cached['user_id'])) {
                            $userToLogin = \App\Models\User::find($cached['user_id']);
                            \Illuminate\Support\Facades\Cache::forget('pending_app_login_' . md5($ip));
                        }
                    } catch (\Throwable $e) {}
                }
            }

            if ($userToLogin) {
                auth()->login($userToLogin, true);
                $request->session()->regenerate();
            }
        }

        $workspace = WorkspaceController::getActiveWorkspace();

        if (!$workspace) {
            // If user has no workspace, auto-create one or redirect to onboarding
            $user = auth()->user();
            if ($user) {
                $workspace = Workspace::create([
                    'name' => 'PT WUNAWAN',
                    'slug' => 'pt-wunawan-' . rand(100, 999),
                    'owner_id' => $user->id,
                    'description' => 'Workspace Kolaborasi',
                    'invite_code' => strtoupper(\Illuminate\Support\Str::random(8)),
                ]);
                $workspace->members()->attach($user->id, ['role' => 'owner']);
                session(['active_workspace_id' => $workspace->id]);
            } else {
                return redirect()->route('login');
            }
        }

        // Active project
        $projectId = $request->query('project_id') ?: session('active_project_id');
        $project = null;

        if ($projectId) {
            $project = Project::where('workspace_id', $workspace->id)->find($projectId);
        }

        if (!$project) {
            $project = $workspace->projects()->first();
            if (!$project) {
                $project = Project::create([
                    'workspace_id' => $workspace->id,
                    'name' => 'Proyek Akhir RPL',
                    'slug' => 'proyek-akhir-rpl',
                    'description' => 'Proyek Manajemen Tugas Kolaboratif',
                    'created_by' => auth()->id() ?: $workspace->owner_id,
                ]);
            }
            session(['active_project_id' => $project->id]);
        }

        // Tasks in project (Single optimized DB query)
        $allTasks = Task::where('project_id', $project->id)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        $todoTasks = $allTasks->where('status', 'todo')->values();
        $inProgressTasks = $allTasks->where('status', 'in_progress')->values();
        $reviewTasks = $allTasks->where('status', 'review')->values();
        $doneTasks = $allTasks->where('status', 'done')->values();

        // Workspace projects list
        $projects = $workspace->projects()->get();

        // Workspace members list for assignee dropdown
        $members = $workspace->members()->get();

        return view('board.index', compact(
            'workspace',
            'project',
            'projects',
            'members',
            'todoTasks',
            'inProgressTasks',
            'reviewTasks',
            'doneTasks'
        ));
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'priority' => ['required', 'in:didahulukan,perlu_diperhatikan,eksternal,biasa'],
            'status' => ['required', 'in:todo,in_progress,review,done'],
            'due_date' => ['nullable', 'date'],
            'assignee_name' => ['nullable', 'string', 'max:255'],
        ]);

        $project = Project::findOrFail($request->project_id);
        $workspace = $project->workspace;

        $task = Task::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'assignee_name' => $request->assignee_name ?: (auth()->user()?->name ?? 'Tim'),
            'created_by' => auth()->id() ?: $workspace->owner_id,
        ]);

        // Log Activity
        $userName = auth()->user()?->name ?? 'Pengguna';
        ActivityLog::log(
            workspaceId: $workspace->id,
            description: $userName . ' menambahkan tugas baru "' . $task->title . '"',
            badgeText: 'NEW TASK',
            badgeColor: 'green',
            actionType: 'new_task',
            projectId: $project->id,
            userId: auth()->id(),
            userName: $userName
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil ditambahkan!',
                'task' => $task,
            ]);
        }

        return redirect()->route('board', ['project_id' => $project->id])->with('success', 'Tugas "' . $task->title . '" berhasil dibuat!');
    }

    /**
     * Update status (Quick 1-click switcher or drag & drop)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:todo,in_progress,review,done'],
        ]);

        $task = Task::with(['workspace', 'project'])->findOrFail($id);
        $oldStatus = $task->status;
        $newStatus = $request->status;

        if ($oldStatus !== $newStatus) {
            $task->update(['status' => $newStatus]);

            $userName = auth()->user()?->name ?? 'Pengguna';
            $statusLabels = [
                'todo' => 'To-Do',
                'in_progress' => 'In Progress',
                'review' => 'Review',
                'done' => 'Done',
            ];

            ActivityLog::log(
                workspaceId: $task->workspace_id,
                description: $userName . ' memindahkan tugas "' . $task->title . '" ke ' . ($statusLabels[$newStatus] ?? $newStatus),
                badgeText: 'STATUS UPDATE',
                badgeColor: 'blue',
                actionType: 'status_update',
                projectId: $task->project_id,
                userId: auth()->id(),
                userName: $userName
            );
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status tugas berhasil diperbarui!',
                'task' => $task,
            ]);
        }

        return redirect()->back()->with('success', 'Status tugas diperbarui ke ' . $task->status_label);
    }

    /**
     * Update priority
     */
    public function updatePriority(Request $request, $id)
    {
        $request->validate([
            'priority' => ['required', 'in:didahulukan,perlu_diperhatikan,eksternal,biasa'],
        ]);

        $task = Task::findOrFail($id);
        $oldPriority = $task->priority;
        $newPriority = $request->priority;

        if ($oldPriority !== $newPriority) {
            $task->update(['priority' => $newPriority]);

            $priorityLabels = [
                'didahulukan' => 'Didahulukan (Merah)',
                'perlu_diperhatikan' => 'Perlu Diperhatikan (Kuning)',
                'eksternal' => 'Eksternal / Tambahan (Biru)',
                'biasa' => 'Biasa (Hijau)',
            ];

            $userName = auth()->user()?->name ?? 'Pengguna';
            ActivityLog::log(
                workspaceId: $task->workspace_id,
                description: $userName . ' mengubah prioritas tugas "' . $task->title . '" menjadi ' . ($priorityLabels[$newPriority] ?? $newPriority),
                badgeText: 'PRIORITY CHANGE',
                badgeColor: 'red',
                actionType: 'priority_change',
                projectId: $task->project_id,
                userId: auth()->id(),
                userName: $userName
            );
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Prioritas tugas berhasil diperbarui!',
                'task' => $task,
            ]);
        }

        return redirect()->back()->with('success', 'Prioritas tugas diperbarui.');
    }

    /**
     * Update task full details
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'priority' => ['required', 'in:didahulukan,perlu_diperhatikan,eksternal,biasa'],
            'status' => ['required', 'in:todo,in_progress,review,done'],
            'due_date' => ['nullable', 'date'],
            'assignee_name' => ['nullable', 'string', 'max:255'],
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'assignee_name' => $request->assignee_name,
        ]);

        $userName = auth()->user()?->name ?? 'Pengguna';
        ActivityLog::log(
            workspaceId: $task->workspace_id,
            description: $userName . ' memperbarui detail tugas "' . $task->title . '"',
            badgeText: 'STATUS UPDATE',
            badgeColor: 'blue',
            actionType: 'task_updated',
            projectId: $task->project_id,
            userId: auth()->id(),
            userName: $userName
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diperbarui!',
                'task' => $task,
            ]);
        }

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Delete task
     */
    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $title = $task->title;
        $workspaceId = $task->workspace_id;
        $projectId = $task->project_id;

        $task->delete();

        $userName = auth()->user()?->name ?? 'Pengguna';
        ActivityLog::log(
            workspaceId: $workspaceId,
            description: $userName . ' menghapus tugas "' . $title . '"',
            badgeText: 'TASK DELETED',
            badgeColor: 'red',
            actionType: 'task_deleted',
            projectId: $projectId,
            userId: auth()->id(),
            userName: $userName
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dihapus.',
            ]);
        }

        return redirect()->back()->with('success', 'Tugas berhasil dihapus.');
    }
}
