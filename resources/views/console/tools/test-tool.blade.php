@extends('layouts.console')

@section('title', 'API Test Tool - Shopee Open Platform Console')

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="bg-white rounded-lg border border-[#e8e8e8] p-5 shadow-2xs">
        <h1 class="text-xl font-bold text-[#222]">API Test Tool</h1>
        <p class="text-xs text-slate-500 mt-1">Simulate and test Odoo 13 request calls to store connectors in real-time.</p>
    </div>

    <div class="bg-white rounded-lg border border-[#e8e8e8] shadow-2xs p-6 space-y-4 text-xs" x-data="{ loading: false, result: null }">
        
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Select Target Application</label>
            <select id="targetApp" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs bg-white font-medium">
                @foreach($apps as $app)
                    <option value="{{ $app->id }}">{{ $app->name }} (Partner ID: {{ $app->partner_id }} - {{ $app->store_url ?: 'Local' }})</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-1">
                <label class="block font-semibold text-slate-700 mb-1.5">HTTP Method</label>
                <select id="httpMethod" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs bg-white font-mono font-bold">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                </select>
            </div>
            <div class="sm:col-span-3">
                <label class="block font-semibold text-slate-700 mb-1.5">Endpoint Path</label>
                <input type="text" id="endpointPath" value="api/v1/odoo/orders" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-md focus:outline-none focus:border-[#ee4d2d] text-xs font-mono">
            </div>
        </div>

        <div class="pt-2">
            <button type="button" id="btnRunTest" class="px-6 py-2.5 bg-[#ee4d2d] hover:bg-[#d73211] text-white font-semibold rounded-md shadow-xs transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">send</span>
                <span>Send API Test Request</span>
            </button>
        </div>

        <!-- Output Result -->
        <div id="resultBox" class="hidden mt-6 pt-4 border-t border-slate-200 space-y-2">
            <div class="flex items-center justify-between font-bold text-xs">
                <span class="text-slate-700">Response Inspector:</span>
                <span id="responseStatusBadge" class="px-2 py-0.5 rounded text-[11px] font-mono"></span>
            </div>
            <pre id="responseJson" class="p-4 bg-slate-900 text-emerald-400 font-mono text-xs rounded-lg overflow-x-auto max-h-96"></pre>
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
                badge.className = 'px-2 py-0.5 rounded text-[11px] font-mono bg-emerald-100 text-emerald-800';
            } else {
                badge.className = 'px-2 py-0.5 rounded text-[11px] font-mono bg-rose-100 text-rose-800';
            }
            jsonPre.innerText = JSON.stringify(data.response || data.error, null, 2);
        })
        .catch(err => {
            resultBox.classList.remove('hidden');
            badge.innerText = 'Network Error';
            badge.className = 'px-2 py-0.5 rounded text-[11px] font-mono bg-rose-100 text-rose-800';
            jsonPre.innerText = err.toString();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">send</span> Send API Test Request';
        });
    });
</script>
@endsection