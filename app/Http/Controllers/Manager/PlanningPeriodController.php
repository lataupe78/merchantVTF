<?php

namespace App\Http\Controllers\Manager;

use DateTime;
use Inertia\Inertia;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PlanningPeriodController extends Controller
{
    public function month($month = '')
    {
        try {
            $currentMonth = Carbon::createFromFormat('Y-m', $month);
        } catch (\Exception $e) {
            // dump('error ' . $e->getMessage());
            $currentMonth = Carbon::now();
        }

        $date_start = $currentMonth->copy()->startOfMonth();
        $date_end = $currentMonth->copy()->endOfMonth();

        $totals_by_day = $this->totalsByDay($date_start, $date_end);

        $prev = $currentMonth->copy()->sub('month', 1)->startOfMonth();
        $next = $currentMonth->copy()->add('month', 1)->startOfMonth();

        return Inertia::render('Manager/PlanningPeriods/Month', [
            'totals_by_day' => $totals_by_day,
            'dates' => [
                'current' => $currentMonth->format('Y-m-d'),
                'date_start' => $date_start->format('Y-m-d'),
                'date_end' => $date_end->format('Y-m-d'),
                'prev' => $prev,
                'next' => $next,
            ],
        ]);
    }


    public function week($week = '')
    {


        try {
            $currentWeek = new DateTime;
            list($Y, $W) = explode('-', $week);

            // dd([
            //     'W' => $W,
            //     'Y' => $Y
            // ]);


            $currentWeek->setISODate($Y, $W);

            $currentWeek = CarbonImmutable::parse($currentWeek);
            // dd($currentWeek);
        } catch (\Exception $e) {
            $currentWeek = CarbonImmutable::now()->startOfDay();
        }

        $date_start = $currentWeek->startOfWeek();
        $date_end = $currentWeek->endOfWeek();

        $totals_by_day = $this->totalsByDay($date_start, $date_end);

        $prev = $currentWeek->sub('week', 1)->startOfday();
        $next = $currentWeek->add('week', 1)->startOfday();

        return Inertia::render('Manager/PlanningPeriods/Week', [
            'totals_by_day' => $totals_by_day,
            'dates' => [
                'current' => $currentWeek->format('Y-m-d'),
                'date_start' => $date_start->format('Y-m-d'),
                'date_end' => $date_end->format('Y-m-d'),
                'prev' => $prev,
                'next' => $next,
            ],
        ]);
    }



    public function  day($day = '')
    {

        try {
            $currentDay = CarbonImmutable::createFromFormat('Y-m-d', $day)->startOfDay();
        } catch (\Exception $e) {
            $currentDay = CarbonImmutable::now()->startOfDay();
        }

        $totals_by_day = $this->totalsByDay($currentDay, $currentDay);


        $prev = $currentDay->sub('day', 1)->startOfday();
        $next = $currentDay->add('day', 1)->startOfday();

        return Inertia::render('Manager/PlanningPeriods/Day', [
            'totals_by_day' => $totals_by_day,
            'dates' => [
                'current' => $currentDay->format('Y-m-d'),
                
                'prev' => $prev,
                'next' => $next,
            ],
        ]);
    }


    private function totalsByDay(
        $date_start,
        $date_end
    ) {
        $query = DB::table('working_ranges')
            ->join('users', 'working_ranges.worker_id', 'users.id')
            ->join('sale_points', 'working_ranges.sale_point_id', 'sale_points.id')
            ->select(
                DB::raw("
                sale_points.name as salepointName,
                users.name as workerName,
                SUM(time_to_sec(timediff(ends_at, starts_at)) / 3600) as result_prev,
                MAX(validated_at) AS max_validated_at,
                MAX(ended_at) AS max_ended_at,




        COUNT(ended_at) AS completed_days_count,
        COUNT(validated_ended_at) AS validated_completed_days_count,






                DATEDIFF(MAX(ended_at),  MAX(validated_at)) AS days_to_validate,
                DATEDIFF(CURRENT_DATE(),  MAX(validated_at)) AS days_to_validate_from_now,
                SUM(time_to_sec(timediff(ended_at, started_at)) / 3600) as result_reel,
                SUM(time_to_sec(timediff(validated_ended_at, validated_started_at)) / 3600) as validated_result_reel
                ")
            );
        // date_format(current_date, '%Y-%m-%d') as current_date_formatted, 

        $query->whereBetween('current_date', [
            $date_start->format('Y-m-d'),
            $date_end->format('Y-m-d')
        ])

            // ->groupByRaw("date_format(current_date, '%Y-%m-%d'), working_ranges.sale_point_id, working_ranges.worker_id")
            ->groupByRaw("working_ranges.sale_point_id, working_ranges.worker_id")
            //  ->orderBy('current_date')
        ;

        return $query->get()


            ->groupBy('workerName');
    }
}
