<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class services extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'description' => 'Corte de pelo',
                'price' => 20.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'description' => 'Arreglo barba',
                'price' => 10.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'description' => 'Corte de pelo + Barba',
                'price' => 25.00,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
