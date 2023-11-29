<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Punching;
use App\Models\SalePoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanningPunchingController extends Controller
{
    public function index()
    {
        request()->validate([
            'sort_by' => ['nullable', 'in:date,punched_at,worker_id,current_date'],
            'dir' => ['nullable', 'in:asc,desc'],
            'date_start' => ['nullable', 'date'],
            'date_end' =>   ['nullable', 'date'],
            'workers_ids',
            'search',
        ]);


        $query = Punching::query()->with([
            'worker:id,name'
        ])
            ->when(request('date_start'), function ($query) {
                $query->whereDate('current_date', '>=', request('date_start'));
            })
            ->when(request('date_end'), function ($query) {
                $query->whereDate('current_date', '<=', request('date_end'));
            })
            ->when(request('workers_ids'), function ($query) {
                $ids = request('workers_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('worker_id', $ids);
            });


        $sortBy = request('sort_by') ?? 'current_date';
        $sortDir = request('dir') ?? 'desc';

        $query
            ->orderBy($sortBy, $sortDir);

        $punchings = $query
            ->paginate(session()->get('pagination.punchings', 20))
            ->withQueryString();

        $workers = User::query()

            ->select('id', 'name')
            ->with('roles:id,name')
            ->get();

        $sale_points = SalePoint::select('id', 'name')
            ->where('is_active', true)
            ->get();

        return Inertia::render('Manager/PlanningPunchings/Index', [

            'punchings' => $punchings,
            'options' => [
                'sale_points' => $sale_points,
                'workers' => $workers,
            ],

            'filters' => [
                ...request()->all([
                    'date_start', 'date_end',
                    'workers_ids', 'search'
                ]),
                ...[
                    'sort_by' => $sortBy,
                    'dir' => $sortDir
                ],
            ],
        ]);
    }
}
