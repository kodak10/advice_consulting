<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            [
                'reference' => 'SERV001',
                'description' => 'Installation de système de paiement par carte (POS)',
                'prix_unitaire' => 150000.00,
            ],
            [
                'reference' => 'SERV002',
                'description' => 'Maintenance et support des terminaux de paiement électronique',
                'prix_unitaire' => 100000.00,
            ],
            [
                'reference' => 'SERV003',
                'description' => 'Développement d\'applications bancaires pour le mobile',
                'prix_unitaire' => 500000.00,
            ],
            [
                'reference' => 'SERV004',
                'description' => 'Gestion des systèmes de sécurité pour transactions électroniques',
                'prix_unitaire' => 800000.00,
            ],
            [
                'reference' => 'SERV005',
                'description' => 'Intégration des solutions de paiement mobile (M-Payment)',
                'prix_unitaire' => 300000.00,
            ],
            [
                'reference' => 'SERV006',
                'description' => 'Audit de conformité des systèmes de paiement',
                'prix_unitaire' => 400000.00,
            ],
            [
                'reference' => 'SERV007',
                'description' => 'Développement d\'API pour les services financiers et bancaires',
                'prix_unitaire' => 600000.00,
            ],
            [
                'reference' => 'SERV008',
                'description' => 'Formation sur la gestion des paiements électroniques et la sécurité des données',
                'prix_unitaire' => 200000.00,
            ],
            [
                'reference' => 'SERV009',
                'description' => 'Consultation pour la mise en place de solutions de paiement sans contact (NFC)',
                'prix_unitaire' => 350000.00,
            ],
            [
                'reference' => 'SERV010',
                'description' => 'Mise en place de solutions de paiement par QR Code',
                'prix_unitaire' => 250000.00,
            ],
        ];

        foreach ($designations as $designation) {
            Designation::create($designation);
        }
    }
}
