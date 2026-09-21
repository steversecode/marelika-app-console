<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\ApiApp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = ApiApp::withCount('logs')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('partner_id', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $apps = $query->paginate(12);

        $stats = [
            'total_apps' => ApiApp::count(),
            'online_apps' => ApiApp::where('status', 'online')->count(),
            'total_api_calls' => ApiApp::sum('total_calls'),
        ];

        return view('console.apps.index', compact('apps', 'stats'));
    }

    public function create()
    {
        $categories = [
            'Seller In-House System',
            'E-Commerce Store Connector',
            'ERP & Odoo Integration',
            'Warehouse & Logistics',
            'Accounting & Finance',
            'Custom Third-Party App',
        ];

        return view('console.apps.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'connection_type' => 'required|in:database,http_api',
            'db_host' => 'nullable|string|max:100',
            'db_port' => 'nullable|string|max:10',
            'db_database' => 'nullable|string|max:100',
            'db_username' => 'nullable|string|max:100',
            'db_password' => 'nullable|string|max:100',
            'store_url' => 'nullable|url|max:255',
            'redirect_url' => 'nullable|url|max:255',
        ]);

        $partnerId = ApiApp::generatePartnerId();
        $testPartnerId = ApiApp::generateTestPartnerId();
        $apiKey = ApiApp::generateApiKey();
        $testApiKey = ApiApp::generateTestApiKey();

        $app = ApiApp::create(array_merge($validated, [
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'partner_id' => $partnerId,
            'api_key' => $apiKey,
            'test_partner_id' => $testPartnerId,
            'test_api_key' => $testApiKey,
            'status' => 'online',
            'key_expires_at' => now()->addYears(2),
        ]));

        return redirect()->route('console.apps.show', $app->id)
            ->with('success', "App '{$app->name}' successfully created with Partner ID: {$app->partner_id}");
    }

    public function show($id)
    {
        $app = ApiApp::with(['logs' => function ($q) {
            $q->latest()->limit(10);
        }])->findOrFail($id);

        $dbStatus = null;
        if (!empty($app->db_database)) {
            try {
                $db = $app->getStoreConnection();
                $count = $db->table('orders')->count();
                $dbStatus = ['connected' => true, 'orders_count' => $count];
            } catch (\Exception $e) {
                $dbStatus = ['connected' => false, 'error' => $e->getMessage()];
            }
        }

        return view('console.apps.show', compact('app', 'dbStatus'));
    }

    public function edit($id)
    {
        $app = ApiApp::findOrFail($id);
        $categories = [
            'Seller In-House System',
            'E-Commerce Store Connector',
            'ERP & Odoo Integration',
            'Warehouse & Logistics',
            'Accounting & Finance',
            'Custom Third-Party App',
        ];

        return view('console.apps.edit', compact('app', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $app = ApiApp::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'connection_type' => 'required|in:database,http_api',
            'db_host' => 'nullable|string|max:100',
            'db_port' => 'nullable|string|max:10',
            'db_database' => 'nullable|string|max:100',
            'db_username' => 'nullable|string|max:100',
            'db_password' => 'nullable|string|max:100',
            'store_url' => 'nullable|url|max:255',
            'redirect_url' => 'nullable|url|max:255',
            'status' => 'required|in:online,offline,development',
        ]);

        $app->update($validated);

        return redirect()->route('console.apps.show', $app->id)
            ->with('success', 'App configuration updated successfully.');
    }

    public function rotateKey(Request $request, $id)
    {
        $app = ApiApp::findOrFail($id);
        $type = $request->input('type', 'live');

        if ($type === 'test') {
            $newKey = $app->rotateTestKey();
            $msg = 'Test API Partner Key has been regenerated.';
        } else {
            $newKey = $app->rotateKey();
            $msg = 'Live API Partner Key has been rotated. Please update your Odoo configuration with the new key!';
        }

        return redirect()->route('console.apps.show', $app->id)->with('success', $msg);
    }

    public function destroy($id)
    {
        $app = ApiApp::findOrFail($id);
        $name = $app->name;
        $app->delete();

        return redirect()->route('console.apps.index')
            ->with('success', "App '{$name}' deleted successfully.");
    }
}