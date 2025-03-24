<?php

namespace Database\Factories;

use App\Models\Devis;
use App\Models\User;
use App\Models\Client;
use App\Models\Banque;
use Illuminate\Database\Eloquent\Factories\Factory;

class DevisFactory extends Factory
{
    protected $model = Devis::class;

    public function definition()
    {
        return [
            'client_id' => Client::factory(), // Crée un client automatiquement
            'user_id' => User::factory(), // Crée un utilisateur automatiquement
            'date_emission' => now(),
            'date_echeance' => now()->addDays(30),
            'commande' => $this->faker->word(),
            'livraison' => $this->faker->word(),
            'validite' => '30 jours',
            'delai' => '7 jours',
            'banque_id' => Banque::factory()->create()->id ?? null, // Crée une banque si nécessaire
            'total_ht' => $this->faker->randomFloat(2, 1000, 5000),
            'tva' => 0.18,
            'total_ttc' => function (array $attributes) {
                return $attributes['total_ht'] * (1 + $attributes['tva']);
            },
            'acompte' => $this->faker->randomFloat(2, 100, 500),
            'solde' => function (array $attributes) {
                return $attributes['total_ttc'] - $attributes['acompte'];
            },
            'devise' => 'XOF',
            'taux' => 1,

            'num_proforma' => $this->faker->unique()->numerify('PROFORMA-#######'), // Exemple de numéro proforma
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']), // Exemple de status
        ];
    }
}
