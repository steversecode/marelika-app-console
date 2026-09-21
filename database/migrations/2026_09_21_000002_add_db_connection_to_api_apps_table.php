<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_apps', function (Blueprint $table) {
            $table->string('connection_type')->default('database')->after('status'); // database, http_api
            $table->string('db_host')->default('127.0.0.1')->nullable()->after('connection_type');
            $table->string('db_port', 10)->default('3306')->nullable()->after('db_host');
            $table->string('db_database')->nullable()->after('db_port');
            $table->string('db_username')->default('root')->nullable()->after('db_database');
            $table->string('db_password')->nullable()->after('db_username');
        });
    }

    public function down(): void
    {
        Schema::table('api_apps', function (Blueprint $table) {
            $table->dropColumn(['connection_type', 'db_host', 'db_port', 'db_database', 'db_username', 'db_password']);
        });
    }
};