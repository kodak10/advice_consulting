<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * Le nom du modèle associé à la factory.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Définir l'état par défaut de la factory.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->company, // Nom du client, ici une entreprise générée
            'numero_cc' => $this->faker->unique()->numerify('CC-#####'), // Numéro unique pour le client
            'telephone' => $this->faker->phoneNumber, // Numéro de téléphone généré
            'adresse' => $this->faker->address, // Adresse générée
            'ville' => $this->faker->city, // Ville générée
            'attn' => $this->faker->name, // Personne à contacter
            'created_by' => User::factory(), // ID de l'utilisateur qui a créé ce client, en utilisant une factory pour User
        ];
    }
}
