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
            ['username' => 'Intan'],
            [
                'nomor_hp' => null,
                'password_hash' => Hash::make('Intan123'),
                'peran' => 'kasir',
                'dibuat_pada' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['username' => 'Permana'],
            [
                'nomor_hp' => null,
                'password_hash' => Hash::make('Permana123'),
                'peran' => 'manajer',
                'dibuat_pada' => now(),
            ]
        );
    }
}
