<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_in', function (Blueprint $table) {
            $table->increments('id_stok_masuk');

            $table->unsignedInteger('id_bahan');

            $table->unsignedInteger('id_pengguna');

            $table->date('tanggal');

            $table->decimal('jumlah', 12, 3);

            $table->foreign('id_bahan')
                  ->references('id_bahan')
                  ->on('ingredients');

            $table->foreign('id_pengguna')
                  ->references('id_pengguna')
                  ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_in');
    }
};