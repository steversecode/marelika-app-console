<?php

namespace Database\Seeders;

use App\Models\ApiApp;
use Illuminate\Database\Seeder;

class ApiAppSeeder extends Seeder
{
    public function run(): void
    {
        $initialApps = [
            [
                'name' => 'Biemi Official Web Store',
                'slug' => 'biemi-store',
                'category' => 'Seller In-House System',
                'description' => 'Main website e-commerce connector for Biemi Official Store',
                'store_url' => 'https://biemi.id',
                'partner_id' => '2010556',
                'test_partner_id' => '1132042',
                'status' => 'online',
                'key_expires_at' => now()->addYears(2),
            ],
            [
                'name' => 'Clabycla Official Store',
                'slug' => 'clabycla-store',
                'category' => 'Seller In-House System',
                'description' => 'Website e-commerce connector for Clabycla Store',
                'store_url' => 'https://clabycla.com',
                'partner_id' => '2010632',
                'test_partner_id' => '1132091',
                'status' => 'online',
                'key_expires_at' => now()->addYears(2),
            ],
            [
                'name' => 'Manee Official Store',
                'slug' => 'manee-store',
                'category' => 'Seller In-House System',
                'description' => 'Website e-commerce connector for Manee Store',
                'store_url' => 'https://manee.id',
                'partner_id' => '2010532',
                'test_partner_id' => '1132012',
                'status' => 'online',
                'key_expires_at' => now()->addYears(2),
            ],
        ];

        foreach ($initialApps as $appData) {
            if (!ApiApp::where('slug', $appData['slug'])->exists()) {
                $appData['api_key'] = ApiApp::generateApiKey();
                $appData['test_api_key'] = ApiApp::generateTestApiKey();
                ApiApp::create($appData);
            }
        }
    }
}
