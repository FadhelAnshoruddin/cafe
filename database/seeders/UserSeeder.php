<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Kasir
        DB::table('users')->updateOrInsert(
            ['username' => 'Kasir'],
            [
                'nomor_hp' => null,
                'password_hash' => Hash::make('Kasir123'),
                'peran' => 'kasir',
                'dibuat_pada' => now(),
            ]
        );

        // Akun Manajer
        DB::table('users')->updateOrInsert(
            ['username' => 'Manajer'],
            [
                'nomor_hp' => null,
                'password_hash' => Hash::make('Manajer123'),
                'peran' => 'manajer',
                'dibuat_pada' => now(),
            ]
        );
    }
}