<?php

namespace Database\Seeders;

use App\Models\Devise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devises = [
            [
                'code' => 'CFA',
                'taux_conversion' => 1.00, // Devise de base
            ],
            [
                'code' => 'EUR',
                'taux_conversion' => 655.957, // 1 Euro = 655.957 CFA
            ],
            [
                'code' => 'USD',
                'taux_conversion' => 600.00, // 1 Dollar = 600 CFA (approximatif)
            ],
            [
                'code' => 'GBP',
                'taux_conversion' => 850.00, // 1 Livre = 850 CFA (approximatif)
            ],
            [
                'code' => 'XOF',
                'taux_conversion' => 1.00, // Zone UEMOA (par défaut, équivalent au CFA)
            ],
            [
                'code' => 'XPF',
                'taux_conversion' => 5.427, // 1 XPF = 5.427 CFA
            ],
        ];

        foreach ($devises as $devise) {
            Devise::create($devise);
        }
    }
}
