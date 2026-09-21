@extends('layouts.console')

@section('title', 'API Calls Statistics - Marelika Console')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-lg border border-[#e8e8e8] p-5 shadow-2xs flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#222]">API Calls Statistics</h1>
            <p class="text-xs text-slate-500 mt-1">Traffic and volume metrics across all store connectors.</p>
        </div>
        <a href="{{ route('console.logs.index') }}" class="px-3 py-1.5 border border-slate-300 text-slate-700 text-xs font-semibold rounded-md hover:bg-slate-50">
            View Raw Logs
        </a>
    </div>

    <!-- App Breakdown Table -->
    <div class="bg-white rounded-lg border border-[#e8e8e8] shadow-2xs p-5">
        <h2 class="font-bold text-sm text-slate-800 mb-4">Traffic Breakdown by Store Application</h2>
        <div class="space-y-3">
            @foreach($apps as $app)
                @php
                    $pct = $totalLogs > 0 ? round(($app->logs_count / $totalLogs) * 100, 1) : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-800">{{ $app->name }} (Partner ID: {{ $app->partner_id }})</span>
                        <span class="font-mono text-slate-500">{{ number_format($app->logs_count) }} calls ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full bg-[#ee4d2d]" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Errors -->
    @if($recentErrors->count() > 0)
        <div class="bg-white rounded-lg border border-[#e8e8e8] shadow-2xs p-5">
            <h2 class="font-bold text-sm text-rose-800 mb-3 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-rose-600 text-[18px]">warning</span>
                <span>Recent Sync / Connection Errors</span>
            </h2>
            <div class="space-y-2">
                @foreach($recentErrors as $err)
                    <div class="p-3 rounded bg-rose-50/60 border border-rose-200/60 text-xs flex items-start justify-between gap-4">
                        <div>
                            <div class="font-semibold text-rose-900">{{ $err->app ? $err->app->name : 'Unknown' }} &bull; {{ $err->endpoint }}</div>
                            <div class="text-rose-700 text-[11px] mt-0.5">{{ $err->error_message }}</div>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono shrink-0">{{ $err->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection