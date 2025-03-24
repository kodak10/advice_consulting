<?php

namespace Database\Factories;

use App\Models\Banque;
use Illuminate\Database\Eloquent\Factories\Factory;

class BanqueFactory extends Factory
{
    /**
     * Le nom du modèle associé à la factory.
     *
     * @var string
     */
    protected $model = Banque::class;

    /**
     * Définir l'état par défaut de la factory.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,  // Génère un nom de banque
            'num_compte' => $this->faker->iban,  // Génère un IBAN (ou utilisez 'bankAccountNumber')
        ];
    }

}
