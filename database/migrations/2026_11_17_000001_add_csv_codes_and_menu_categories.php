<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->string('kode_bahan', 30)->nullable()->unique()->after('id_bahan');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->string('kode_menu', 30)->nullable()->unique()->after('id_menu');
        });

        Schema::create('kategori_menus', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('kode_kategori', 30)->unique();
            $table->string('nama_kategori')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_menus');

        Schema::table('menus', function (Blueprint $table) {
            $table->dropUnique(['kode_menu']);
            $table->dropColumn('kode_menu');
        });

        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropUnique(['kode_bahan']);
            $table->dropColumn('kode_bahan');
        });
    }
};
