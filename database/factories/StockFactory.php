<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'current_stock' => 0,
            'asset_id' => Asset::inRandomOrder()->first()->id,

            'sale_point_id' => null, //SalePoint::inRandomOrder()->first()->id,
        ];
    }
}
