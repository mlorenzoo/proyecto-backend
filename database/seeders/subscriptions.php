<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class subscriptions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            [
                'plan' => 'Plan Menusal',
                'price' => 29.99,
                'description' => 'Plan menusal.',
                'duration' => 'Mensual',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan' => 'Plan Trimestral',
                'price' => 69.99,
                'description' => 'Plan trimestal.',
                'duration' => 'Trimestral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan' => 'Plan Anual',
                'price' => 249.99,
                'description' => 'Plan anual.',
                'duration' => 'Anual',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
