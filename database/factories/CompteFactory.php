<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Compte;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompteFactory extends Factory
{
    protected $model = Compte::class;
    
    public function definition(): array
    {

        // $client = Client::factory()->create();

        return [
            'numCompte' => 'CPT-' . fake()->unique()->numberBetween(100000, 999999), 
            'titulaire' => Client::factory(), 
            // 'titulaire' => $client->id,
            'solde' => fake()->randomFloat(2, 10000, 500000),
            'date_creation' => now(),
            'statut' => fake()->randomElement(['actif', 'inactif', 'bloqué'])
        ];
    }
}
