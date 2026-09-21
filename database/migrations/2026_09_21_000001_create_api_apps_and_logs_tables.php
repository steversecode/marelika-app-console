<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('Seller In-House System');
            $table->text('description')->nullable();
            $table->string('partner_id')->unique();
            $table->string('api_key', 128)->unique();
            $table->string('test_partner_id')->nullable();
            $table->string('test_api_key', 128)->nullable();
            $table->string('status')->default('online'); // online, offline, development
            $table->string('store_url')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('app_icon')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->unsignedBigInteger('total_calls')->default(0);
            $table->timestamp('key_expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_app_id')->nullable()->constrained('api_apps')->onDelete('cascade');
            $table->string('endpoint');
            $table->string('method', 10)->default('GET');
            $table->integer('status_code')->default(200);
            $table->integer('response_time_ms')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('request_headers')->nullable();
            $table->longText('request_payload')->nullable();
            $table->longText('response_payload')->nullable();
            $table->string('status', 20)->default('success'); // success, failed, warning
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('api_apps');
    }
};