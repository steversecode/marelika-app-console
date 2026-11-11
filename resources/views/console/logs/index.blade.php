@extends('layouts.console')

@section('title', 'API Access Logs - Marelika Console')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb & Header Bar -->
    <div class="flex items-center justify-between text-xs text-slate-500">
        <div class="flex items-center gap-2">
            <span class="hover:text-[#2258a5] dark:hover:text-white transition-colors">Observability</span>
            <span class="text-slate-400 dark:text-slate-600">/</span>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">API Access Logs</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Live Audit Stream</span>
            </span>
        </div>
    </div>

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#edf4fc] dark:bg-slate-900 border border-blue-100 dark:border-slate-700 flex items-center justify-center text-[#2258a5] dark:text-sky-400 shrink-0">
                    <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-[#2258a5] dark:text-white tracking-tight">API Access Logs</h1>
                </div>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 pl-11">
                Real-time audit log and payload inspector for inbound Odoo ERP and external sync callers.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-auto pl-11 sm:pl-0">
            <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors focus-visible:ring-2 focus-visible:ring-[#2258a5]/30 outline-none" title="Refresh log feed">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
                <span>Refresh</span>
            </button>
            <a href="{{ route('console.logs.statistics') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-[#2258a5] hover:bg-[#1b4787] text-white text-xs font-semibold rounded-xl transition-colors shadow-2xs focus-visible:ring-2 focus-visible:ring-[#2258a5]/40 outline-none">
                <span class="material-symbols-outlined text-[16px] text-[#f58220]">bar_chart</span>
                <span>View Analytics</span>
            </a>
        </div>
    </div>

    <!-- Health & Performance Metric Cards -->
    @php
        $total = $stats['total_calls'] ?? 0;
        $success = $stats['success_calls'] ?? 0;
        $failed = $stats['failed_calls'] ?? 0;
        $successRate = $total > 0 ? round(($success / $total) * 100, 1) : 100;
        $failureRate = $total > 0 ? round(($failed / $total) * 100, 1) : 0;
        $avgLatency = $stats['avg_response_time'] ?? 0;
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Total Invocations -->
        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider">Total Calls</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#2258a5] dark:text-blue-300 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">swap_horiz</span>
                </span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-[#2258a5] dark:text-white mt-2 tracking-tight">
                {{ number_format($total) }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>All recorded gateway requests</span>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-emerald-700 dark:text-emerald-400 text-[11px] font-bold uppercase tracking-wider">Success (2xx)</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                </span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-2 tracking-tight">
                {{ number_format($success) }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-emerald-700 dark:text-emerald-400 font-medium">
                <span class="px-1.5 py-0.2 rounded bg-emerald-100 dark:bg-emerald-950/80 text-[10px] font-bold">{{ $successRate }}%</span>
                <span>Healthy response rate</span>
            </div>
        </div>

        <!-- Failure Count -->
        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-rose-700 dark:text-rose-400 text-[11px] font-bold uppercase tracking-wider">Errors / Failed</span>
                <span class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                </span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-rose-600 dark:text-rose-400 mt-2 tracking-tight">
                {{ number_format($failed) }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] {{ $failed > 0 ? 'text-rose-600 dark:text-rose-400 font-semibold' : 'text-slate-400 dark:text-slate-500' }}">
                @if($failed > 0)
                    <span class="px-1.5 py-0.2 rounded bg-rose-100 dark:bg-rose-950/80 text-[10px] font-bold">{{ $failureRate }}%</span>
                    <span>Needs attention</span>
                @else
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                    <span>Zero active failures</span>
                @endif
            </div>
        </div>

        <!-- Average Latency -->
        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider">Avg Latency</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">speed</span>
                </span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-slate-200 mt-2 tracking-tight font-mono">
                {{ $avgLatency }} <span class="text-xs font-normal text-slate-400 dark:text-slate-500 font-sans">ms</span>
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full {{ $avgLatency < 200 ? 'bg-emerald-500' : ($avgLatency < 500 ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                <span>{{ $avgLatency < 200 ? 'Optimal round-trip' : ($avgLatency < 500 ? 'Moderate latency' : 'High processing time') }}</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs">
        <form method="GET" action="{{ route('console.logs.index') }}" class="space-y-3" role="search" aria-label="Filter API Access Logs">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center text-xs">
                
                <!-- Application Filter -->
                <div class="sm:col-span-4">
                    <label for="filterAppId" class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Application Filter</label>
                    <div class="relative">
                        <select id="filterAppId" name="app_id" class="w-full pl-3.5 pr-8 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2258a5]/30 focus:border-[#2258a5] text-xs bg-slate-50/60 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-medium appearance-none transition-colors">
                            <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Applications ({{ $apps->count() }})</option>
                            @foreach($apps as $a)
                                <option value="{{ $a->id }}" {{ request('app_id') == $a->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                    {{ $a->name }} ({{ $a->partner_id }})
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined text-[18px] text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="sm:col-span-3">
                    <label for="filterStatus" class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Status Filter</label>
                    <div class="relative">
                        <select id="filterStatus" name="status" class="w-full pl-3.5 pr-8 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2258a5]/30 focus:border-[#2258a5] text-xs bg-slate-50/60 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-medium appearance-none transition-colors">
                            <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Status Codes</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Success (2xx OK)</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Failed / Errors (4xx, 5xx)</option>
                        </select>
                        <span class="material-symbols-outlined text-[18px] text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                    </div>
                </div>

                <!-- Endpoint Search Input -->
                <div class="sm:col-span-5">
                    <label for="filterEndpoint" class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Endpoint Query</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined text-[16px] text-slate-400 absolute left-3 pointer-events-none">search</span>
                        <input type="text" id="filterEndpoint" name="endpoint" value="{{ request('endpoint') }}" placeholder="Filter endpoint e.g. api/v1/odoo/orders..." class="w-full pl-9 pr-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2258a5]/30 focus:border-[#2258a5] text-xs font-mono bg-slate-50/60 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Action Buttons and Active Badges -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-1 border-t border-slate-100 dark:border-slate-800/80">
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-[#2258a5] hover:bg-[#1b4787] text-white font-semibold text-xs rounded-xl shadow-2xs transition-colors focus-visible:ring-2 focus-visible:ring-[#2258a5]/40 outline-none">
                        <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                        <span>Apply Filters</span>
                    </button>
                    @if(request()->hasAny(['app_id', 'status', 'endpoint']))
                        <a href="{{ route('console.logs.index') }}" class="inline-flex items-center justify-center gap-1 px-3 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white text-xs font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-[15px]">close</span>
                            <span>Reset Filters</span>
                        </a>
                    @endif
                </div>

                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <span>Showing <strong class="text-slate-800 dark:text-slate-200">{{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }}</strong> of <strong class="text-slate-800 dark:text-slate-200">{{ number_format($logs->total()) }}</strong> records</span>
                </div>
            </div>
        </form>
    </div>

    <!-- Logs Table Container -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-[#2258a5] dark:text-sky-400">table_rows</span>
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200">Request Transactions</h2>
            </div>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">Click any row to inspect full headers &amp; payloads</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 dark:bg-slate-900/70 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[10px]">
                        <th scope="col" class="py-3 px-4 w-44">Time</th>
                        <th scope="col" class="py-3 px-4">Client App</th>
                        <th scope="col" class="py-3 px-4">Method &amp; Endpoint</th>
                        <th scope="col" class="py-3 px-4 w-28">Status</th>
                        <th scope="col" class="py-3 px-4 w-28">Latency</th>
                        <th scope="col" class="py-3 px-4 w-32">Caller IP</th>
                        <th scope="col" class="py-3 px-4 w-24 text-center">Inspect</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 font-normal">
                    @forelse($logs as $log)
                        @php
                            $logJson = [
                                'id' => $log->id,
                                'time' => $log->created_at->format('d M Y, H:i:s'),
                                'time_ago' => $log->created_at->diffForHumans(),
                                'app_name' => $log->app ? $log->app->name : 'Unknown Application',
                                'partner_id' => $log->app ? $log->app->partner_id : '-',
                                'method' => $log->method,
                                'endpoint' => $log->endpoint,
                                'status_code' => $log->status_code,
                                'status' => $log->status,
                                'response_time_ms' => $log->response_time_ms,
                                'ip_address' => $log->ip_address ?: '-',
                                'user_agent' => $log->user_agent ?: '-',
                                'error_message' => $log->error_message,
                                'request_headers' => $log->request_headers,
                                'request_payload' => $log->request_payload,
                                'response_payload' => $log->response_payload,
                            ];
                        @endphp
                        <tr class="hover:bg-blue-50/40 dark:hover:bg-slate-800/60 transition-colors group cursor-pointer" onclick="openLogInspector({{ json_encode($logJson) }})">
                            
                            <!-- Timestamp -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="font-mono text-slate-800 dark:text-slate-200 text-[11px] font-medium">
                                    {{ $log->created_at->format('H:i:s') }}
                                </div>
                                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-sans">
                                    {{ $log->created_at->format('d M Y') }} &bull; {{ $log->created_at->diffForHumans(null, true) }}
                                </div>
                            </td>

                            <!-- App / Partner ID -->
                            <td class="py-3 px-4">
                                @if($log->app)
                                    <div class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#2258a5] dark:bg-sky-400"></span>
                                        <span>{{ $log->app->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="font-mono text-[10px] text-slate-400 dark:text-slate-500">{{ $log->app->partner_id }}</span>
                                        <button type="button" onclick="event.stopPropagation(); copyToClipboard('{{ $log->app->partner_id }}', this, 'Partner ID')" class="text-slate-400 hover:text-[#2258a5] dark:hover:text-sky-400 p-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity" title="Copy Partner ID" aria-label="Copy Partner ID">
                                            <span class="material-symbols-outlined text-[13px] block">content_copy</span>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic text-[11px]">Unregistered App</span>
                                @endif
                            </td>

                            <!-- Method & Endpoint -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-1.5 font-mono text-[11px]">
                                    @php
                                        $methodBg = match(strtoupper($log->method)) {
                                            'GET' => 'bg-sky-100 dark:bg-sky-950/70 text-sky-800 dark:text-sky-300 border-sky-200 dark:border-sky-800/80',
                                            'POST' => 'bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800/80',
                                            'PUT', 'PATCH' => 'bg-purple-100 dark:bg-purple-950/70 text-purple-800 dark:text-purple-300 border-purple-200 dark:border-purple-800/80',
                                            'DELETE' => 'bg-rose-100 dark:bg-rose-950/70 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-800/80',
                                            default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold border {{ $methodBg }}">
                                        {{ $log->method }}
                                    </span>
                                    <span class="text-slate-800 dark:text-slate-200 font-semibold truncate max-w-xs sm:max-w-sm md:max-w-md block" title="{{ $log->endpoint }}">
                                        {{ $log->endpoint }}
                                    </span>
                                    <button type="button" onclick="event.stopPropagation(); copyToClipboard('{{ $log->endpoint }}', this, 'Endpoint')" class="text-slate-400 hover:text-[#2258a5] dark:hover:text-sky-400 p-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity" title="Copy endpoint" aria-label="Copy endpoint">
                                        <span class="material-symbols-outlined text-[13px] block">content_copy</span>
                                    </button>
                                </div>
                                @if($log->error_message)
                                    <div class="flex items-center gap-1 text-rose-600 dark:text-rose-400 text-[10px] font-sans mt-1 bg-rose-50/70 dark:bg-rose-950/40 px-2 py-0.5 rounded-md border border-rose-100 dark:border-rose-900/50 max-w-lg truncate" title="{{ $log->error_message }}">
                                        <span class="material-symbols-outlined text-[13px] shrink-0">error</span>
                                        <span class="truncate">{{ $log->error_message }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Status Code -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($log->status === 'success' || ($log->status_code >= 200 && $log->status_code < 300))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="material-symbols-outlined text-[12px]">check</span>
                                        <span>{{ $log->status_code }} OK</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                        <span class="material-symbols-outlined text-[12px]">warning</span>
                                        <span>{{ $log->status_code }} ERR</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Latency -->
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-[11px]">
                                @if($log->response_time_ms !== null)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $log->response_time_ms < 150 ? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : ($log->response_time_ms < 500 ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300') }}">
                                        {{ $log->response_time_ms }} ms
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600">-</span>
                                @endif
                            </td>

                            <!-- Caller IP -->
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-slate-500 dark:text-slate-400 text-[11px]">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px] text-slate-400">public</span>
                                    <span>{{ $log->ip_address ?: '-' }}</span>
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <button type="button" class="px-2.5 py-1 text-[#2258a5] dark:text-sky-400 hover:bg-[#edf4fc] dark:hover:bg-slate-800 font-bold rounded-lg transition-colors inline-flex items-center gap-1 text-[11px] focus-visible:ring-2 focus-visible:ring-[#2258a5]/30 outline-none" title="Inspect Request Payload">
                                    <span class="material-symbols-outlined text-[15px]">search</span>
                                    <span>Inspect</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto">
                                        <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300">No log records found</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        No incoming API calls match your current filter parameters. Try clearing the filters or making a test call.
                                    </p>
                                    @if(request()->hasAny(['app_id', 'status', 'endpoint']))
                                        <a href="{{ route('console.logs.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#2258a5] text-white text-xs font-semibold rounded-xl hover:bg-[#1b4787] transition-colors shadow-2xs">
                                            <span>Clear All Filters</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 sm:p-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Slide-Over Drawer Inspector Modal -->
<div id="logInspectorDrawer" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="inspectorTitle">
    <!-- Backdrop with blur -->
    <div id="inspectorBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity opacity-0" onclick="closeLogInspector()"></div>

    <!-- Slide-over Drawer Panel -->
    <div class="fixed inset-y-0 right-0 max-w-2xl w-full bg-white dark:bg-[#102038] shadow-2xl border-l border-slate-200 dark:border-slate-800 flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out z-10" id="inspectorPanel">
        
        <!-- Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3 bg-slate-50/80 dark:bg-slate-900/80">
            <div class="flex items-center gap-2.5">
                <span id="inspMethodBadge" class="px-2 py-0.5 rounded-md text-[10px] font-extrabold border font-mono"></span>
                <div>
                    <h3 id="inspectorTitle" class="text-sm font-bold text-slate-800 dark:text-white font-mono truncate max-w-sm sm:max-w-md"></h3>
                    <div id="inspTimestamp" class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5"></div>
                </div>
            </div>
            
            <button type="button" onclick="closeLogInspector()" class="w-8 h-8 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus-visible:ring-2 focus-visible:ring-[#2258a5]/30 outline-none" aria-label="Close Inspector">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <!-- Body Content -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-5 text-xs">
            
            <!-- Metadata Summary Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-3.5 rounded-xl bg-slate-50/80 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500">Status Code</div>
                    <div id="inspStatusCode" class="text-xs font-bold font-mono mt-1"></div>
                </div>
                <div>
                    <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500">Duration</div>
                    <div id="inspDuration" class="text-xs font-bold font-mono text-slate-800 dark:text-slate-200 mt-1"></div>
                </div>
                <div>
                    <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500">Target App</div>
                    <div id="inspAppName" class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-1 truncate"></div>
                </div>
                <div>
                    <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500">Caller IP</div>
                    <div id="inspIp" class="text-xs font-mono text-slate-800 dark:text-slate-200 mt-1"></div>
                </div>
            </div>

            <!-- Error Banner (if present) -->
            <div id="inspErrorBox" class="hidden p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 space-y-1">
                <div class="flex items-center gap-1.5 font-bold text-rose-700 dark:text-rose-400 text-xs">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                    <span>Error Description</span>
                </div>
                <p id="inspErrorMessage" class="text-rose-800 dark:text-rose-300 font-mono text-[11px] break-words whitespace-pre-wrap"></p>
            </div>

            <!-- Client Info / User Agent -->
            <div class="space-y-1.5">
                <div class="font-bold text-slate-700 dark:text-slate-300 flex items-center justify-between">
                    <span>Client User-Agent</span>
                </div>
                <div id="inspUserAgent" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-[11px] font-mono text-slate-700 dark:text-slate-300 break-all select-all">
                </div>
            </div>

            <!-- Request Headers Section -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between font-bold text-slate-700 dark:text-slate-300">
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-[#2258a5]">code</span>
                        <span>Request Headers</span>
                    </span>
                    <button type="button" onclick="copyInspectorContent('inspHeadersCode', this)" class="text-[#2258a5] dark:text-sky-400 hover:underline text-[11px] flex items-center gap-1 font-bold">
                        <span class="material-symbols-outlined text-[13px]">content_copy</span>
                        <span>Copy Headers</span>
                    </button>
                </div>
                <pre id="inspHeadersCode" class="p-3.5 bg-slate-950 text-slate-200 font-mono text-[11px] rounded-xl overflow-x-auto max-h-48 border border-slate-800 select-all"></pre>
            </div>

            <!-- Request Payload Section -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between font-bold text-slate-700 dark:text-slate-300">
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-amber-500">upload</span>
                        <span>Inbound Request Body / Payload</span>
                    </span>
                    <button type="button" onclick="copyInspectorContent('inspRequestPayloadCode', this)" class="text-[#2258a5] dark:text-sky-400 hover:underline text-[11px] flex items-center gap-1 font-bold">
                        <span class="material-symbols-outlined text-[13px]">content_copy</span>
                        <span>Copy JSON</span>
                    </button>
                </div>
                <pre id="inspRequestPayloadCode" class="p-3.5 bg-slate-950 text-amber-400 font-mono text-[11px] rounded-xl overflow-x-auto max-h-64 border border-slate-800 select-all"></pre>
            </div>

            <!-- Response Payload Section -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between font-bold text-slate-700 dark:text-slate-300">
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-emerald-500">download</span>
                        <span>Outbound Response Body / Payload</span>
                    </span>
                    <button type="button" onclick="copyInspectorContent('inspResponsePayloadCode', this)" class="text-[#2258a5] dark:text-sky-400 hover:underline text-[11px] flex items-center gap-1 font-bold">
                        <span class="material-symbols-outlined text-[13px]">content_copy</span>
                        <span>Copy JSON</span>
                    </button>
                </div>
                <pre id="inspResponsePayloadCode" class="p-3.5 bg-slate-950 text-emerald-400 font-mono text-[11px] rounded-xl overflow-x-auto max-h-64 border border-slate-800 select-all"></pre>
            </div>

        </div>

        <!-- Footer -->
        <div class="p-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/60 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <span>Press <kbd class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-800 font-mono text-[10px]">Esc</kbd> to close</span>
            <button type="button" onclick="closeLogInspector()" class="px-3.5 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold rounded-lg transition-colors">
                Close
            </button>
        </div>

    </div>
</div>

<script>
    function safeFormatJson(input) {
        if (!input) return '(Empty)';
        if (typeof input === 'object') {
            try {
                return JSON.stringify(input, null, 2);
            } catch (e) {
                return String(input);
            }
        }
        try {
            const parsed = JSON.parse(input);
            return JSON.stringify(parsed, null, 2);
        } catch (e) {
            return String(input);
        }
    }

    function openLogInspector(log) {
        const drawer = document.getElementById('logInspectorDrawer');
        const backdrop = document.getElementById('inspectorBackdrop');
        const panel = document.getElementById('inspectorPanel');

        if (!drawer || !backdrop || !panel) return;

        // Title and Method
        document.getElementById('inspectorTitle').innerText = log.endpoint || '/';
        document.getElementById('inspTimestamp').innerText = `${log.time} (${log.time_ago})`;

        const badge = document.getElementById('inspMethodBadge');
        badge.innerText = log.method || 'GET';
        if (log.method === 'POST') {
            badge.className = 'px-2 py-0.5 rounded-md text-[10px] font-extrabold border font-mono bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800';
        } else if (log.method === 'GET') {
            badge.className = 'px-2 py-0.5 rounded-md text-[10px] font-extrabold border font-mono bg-sky-100 dark:bg-sky-950/70 text-sky-800 dark:text-sky-300 border-sky-200 dark:border-sky-800';
        } else {
            badge.className = 'px-2 py-0.5 rounded-md text-[10px] font-extrabold border font-mono bg-purple-100 dark:bg-purple-950/70 text-purple-800 dark:text-purple-300 border-purple-200 dark:border-purple-800';
        }

        // Meta stats
        const statusEl = document.getElementById('inspStatusCode');
        statusEl.innerText = `${log.status_code || 200} ${log.status === 'success' ? 'OK' : 'ERR'}`;
        statusEl.className = `text-xs font-bold font-mono mt-1 ${log.status === 'success' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'}`;

        document.getElementById('inspDuration').innerText = log.response_time_ms !== null ? `${log.response_time_ms} ms` : '-';
        document.getElementById('inspAppName').innerText = log.app_name;
        document.getElementById('inspIp').innerText = log.ip_address;
        document.getElementById('inspUserAgent').innerText = log.user_agent;

        // Error message
        const errBox = document.getElementById('inspErrorBox');
        if (log.error_message) {
            errBox.classList.remove('hidden');
            document.getElementById('inspErrorMessage').innerText = log.error_message;
        } else {
            errBox.classList.add('hidden');
        }

        // Headers and Payloads
        document.getElementById('inspHeadersCode').innerText = safeFormatJson(log.request_headers);
        document.getElementById('inspRequestPayloadCode').innerText = safeFormatJson(log.request_payload);
        document.getElementById('inspResponsePayloadCode').innerText = safeFormatJson(log.response_payload);

        // Open animation
        drawer.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeLogInspector() {
        const drawer = document.getElementById('logInspectorDrawer');
        const backdrop = document.getElementById('inspectorBackdrop');
        const panel = document.getElementById('inspectorPanel');

        if (!drawer || !backdrop || !panel) return;

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        panel.classList.add('translate-x-full');

        setTimeout(() => {
            drawer.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }

    function copyInspectorContent(elementId, btnElement) {
        const el = document.getElementById(elementId);
        if (!el) return;
        const text = el.innerText;
        if (text === '(Empty)') {
            MarelToast.fire({
                icon: 'info',
                title: 'Data payload kosong'
            });
            return;
        }
        copyToClipboard(text, btnElement, 'Payload Content');
    }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const drawer = document.getElementById('logInspectorDrawer');
            if (drawer && !drawer.classList.contains('hidden')) {
                closeLogInspector();
            }
        }
    });
</script>
@endsection
