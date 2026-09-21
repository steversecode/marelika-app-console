@extends('layouts.console')

@section('title', 'API Calls Statistics - Marelika Console')

@section('content')
<div class="space-y-6">

    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-[#2258a5] dark:text-white">API Calls Statistics</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Traffic and volume metrics across all store connectors.</p>
        </div>
        <a href="{{ route('console.logs.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[16px] text-[#f58220]">receipt_long</span>
            <span>View Raw Logs</span>
        </a>
    </div>

    <!-- App Breakdown Table -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-6">
        <h2 class="font-bold text-sm text-[#2258a5] dark:text-white mb-4">Traffic Breakdown by Store Application</h2>
        <div class="space-y-4">
            @forelse($apps as $app)
                @php
                    $pct = $totalLogs > 0 ? round(($app->logs_count / $totalLogs) * 100, 1) : 0;
                @endphp
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ $app->name }} <span class="font-mono text-[11px] text-slate-400 font-normal">({{ $app->partner_id }})</span></span>
                        <span class="font-mono text-slate-600 dark:text-slate-400">{{ number_format($app->logs_count) }} calls ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#2258a5] to-[#f58220] rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs">
                    No application traffic data recorded yet.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Errors -->
    @if($recentErrors->count() > 0)
        <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-6">
            <h2 class="font-bold text-sm text-rose-700 dark:text-rose-400 mb-4 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-[18px]">warning</span>
                <span>Recent Sync / Connection Errors</span>
            </h2>
            <div class="space-y-2.5">
                @foreach($recentErrors as $err)
                    <div class="p-3.5 rounded-xl bg-rose-50/70 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-xs flex items-start justify-between gap-4">
                        <div>
                            <div class="font-bold text-rose-900 dark:text-rose-200">{{ $err->app ? $err->app->name : 'Unknown' }} &bull; <span class="font-mono text-[11px] font-normal">{{ $err->endpoint }}</span></div>
                            <div class="text-rose-700 dark:text-rose-300 text-[11px] mt-0.5">{{ $err->error_message }}</div>
                        </div>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono shrink-0">{{ $err->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection