<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $workspace = WorkspaceController::getActiveWorkspace();
        if (!$workspace) return back()->with('error', 'Pilih workspace terlebih dahulu.');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $project = Project::create([
            'workspace_id' => $workspace->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        ActivityLog::log(
            workspaceId: $workspace->id,
            description: (auth()->user()?->name ?? 'Pengguna') . ' membuat proyek baru "' . $project->name . '"',
            badgeText: 'NEW TASK',
            badgeColor: 'green',
            actionType: 'new_task',
            projectId: $project->id,
            userId: auth()->id(),
            userName: auth()->user()?->name
        );

        session(['active_project_id' => $project->id]);

        return redirect()->route('board', ['project_id' => $project->id])->with('success', 'Proyek "' . $project->name . '" berhasil dibuat!');
    }

    public function switchProject($id)
    {
        $project = Project::with('workspace')->findOrFail($id);
        $user = auth()->user();

        if ($user && !$project->workspace->hasMember($user)) {
            return back()->with('error', 'Akses ditolak ke proyek ini.');
        }

        session([
            'active_workspace_id' => $project->workspace_id,
            'active_project_id' => $project->id,
        ]);

        return redirect()->route('board', ['project_id' => $project->id])->with('success', 'Beralih ke proyek ' . $project->name);
    }

    public function destroy(Request $request, $id)
    {
        $project = Project::with('workspace')->findOrFail($id);
        $user = auth()->user();

        // Check permission: workspace admin/owner OR project creator
        if ($user && !$project->workspace->isAdmin($user) && $project->created_by !== $user->id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus proyek ini.'
                ], 403);
            }
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus proyek ini.');
        }

        $projectName = $project->name;
        $workspaceId = $project->workspace_id;

        // If this is currently the active project in session, switch or forget
        if (session('active_project_id') == $project->id) {
            $otherProject = Project::where('workspace_id', $workspaceId)
                ->where('id', '!=', $project->id)
                ->first();

            if ($otherProject) {
                session(['active_project_id' => $otherProject->id]);
            } else {
                session()->forget('active_project_id');
            }
        }

        $project->delete();

        ActivityLog::log(
            workspaceId: $workspaceId,
            description: ($user?->name ?? 'Pengguna') . ' menghapus proyek "' . $projectName . '"',
            badgeText: 'PROJECT DELETED',
            badgeColor: 'red',
            actionType: 'project_deleted',
            projectId: null,
            userId: auth()->id(),
            userName: $user?->name
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Proyek "' . $projectName . '" berhasil dihapus.'
            ]);
        }

        return redirect()->route('board')->with('success', 'Proyek "' . $projectName . '" berhasil dihapus.');
    }
}
