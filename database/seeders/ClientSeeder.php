<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un utilisateur existant pour remplir 'created_by'
        $user = User::first();

        if (!$user) {
            $this->command->error('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return;
        }

        $clients = [
            [
                'nom' => 'Société A',
                'numero_cc' => 'CC001',
                'telephone' => '0102030405',
                'adresse' => '01 BP 100 Abidjan',
                'ville' => 'Abidjan',
                'attn' => 'M. Traoré',
                'email' => 'societeA@example.com',
                'created_by' => $user->id,
            ],
            [
                'nom' => 'Entreprise B',
                'numero_cc' => 'CC002',
                'telephone' => '0605040302',
                'adresse' => '02 BP 200 Bouaké',
                'ville' => 'Bouaké',
                'attn' => 'Mme Koné',
                'email' => 'entrepriseB@example.com',
                'created_by' => $user->id,
            ],
            [
                'nom' => 'Commerce C',
                'numero_cc' => 'CC003',
                'telephone' => '0706050403',
                'adresse' => '03 BP 300 Yamoussoukro',
                'ville' => 'Yamoussoukro',
                'attn' => 'M. Koffi',
                'email' => 'commerceC@example.com',
                'created_by' => $user->id,
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
