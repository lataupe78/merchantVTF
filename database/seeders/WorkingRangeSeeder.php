<?php

namespace Database\Seeders;

use App\Models\Punching;
use App\Models\User;
use Carbon\CarbonPeriod;
use App\Models\SalePoint;
use Carbon\CarbonImmutable;
use App\Models\WorkingRange;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Carbon\CarbonPeriodImmutable;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkingRangeSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workers  = User::select('id', 'name')
            ->role('seller')
            ->with('sale_points:id,name')
            ->get();

        $today = CarbonImmutable::today();
        // $sale_points  = SalePoint::select('id', 'name')->get();
        $date_last_validation = $today->subWeek(2)->startOfWeek();

        $date_start = $today->subMonths(self::DURATION_IN_MONTHS);

        // dates planifiées en avance
        $date_end = $today->addMonth(1);

        $period = CarbonPeriodImmutable::create($date_start, $date_end);

        $workingRangeSamples = [
            ['start' => 9, 'end' => 15],
            ['start' => 10, 'end' => 18],
            ['start' => 13, 'end' => 21],
        ];

        // Iterate over the period
        foreach ($period as $date) {

            $date = $date->startOfDay();

            dump('workers ranges on '.$date->format('d/m/Y'));

            foreach ($workers as $worker) {
                // worker day off ?
                if (mt_rand(1, 8) < 2) {
                    continue;
                }

                $WR = fake()->randomElement($workingRangeSamples);

                $hasEmployeeRange = $date->lt($today);
                $hasValidation = $date->lt($date_last_validation);

                $starts_at = $date->addHours($WR['start']);
                $ends_at =  $date->addHours($WR['end']);
                $started_at = $ended_at = $validated_started_at = $validated_ended_at = $validated_at = null;

                if ($hasEmployeeRange) {
                    $started_at = $date->addHours($WR['start'])->addMinutes(mt_rand(-10, +30));
                    $ended_at = $date->addHours($WR['end'])->addMinutes(mt_rand(-30, +150));
           
                    // on crée des pointages entrée / sortie
                    $this->createPunchingFor($worker, $date, $started_at, $ended_at);
           
                }


                if ($hasValidation) {


                    $validated_started_at = $this->roundedMinutes($started_at, true, 15);
                    $validated_ended_at = $this->roundedMinutes($ended_at, false, 15);
                    $validated_at = $ended_at->endOfWeek()->addMinutes(mt_rand(120, 60 * 12));

                    // dump([
                    //     'started_at' => $started_at->format('Y-m-d H:i'),
                    //     'validated_started_at' => $validated_started_at->format('Y-m-d H:i', false, 15),

                    //     'ended_at' => $ended_at->format('Y-m-d H:i'),
                    //     'validated_ended_at' => $validated_ended_at->format('Y-m-d H:i', true, 15),


                    // ]);
                }

                $salePoint = fake()->randomElement($worker->sale_points);

                WorkingRange::factory()->createQuietly([
                    'title' => $date->format('d/m/Y') . " - " . $worker->name . " at " . optional($salePoint)->name,
                    'worker_id' => $worker->id,
                    'sale_point_id' => optional($salePoint)->id,

                    'current_date' => $date->format('Y-m-d'),

                    'starts_at' => $starts_at->format('Y-m-d H:i:s'),
                    'ends_at' => $ends_at->format('Y-m-d H:i:s'),

                    'started_at' => $started_at ? $started_at->format('Y-m-d H:i:s') : null,
                    'ended_at' => $ended_at ? $ended_at->format('Y-m-d H:i:s') : null,

                    'validated_started_at' => $validated_started_at ? $validated_started_at->format('Y-m-d H:i:s') : null,
                    'validated_ended_at' => $validated_ended_at ? $validated_ended_at->format('Y-m-d H:i:s') : null,

                    'validated_at' => $validated_at ? $validated_at->format('Y-m-d H:i:s') : null,

                ]);
            }
        }
    }


    private function createPunchingFor($worker, $date, $started_at = null, $ended_at = null)
    {
        // on crée des pointages entrée / sortie
        // avec un peu de chaos si possible (oubli de pointage, multiple pointages)

        Punching::factory()->create([
            'worker_id' => $worker->id,
            'current_date' => $date->format('Y-m-d'),
            'status' => 'in',
            'punched_at' => $started_at ? $started_at->format('Y-m-d H:i:s') : null,
        ]);

        Punching::factory()->create([
            'worker_id' => $worker->id,
            'current_date' => $date->format('Y-m-d'),
            'status' => 'off',
            'punched_at' => $ended_at ? $ended_at->format('Y-m-d H:i:s') : null,

        ]);
    }




    private function roundedMinutes(CarbonImmutable $date, $up = false, $step = 10): Carbon
    {
        $minutes = $date->minute;

        $roundedMinutes = ($up == true)
            ? $step * ceil($minutes / $step)
            : $step * floor($minutes / $step);

        dump("minutes was $minutes, now $roundedMinutes");

        $roundedDate = new Carbon($date);


        $roundedDate->setMinutes(0)->addMinutes($roundedMinutes);
        return $roundedDate;
    }
}
