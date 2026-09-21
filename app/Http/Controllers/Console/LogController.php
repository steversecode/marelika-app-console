<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\ApiApp;
use App\Models\ApiLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ApiLog::with('app')->latest();

        if ($request->filled('app_id')) {
            $query->where('api_app_id', $request->app_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('endpoint')) {
            $query->where('endpoint', 'like', "%{$request->endpoint}%");
        }

        $logs = $query->paginate(25);
        $apps = ApiApp::orderBy('name')->get();

        $stats = [
            'total_calls' => ApiLog::count(),
            'success_calls' => ApiLog::where('status', 'success')->count(),
            'failed_calls' => ApiLog::where('status', 'failed')->count(),
            'avg_response_time' => round(ApiLog::avg('response_time_ms') ?? 0, 1),
        ];

        return view('console.logs.index', compact('logs', 'apps', 'stats'));
    }

    public function statistics()
    {
        $apps = ApiApp::withCount('logs')->get();
        $totalLogs = ApiLog::count();
        $recentErrors = ApiLog::with('app')->where('status', 'failed')->latest()->limit(15)->get();

        return view('console.logs.statistics', compact('apps', 'totalLogs', 'recentErrors'));
    }
}