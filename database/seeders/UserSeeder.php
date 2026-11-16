<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'admin'],
            [
                'nomor_hp' => null,
                'password_hash' => Hash::make('Manajer123!'),
                'peran' => 'manajer',
                'Manajer123' => true,
                'dibuat_pada' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['username' => 'kasir01'],
            [
                'nomor_hp' => null,
                'password_hash' => Hash::make('Kasir12345!'),
                'peran' => 'kasir',
                'Kasir123' => true,
                'dibuat_pada' => now(),
            ]
        );
    }
}