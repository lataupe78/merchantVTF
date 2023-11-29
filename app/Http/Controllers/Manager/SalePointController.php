<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\SalePoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalePointRequest;
use App\Http\Requests\UpdateSalePointRequest;

class SalePointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = SalePoint::query()
            ->with('workers.roles');
        // ->withCount('workers');

        $sale_points = $query->orderBy('sale_points.name', 'asc')
            ->paginate(session('paginate.sale_points', 10))
            ->withQueryString();

        // return Inertia::render('Manager/SalePoints/Index', [
        return Inertia::render('Manager/SalePoints/Index', [
            'sale_points' => $sale_points,
            'can' => [
                'sale_point' => [
                    'create' => auth()->user()->can('create',  SalePoint::class),
                ]
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salePoint = new SalePoint();

        // return Inertia::render('Manager/SalePoints/Create', [
        return Inertia::render('Manager/SalePoints/Create', [
            'sale_point' => $salePoint,
            'options' => [
                'users' => User::query()
                    // ->where('is_active', true)
                    ->select('id', 'name', 'is_active')
                    ->with('roles:id,name')
                    ->get()

            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalePointRequest $request)
    {
        $this->authorize('create', SalePoint::class);

        $workers = $request->workers ?? null;
        unset($request->workers); // avoid error when fillable is set *

        $salePoint = SalePoint::create(
            $request->validated()
            // useless because of creating in BelongsToTeam Trait
            // + ['team_id' => auth()->user()->current_team_id]
        );

        if ($workers) {
            $salePoint->workers()->sync($workers);
        }

        return redirect()->route('manager.sale_points.index')
            ->with('success', 'update salepoint success');;
    }

    /**
     * Display the specified resource.
     */
    public function show(SalePoint $salePoint)
    {
        $salePoint->load('workers.roles');

        // return Inertia::render('Manager/SalePoints/Show', [
        return Inertia::render('Manager/SalePoints/Show', [
            'sale_point' => $salePoint,
            'can' => [
                'sale_point' => [
                    'create' => auth()->user()->can('create',  SalePoint::class),
                    'update' => auth()->user()->can('update',  $salePoint)
                ]
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalePoint $salePoint)
    {
        $this->authorize('update', $salePoint);

        $salePoint->load('workers');

        // return Inertia::render('Manager/SalePoints/Edit', [
        return Inertia::render('Manager/SalePoints/Edit', [
            'sale_point' => $salePoint,
            'options' => [
                'users' => User::where('is_active', true)
                    ->select('id', 'name')
                    ->with('roles:id,name')
                    ->get()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalePointRequest $request, SalePoint $salePoint)
    {
        $this->authorize('update', $salePoint);

        $workers = $request->workers ?? null;
        unset($request->workers);

        $salePoint->update($request->validated());

        if ($workers) {
            $salePoint->workers()->sync($workers);
        }

        return redirect()->route('manager.sale_points.index')
            ->with('success', 'update salepoint success');;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalePoint $salePoint)
    {
        //
    }
}
