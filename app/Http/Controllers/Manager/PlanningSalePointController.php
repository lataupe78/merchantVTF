<?php

namespace App\Http\Controllers\Manager;

use Inertia\Inertia;
use App\Models\SalePoint;
use App\Models\WorkingRange;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HasPeriod;

class PlanningSalePointController extends Controller
{
    use HasPeriod;

    public function index($month = '')
    {
        $period = $this->datesForMonth($month);

        request()->validate([
            'sale_points_ids',
            // 'workers_ids',
        ]);

        $sale_points = SalePoint::query()
            ->where('is_active', true)
            ->select('id', 'name', 'is_active')
            ->get();


        $working_ranges = WorkingRange::query()
            ->with('sale_point:id,name')
            ->with('worker:id,name')
            ->when(request('sale_points_ids'), function ($query) {
                $ids = request('sale_points_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('sale_point_id', $ids);
            })
            ->whereBetween('current_date', [
                $period->date_start->format('Y-m-d'),
                $period->date_end->format('Y-m-d')
            ])

            ->orderBy('current_date', 'asc')
            ->get()

            ->groupBy('sale_point_id');

        return Inertia::render('Manager/PlanningSalePoints/PlanningSalePointIndex', [
            'sale_points' => $sale_points,
            'working_ranges' => $working_ranges,
            'dates' => $period->formatted_dates,

            'filters' =>  request()->all([
                'salepoints_ids',
                // 'workers_ids'
            ]),

        ]);
    }



    public function show(SalePoint $sale_point, $month = '')
    {
        $period = $this->datesForMonth($month);

        $working_ranges = WorkingRange::query()
            ->where('sale_point_id', $sale_point->id)
            ->with('worker:id,name')

            ->whereBetween('current_date', [
                $period->date_start->format('Y-m-d'),
                $period->date_end->format('Y-m-d')
            ])
            ->get()
            ->groupBy('current_date');

        return Inertia::render('Manager/PlanningSalePoints/PlanningSalePointShow', [

            'sale_point' => $sale_point,
            // 'options' => [
            //     'workers' => $workers,
            //     'sale_points' => $sale_points,
            // ],
            'working_ranges' => $working_ranges,
            'dates' => $period->formatted_dates,


        ]);
    }
}
