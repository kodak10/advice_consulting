<?php

namespace Database\Factories;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignationFactory extends Factory
{
    protected $model = Designation::class;

    public function definition()
    {
        return [
            'reference' => $this->faker->unique()->word,  // Génère une référence unique
            'description' => $this->faker->sentence,      // Génère une description aléatoire
            'prix_unitaire' => $this->faker->randomFloat(2, 1, 1000), // Prix unitaire aléatoire
        ];
    }
}
