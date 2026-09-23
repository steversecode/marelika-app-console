@extends('layouts.console')

@section('title', 'Create App - Marelika Open Platform Console')

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
        <a href="{{ route('console.apps.index') }}" class="hover:text-[#2258a5] dark:hover:text-white transition-colors">App Management</a>
        <span>&gt;</span>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">Create App</span>
    </div>

    <!-- Card Form -->
    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-slate-800">
            <h1 class="text-lg font-bold text-[#2258a5] dark:text-white">Create New Application</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Register a new store application (Biemi, Clabycla, Manee, etc) to generate API Partner ID and secure credentials for Odoo synchronization.</p>
        </div>

        <form method="POST" action="{{ route('console.apps.store') }}" class="p-4 sm:p-6 space-y-4 sm:space-y-5 text-xs">
            @csrf

            <!-- App Category -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    <span class="text-rose-500">*</span> App Category
                </label>
                <select name="category" required class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors font-medium">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">{{ $cat }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Choose 'Seller In-House System' for internal store connectors.</p>
            </div>

            <!-- App Name -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    <span class="text-rose-500">*</span> App Name
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="50" placeholder="e.g. Biemi Official Store / Clabycla / Manee" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-colors">
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Max 50 characters. This name will identify the store in Odoo configuration.</p>
            </div>

            <!-- App Description -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">App Description</label>
                <textarea name="description" rows="3" maxlength="250" placeholder="Describe the purpose of this app integration..." class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-colors">{{ old('description') }}</textarea>
            </div>

            <!-- Store Base URL -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Store Base URL</label>
                <input type="url" name="store_url" value="{{ old('store_url') }}" placeholder="https://biemi.id or https://clabycla.com" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-colors">
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">The website domain where Laravel orders API is hosted.</p>
            </div>

            <!-- Redirect URL Domain -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Redirect URL Domain</label>
                <input type="text" name="redirect_url" value="{{ old('redirect_url') }}" placeholder="https://..." class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-colors">
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 sm:gap-3">
                <a href="{{ route('console.apps.index') }}" class="w-full sm:w-auto text-center px-4 py-2.5 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#f58220] hover:bg-[#dc6e10] text-white font-bold rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">key</span>
                    <span>Submit &amp; Generate Key</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection