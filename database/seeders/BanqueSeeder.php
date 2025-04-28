<?php

namespace Database\Seeders;

use App\Models\Banque;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BanqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banques = [
            [
                'name' => 'Société Générale',
                'num_compte' => 'CI12 1234 5678 9012 3456 7890 1234', // Exemple IBAN
            ],
            [
                'name' => 'NSIA Banque',
                'num_compte' => 'CI34 5678 9012 3456 7890 1234 5678',
            ],
            [
                'name' => 'Banque Atlantique',
                'num_compte' => 'CI56 7890 1234 5678 9012 3456 7890',
            ],
        ];

        foreach ($banques as $banque) {
            Banque::create($banque);
        }
    }
}
