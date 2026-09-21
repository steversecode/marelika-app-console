@extends('layouts.console')

@section('title', 'Edit App - ' . $app->name)

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('console.apps.index') }}" class="hover:text-[#2258a5] dark:hover:text-white">App Management</a>
        <span>&gt;</span>
        <a href="{{ route('console.apps.show', $app->id) }}" class="hover:text-[#2258a5] dark:hover:text-white">{{ $app->name }}</a>
        <span>&gt;</span>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">Edit</span>
    </div>

    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
            <h1 class="text-lg font-bold text-[#2258a5] dark:text-white">Edit Application Settings</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Konfigurasi nama, status, dan target database untuk {{ $app->name }}.</p>
        </div>

        <form method="POST" action="{{ route('console.apps.update', $app->id) }}" class="p-6 space-y-6 text-xs">
            @csrf
            @method('PUT')

            <!-- App Status & Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">App Status</label>
                    <select name="status" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900 font-medium">
                        <option value="online" {{ $app->status === 'online' ? 'selected' : '' }}>🟢 Online (Aktif - Terima Request Odoo)</option>
                        <option value="offline" {{ $app->status === 'offline' ? 'selected' : '' }}>🔴 Offline (Nonaktifkan)</option>
                        <option value="development" {{ $app->status === 'development' ? 'selected' : '' }}>🟡 Development / Sandbox</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">App Category</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $app->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- App Name & Description -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">App Name</label>
                <input type="text" name="name" value="{{ old('name', $app->name) }}" required maxlength="50" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">App Description</label>
                <textarea name="description" rows="2" maxlength="250" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900">{{ old('description', $app->description) }}</textarea>
            </div>

            <!-- Direct Database Connection Box -->
            <div class="p-5 rounded-2xl bg-[#edf4fc]/60 dark:bg-slate-900/60 border border-[#d3e4f7] dark:border-slate-700 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="font-bold text-[#2258a5] dark:text-[#f58220] flex items-center gap-1.5 text-sm">
                        <span class="material-symbols-outlined text-[18px]">database</span>
                        <span>Direct Database Connection (Tanpa Sentuh .env Website)</span>
                    </div>
                    <span class="text-[10px] bg-[#2258a5] text-white px-2 py-0.5 rounded-full font-bold">Direkomendasikan</span>
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-[11px]">Masukkan nama database toko website. Console akan langsung membaca tabel <code>orders</code> dari database ini saat Odoo melakukan sync.</p>

                <input type="hidden" name="connection_type" value="database">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Database Name</label>
                        <input type="text" name="db_database" value="{{ old('db_database', $app->db_database) }}" placeholder="e.g. maneeproject / biemiproject" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-white dark:bg-slate-950 font-bold text-[#2258a5]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">DB Host</label>
                        <input type="text" name="db_host" value="{{ old('db_host', $app->db_host ?: '127.0.0.1') }}" placeholder="127.0.0.1" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-white dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">DB Port</label>
                        <input type="text" name="db_port" value="{{ old('db_port', $app->db_port ?: '3306') }}" placeholder="3306" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-white dark:bg-slate-950">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">DB Username</label>
                        <input type="text" name="db_username" value="{{ old('db_username', $app->db_username ?: 'root') }}" placeholder="root" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-white dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">DB Password</label>
                        <input type="password" name="db_password" value="{{ old('db_password', $app->db_password) }}" placeholder="Kosongkan jika tanpa password" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-white dark:bg-slate-950">
                    </div>
                </div>
            </div>

            <!-- Optional Store URL -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Store Web URL (Opsional)</label>
                <input type="url" name="store_url" value="{{ old('store_url', $app->store_url) }}" placeholder="https://biemi.id" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-slate-50/50 dark:bg-slate-900">
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('console.apps.show', $app->id) }}" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 font-bold rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#f58220] hover:bg-[#dc6e10] text-white font-bold rounded-xl shadow-xs transition-colors">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection