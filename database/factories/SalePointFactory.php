<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalePoint>
 */
class SalePointFactory extends Factory
{
    public function definition(): array
    {

        // 1° -> 111.1 km
        // 2 kms : 2 * 1 /111.1

        // The latitude of Paris, France is 48.864716, and the longitude is 2.349014.

        $oneKm = 1 / 111.1;
        $radius = 2.5 * $oneKm;
        $lat =  48.864716;
        $lng =  2.349014;
 

        return [
            'name' => fake()->company(),
            'description' => fake()->jobTitle(),
            'latitude' => fake()->latitude($lat - $radius, $lat + $radius),
            'longitude' => fake()->longitude($lng - $radius, $lng + $radius),
            'city' => 'Paris',
            'address' => fake()->streetAddress()

        ];
    }
}
