<?php

namespace Database\Seeders;

use App\Models\AssetUnit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssetUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => 'unité',
                'plural' => 'unités',
                'abbr' => '',
            ],
            [
                'name' => 'litre',
                'plural' => 'litres',
                'abbr' => 'l',
            ],
            [
                'name' => 'kilogramme',
                'plural' => 'kilogrammes',
                'abbr' => 'kg',
            ],
            [
                'name' => 'gramme',
                'plural' => 'grammes',
                'abbr' => 'g',
            ],

            [
                'name' => 'bouteille de 1',
                'plural' => 'bouteilles de 1',
                'abbr' => 'bouteille[1]',
                'quantity' => 1,
            ],
            [
                'name' => 'bouteille de 2',
                'plural' => 'bouteilles de 2',
                'abbr' => 'bouteille[2]',
                'quantity' => 2,
            ],


            [
                'name' => 'bouteille de 5',
                'plural' => 'bouteilles de 5',
                'abbr' => 'bouteille[5]',
                'quantity' => 5,
            ],


            [
                'name' => 'carton de 10',
                'plural' => 'cartons de 10',
                'abbr' => 'carton[10]',
                'quantity' => 10,
            ],
            [
                'name' => 'carton de 20',
                'plural' => 'cartons de 20',
                'abbr' => 'carton[20]',
                'quantity' => 20,
            ],
            [
                'name' => 'carton de 30',
                'plural' => 'cartons de 30',
                'abbr' => 'carton[30]',
                'quantity' => 30,
            ],
            [
                'name' => 'carton de 50',
                'plural' => 'cartons de 50',
                'abbr' => 'carton[50]',
                'quantity' => 50,
            ],
            [
                'name' => 'sac de 5',
                'plural' => 'sacs de 5',
                'abbr' => 'sac[5]',
                'quantity' => 5,
            ],
            [
                'name' => 'sac de 10',
                'plural' => 'sacs de 10',
                'abbr' => 'sac[10]',
                'quantity' => 10,
            ],
            [
                'name' => 'sac de 20',
                'plural' => 'sacs de 20',
                'abbr' => 'sac[20]',
                'quantity' => 20,
            ],
            [
                'name' => 'sac de 25',
                'plural' => 'sacs de 25',
                'abbr' => 'sac[25]',
                'quantity' => 25,
            ],

            [
                'name' => 'Pack de 6',
                'plural' => 'Packs de 6',
                'abbr' => 'pack[6]',
                'quantity' => 6,
            ],
            [
                'name' => 'Pack de 12',
                'plural' => 'Packs de 12',
                'abbr' => 'pack[12]',
                'quantity' => 12,
            ],
            [
                'name' => 'Pack de 30',
                'plural' => 'Packs de 30',
                'abbr' => 'pack[30]',
                'quantity' => 30,
            ],
            [
                'name' => 'Pack de 100',
                'plural' => 'Packs de 100',
                'abbr' => 'pack[100]',
                'quantity' => 100,
            ],
        ];

        foreach ($units as $unit) {
            AssetUnit::create([
                'name' => $unit['name'],
                'plural' => $unit['plural'],
                'abbr' => $unit['abbr'],
                'quantity' => $unit['quantity'] ?? 1
            ]);
        }
    }
}
