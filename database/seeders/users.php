<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de ejemplo
        DB::table('users')->insert([
            [
                'name' => 'Marc',
                'surname' => 'Lorenzo Oltra',
                'email' => 'marc@example.com',
                'password' => bcrypt('patata'),
                'role' => 'Admin',
                'address' => 'Calle Secundaria 456',
                'phone' => '987654321',
            ],
            [
                'name' => 'Juan',
                'surname' => 'Pérez',
                'email' => 'juan@example.com',
                'password' => bcrypt('patata'),
                'role' => 'Cliente',
                'address' => 'Calle Principal 123',
                'phone' => '123456789',
            ],
            [
                'name' => 'Andrés',
                'surname' => 'León',
                'email' => 'andres@example.com',
                'password' => bcrypt('patata'),
                'role' => 'Barbero',
                'address' => 'Calle Secundaria 456',
                'phone' => '987654321',
            ]
        ]);
    }
}
