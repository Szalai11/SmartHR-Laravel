<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nom' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'RH', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Chef', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Employe', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Candidat', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insert($roles);
    }
}