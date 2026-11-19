<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_in', function (Blueprint $table) {
            $table->decimal('harga_per_satuan_dasar', 14, 4)->default(0);
            $table->string('supplier')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('stok_in', function (Blueprint $table) {
            $table->dropColumn(['harga_per_satuan_dasar', 'supplier']);
        });
    }
};
