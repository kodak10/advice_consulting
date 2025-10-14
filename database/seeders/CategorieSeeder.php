<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nom' => 'Électronique', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Papeterie', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Informatique', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
