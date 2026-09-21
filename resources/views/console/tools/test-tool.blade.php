@extends('layouts.console')

@section('title', 'API Test Tool - Marelika Open Platform Console')

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-2xs">
        <h1 class="text-xl font-bold text-[#2258a5] dark:text-white">API Test Tool</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Simulate and test Odoo 13 request calls to store connectors in real-time.</p>
    </div>

    <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-6 space-y-4 text-xs" x-data="{ loading: false, result: null }">
        
        <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Select Target Application</label>
            <select id="targetApp" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-medium">
                @foreach($apps as $app)
                    <option value="{{ $app->id }}" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">{{ $app->name }} (Partner ID: {{ $app->partner_id }} - {{ $app->store_url ?: 'Local' }})</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-1">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">HTTP Method</label>
                <select id="httpMethod" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-mono font-bold">
                    <option value="GET" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">GET</option>
                    <option value="POST" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">POST</option>
                </select>
            </div>
            <div class="sm:col-span-3">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Endpoint Path</label>
                <input type="text" id="endpointPath" value="api/v1/odoo/orders" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-[#2258a5] text-xs font-mono bg-slate-50/50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500">
            </div>
        </div>

        <div class="pt-2">
            <button type="button" id="btnRunTest" class="px-6 py-2.5 bg-[#f58220] hover:bg-[#dc6e10] text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">send</span>
                <span>Send API Test Request</span>
            </button>
        </div>

        <!-- Output Result -->
        <div id="resultBox" class="hidden mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2">
            <div class="flex items-center justify-between font-bold text-xs">
                <span class="text-slate-700 dark:text-slate-300">Response Inspector:</span>
                <span id="responseStatusBadge" class="px-2.5 py-1 rounded-lg text-[11px] font-mono"></span>
            </div>
            <pre id="responseJson" class="p-4 bg-slate-950 text-emerald-400 border border-slate-800 font-mono text-xs rounded-xl overflow-x-auto max-h-96"></pre>
        </div>

    </div>

</div>

<script>
    document.getElementById('btnRunTest').addEventListener('click', function() {
        const appId = document.getElementById('targetApp').value;
        const method = document.getElementById('httpMethod').value;
        const endpoint = document.getElementById('endpointPath').value;
        const btn = this;
        const resultBox = document.getElementById('resultBox');
        const badge = document.getElementById('responseStatusBadge');
        const jsonPre = document.getElementById('responseJson');

        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">refresh</span> Testing...';

        fetch("{{ route('console.tools.execute') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                app_id: appId,
                method: method,
                endpoint: endpoint
            })
        })
        .then(res => res.json())
        .then(data => {
            resultBox.classList.remove('hidden');
            badge.innerText = `${data.status_code} (${data.duration_ms} ms)`;
            if (data.status_code >= 200 && data.status_code < 300) {
                badge.className = 'px-2.5 py-1 rounded-lg text-[11px] font-mono bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800';
            } else {
                badge.className = 'px-2.5 py-1 rounded-lg text-[11px] font-mono bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800';
            }
            jsonPre.innerText = JSON.stringify(data.response || data.error, null, 2);
        })
        .catch(err => {
            resultBox.classList.remove('hidden');
            badge.innerText = 'Network Error';
            badge.className = 'px-2.5 py-1 rounded-lg text-[11px] font-mono bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800';
            jsonPre.innerText = err.toString();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">send</span> <span>Send API Test Request</span>';
        });
    });
</script>
@endsection