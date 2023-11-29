<?php

namespace App\Http\Controllers\Manager;

use Inertia\Inertia;
use App\Models\AssetUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetUnitRequest;
use App\Http\Requests\UpdateAssetUnitRequest;

class AssetUnitController extends Controller
{
    public function index()
    {
        $units = AssetUnit::query()
            ->orderBy('name', 'asc')
            ->paginate(session()->get('pagination.assets_units', 20));

        return Inertia::render('Manager/AssetUnits/Index', [
            'units' => $units,

        ]);
    }

    public function create()
    {

        return Inertia::render('Manager/AssetUnits/Create', [
            'asset_unit' => new AssetUnit(),

        ]);
    }

    public function store(StoreAssetUnitRequest $request)
    {

        $asset_unit = AssetUnit::create($request->validated());



        return redirect()->route('manager.asset_units.index')
            ->with('success', __('success_create_item', ['item' => __('asset_unit')]));
    }

    public function edit(AssetUnit $asset_unit)
    {

        return Inertia::render('Manager/AssetUnits/Edit', [
            'asset_unit' => $asset_unit,

        ]);
    }

    public function update(UpdateAssetUnitRequest $request, AssetUnit $asset_unit)
    {


        $asset_unit->update($request->validated());


        return redirect()->route('manager.asset_units.index')
            ->with('success', __('success_update_item', ['item' => __('asset_unit')]));
    }
}
