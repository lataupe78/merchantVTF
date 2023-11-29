<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Punching;
use Carbon\CarbonImmutable;
use App\Models\WorkingRange;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlanningRangeValidationRequest;
use App\Http\Requests\StoreValidationWorkingRangeRequest;
use App\Http\Requests\UpdateValidationWorkingRangeRequest;

class PlanningValidationController extends Controller
{

    // public function show($day = null)
    // {
    //     $tomorrow = Carbon::tomorrow();

    //     try {
    //         $currentDay = CarbonImmutable::createFromFormat('Y-m-d', $day)->startOfDay();
    //     } catch (\Exception $e) {
    //         $currentDay = CarbonImmutable::now()->startOfDay();
    //     }
    //     $prev = $currentDay->sub('day', 1)->startOfday();
    //     $next = $currentDay->lt($tomorrow) ?
    //         $currentDay->add('day', 1)->startOfday()
    //         : null;

    //     $rangesToValidate = WorkingRange::query()
    //         // ->withCount('worker')
    //         ->selectRaw('working_ranges.*,ROUND(time_to_sec((TIMEDIFF(ends_at, starts_at))) / 60) AS duration_minutes')
    //         ->with(['worker:id,name', 'sale_point:id,name'])
    //         ->whereDate('current_date',  $currentDay->format('Y-m-d'))
    //         ->whereNull(['validated_started_at', 'validated_ended_at'])
    //         ->get();

    //     // dd($rangesToValidate);

    //     // $users = User::query()


    //     $punchings = Punching::query()
    //         ->with('worker:id,name')
    //         ->whereDate('current_date',  $currentDay->format('Y-m-d'))
    //         // ->whereDate('punchings.punched_at', $currentDay->endOfDay()->format('Y-m-d H:i:s'))
    //         ->get();

    //     // dump($punchings);

    //     // $days = $punchings->merge($rangesToValidate, true);

    //     $daysGroupedByWorker = $punchings
    //         ->groupBy(function ($item) {
    //             return $item->current_date;
    //         })
    //         ->transform(function ($item) {
    //             return $item->groupBy('worker_id');
    //         });

    //     $daysGroupedByWorker = array_values($daysGroupedByWorker->toArray());

    //     // dump($days);

    //     return Inertia::render('Manager/PlanningValidations/PlanningValidationShow', [
    //         'days' =>  $daysGroupedByWorker,
    //         'ranges' => $rangesToValidate,
    //         'dates' => [
    //             'current' => $currentDay->format('Y-m-d'),
    //             'prev' => $prev,
    //             'next' => $next,
    //         ],
    //     ]);
    // }


    public function index()
    {
        // listes de tous les pointages passés sans validation

        $today = Carbon::now();
        $tomorrow = Carbon::now()->addDay(1);

        $rangesToValidate = WorkingRange::query()
            // ->with(['worker:id,name', 'sale_point:id,name'])
            ->whereDate('current_date', '<', $tomorrow->format('Y-m-d'))
            // ->whereNotNull(['started_at', 'ended_at'])
            ->whereNull(['validated_started_at', 'validated_ended_at'])
            ->get()
            ->groupBy('current_date');

        // dd($rangesToValidate);
        return Inertia::render('Manager/PlanningValidations/PlanningValidationIndex', [
            'ranges' => $rangesToValidate,
            'punchings' => [], //$punchings,
        ]);

        // dd($rangesToValidate);
    }



    public function store(StoreValidationWorkingRangeRequest $request)
    {

        $data = $request->validated();

        $workingRange = WorkingRange::create([
            'validated_started_at' => $data['validated_started_at']['date'] . ' ' . $data['validated_started_at']['time'] . ':00',
            'validated_ended_at' => $data['validated_ended_at']['date'] . ' ' . $data['validated_ended_at']['time'] . ':00',
            'validated_at' => Carbon::now(),
            'current_date' => $data['current_date'],
            'worker_id' => $data['worker_id'],
            'sale_point_id' => $data['sale_point_id'] ?? null,
        ]);

        return redirect()->back()->with(__('success_create_item', ['item' => __('working_range')]));
    }


    public function update(UpdateValidationWorkingRangeRequest $request, $rangeId = null)
    {

        $data = $request->validated();
        $workingRange = WorkingRange::findOrFail($rangeId);
        // dd($data);

        $workingRange->update([
            'validated_started_at' => $data['validated_started_at']['date'] . ' ' . $data['validated_started_at']['time'] . ':00',
            'validated_ended_at' => $data['validated_ended_at']['date'] . ' ' . $data['validated_ended_at']['time'] . ':00',
            'validated_at' => Carbon::now(),
            'worker_id' => $data['worker_id'],
        ]);

        return redirect()->back()->with(__('success_update_item', ['item' => __('working_range')]));
        // $i        ->whereNull(['validated_at']) 
    }
}
