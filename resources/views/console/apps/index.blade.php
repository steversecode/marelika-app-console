@extends('layouts.console')

@section('title', 'App List - Marelika Open Platform Console')

@section('content')
<div class="space-y-6">

    <!-- Top Banner -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-[#2258a5] dark:text-white">App Management</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar konektor aplikasi toko (Biemi, Clabycla, Manee) untuk integrasi dengan Odoo 13 ERP.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('console.apps.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#f58220] hover:bg-[#dc6e10] text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                <span class="material-symbols-outlined text-[16px]">add</span>
                <span>+ Add New APP</span>
            </a>
        </div>
    </div>

    <!-- Stat Highlights -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="flex items-center justify-between">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Apps</div>
                <span class="material-symbols-outlined text-[#2258a5] text-[20px]">store</span>
            </div>
            <div class="text-2xl font-bold text-[#2258a5] dark:text-white mt-2">{{ $stats['total_apps'] }}</div>
        </div>

        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="flex items-center justify-between">
                <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Active / Online</div>
                <span class="material-symbols-outlined text-emerald-500 text-[20px]">check_circle</span>
            </div>
            <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-400 mt-2">{{ $stats['online_apps'] }}</div>
        </div>

        <div class="bg-white dark:bg-[#102038] p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
            <div class="flex items-center justify-between">
                <div class="text-[11px] font-bold uppercase tracking-wider text-[#f58220]">Total API Traffic</div>
                <span class="material-symbols-outlined text-[#f58220] text-[20px]">sync_alt</span>
            </div>
            <div class="text-2xl font-bold text-[#f58220] mt-2">{{ number_format($stats['total_api_calls']) }}</div>
        </div>
    </div>

    <!-- Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        @forelse($apps as $app)
            <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-md hover:border-[#2258a5]/40 transition-all flex flex-col justify-between overflow-hidden group">
                
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-start gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-[#edf4fc] dark:bg-slate-800 border border-[#d3e4f7] dark:border-slate-700 flex items-center justify-center shrink-0 text-[#2258a5] dark:text-[#f58220] font-bold text-lg">
                            <span class="material-symbols-outlined text-[24px]">storefront</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="font-bold text-sm text-[#2258a5] dark:text-white truncate" title="{{ $app->name }}">
                                    {{ $app->name }}
                                </h3>
                                @if($app->status === 'online')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Online</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 shrink-0">
                                        <span>Offline</span>
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate mt-0.5">{{ $app->category }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 space-y-3 text-xs text-slate-600 dark:text-slate-300 flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">Test Partner ID:</span>
                        <span class="font-mono font-medium text-slate-700 dark:text-slate-300">{{ $app->test_partner_id ?: '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">Live Partner ID:</span>
                        <span class="font-mono font-bold text-[#f58220] text-sm">{{ $app->partner_id }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">Key Expire:</span>
                        <span class="text-slate-700 dark:text-slate-400 text-[11px]">
                            {{ $app->key_expires_at ? $app->key_expires_at->format('d/m/Y') : 'Unlimited' }}
                        </span>
                    </div>

                    <div class="pt-2 border-t border-dashed border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">Store URL:</span>
                        <span class="font-mono text-slate-700 dark:text-slate-300 text-[11px] truncate max-w-[150px]">
                            {{ $app->store_url ?: 'Local API' }}
                        </span>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="bg-slate-50/80 dark:bg-slate-900/60 px-5 py-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-400 text-[11px]">
                        {{ $app->total_calls }} API Calls
                    </span>
                    <a href="{{ route('console.apps.show', $app->id) }}" class="font-bold text-[#2258a5] dark:text-[#f58220] hover:underline flex items-center gap-1">
                        <span>Kelola APP Key</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-12 text-center">
                <span class="material-symbols-outlined text-[36px] text-slate-400 mb-2">storefront</span>
                <h3 class="font-bold text-base text-slate-800 dark:text-white">Belum ada aplikasi yang didaftarkan</h3>
                <p class="text-xs text-slate-500 mt-1 mb-4">Buat konektor aplikasi untuk Biemi, Clabycla, atau Manee.</p>
                <a href="{{ route('console.apps.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#f58220] text-white text-xs font-bold rounded-xl">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Create App</span>
                </a>
            </div>
        @endforelse
    </div>

    @if($apps->hasPages())
        <div class="mt-6">
            {{ $apps->links() }}
        </div>
    @endif

</div>
@endsection