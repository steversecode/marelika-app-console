@extends('layouts.console')

@section('title', 'API Calls Statistics - Marelika Console')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb & Header Bar -->
    <div class="flex items-center justify-between text-xs text-slate-500">
        <div class="flex items-center gap-2">
            <span class="hover:text-[#2258a5] dark:hover:text-white transition-colors">Observability</span>
            <span class="text-slate-400 dark:text-slate-600">/</span>
            <a href="{{ route('console.logs.index') }}" class="hover:text-[#2258a5] dark:hover:text-white transition-colors">API Access Logs</a>
            <span class="text-slate-400 dark:text-slate-600">/</span>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">Analytics &amp; Statistics</span>
        </div>
        <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 dark:bg-blue-950/60 text-[#2258a5] dark:text-sky-300 border border-blue-200 dark:border-blue-800">
                <span class="material-symbols-outlined text-[14px]">insights</span>
                <span>Telemetry Insights</span>
            </span>
        </div>
    </div>

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-50 dark:bg-slate-900 border border-orange-100 dark:border-slate-700 flex items-center justify-center text-[#f58220] shrink-0">
                    <span class="material-symbols-outlined text-[20px]">bar_chart</span>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-[#2258a5] dark:text-white tracking-tight">API Calls Statistics</h1>
                </div>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 pl-11">
                Volume metrics, traffic distribution across connectors, and real-time error auditing.
            </p>
        </div>
        
        <div class="flex items-center gap-2.5 pl-11 sm:pl-0">
            <a href="{{ route('console.logs.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors focus-visible:ring-2 focus-visible:ring-[#2258a5]/30 outline-none">
                <span class="material-symbols-outlined text-[16px] text-[#2258a5] dark:text-sky-400">receipt_long</span>
                <span>View Transaction Logs</span>
            </a>
        </div>
    </div>

    <!-- Telemetry Summary Cards -->
    @php
        $errorCount = $recentErrors->count();
        $activeAppsCount = $apps->where('logs_count', '>', 0)->count();
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider">Total Inbound Calls</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-[#2258a5] dark:text-white mt-1.5 tracking-tight font-mono">
                {{ number_format($totalLogs) }}
            </div>
            <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Across all registered apps</div>
        </div>

        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider">Active Connectors</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-slate-200 mt-1.5 tracking-tight">
                {{ $activeAppsCount }} <span class="text-xs font-normal text-slate-400">/ {{ $apps->count() }} apps</span>
            </div>
            <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Apps with traffic history</div>
        </div>

        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider">Failure Hotspots</div>
            <div class="text-2xl sm:text-3xl font-extrabold {{ $errorCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} mt-1.5 tracking-tight">
                {{ $errorCount }}
            </div>
            <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ $errorCount > 0 ? 'Recent error incidents' : 'Zero recorded errors' }}</div>
        </div>

        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider">System Reliability</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1.5 tracking-tight font-mono">
                {{ $totalLogs > 0 ? max(0, round((($totalLogs - $errorCount) / $totalLogs) * 100, 1)) : 100 }}%
            </div>
            <div class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-1 font-medium">Estimated service health</div>
        </div>
    </div>

    <!-- App Breakdown Table Card -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-4 sm:p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <h2 class="font-bold text-sm text-[#2258a5] dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">pie_chart</span>
                    <span>Traffic Breakdown by Store Application</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Distribution of incoming requests across different Odoo partner connectors.</p>
            </div>
            <span class="text-xs font-mono text-slate-400 dark:text-slate-500">{{ $apps->count() }} registered applications</span>
        </div>

        <div class="space-y-4">
            @forelse($apps as $app)
                @php
                    $pct = $totalLogs > 0 ? round(($app->logs_count / $totalLogs) * 100, 1) : 0;
                @endphp
                <div class="p-3.5 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/70 dark:border-slate-800/80 space-y-2 hover:border-[#2258a5]/40 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-1.5">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $app->name }}</span>
                            <span class="font-mono text-[10px] px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400">{{ $app->partner_id }}</span>
                            @if($app->store_url)
                                <span class="text-[10px] text-slate-400 truncate max-w-xs">&bull; {{ $app->store_url }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ number_format($app->logs_count) }} calls</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-950/70 text-[#2258a5] dark:text-sky-300 border border-blue-200 dark:border-blue-800">
                                {{ $pct }}%
                            </span>
                            <a href="{{ route('console.logs.index', ['app_id' => $app->id]) }}" class="text-[#2258a5] dark:text-sky-400 hover:underline text-[11px] font-bold inline-flex items-center gap-0.5" title="Filter logs for this app">
                                <span>Logs</span>
                                <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-200/80 dark:bg-slate-800 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#2258a5] to-[#f58220] rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400 dark:text-slate-500 text-xs">
                    No application traffic data recorded yet.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Errors Section -->
    @if($recentErrors->count() > 0)
        <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-4 sm:p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <h2 class="font-bold text-sm text-rose-700 dark:text-rose-400 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-[18px]">warning</span>
                    <span>Recent Sync &amp; Connection Errors</span>
                </h2>
                <a href="{{ route('console.logs.index', ['status' => 'failed']) }}" class="text-rose-600 dark:text-rose-400 hover:underline text-xs font-bold inline-flex items-center gap-1">
                    <span>View All Failed Logs</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            
            <div class="space-y-2.5">
                @foreach($recentErrors as $err)
                    <div class="p-3.5 rounded-xl bg-rose-50/70 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-xs flex flex-col sm:flex-row sm:items-start justify-between gap-2 sm:gap-4 hover:border-rose-300 transition-colors">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-rose-900 dark:text-rose-200">{{ $err->app ? $err->app->name : 'Unknown Application' }}</span>
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-rose-200 dark:bg-rose-900 text-rose-800 dark:text-rose-200">{{ $err->method }}</span>
                                <span class="font-mono text-[11px] text-slate-700 dark:text-slate-300">{{ $err->endpoint }}</span>
                            </div>
                            <div class="text-rose-700 dark:text-rose-300 text-[11px] font-mono break-words bg-white/60 dark:bg-slate-900/60 p-2 rounded-lg border border-rose-100 dark:border-rose-900/40">
                                {{ $err->error_message }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:flex-col sm:items-end shrink-0">
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">{{ $err->created_at->diffForHumans() }}</span>
                            <a href="{{ route('console.logs.index', ['endpoint' => $err->endpoint]) }}" class="text-xs text-[#2258a5] dark:text-sky-400 hover:underline font-semibold" title="Inspect this endpoint">
                                Inspect
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
