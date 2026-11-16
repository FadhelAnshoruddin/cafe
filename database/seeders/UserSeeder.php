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
            ['username' => 'Intan Kusuma'],
            [
                'nomor_hp' => null,
                'password_hash' => Hash::make('Intan Kusuma123'),
                'peran' => 'kasir',
                'dibuat_pada' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['username' => 'Dio Permana'],
            [
                'nomor_hp' => null,
                'password_hash' => Hash::make('Dio Permana123'),
                'peran' => 'manajer',
                'dibuat_pada' => now(),
            ]
        );
    }
}
