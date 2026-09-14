<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $workspace = WorkspaceController::getActiveWorkspace();
        if (!$workspace) {
            return redirect()->route('board');
        }

        $query = ActivityLog::where('workspace_id', $workspace->id);

        // Filter search keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('badge_text', 'like', "%{$search}%");
            });
        }

        // Filter action type
        if ($request->filled('filter') && $request->filter !== 'all') {
            $query->where('action_type', $request->filter);
        }

        // Filter date
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date)->toDateString();
            $query->whereDate('created_at', $date);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Group logs by Date Group ("HARI INI", "KEMARIN", etc.)
        $groupedLogs = $logs->getCollection()->groupBy(function ($log) {
            return $log->date_group;
        });

        return view('activity_log.index', compact('workspace', 'logs', 'groupedLogs'));
    }
}
