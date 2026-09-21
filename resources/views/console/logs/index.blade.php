@extends('layouts.console')

@section('title', 'API Access Logs - Marelika Console')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-lg border border-[#e8e8e8] p-5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-[#222]">API Access Logs</h1>
            <p class="text-xs text-slate-500 mt-1">Real-time audit log of requests incoming from Odoo ERP and external sync callers.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('console.logs.statistics') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-300 text-slate-700 text-xs font-semibold rounded-md hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-[16px]">bar_chart</span>
                <span>View Statistics</span>
            </a>
        </div>
    </div>

    <!-- Stats summary -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-[#e8e8e8] shadow-2xs">
            <div class="text-slate-400 text-[11px] font-medium">Total Calls</div>
            <div class="text-xl font-bold text-[#222] mt-1">{{ number_format($stats['total_calls']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#e8e8e8] shadow-2xs">
            <div class="text-emerald-600 text-[11px] font-medium">Success (2xx)</div>
            <div class="text-xl font-bold text-emerald-700 mt-1">{{ number_format($stats['success_calls']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#e8e8e8] shadow-2xs">
            <div class="text-rose-600 text-[11px] font-medium">Failed Calls</div>
            <div class="text-xl font-bold text-rose-700 mt-1">{{ number_format($stats['failed_calls']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#e8e8e8] shadow-2xs">
            <div class="text-slate-400 text-[11px] font-medium">Avg Latency</div>
            <div class="text-xl font-bold text-slate-800 mt-1">{{ $stats['avg_response_time'] }} ms</div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg border border-[#e8e8e8] p-4 shadow-2xs">
        <form method="GET" action="{{ route('console.logs.index') }}" class="flex flex-wrap items-center gap-3 text-xs">
            <div>
                <select name="app_id" class="px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs bg-white">
                    <option value="">All Applications</option>
                    @foreach($apps as $a)
                        <option value="{{ $a->id }}" {{ request('app_id') == $a->id ? 'selected' : '' }}>{{ $a->name }} ({{ $a->partner_id }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs bg-white">
                    <option value="">All Statuses</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success (200 OK)</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed / Error</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="endpoint" value="{{ request('endpoint') }}" placeholder="Filter by endpoint..." class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs font-mono">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-medium rounded-md transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['app_id', 'status', 'endpoint']))
                <a href="{{ route('console.logs.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-800">Clear</a>
            @endif
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-lg border border-[#e8e8e8] shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#fafafa] border-b border-[#e8e8e8] text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Time</th>
                        <th class="py-3 px-4">App / Partner ID</th>
                        <th class="py-3 px-4">Method &amp; Endpoint</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Duration</th>
                        <th class="py-3 px-4">Caller IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f0f0]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap text-slate-500 text-[11px]">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($log->app)
                                    <div class="font-semibold text-slate-800">{{ $log->app->name }}</div>
                                    <div class="font-mono text-[10px] text-slate-400">{{ $log->app->partner_id }}</div>
                                @else
                                    <span class="text-slate-400 italic">Unknown App</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-[11px]">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $log->method === 'POST' ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800' }}">
                                    {{ $log->method }}
                                </span>
                                <span class="text-slate-800 ml-1">{{ $log->endpoint }}</span>
                                @if($log->error_message)
                                    <div class="text-rose-600 text-[10px] font-sans truncate max-w-md mt-0.5" title="{{ $log->error_message }}">
                                        {{ $log->error_message }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($log->status === 'success')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $log->status_code }} OK
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        {{ $log->status_code }} ERR
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600 text-[11px]">
                                {{ $log->response_time_ms ? $log->response_time_ms . ' ms' : '-' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-500 text-[11px]">
                                {{ $log->ip_address ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No API log records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-[#f0f0f0]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection