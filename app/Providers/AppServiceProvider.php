<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Workspace;
use App\Models\Project;
use App\Http\Controllers\WorkspaceController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        config(['app.timezone' => 'Asia/Jakarta']);
        View::composer(['layouts.app', 'board.*', 'progress.*', 'activity_log.*'], function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $workspace = WorkspaceController::getActiveWorkspace();
                
                $userWorkspaces = Workspace::where('owner_id', $user->id)
                    ->orWhereHas('members', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    })
                    ->withCount(['projects', 'members'])
                    ->get();

                $projects = $workspace ? $workspace->projects()->withCount('tasks')->get() : collect();
                
                $activeProjectId = request()->query('project_id') ?: session('active_project_id');
                $project = null;
                if ($workspace && $activeProjectId) {
                    $project = $projects->firstWhere('id', $activeProjectId);
                }
                if (!$project && $projects->isNotEmpty()) {
                    $project = $projects->first();
                }

                $view->with([
                    'workspace' => $view->getData()['workspace'] ?? $workspace,
                    'userWorkspaces' => $userWorkspaces,
                    'projects' => $view->getData()['projects'] ?? $projects,
                    'project' => $view->getData()['project'] ?? $project,
                ]);
            }
        });
    }
}
