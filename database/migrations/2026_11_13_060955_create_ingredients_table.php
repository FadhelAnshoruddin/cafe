<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->increments('id_bahan');

            $table->string('nama_bahan')->unique();

            $table->enum('satuan_dasar', [
                'g',
                'ml',
                'pcs'
            ]);

            $table->decimal('stok', 12, 3);

            $table->decimal('stok_minimum', 12, 3)
                  ->default(0);

            $table->decimal('harga_per_satuan_dasar', 14, 4);

            $table->dateTime('harga_diperbarui_pada')
                  ->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};