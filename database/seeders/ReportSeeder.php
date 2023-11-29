<?php

namespace Database\Seeders;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Report;
use Carbon\CarbonPeriod;
use App\Models\SalePoint;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReportSeeder extends DatabaseSeeder
{
   
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SalePoint::removeGlobalScope('team');

        $managersIds = User::role(['admin', 'manager'])
            ->select('id', 'name')->pluck('id')->toArray();

        $salepoints = SalePoint::withoutGlobalScopes()
            ->select('id', 'name')
            ->with(['workers:id,name'])

            ->get()
            ->transform(function ($salePoint) {
                $salePoint->workersIds = $salePoint->workers->pluck('id');
                return $salePoint;
            });


        // dump($salepoints);




        // dd($salepoints);

        $date_start = CarbonImmutable::now()->subMonths(self::DURATION_IN_MONTHS);
        $date_end = CarbonImmutable::now();

        $period = CarbonPeriod::create($date_start, $date_end);

        // Iterate over the period
        foreach ($period as $date) {
            dump('reports for ' . $date->format('Y-m-d'));

            foreach ($salepoints as $salePoint) {

                if (mt_rand(1, 10) > 3) {


                    $sellerIds  = fake()->randomElements(
                        $salePoint->workersIds,
                        min(count($salePoint->workersIds), mt_rand(1, 3))
                    );
                    foreach ($sellerIds as $sellerId) {

                        Report::factory()->createQuietly([

                            'sale_point_id' => $salePoint->id,

                            'author_id' => fake()->randomElement($managersIds),
                            'seller_id' => $sellerId,

                            'date' => Carbon::parse($date)->format('Y-m-d'),
                            'created_at' => Carbon::parse($date)
                                ->startOfDay()
                                ->addHours(17) // fin de journée
                                ->addMinutes(mt_rand(1, 60 * 30))
                                ->format('Y-m-d H:i:00')
                                ?? null
                        ]);
                    }
                }
            }
        }
    }
}
