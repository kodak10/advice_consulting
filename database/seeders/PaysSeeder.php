<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pays = [
            [
                'name' => 'Côte d\'Ivoire',
                'indicatif' => '225',
                'devise' => 'F CFA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guinée',
                'indicatif' => '224',
                'devise' => 'GNF', // Franc Guinéen
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tchad',
                'indicatif' => '235',
                'devise' => 'F CFA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pays')->insert($pays);
    }
}
