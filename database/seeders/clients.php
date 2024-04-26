<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class clients extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            [
                'user_id' => 2, // ID del usuario correspondiente al cliente (debes cambiarlo según tu configuración)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agrega más datos de clientes si lo deseas...
        ]);
    }
}
