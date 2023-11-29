<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Stock;
use App\Models\AssetUnit;
use App\Models\SalePoint;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $AssetUnits = AssetUnit::get()->pluck('id', 'abbr');
        // dd($units);


        $assets = [
            ['name' => 'Farine', 'unit' => ['kg', 'sac[5]', 'sac[10]'],],
            ['name' => 'Oeuf', 'unit' => ['', 'carton[20]', 'carton[30]']],
            ['name' => 'Lait', 'unit' => ['l', 'pack[6]']],
            ['name' => 'Sel', 'unit' => 'kg',],
            ['name' => 'Sucre', 'unit' => 'kg',],
            ['name' => 'Piment', 'unit' => 'kg',],
            ['name' => 'Beurre', 'unit' => 'kg',],
            ['name' => 'Huile', 'unit' => ['l', 'bouteille[2]', 'bouteille[5]']],
            ['name' => 'Pommes de terre', 'unit' => 'kg',],

            ['name' => 'Riz', 'unit' => ['kg', 'sac[10]', 'sac[20]', 'sac[25]']],

            ['name' => 'sauce Mayo', 'unit' => ['l', 'bouteille[1]', 'bouteille[5]']],
            ['name' => 'sauce Ketchup', 'unit' => ['l', 'bouteille[1]', 'bouteille[5]']],
            ['name' => 'sauce Aïoli', 'unit' => ['l', 'bouteille[1]', 'bouteille[5]']],
            ['name' => 'sauce Sriracha', 'unit' => ['l', 'bouteille[1]', 'bouteille[5]']],
            ['name' => 'sauce Harissa', 'unit' => ['l', 'bouteille[1]', 'bouteille[5]']],

            // ['name' => 'Pack Bière', 'unit' => 'l',],
            // ['name' => 'Fût Bière', 'quantity_unit' => '25', 'unit' => 'l'],
        ];


        $sale_points = SalePoint::select('id')
            ->withCount('workers')
            ->get();
 

        // foreach (Team::all() as $team) {
        foreach ($assets as $asset) {
            $defaultUnitId = null;

            $hasUnit = (isset($asset['unit']));
            $currentAssetunits = [];
            if ($hasUnit) {
                $currentAssetunits = $asset['unit'];
                if (is_array($currentAssetunits) === true) {
                    $defaultUnitId = $AssetUnits[$asset['unit'][0]] ?? null;
                } else {
                    $defaultUnitId = $AssetUnits[$asset['unit']] ?? null;
                }

                dump(
                    'asset ' . $asset['name'] . ' has defaultUnitId : #' . $defaultUnitId .   print_r($currentAssetunits, true)
                );
            }

            $newAsset = Asset::create([
                'name'        => $asset['name'],
                'description' => $asset['description'] ?? $asset['name'],
                'asset_unit_id' => $defaultUnitId,
            ]);

            if (!is_null($defaultUnitId)) {
                $newAsset->units()->attach($defaultUnitId);
            }

            // remove first element and attach secondary units
            if (is_array($currentAssetunits) === true) {
                dump(['multiple units for ' . $asset['name'] =>  $currentAssetunits]);

                array_shift($currentAssetunits);
                // dump(['currentAssetunits' => $currentAssetunits]);

                if (is_array($currentAssetunits) === true) {
                    $position = 1;
                    foreach ($currentAssetunits as $unit) {
                        $unitId = $AssetUnits[$unit] ?? null;

                        if ($unitId != null) {

                            $newAsset->units()->attach($unitId, ['position' => $position]);

                            $position++;
                        } else {
                            dump($unit . 'not found in ' . print_r($AssetUnits, true));
                        }
                    }
                }
            }


            $danger_level = mt_rand(3, 10);

            foreach ($sale_points as $sale_point) {

                Stock::create([
                    'asset_id' => $newAsset->id,
                    'sale_point_id' => $sale_point->id,
                    'current_stock' => mt_rand(0, 150),
                    'danger_level' => $danger_level * $sale_point->workers_count,
                ]);
            }
        }
    }
}
