<?php

namespace App\Http\Controllers\Manager;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanningValidationV2Controller extends Controller
{
    public function show($day = null)
    {

        $tomorrow = Carbon::tomorrow();

        try {
            $currentDay = CarbonImmutable::createFromFormat('Y-m-d', $day)->startOfDay();
        } catch (\Exception $e) {
            $currentDay = CarbonImmutable::now()->startOfDay();
        }
        $prev = $currentDay->sub('day', 1)->startOfday();
        $next = $currentDay->lt($tomorrow) ?
            $currentDay->add('day', 1)->startOfday()
            : null;


        request()->validate([

            // with range validated
            'validated' => ['nullable', 'in:validated,unvalidated,both'],
            // with range planned
            'planned' => ['nullable', 'in:planned,unplanned,both'],
            // with punching
            'completed' => ['nullable', 'in:completed,uncompleted,both'],

            'workers_ids',

        ]);

        $users = User::query()
            ->select('id', 'name', 'is_active')

            ->when(request('workers_ids'), function ($query) {
                $ids = request('workers_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('id', $ids);
            })

            ->when(request('completed'), function ($query) use ($currentDay) {
                $status = request('completed');
                if ($status == 'completed') {
                    $query->whereHas('punchings', function ($query) use ($currentDay) {
                        $query->whereDate('current_date',  $currentDay->format('Y-m-d'));
                    });
                }
                if ($status == 'uncompleted') {
                    $query->whereDoesntHave('punchings', function ($query) use ($currentDay) {
                        $query->whereDate('current_date',  $currentDay->format('Y-m-d'));
                    });
                }
            })
            ->when(request('planned'), function ($query) use ($currentDay) {
                $status = request('planned');
                if ($status == 'planned') {
                    $query->whereHas('working_ranges', function ($query) use ($currentDay) {
                        $query->whereDate('current_date',  $currentDay->format('Y-m-d'));
                    });
                }
                if ($status == 'unplanned') {
                    $query->whereDoesntHave('working_ranges', function ($query) use ($currentDay) {
                        $query->whereDate('current_date',  $currentDay->format('Y-m-d'));
                    });
                }
            })
            ->when(request('validated'), function ($query) use ($currentDay) {
                $status = request('validated');
                if ($status == 'validated') {
                    $query->whereHas('working_ranges', function ($query) use ($currentDay) {
                        $query->whereDate('current_date',  $currentDay->format('Y-m-d'))
                            ->validated();
                    });
                }
                if ($status == 'unvalidated') {
                    $query->whereHas('working_ranges', function ($query) use ($currentDay) {
                        $query->whereDate('current_date',  $currentDay->format('Y-m-d'))
                            ->unvalidated();
                    });
                }
            })

            ->with([
                'punchings' => function ($query) use ($currentDay) {
                    $query->whereDate('current_date',  $currentDay->format('Y-m-d'));
                },
                'working_ranges' => function ($query) use ($currentDay) {
                    $query

                        ->whereDate('current_date',  $currentDay->format('Y-m-d'));
                },
            ])
            // ->withQueryString()
            ->get();


        $workers = User::query()
            ->select('id', 'name')
            ->with('roles:id,name')
            ->get();



        return Inertia::render('Manager/PlanningValidations/PlanningValidationShowV2', [

            'users' => $users,

            'dates' => [
                'current' => $currentDay->format('Y-m-d'),
                'prev' => $prev,
                'next' => $next,
            ],

            'options' => [
                // 'sale_points' => $sale_points,
                'workers' => $workers,
            ],

            'filters' => [
                ...request()->all([
                    'validated',
                    'completed',
                    'planned',
                    'workers_ids',
                ]),
            ]
        ]);
    }
}
