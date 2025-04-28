<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les rôles si ce n'est pas déjà fait
        $roles = [
            'Administrateur',
            'DG',
            'Daf',
            'Commercial',
            'Comptable',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Liste des pays
        $pays = [
            1 => 'Côte d\'Ivoire',
            2 => 'Guinée',
            3 => 'Tchad',
            4 => 'Sénégal', // Un autre pays fictif
        ];

        // Tableau d'utilisateurs à créer
        $users = [
            [
                'name' => 'Admin Côte d\'Ivoire',
                'email' => 'admin@example.com',
                'role' => 'Administrateur',
                'pays_id' => 1,
                'phone' => '+22501010101',
                'adresse' => 'Abidjan, Côte d\'Ivoire',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'DG Côte d\'Ivoire',
                'email' => 'dg_ci@example.com',
                'role' => 'DG',
                'pays_id' => 1,
                'phone' => '+22502020202',
                'adresse' => 'Yamoussoukro, Côte d\'Ivoire',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Comptable Côte d\'Ivoire',
                'email' => 'comptable_ci@example.com',
                'role' => 'Comptable',
                'pays_id' => 1,
                'phone' => '+22503030303',
                'adresse' => 'San Pedro, Côte d\'Ivoire',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Commercial Côte d\'Ivoire',
                'email' => 'commercial_ci@example.com',
                'role' => 'Commercial',
                'pays_id' => 1,
                'phone' => '+22504040404',
                'adresse' => 'Bouaké, Côte d\'Ivoire',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'DG Guinée',
                'email' => 'dg_guinee@example.com',
                'role' => 'DG',
                'pays_id' => 2,
                'phone' => '+22402020202',
                'adresse' => 'Kindia, Guinée',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Comptable Guinée',
                'email' => 'comptable_guinee@example.com',
                'role' => 'Comptable',
                'pays_id' => 2,
                'phone' => '+22403030303',
                'adresse' => 'Kankan, Guinée',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Commercial Guinée',
                'email' => 'commercial_guinee@example.com',
                'role' => 'Commercial',
                'pays_id' => 2,
                'phone' => '+22404040404',
                'adresse' => 'Nzérékoré, Guinée',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'DG Tchad',
                'email' => 'dg_tchad@example.com',
                'role' => 'DG',
                'pays_id' => 3,
                'phone' => '+23502020202',
                'adresse' => 'Moundou, Tchad',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Comptable Tchad',
                'email' => 'comptable_tchad@example.com',
                'role' => 'Comptable',
                'pays_id' => 3,
                'phone' => '+23503030303',
                'adresse' => 'Sarh, Tchad',
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Commercial Tchad',
                'email' => 'commercial_tchad@example.com',
                'role' => 'Commercial',
                'pays_id' => 3,
                'phone' => '+23504040404',
                'adresse' => 'Abeché, Tchad',
                'verified_at' => Carbon::now(),
            ],

            
        ];

        // Boucle pour créer chaque utilisateur et lui attribuer un rôle
        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt('password'), // Mot de passe par défaut
                'status' => 'Actif',
                'image' => 'storage/images/user.jpg',
                'pays_id' => $userData['pays_id'],
                'phone' => $userData['phone'],
                'adresse' => $userData['adresse'],
                'email_verified_at' => $userData['verified_at'],
            ]);

            // Assignation du rôle à l'utilisateur
            $user->assignRole($userData['role']);
        }
    }
}
