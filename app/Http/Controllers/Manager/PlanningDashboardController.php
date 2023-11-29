<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\SalePoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanningDashboardController extends Controller
{
    public function show()
    {
        $workers = User::query()
            ->where('is_active', true)
            ->select('id', 'name', 'email')
            ->with([
                'latest_punching',
                'latest_working_range',
                'latest_validated_working_range',
            ])
            ->get();

        $sale_points = SalePoint::query()
            ->where('is_active', true)

            ->select('id', 'name')
            ->get();

        return Inertia::render('Manager/PlanningDashboard', [
            'options' => [

                'workers' => $workers,
                'sale_points' => $sale_points,
            ]
        ]);
    }
}
