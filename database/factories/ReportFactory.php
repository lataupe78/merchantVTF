<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{

    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-3 months', 'now');

        return [
            'author_id' => 1,
            'seller_id' => 1,
            'sale_point_id' => 1,
            'date' => Carbon::parse($date)->format('Y-m-d'),
            'cash' => $cash = fake()->numberBetween(100, 2000),
            'card' => $card = fake()->numberBetween(100, 2000),
            'cash_reel' => $cash + fake()->boolean(20) * mt_rand(-150, 150),
            'card_reel' => $card + fake()->boolean(20) * mt_rand(-150, 150),
            'created_at' => Carbon::parse($date)->addMinutes(60 * 48)->format('Y-m-d'),
        ];
    }
}
