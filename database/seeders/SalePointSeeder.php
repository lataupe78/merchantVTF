<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SalePoint;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalePointSeeder extends Seeder
{
    public const SALE_POINTS_COUNT = 5;

    public $sale_points = [

        'Le kiosque',
        'Furoncle',
        'glaces',
        'triporteur 1',
        'triporteur 2',
    ];


    public function run(): void
    {
        $workers =  User::query()
            ->role(['manager', 'seller'])
            ->whereNot('id', 1)
            ->get();

        foreach ($this->sale_points as $name) {
            $sale_point = SalePoint::factory()->createQuietly([
                'name' => $name
            ]);
        }

        $sale_points = SalePoint::select('id', 'name')->get();


        foreach ($workers as $worker) {
            // ensure each worker has at least one sale_point
            $salePointsCollection = collect(fake()->randomElements($sale_points, mt_rand(1, count($this->sale_points))));

            $worker->sale_points()->attach($salePointsCollection->pluck('id'));

            dump('sale_points for ' . $worker->name  . ': ' . $salePointsCollection->pluck('name')->implode(', '));
        }
    }
}
