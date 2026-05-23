<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\Admin\Admin;

class ActivityLogController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:activity-log-view');
    }

    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id)
                  ->where('causer_type', 'App\Models\Admin\Admin');
        }

        // Filter by log name (activity type)
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $activities = $query->latest()->paginate(20);
        
        // Get all admins for filter dropdown
        $admins = Admin::orderBy('name')->get();
        
        // Get distinct log names for filter
        $logNames = Activity::select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->pluck('log_name');

        return view('admin.activity-logs.index', compact('activities', 'admins', 'logNames'), [
            'page_title' => 'Activity Logs'
        ]);
    }
}
