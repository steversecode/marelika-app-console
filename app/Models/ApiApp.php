<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'partner_id',
        'api_key',
        'test_partner_id',
        'test_api_key',
        'status',
        'connection_type',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
        'store_url',
        'redirect_url',
        'app_icon',
        'last_used_at',
        'total_calls',
        'key_expires_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'key_expires_at' => 'datetime',
        'total_calls' => 'integer',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(ApiLog::class, 'api_app_id')->latest();
    }

    public function getStoreConnection()
    {
        if (empty($this->db_database)) {
            return null;
        }

        $connectionName = 'store_db_' . $this->id;

        Config::set("database.connections.{$connectionName}", [
            'driver' => 'mysql',
            'host' => $this->db_host ?: '127.0.0.1',
            'port' => $this->db_port ?: '3306',
            'database' => $this->db_database,
            'username' => $this->db_username ?: 'root',
            'password' => $this->db_password ?: '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ]);

        return DB::connection($connectionName);
    }

    public static function generatePartnerId(string $prefix = '201'): string
    {
        do {
            $id = $prefix . str_pad((string) mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('partner_id', $id)->exists());

        return $id;
    }

    public static function generateTestPartnerId(string $prefix = '113'): string
    {
        do {
            $id = $prefix . str_pad((string) mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('test_partner_id', $id)->exists());

        return $id;
    }

    public static function generateApiKey(): string
    {
        return 'sk_live_' . hash('sha256', Str::random(64) . microtime(true));
    }

    public static function generateTestApiKey(): string
    {
        return 'sk_test_' . hash('sha256', Str::random(64) . microtime(true));
    }

    public function rotateKey(): string
    {
        $newKey = self::generateApiKey();
        $this->update([
            'api_key' => $newKey,
            'key_expires_at' => now()->addYears(2),
        ]);
        return $newKey;
    }

    public function rotateTestKey(): string
    {
        $newKey = self::generateTestApiKey();
        $this->update([
            'test_api_key' => $newKey,
        ]);
        return $newKey;
    }
}