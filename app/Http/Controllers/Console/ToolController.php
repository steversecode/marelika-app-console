<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\ApiApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ToolController extends Controller
{
    public function testTool(Request $request)
    {
        $apps = ApiApp::where('status', 'online')->get();
        return view('console.tools.test-tool', compact('apps'));
    }

    public function executeTest(Request $request)
    {
        $validated = $request->validate([
            'app_id' => 'required|exists:api_apps,id',
            'endpoint' => 'required|string',
            'method' => 'required|in:GET,POST',
        ]);

        $app = ApiApp::findOrFail($validated['app_id']);
        $url = rtrim($app->store_url ?: url('/'), '/') . '/' . ltrim($validated['endpoint'], '/');

        $start = microtime(true);
        $responsePayload = null;
        $statusCode = 500;
        $error = null;

        try {
            $headers = [
                'X-BIEMI-API-KEY' => $app->api_key,
                'X-PARTNER-ID' => $app->partner_id,
                'Accept' => 'application/json',
            ];

            if ($validated['method'] === 'POST') {
                $response = Http::withHeaders($headers)->timeout(15)->post($url);
            } else {
                $response = Http::withHeaders($headers)->timeout(15)->get($url);
            }

            $statusCode = $response->status();
            $responsePayload = $response->json() ?? $response->body();
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $duration = round((microtime(true) - $start) * 1000, 2);

        return response()->json([
            'status_code' => $statusCode,
            'duration_ms' => $duration,
            'url' => $url,
            'response' => $responsePayload,
            'error' => $error,
        ]);
    }
}