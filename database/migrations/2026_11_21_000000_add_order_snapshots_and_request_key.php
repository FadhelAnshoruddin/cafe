<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('request_key')->nullable()->unique();
        });

        Schema::table('orders_details', function (Blueprint $table) {
            $table->string('nama_menu_snapshot')->nullable();
            $table->json('resep_snapshot')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders_details', function (Blueprint $table) {
            $table->dropColumn(['nama_menu_snapshot', 'resep_snapshot']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['request_key']);
            $table->dropColumn('request_key');
        });
    }
};
