<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador 1',
            'email' => 'admin1@admin1',
            'password' => bcrypt('admin1@admin1'),
            'type' => 'administrador', 
        ]);

        User::create([
            'name' => 'Administrador 2',
            'email' => 'admin2@admin2',
            'password' => bcrypt('admin2@admin2'), 
            'type' => 'administrador',
        ]);
    }
}
