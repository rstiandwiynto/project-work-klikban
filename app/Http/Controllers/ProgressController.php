<?php

namespace App\Http\Controllers;



class ProgressController extends Controller
{
    public function index()
    {
        $workspace = WorkspaceController::getActiveWorkspace();
        if (!$workspace) {
            return redirect()->route('board');
        }

        $projects = $workspace->projects()->with('tasks')->get();
        $tasks = $workspace->tasks()->get();

        $totalTasks = $tasks->count();
        $doneTasks = $tasks->where('status', 'done')->count();
        $inProgressTasks = $tasks->where('status', 'in_progress')->count();
        $reviewTasks = $tasks->where('status', 'review')->count();
        $todoTasks = $tasks->where('status', 'todo')->count();

        $completionPercentage = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        // Tasks grouped by Priority (Didahulukan, Perlu Diperhatikan, Eksternal, Biasa)
        $priorityStats = [
            'didahulukan' => [
                'label' => 'Didahulukan (Merah)',
                'count' => $tasks->where('priority', 'didahulukan')->count(),
                'color' => 'rose',
                'bg' => 'bg-rose-500',
                'badge' => 'bg-rose-50 text-rose-600 border-rose-200',
            ],
            'perlu_diperhatikan' => [
                'label' => 'Perlu Diperhatikan (Kuning)',
                'count' => $tasks->where('priority', 'perlu_diperhatikan')->count(),
                'color' => 'amber',
                'bg' => 'bg-amber-500',
                'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
            ],
            'eksternal' => [
                'label' => 'Eksternal / Tambahan (Biru)',
                'count' => $tasks->where('priority', 'eksternal')->count(),
                'color' => 'blue',
                'bg' => 'bg-blue-600',
                'badge' => 'bg-blue-50 text-blue-600 border-blue-200',
            ],
            'biasa' => [
                'label' => 'Biasa (Hijau)',
                'count' => $tasks->where('priority', 'biasa')->count(),
                'color' => 'emerald',
                'bg' => 'bg-emerald-500',
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            ],
        ];

        // Assignee workload stats
        $assigneeStats = $tasks->groupBy('assignee_name')->map(function ($items, $name) {
            $total = $items->count();
            $done = $items->where('status', 'done')->count();
            $percent = $total > 0 ? round(($done / $total) * 100) : 0;
            return [
                'name' => $name ?: 'Belum Ditugaskan',
                'total' => $total,
                'done' => $done,
                'in_progress' => $items->where('status', 'in_progress')->count(),
                'todo' => $items->where('status', 'todo')->count(),
                'review' => $items->where('status', 'review')->count(),
                'percent' => $percent,
            ];
        })->sortByDesc('total');

        return view('progress.index', compact(
            'workspace',
            'projects',
            'totalTasks',
            'doneTasks',
            'inProgressTasks',
            'reviewTasks',
            'todoTasks',
            'completionPercentage',
            'priorityStats',
            'assigneeStats'
        ));
    }
}
