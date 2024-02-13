<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class StartSeeder extends Seeder
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

        User::create([
            'name' => 'Organizador 1',
            'email' => 'organizador1@organizador1',
            'password' => bcrypt('organizador1@organizador1'), 
            'type' => 'organizador',
        ]);

        User::create([
            'name' => 'Inscrito 1',
            'email' => 'inscrito1@inscrito1',
            'password' => bcrypt('inscrito1@inscrito1'), 
            'type' => 'inscrito',
        ]);
    }
}
