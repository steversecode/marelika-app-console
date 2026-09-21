@extends('layouts.console')

@section('title', 'API Access Logs - Marelika Console')

@section('content')
<div class="space-y-6">

    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-[#2258a5] dark:text-white">API Access Logs</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Real-time audit log of requests incoming from Odoo ERP and external sync callers.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('console.logs.statistics') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-[16px] text-[#f58220]">bar_chart</span>
                <span>View Statistics</span>
            </a>
        </div>
    </div>

    <!-- Stats summary -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-[#102038] p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="text-slate-400 dark:text-slate-500 text-[11px] font-bold uppercase tracking-wider">Total Calls</div>
            <div class="text-2xl font-bold text-[#2258a5] dark:text-white mt-1">{{ number_format($stats['total_calls']) }}</div>
        </div>
        <div class="bg-white dark:bg-[#102038] p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="text-emerald-600 text-[11px] font-bold uppercase tracking-wider">Success (2xx)</div>
            <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-400 mt-1">{{ number_format($stats['success_calls']) }}</div>
        </div>
        <div class="bg-white dark:bg-[#102038] p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="text-rose-600 text-[11px] font-bold uppercase tracking-wider">Failed Calls</div>
            <div class="text-2xl font-bold text-rose-700 dark:text-rose-400 mt-1">{{ number_format($stats['failed_calls']) }}</div>
        </div>
        <div class="bg-white dark:bg-[#102038] p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="text-slate-400 dark:text-slate-500 text-[11px] font-bold uppercase tracking-wider">Avg Latency</div>
            <div class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">{{ $stats['avg_response_time'] }} ms</div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs">
        <form method="GET" action="{{ route('console.logs.index') }}" class="flex flex-wrap items-center gap-3 text-xs">
            <div>
                <select name="app_id" class="px-3.5 py-2 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-medium">
                    <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Applications</option>
                    @foreach($apps as $a)
                        <option value="{{ $a->id }}" {{ request('app_id') == $a->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">{{ $a->name }} ({{ $a->partner_id }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="px-3.5 py-2 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-medium">
                    <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Statuses</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Success (200 OK)</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Failed / Error</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="endpoint" value="{{ request('endpoint') }}" placeholder="Filter by endpoint..." class="w-full px-3.5 py-2 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#2258a5] hover:bg-[#1b4787] text-white font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['app_id', 'status', 'endpoint']))
                <a href="{{ route('console.logs.index') }}" class="px-3 py-2 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white">Clear</a>
            @endif
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/60 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Time</th>
                        <th class="py-3 px-4">App / Partner ID</th>
                        <th class="py-3 px-4">Method &amp; Endpoint</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Duration</th>
                        <th class="py-3 px-4">Caller IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap text-slate-500 dark:text-slate-400 text-[11px]">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($log->app)
                                    <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $log->app->name }}</div>
                                    <div class="font-mono text-[10px] text-slate-400 dark:text-slate-500">{{ $log->app->partner_id }}</div>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic">Unknown App</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-[11px]">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $log->method === 'POST' ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300' : 'bg-sky-100 dark:bg-sky-950/60 text-sky-800 dark:text-sky-300' }}">
                                    {{ $log->method }}
                                </span>
                                <span class="text-slate-800 dark:text-slate-200 ml-1">{{ $log->endpoint }}</span>
                                @if($log->error_message)
                                    <div class="text-rose-600 dark:text-rose-400 text-[10px] font-sans truncate max-w-md mt-0.5" title="{{ $log->error_message }}">
                                        {{ $log->error_message }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($log->status === 'success')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ $log->status_code }} OK
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                        {{ $log->status_code }} ERR
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600 dark:text-slate-400 text-[11px]">
                                {{ $log->response_time_ms ? $log->response_time_ms . ' ms' : '-' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-500 dark:text-slate-400 text-[11px]">
                                {{ $log->ip_address ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                No API log records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection