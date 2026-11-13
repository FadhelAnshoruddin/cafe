<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders_details', function (Blueprint $table) {
            $table->increments('id_detail');

            $table->unsignedInteger('id_pesanan');

            $table->unsignedInteger('id_menu');

            $table->unsignedInteger('jumlah');

            $table->decimal('harga_satuan', 12, 2);

            $table->decimal('subtotal_item', 12, 2);

            $table->foreign('id_pesanan')
                  ->references('id_pesanan')
                  ->on('orders');

            $table->foreign('id_menu')
                  ->references('id_menu')
                  ->on('menus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders_details');
    }
};