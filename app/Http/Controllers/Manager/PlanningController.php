<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\SalePoint;
use Carbon\CarbonImmutable;
use App\Models\WorkingRange;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanningRequest;
use App\Http\Requests\UpdatePlanningRequest;

class PlanningController extends Controller
{
    public function index($month = '')
    {

        request()->validate([
            'sale_points_ids',
            'workers_ids',
        ]);


        try {
            $currentMonth = CarbonImmutable::createFromFormat('Y-m', $month)
                ->startOfMonth();
        } catch (\Exception $e) {
            // dump('error ' . $e->getMessage());
            $currentMonth = CarbonImmutable::now();
        }

        $date_start = $currentMonth->startOfMonth();
        $date_end = $currentMonth->endOfMonth();

        $prev = $currentMonth->copy()->sub('month', 1)->startOfMonth();
        $next = $currentMonth->copy()->add('month', 1)->startOfMonth();

        $workers  = User::select('id', 'name')
            ->role('seller')
            // ->when(request('workers_ids'), function ($query) {
            //     $ids = request('workers_ids');
            //     $ids = \is_string($ids) ? [$ids] : $ids;

            //     $query->whereIn('id', $ids);
            // })
            // ->with([
            //     'working_ranges' => function ($query) use ($date_start, $date_end) {
            //         $query->whereDateBetween('date', $date_start, $date_end);
            //     }
            // ])
            ->get();

        $sale_points  = SalePoint::select('id', 'name')->get();


        $working_ranges = WorkingRange::query()
            ->with('sale_point:id,name')

            ->when(request('workers_ids'), function ($query) {
                $ids = request('workers_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('worker_id', $ids);
            })


            // ->whereIn('working_ranges.worker_id', collect($workers)->pluck('id'))

            ->when(request('salepoints_ids'), function ($query) {
                $ids = request('salepoints_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('sale_point_id', $ids);
            })
            ->whereBetween('current_date', [
                $date_start->format('Y-m-d'),
                $date_end->format('Y-m-d')
            ])
            ->get()

            ->groupBy('current_date');


        
        return Inertia::render('Manager/Plannings/Index', [
            'workers' => $workers,
            'sale_points' => $sale_points,
            'working_ranges' => $working_ranges,
            'dates' => [
                'current' => $currentMonth->format('Y-m-d'),
                'date_start' => $date_start->format('Y-m-d'),
                'date_end' => $date_end->format('Y-m-d'),
                'prev' => $prev,
                'next' => $next,
            ],

            'filters' =>  request()->all([

                'salepoints_ids', 'workers_ids'
            ]),


        ]);
    }


    public function show(User $worker, $month = '')
    {

        try {
            $currentMonth = CarbonImmutable::createFromFormat('Y-m', $month)
                ->startOfMonth();
        } catch (\Exception $e) {
            // dump('error ' . $e->getMessage());
            $currentMonth = CarbonImmutable::now();
        }

        $date_start = $currentMonth->startOfMonth();
        $date_end = $currentMonth->endOfMonth();

        $prev = $currentMonth->copy()->sub('month', 1)->startOfMonth();
        $next = $currentMonth->copy()->add('month', 1)->startOfMonth();


        $sale_points  = SalePoint::select('id', 'name')->get();

        $workers  = User::select('id', 'name')
            ->role('seller')
            ->get();

        $working_ranges = WorkingRange::query()
            ->where('worker_id', $worker->id)
            ->with('sale_point:id,name')

            ->whereBetween('current_date', [
                $date_start->format('Y-m-d'),
                $date_end->format('Y-m-d')
            ])
            ->get()

            ->groupBy('current_date');


        // return Inertia::render('Manager/Plannings/Show', [
        return Inertia::render('Manager/Plannings/Show', [
            'worker' => $worker,
            'options' => [
                'workers' => $workers,
                'sale_points' => $sale_points,
            ],
            'working_ranges' => $working_ranges,
            'dates' => [
                'current' => $currentMonth->format('Y-m-d'),
                'date_start' => $date_start->format('Y-m-d'),
                'date_end' => $date_end->format('Y-m-d'),
                'prev' => $prev,
                'next' => $next,
            ],

        ]);
    }




    public function store(StorePlanningRequest $request)
    {
        $data = $request->validated();
        dd($data);

        $currentDate = Carbon::parse($data['current_date']);

        dump(['date' => $currentDate->format('d/m/Y')]);
        dump(['started_at' => $data['started_at']]);


        WorkingRange::create($data);

        return redirect()->route('manager.plannings.index')
            ->with('success', 'update salepoint success');;
    }

    public function update(UpdatePlanningRequest $request, $planningId = null)
    {
        dd([
            'planningId' => $planningId,
            'validated' => $request->validated()
        ]);

        $workkingRange = WorkingRange::findOrFail($planningId);
        dump($workkingRange);
    }
}
