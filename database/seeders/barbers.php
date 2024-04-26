<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class barbers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barbers')->insert([
            [
                'id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
