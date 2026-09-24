<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiApp;
use App\Models\ApiLog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OdooGatewayController extends Controller
{
    /**
     * Fetch pending orders for Odoo from direct database or proxy.
     */
    public function getOrders(Request $request): JsonResponse
    {
        $start = microtime(true);
        $apiKey = $request->header('X-BIEMI-API-KEY') 
            ?? $request->header('X-API-KEY') 
            ?? $request->bearerToken() 
            ?? $request->input('api_key');

        $app = ApiApp::where('api_key', $apiKey)
            ->orWhere('test_api_key', $apiKey)
            ->first();

        if (!$app || $app->status === 'offline') {
            $this->logRequest(null, '/api/v1/odoo/orders', 'GET', 401, 'failed', 'Unauthorized: Invalid API Key or App Offline', microtime(true) - $start, $request);
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid Partner Key or App is Inactive in Marelika Console.',
            ], 401);
        }

        $app->increment('total_calls');
        $app->update(['last_used_at' => now()]);

        // 1. DIRECT DATABASE ACCESS (If db_database is set)
        if (!empty($app->db_database)) {
            try {
                $db = $app->getStoreConnection();
                $limit = min((int) $request->input('limit', 50), 100);
                $syncStatus = $request->input('sync_status', 'pending');
                $days = (int) $request->input('days', 30);

                $ordersQuery = $db->table('orders')
                    ->where('payment_status', 'paid')
                    ->where('created_at', '>=', Carbon::now()->subDays($days));

                if ($syncStatus === 'pending') {
                    $ordersQuery->where(function ($q) {
                        $q->whereNull('odoo_sync_status')
                          ->orWhere('odoo_sync_status', 'pending')
                          ->orWhere('odoo_sync_status', 'failed');
                    });
                } elseif ($syncStatus !== 'all') {
                    $ordersQuery->where('odoo_sync_status', $syncStatus);
                }

                $orders = $ordersQuery->orderBy('created_at', 'asc')->limit($limit)->get();

                $orderIds = $orders->pluck('id')->toArray();
                $itemsByOrder = [];
                $addressesByOrder = [];

                if (!empty($orderIds)) {
                    // Check schema for products and product_variants tables
                    $hasVariants = $db->getSchemaBuilder()->hasTable('product_variants');
                    $hasProducts = $db->getSchemaBuilder()->hasTable('products');

                    $itemsQuery = $db->table('order_items')
                        ->whereIn('order_items.order_id', $orderIds);

                    if ($hasVariants && $hasProducts) {
                        $items = $itemsQuery
                            ->leftJoin('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                            ->select(
                                'order_items.*',
                                'product_variants.sku as variant_sku',
                                'product_variants.color as variant_color',
                                'product_variants.size as variant_size',
                                'products.product_name as main_product_name',
                                'products.sku as product_sku'
                            )
                            ->get();
                    } elseif ($hasProducts) {
                        $items = $itemsQuery
                            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                            ->select(
                                'order_items.*',
                                'products.product_name as main_product_name',
                                'products.sku as product_sku'
                            )
                            ->get();
                    } else {
                        $items = $itemsQuery->get();
                    }

                    foreach ($items as $item) {
                        $itemsByOrder[$item->order_id][] = $item;
                    }

                    // Fetch shipping addresses
                    if ($db->getSchemaBuilder()->hasTable('order_addresses')) {
                        $addresses = $db->table('order_addresses')
                            ->whereIn('order_id', $orderIds)
                            ->get()
                            ->keyBy('order_id');
                        $addressesByOrder = $addresses;
                    }
                }

                $formattedOrders = $orders->map(function ($order) use ($itemsByOrder, $addressesByOrder, $db) {
                    $address = $addressesByOrder[$order->id] ?? null;

                    $customerName = $order->is_guest ? $order->guest_name : ($order->guest_name ?: 'Customer #' . ($order->user_id ?? $order->id));
                    $customerEmail = $order->is_guest ? $order->guest_email : $order->guest_email;
                    $customerPhone = $order->is_guest ? $order->guest_phone : ($address->phone ?? $order->guest_phone);

                    // Fetch user name/email if null and user_id exists
                    if (empty($customerName) && !empty($order->user_id)) {
                        $user = $db->table('users')->where('id', $order->user_id)->first();
                        if ($user) {
                            $customerName = $user->name;
                            $customerEmail = $user->email;
                            $customerPhone = $user->phone ?? $customerPhone;
                        }
                    }

                    $shipping = [
                        'recipient_name' => $address->recipient_name ?? $customerName,
                        'phone' => $address->phone ?? $customerPhone,
                        'address_line' => $address->address_line ?? ($order->guest_address ?? ''),
                        'city' => $address->city ?? '',
                        'province' => $address->province ?? '',
                        'postal_code' => $address->postal_code ?? '',
                        'district' => $address->district ?? '',
                    ];

                    $rawItems = $itemsByOrder[$order->id] ?? [];
                    $lines = [];

                    foreach ($rawItems as $item) {
                        // SKU: Prioritaskan SKU Varian > SKU Order Item > SKU Produk Utama > Fallback
                        $sku = !empty($item->variant_sku) 
                            ? trim($item->variant_sku) 
                            : (!empty($item->sku) 
                                ? trim($item->sku) 
                                : (!empty($item->product_sku) 
                                    ? trim($item->product_sku) 
                                    : ('PRD-' . ($item->product_id ?? $item->id))));

                        // Format nama produk beserta variasi (warna / ukuran) jika ada
                        $variantParts = array_filter([$item->variant_color ?? null, $item->variant_size ?? null]);
                        $variantSuffix = !empty($variantParts) ? ' (' . implode(' / ', $variantParts) . ')' : '';

                        $prodName = !empty($item->main_product_name) 
                            ? ($item->main_product_name . $variantSuffix) 
                            : ($item->product_name ?? ($item->name ?? 'Product Item'));

                        $lines[] = [
                            'item_id' => $item->id,
                            'type' => 'product',
                            'product_id' => $item->product_id ?? null,
                            'variant_id' => $item->product_variant_id ?? null,
                            'name' => $prodName,
                            'sku' => $sku,
                            'quantity' => (int) $item->quantity,
                            'price_unit' => (float) $item->price,
                            'subtotal' => (float) ($item->price * $item->quantity),
                        ];
                    }

                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'created_at' => $order->created_at ? Carbon::parse($order->created_at)->toIso8601String() : null,
                        'paid_at' => $order->paid_at ? Carbon::parse($order->paid_at)->toIso8601String() : null,
                        'status' => $order->status,
                        'payment_status' => $order->payment_status,
                        'payment_method' => $order->payment_method ?? 'Bank Transfer',
                        'subtotal' => (float) ($order->subtotal ?? $order->total_price),
                        'discount_amount' => (float) ($order->discount_amount ?? 0),
                        'shipping_courier' => $order->shipping_courier ?? 'Kurir',
                        'shipping_service' => $order->shipping_service ?? 'Standard',
                        'shipping_cost' => (float) ($order->shipping_cost ?? 0),
                        'shipping_discount' => (float) ($order->shipping_discount ?? 0),
                        'gross_shipping_cost' => (float) (($order->shipping_cost ?? 0) + ($order->shipping_discount ?? 0)),
                        'tax_amount' => (float) ($order->tax_amount ?? 0),
                        'total_price' => (float) $order->total_price,
                        'notes' => $order->notes ?? '',
                        'customer' => [
                            'name' => $customerName ?: 'Store Customer',
                            'email' => $customerEmail ?: 'customer@example.com',
                            'phone' => $customerPhone ?: '-',
                        ],
                        'shipping' => $shipping,
                        'items' => $lines,
                    ];
                });

                $this->logRequest($app->id, '/api/v1/odoo/orders', 'GET', 200, 'success', null, microtime(true) - $start, $request, ['count' => count($formattedOrders)]);

                return response()->json([
                    'success' => true,
                    'app_name' => $app->name,
                    'partner_id' => $app->partner_id,
                    'count' => count($formattedOrders),
                    'orders' => $formattedOrders,
                ]);
            } catch (\Exception $e) {
                $errorMsg = 'Database Query Error for ' . $app->name . ' (' . $app->db_database . '): ' . $e->getMessage();
                $this->logRequest($app->id, '/api/v1/odoo/orders', 'GET', 500, 'failed', $errorMsg, microtime(true) - $start, $request);
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 500);
            }
        }

        // 2. PROXY VIA HTTP STORE_URL (Fallback)
        if ($app->store_url) {
            try {
                $targetUrl = rtrim($app->store_url, '/') . '/api/v1/odoo/orders?' . http_build_query($request->all());
                $res = Http::withHeaders([
                    'X-BIEMI-API-KEY' => $apiKey,
                    'Accept' => 'application/json',
                ])->timeout(30)->get($targetUrl);

                $this->logRequest($app->id, '/api/v1/odoo/orders', 'GET', $res->status(), $res->successful() ? 'success' : 'failed', null, microtime(true) - $start, $request, $res->json());

                return response()->json($res->json(), $res->status());
            } catch (\Exception $e) {
                $this->logRequest($app->id, '/api/v1/odoo/orders', 'GET', 502, 'failed', $e->getMessage(), microtime(true) - $start, $request);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to reach store server: ' . $e->getMessage(),
                ], 502);
            }
        }

        // 3. Mock Response
        $this->logRequest($app->id, '/api/v1/odoo/orders', 'GET', 200, 'success', null, microtime(true) - $start, $request);
        return response()->json([
            'success' => true,
            'app_name' => $app->name,
            'partner_id' => $app->partner_id,
            'orders' => [],
            'message' => 'Connection OK. Configure Database Name in App detail to query live store orders.',
        ]);
    }

    /**
     * Mark order as synced directly in database.
     */
    public function markSynced(Request $request, $id): JsonResponse
    {
        $start = microtime(true);
        $apiKey = $request->header('X-BIEMI-API-KEY') 
            ?? $request->header('X-API-KEY') 
            ?? $request->header('X-MARELIKA-API-KEY') 
            ?? $request->bearerToken() 
            ?? $request->input('api_key');

        $partnerId = $request->header('X-PARTNER-ID') ?? $request->input('partner_id');

        $app = null;
        if (!empty($apiKey)) {
            $app = ApiApp::where('api_key', $apiKey)
                ->orWhere('test_api_key', $apiKey)
                ->first();
        }
        if (!$app && !empty($partnerId)) {
            $app = ApiApp::where('partner_id', $partnerId)
                ->orWhere('test_partner_id', $partnerId)
                ->first();
        }

        if (!$app) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $odooOrderId = $request->input('odoo_order_id');
        $odooOrderName = $request->input('odoo_order_name');

        if (!empty($app->db_database)) {
            try {
                $db = $app->getStoreConnection();
                $db->table('orders')->where('id', $id)->update([
                    'odoo_sync_status' => 'synced',
                    'odoo_order_id' => $odooOrderId,
                    'odoo_order_name' => $odooOrderName,
                    'odoo_synced_at' => now(),
                    'odoo_sync_error' => null,
                ]);

                $this->logRequest($app->id, "/api/v1/odoo/orders/{$id}/mark-synced", 'POST', 200, 'success', null, microtime(true) - $start, $request);
                return response()->json(['success' => true, 'message' => "Order #{$id} marked as synced in {$app->db_database}."]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => true, 'message' => 'Order marked as synced.']);
    }

    /**
     * Update order delivery tracking number directly in database.
     */
    public function updateTracking(Request $request, $id): JsonResponse
    {
        $start = microtime(true);
        $apiKey = $request->header('X-BIEMI-API-KEY') 
            ?? $request->header('X-API-KEY') 
            ?? $request->header('X-MARELIKA-API-KEY') 
            ?? $request->bearerToken() 
            ?? $request->input('api_key');

        $partnerId = $request->header('X-PARTNER-ID') ?? $request->input('partner_id');

        $app = null;
        if (!empty($apiKey)) {
            $app = ApiApp::where('api_key', $apiKey)
                ->orWhere('test_api_key', $apiKey)
                ->first();
        }
        if (!$app && !empty($partnerId)) {
            $app = ApiApp::where('partner_id', $partnerId)
                ->orWhere('test_partner_id', $partnerId)
                ->first();
        }

        if (!$app) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $trackingNumber = $request->input('tracking_number');

        if (!empty($app->db_database)) {
            try {
                $db = $app->getStoreConnection();
                $db->table('orders')->where('id', $id)->update([
                    'tracking_number' => $trackingNumber,
                    'biteship_status' => 'delivered',
                    'updated_at' => now(),
                ]);

                $this->logRequest($app->id, "/api/v1/odoo/orders/{$id}/update-tracking", 'POST', 200, 'success', null, microtime(true) - $start, $request);
                return response()->json(['success' => true, 'message' => "Tracking #{$trackingNumber} updated in {$app->db_database}."]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => true, 'message' => 'Tracking updated.']);
    }

    private function logRequest($appId, $endpoint, $method, $statusCode, $status, $error, $duration, Request $request, $responsePayload = null)
    {
        try {
            ApiLog::create([
                'api_app_id' => $appId,
                'endpoint' => $endpoint,
                'method' => $method,
                'status_code' => $statusCode,
                'response_time_ms' => round($duration * 1000),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => $status,
                'error_message' => $error,
                'response_payload' => is_array($responsePayload) ? json_encode($responsePayload) : $responsePayload,
            ]);
        } catch (\Exception $e) {
            // ignore
        }
    }
}