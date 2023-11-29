<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkingRange>
 */
class WorkingRangeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::now();
        return [
            'worker_id' => null,
            'sale_point_id' => null,
            'current_date' => $date->format('Y-m-d'),
            'starts_at' => $date->addHours(8),
            'ends_at' => $date->addHours(18),
            'started_at' => null,
            'ended_at' => null,
        ];
    }
}
