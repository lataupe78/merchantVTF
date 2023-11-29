<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\SalePoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalePointController extends Controller
{
    /**
     * manager can manage sale_points
     * 
     */
    public function index()
    {
        $sale_points = SalePoint::query()
            ->with('workers.roles')
            //   ->whereHas('workers', function ($query) {
            //     $query->where('users.id', auth()->id());
            //   })
            ->get();
        // dd($sale_points);

        return Inertia::render('Admin/SalePoints/Index', [
            'sale_points' => $sale_points ?? []
        ]);
    }
}
