<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id_pesanan');

            $table->unsignedInteger('id_pengguna');

            $table->enum('sumber', [
                'kasir',
                'apk'
            ]);

            $table->unsignedTinyInteger('nomor_meja');

            $table->enum('metode_bayar', [
                'tunai',
                'qris'
            ]);

            $table->enum('status', [
                'baru',
                'diproses',
                'selesai',
                'dibatalkan'
            ])->default('baru');

            $table->decimal('subtotal', 12, 2);

            $table->decimal('biaya_layanan', 12, 2);

            $table->decimal('pajak', 12, 2);

            $table->decimal('total', 12, 2);

            $table->dateTime('dibuat_pada');

            $table->foreign('id_pengguna')
                  ->references('id_pengguna')
                  ->on('users');
        });

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT chk_orders_nomor_meja
            CHECK (nomor_meja BETWEEN 1 AND 10)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};