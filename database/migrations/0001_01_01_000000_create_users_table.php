<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_pengguna');

            $table->string('username')->nullable()->unique();

            $table->string('nomor_hp', 20)->nullable()->unique();

            $table->string('password_hash');

            $table->enum('peran', [
                'kasir',
                'manajer',
                'pelanggan',
            ]);

            $table->boolean('wajib_ganti_password')
                ->default(false);

            $table->dateTime('dibuat_pada');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};