<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Punching>
 */
class PunchingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $date = Carbon::now()->subMinutes(mt_rand(1, 60 * 24 * 7));
        return [
            'worker_id' => User::factory(),
            'current_date' => $date->format('Y-m-d'),
            'punched_at' => $date->format('Y-m-d H:i:s'),
        ];
    }
}
