@extends('layouts.console')

@section('title', 'Create App - Shopee Open Platform Console')

@section('content')
<div class="max-w-3xl space-y-6">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('console.apps.index') }}" class="hover:text-[#ee4d2d]">App List</a>
        <span>&gt;</span>
        <span class="text-slate-800 font-semibold">Create App</span>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-lg border border-[#e8e8e8] shadow-2xs overflow-hidden">
        <div class="p-6 border-b border-[#f0f0f0]">
            <h1 class="text-lg font-bold text-[#222]">Create New Application</h1>
            <p class="text-xs text-slate-500 mt-1">Register a new store application (Biemi, Clabycla, Manee, etc) to generate API Partner ID and secure credentials for Odoo synchronization.</p>
        </div>

        <form method="POST" action="{{ route('console.apps.store') }}" class="p-6 space-y-5 text-xs">
            @csrf

            <!-- App Category -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">
                    <span class="text-rose-500">*</span> App Category
                </label>
                <select name="category" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs bg-white">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Choose 'Seller In-House System' for internal store connectors.</p>
            </div>

            <!-- App Name -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">
                    <span class="text-rose-500">*</span> App Name
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="50" placeholder="e.g. Biemi Official Store / Clabycla / Manee" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs">
                <p class="text-[11px] text-slate-400 mt-1">Max 50 characters. This name will identify the store in Odoo configuration.</p>
            </div>

            <!-- App Description -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">App Description</label>
                <textarea name="description" rows="3" maxlength="250" placeholder="Describe the purpose of this app integration..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs">{{ old('description') }}</textarea>
            </div>

            <!-- Store Base URL -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Store Base URL</label>
                <input type="url" name="store_url" value="{{ old('store_url') }}" placeholder="https://biemi.id or https://clabycla.com" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs font-mono">
                <p class="text-[11px] text-slate-400 mt-1">The website domain where Laravel orders API is hosted.</p>
            </div>

            <!-- Redirect URL Domain -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Redirect URL Domain</label>
                <input type="text" name="redirect_url" value="{{ old('redirect_url') }}" placeholder="https://..." class="w-full px-3.5 py-2.5 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs font-mono">
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-[#f0f0f0] flex items-center justify-end gap-3">
                <a href="{{ route('console.apps.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 font-medium rounded-md transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-[#ee4d2d] hover:bg-[#d73211] text-white font-semibold rounded-md shadow-xs transition-colors">
                    Submit &amp; Generate Key
                </button>
            </div>

        </form>
    </div>

</div>
@endsection