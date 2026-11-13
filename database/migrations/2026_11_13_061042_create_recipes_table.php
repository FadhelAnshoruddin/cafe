<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->increments('id_resep');

            $table->unsignedInteger('id_menu');

            $table->unsignedInteger('id_bahan');

            $table->decimal('takaran', 12, 3);

            $table->foreign('id_menu')
                  ->references('id_menu')
                  ->on('menus');

            $table->foreign('id_bahan')
                  ->references('id_bahan')
                  ->on('ingredients');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};