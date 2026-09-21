@extends('layouts.console')

@section('title', $app->name . ' - APP Key Management')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('console.apps.index') }}" class="hover:text-[#2258a5] dark:hover:text-white">App Management</a>
        <span>&gt;</span>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $app->name }}</span>
    </div>

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-[#2258a5] dark:text-white">{{ $app->name }}</h1>
                @if($app->status === 'online')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Online</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                        <span>Offline</span>
                    </span>
                @endif

                @if($dbStatus)
                    @if($dbStatus['connected'])
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800">
                            <span class="material-symbols-outlined text-[13px]">database</span>
                            <span>DB Connected ({{ $dbStatus['orders_count'] }} Pesanan)</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200" title="{{ $dbStatus['error'] }}">
                            <span class="material-symbols-outlined text-[13px]">error</span>
                            <span>DB Gagal Konek</span>
                        </span>
                    @endif
                @endif
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                {{ $app->category }} &bull; Target DB: <strong class="text-[#2258a5] dark:text-[#f58220] font-mono">{{ $app->db_database ?: 'Belum disetting' }}</strong>
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('console.apps.edit', $app->id) }}" class="px-4 py-2 bg-[#2258a5] hover:bg-[#1b4787] text-white text-xs font-bold rounded-xl transition-all shadow-2xs flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[15px]">edit</span>
                <span>Edit &amp; Setting DB</span>
            </a>
            <form method="POST" action="{{ route('console.apps.destroy', $app->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aplikasi ini? Kredensial API akan langsung tidak aktif.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-rose-200 dark:border-rose-900 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-bold rounded-xl transition-all">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- APP Key Details Section -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-bold text-[#2258a5] dark:text-white">Kredensial APP Key</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Gunakan Partner ID dan Live Partner Key ini pada konfigurasi Odoo 13 ERP.</p>
        </div>

        <div class="p-6 space-y-4 text-xs">
            
            <!-- Live Partner ID -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-3 border-b border-slate-100 dark:border-slate-800 bg-[#edf4fc]/50 dark:bg-slate-900/40 -mx-6 px-6">
                <div class="text-slate-700 dark:text-slate-300 font-bold">Live Partner_id</div>
                <div class="sm:col-span-2 flex items-center gap-3">
                    <span class="font-mono font-bold text-lg text-[#f58220]">{{ $app->partner_id }}</span>
                    <button onclick="copyToClipboard('{{ $app->partner_id }}', this, 'Live Partner ID')" class="text-[#2258a5] dark:text-sky-400 hover:underline font-bold text-[11px] flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span> Copy
                    </button>
                </div>
            </div>

            <!-- Live API Partner Key -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-4 border-b border-slate-100 dark:border-slate-800 bg-[#edf4fc]/50 dark:bg-slate-900/40 -mx-6 px-6">
                <div class="text-slate-700 dark:text-slate-300 font-bold flex items-center gap-1">
                    <span>Live API Partner Key</span>
                    <span class="material-symbols-outlined text-[14px] text-slate-400" title="Kunci rahasia untuk API Odoo">help</span>
                </div>
                <div class="sm:col-span-2 space-y-2">
                    <div class="flex items-center gap-3">
                        <span id="liveKeyText" class="font-mono font-bold text-slate-900 dark:text-slate-100 text-xs bg-white dark:bg-slate-950 px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl select-all max-w-md truncate">
                            {{ substr($app->api_key, 0, 16) . '••••••••••••••••••••••••••••••••' }}
                        </span>
                        <button onclick="toggleKeyVisibility('liveKeyText', '{{ $app->api_key }}', this)" class="p-1.5 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" title="Show / Hide Key">
                            <span class="material-symbols-outlined text-[18px] block">visibility</span>
                        </button>
                        <button onclick="copyToClipboard('{{ $app->api_key }}', this, 'Live API Partner Key')" class="px-3.5 py-1.5 bg-[#f58220] hover:bg-[#dc6e10] text-white font-bold text-[11px] rounded-xl flex items-center gap-1 shadow-2xs">
                            <span class="material-symbols-outlined text-[13px]">content_copy</span> Copy Key
                        </button>
                    </div>

                    <form id="rotateKeyForm" method="POST" action="{{ route('console.apps.rotate-key', $app->id) }}" class="inline-block">
                        @csrf
                        <input type="hidden" name="type" value="live">
                        <button type="button" onclick="confirmRotateKey()" class="text-[#f58220] hover:text-[#d76b10] text-[11px] font-bold flex items-center gap-1 mt-1 cursor-pointer">
                            <span class="material-symbols-outlined text-[14px]">rotate_right</span>
                            <span>Rotate / Generate Ulang Key</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Database Info Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-2 border-b border-slate-100 dark:border-slate-800">
                <div class="text-slate-500 dark:text-slate-400 font-medium">Koneksi Database</div>
                <div class="sm:col-span-2 font-mono font-bold text-slate-800 dark:text-slate-200">
                    {{ $app->db_database ?: 'Belum disetting (Klik Edit Detail di atas)' }} ({{ $app->db_host ?: '127.0.0.1' }}:{{ $app->db_port ?: '3306' }})
                </div>
            </div>

            <!-- Test Partner ID -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-2 border-b border-slate-100 dark:border-slate-800">
                <div class="text-slate-500 dark:text-slate-400 font-medium">Test Partner_id</div>
                <div class="sm:col-span-2 flex items-center gap-3">
                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $app->test_partner_id ?: '-' }}</span>
                    <button onclick="copyToClipboard('{{ $app->test_partner_id }}', this, 'Test Partner ID')" class="text-slate-400 hover:text-slate-700 dark:hover:text-white font-medium text-[11px] flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span> Copy
                    </button>
                </div>
            </div>

            <!-- Expire Time -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-2">
                <div class="text-slate-500 dark:text-slate-400 font-medium">Key Expire Time</div>
                <div class="sm:col-span-2 text-slate-700 dark:text-slate-300 font-medium">
                    {{ $app->key_expires_at ? $app->key_expires_at->format('d/m/Y H:i') : 'Unlimited' }}
                </div>
            </div>

        </div>
    </div>

    <!-- Odoo Setup Box (Marel Navy & Orange Card) -->
    <div class="bg-gradient-to-br from-[#123363] via-[#2258a5] to-[#0a1b33] text-white rounded-2xl p-6 shadow-md space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#f58220]">cable</span>
                <h3 class="font-bold text-sm text-white">Panduan Konfigurasi di Odoo 13</h3>
            </div>
            <span class="text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-full bg-white/10 text-white border border-white/15">Gateway Direct Mode</span>
        </div>

        <p class="text-xs text-sky-100">Salin nilai berikut ke form <strong>Sales &gt; Marelika Store &gt; Configuration</strong> di Odoo:</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-mono">
            <div class="bg-black/30 p-3.5 rounded-xl border border-white/10">
                <div class="text-[10px] text-sky-200 uppercase font-sans mb-1">Laravel API Base URL:</div>
                <div class="flex items-center justify-between text-white font-bold">
                    <span class="truncate">{{ url('/') }}</span>
                    <button onclick="copyToClipboard('{{ url('/') }}', this, 'API Base URL')" class="text-slate-300 hover:text-white ml-2">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                    </button>
                </div>
            </div>

            <div class="bg-black/30 p-3.5 rounded-xl border border-white/10">
                <div class="text-[10px] text-sky-200 uppercase font-sans mb-1">Secret API Key:</div>
                <div class="flex items-center justify-between text-[#f58220] font-bold">
                    <span class="truncate">{{ substr($app->api_key, 0, 20) }}...</span>
                    <button onclick="copyToClipboard('{{ $app->api_key }}', this, 'Live API Partner Key')" class="text-slate-300 hover:text-white ml-2">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    function confirmRotateKey() {
        marelConfirm(
            'Regenerate Live Partner Key?',
            'Key lama akan langsung kedaluwarsa dan tidak dapat digunakan lagi. Anda harus memperbarui key di konfigurasi Odoo!',
            'Ya, Regenerate Key',
            'warning',
            '#f58220'
        ).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('rotateKeyForm').submit();
            }
        });
    }

    function confirmDeleteApp() {
        marelConfirm(
            'Hapus Aplikasi Ini?',
            'Seluruh koneksi database dan kredensial API aplikasi ini akan dinonaktifkan secara permanen.',
            'Ya, Hapus Aplikasi',
            'error',
            '#e11d48'
        ).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAppForm').submit();
            }
        });
    }

    function toggleKeyVisibility(elementId, fullKey, btn) {
        const el = document.getElementById(elementId);
        if (el.dataset.revealed === 'true') {
            el.innerText = fullKey.substring(0, 16) + '••••••••••••••••••••••••••••••••';
            el.dataset.revealed = 'false';
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">visibility</span>';
        } else {
            el.innerText = fullKey;
            el.dataset.revealed = 'true';
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">visibility_off</span>';
        }
    }
</script>
@endsection