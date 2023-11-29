<?php

namespace App\Http\Controllers\Manager;

use App\Casts\Money;
use Inertia\Inertia;
use App\Models\Report;
use DateTimeImmutable;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportPeriodController extends Controller
{

    public function month($month = '')
    {
        try {
            $currentMonth = CarbonImmutable::createFromFormat('Y-m', $month);
        } catch (\Exception $e) {
            // dump('error ' . $e->getMessage());
            $currentMonth = CarbonImmutable::now();
        }

        $date_start = $currentMonth->startOfMonth();
        $date_end = $currentMonth->endOfMonth();

        $prev = $currentMonth->sub('month', 1)->startOfMonth();
        $next = $currentMonth->add('month', 1)->startOfMonth();


        $totals_by_day = $this->totalsByDay($date_start, $date_end);

        $total_salepoints = $this->totalsBySalepoint($date_start, $date_end);

        $totals = $this->totals($date_start, $date_end);
        
        // return Inertia::render('Manager/Reports/Periods/Month', [
        return Inertia::render('Manager/ReportPeriods/Month', [
            'totals_by_day' => $totals_by_day,
            'total_salepoints' => $total_salepoints,
            'totals' => $totals,
            'dates' => [
                'current' => $currentMonth->format('Y-m-d'),
                'date_start' => $date_start->format('Y-m-d'),
                'date_end' => $date_end->format('Y-m-d'),
                'prev' => $prev,
                'next' => $next,
            ],
        ]);
    }

    // format : yyyy-ww
    public function week($week = '')
    {
        try {

            list($year, $week) = explode('-', $week);

            $currentWeek = Carbon::now();
            $currentWeek->setISODate($year, $week)->startOfWeek();
        } catch (\Exception $e) {
            // dump('error ' . $e->getMessage());
            $currentWeek = Carbon::now()->startOfWeek();
            // dd('error ' . $e->getMessage());
        }



        // dd($currentWeek);

        $date_start = $currentWeek->copy()->startOfWeek();
        $date_end = $currentWeek->copy()->endOfWeek();

        $prev = $currentWeek->copy()->sub('week', 1)->startOfday();
        $next = $currentWeek->copy()->add('week', 1)->startOfday();

        // dd([
        //     'currentWeek' => $currentWeek,
        //     'date_start' => $date_start,
        //     'date_end' => $date_end,
        // ]);

        $totals_by_day = $this->totalsByDay($date_start, $date_end);

        $total_salepoints = $this->totalsBySalepoint($date_start, $date_end);

        $totals = $this->totals($date_start, $date_end);

        // return Inertia::render('Manager/Reports/Periods/Week', [
        return Inertia::render('Manager/ReportPeriods/Week', [
            'totals_by_day' => $totals_by_day,
            'total_salepoints' => $total_salepoints,
            'totals' => $totals,
            'dates' => [
                'current' => $currentWeek->format('Y-m-d'),
                'date_start' => $date_start->format('Y-m-d'),
                'date_end' => $date_end->format('Y-m-d'),
                'prev' => $prev,
                'next' => $next,
            ],
        ]);
    }

    public function day($day = '')
    {
        try {
            $currentDay = CarbonImmutable::createFromFormat('Y-m-d', $day)->startOfDay();
        } catch (\Exception $e) {
            $currentDay = CarbonImmutable::now()->startOfDay();
        }
        $prev = $currentDay->sub('day', 1)->startOfday();
        $next = $currentDay->add('day', 1)->startOfday();


        $queryReport = Report::query()
            ->select('reports.*',)
            ->with(
                'seller:id,name',
                'sale_point:id,name',
            )
            ->where('date', $currentDay->format('Y-m-d'));

        $reports =  $queryReport->get();

        $reports_grouped = $reports
            ->groupBy('sale_point.name');
        // ->values()
        // ->all();

        $query = Report::query()
            // ->join('sale_points', 'reports.sale_point_id', 'sale_points.id')
            ->selectRaw("
            date_format(date, '%Y-%m-%d') as date,
            sum(cash) as total_cash, 
            sum(cash_reel) as total_cash_reel, 
            sum(card) as total_card,
            sum(card_reel) as total_card_reel,
            sum(card + cash) as total,
            sum(card_reel + cash_reel) as total_reel            
            ")
            ->where('date', $currentDay->format('Y-m-d'));

        // ->whereUserId($user->id)
        $totals = $query->groupByRaw("date_format(date, '%Y-%m-%d')")
            ->orderByDesc('total_reel')
            // ->withCasts([
            //     'total_cash' => Money::class,
            //     'total_cash_reel' => Money::class,
            //     'total_card' => Money::class,
            //     'total_card_reel' => Money::class,
            //     ])
            ->get();


        // dd($totals);

        // return Inertia::render('Manager/Reports/Periods/Day', [
        return Inertia::render('Manager/ReportPeriods/Day', [
            'totals' => $totals,
            'reports' => $reports,
            'reports_grouped' => $reports_grouped,
            'dates' => [
                'current' => $currentDay->format('Y-m-d'),
                'prev' => $prev,
                'next' => $next,
            ],
        ]);
    }


    /**
     * 2023-09-22 :
     * aggregated fiels total* are correctly casted as Money
     */
    private function totalsBySalepoint(
        $date_start,
        $date_end
    ) {

        $query = Report::query()
            ->join('sale_points', 'reports.sale_point_id', 'sale_points.id')
            ->selectRaw('
            sale_points.name as sale_point_name,
            sale_point_id, 
            sum(cash) as total_cash, 
            sum(card) as total_card,
            sum(cash_reel) as total_cash_reel, 
            sum(card_reel) as total_card_reel,
            sum(card + cash) as total,
            sum(card_reel + cash_reel) as total_reel')

            ->whereBetween('date', [
                $date_start->format('Y-m-d'),
                $date_end->format('Y-m-d')
            ])
            ->groupBy('sale_point_id')
            ->orderByDesc('total_reel');




        return $query->get();
    }

    /**
     * 2023-09-22 :
     * aggregated fiels total* are correctly casted as Money
     */
    private function totalsByDay(
        $date_start,
        $date_end
    ) {

        $query = Report::query()
            ->join('sale_points', 'reports.sale_point_id', 'sale_points.id')
            ->selectRaw(" 
            date_format(date, '%Y-%m-%d') as date, 
            sum(cash) as total_cash, 
            sum(cash_reel) as total_cash_reel, 
            sum(card) as total_card,
            sum(card_reel) as total_card_reel,
            sum(card + cash) as total,
            sum(card_reel + cash_reel) as total_reel,
            sale_points.name as sale_point_name,
            sale_point_id 
            ");


        $query->whereBetween('date', [
            $date_start->format('Y-m-d'),
            $date_end->format('Y-m-d')
        ])
            // ->whereUserId($user->id)
            ->groupByRaw("date_format(date, '%Y-%m-%d'), reports.sale_point_id")
            ->orderBy('date');

        // ->withCasts([
        //     'total_cash' => Money::class,
        //     'total_cash_reel' => Money::class,
        //     'total_card' => Money::class,
        //     'total_card_reel' => Money::class,
        // ]);

        return $query->get()
            ->groupBy('date');
    }

    /**
     * 2023-09-22 :
     * aggregated fiels total* are correctly casted as Money
     */
    private function totals(
        $date_start,
        $date_end
    ) {
        $query = Report::query();

        $query->whereBetween('date', [
            $date_start->format('Y-m-d'),
            $date_end->format('Y-m-d')
        ]);

        $query->selectRaw(" 
                sum(cash) as total_cash, 
                sum(card) as total_card,
                sum(cash_reel) as total_cash_reel, 
                sum(card_reel) as total_card_reel,
                sum(card + cash) as total, 
                sum(card_reel + cash_reel) as total_reel 
            ");
        $query->orderByDesc('total_reel');




        // ->whereUserId($user->id)
        // ->groupByRaw("date_format(date, '%Y-%m-%d')")
        // ->orderByDesc('total_reel');

        return $query->get();
    }
}
