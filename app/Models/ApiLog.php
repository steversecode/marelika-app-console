<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_app_id',
        'endpoint',
        'method',
        'status_code',
        'response_time_ms',
        'ip_address',
        'user_agent',
        'request_headers',
        'request_payload',
        'response_payload',
        'status',
        'error_message',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'response_time_ms' => 'integer',
        'status_code' => 'integer',
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(ApiApp::class, 'api_app_id');
    }
}