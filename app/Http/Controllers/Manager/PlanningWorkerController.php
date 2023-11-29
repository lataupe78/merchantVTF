<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\SalePoint;
use Carbon\CarbonImmutable;
use App\Models\WorkingRange;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HasPeriod;
use App\Http\Requests\StorePlanningRequest;
use App\Http\Requests\UpdatePlanningRequest;

class PlanningWorkerController extends Controller
{

    use HasPeriod;
    /**
     * return list des workers pour afficher les plannings correspondants
     */

    public function index($month = '')
    {
        $period = $this->datesForMonth($month);

        request()->validate([
            // 'sale_points_ids',
            'workers_ids',
        ]);

        $workers = User::query()
            ->role('seller')
            ->where('is_active', true)
            ->select('id', 'name', 'is_active')
            ->with('roles:id,name')
            ->get();

        $working_ranges = WorkingRange::query()
            ->with('sale_point:id,name')
            ->when(request('workers_ids'), function ($query) {
                $ids = request('workers_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('worker_id', $ids);
            })
            ->whereBetween('current_date', [
                $period->date_start->format('Y-m-d'),
                $period->date_end->format('Y-m-d')
            ])

            ->orderBy('current_date', 'asc')
            ->get()

            ->groupBy('worker_id');

        return Inertia::render('Manager/PlanningWorkers/PlanningWorkerIndex', [
            'workers' => $workers,
            'working_ranges' => $working_ranges,

            'dates' => $period->formatted_dates,

            'filters' =>  request()->all([
                'workers_ids'
                // 'salepoints_ids', 
            ]),

        ]);
    }


    public function show(User $worker, $month = '')
    {
        $period = $this->datesForMonth($month);

        $sale_points  = SalePoint::select('id', 'name')->get();

        $workers = User::query()
            ->role('seller')
            ->where('is_active', true)
            ->select('id', 'name', 'is_active')
            ->with('roles:id,name')
            ->get();


        $working_ranges = WorkingRange::query()
            ->where('worker_id', $worker->id)
            ->with('sale_point:id,name')

            ->whereBetween('current_date', [
                $period->date_start->format('Y-m-d'),
                $period->date_end->format('Y-m-d')
            ])
            ->get()
            ->groupBy('current_date');



        return Inertia::render('Manager/PlanningWorkers/PlanningWorkerShow', [
            'worker' => $worker,
            'options' => [
                'workers' => $workers,
                'sale_points' => $sale_points,
            ],
            'working_ranges' => $working_ranges,
            'dates' => $period->formatted_dates,

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

        return redirect()->route('manager.plannings.workers.index')
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
