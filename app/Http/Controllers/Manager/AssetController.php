<?php

namespace App\Http\Controllers\Manager;

use Inertia\Inertia;
use App\Models\Asset;
use App\Models\AssetUnit;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::query()
            ->with('unit', 'units')
            ->orderBy('name', 'asc')
            ->paginate(session()->get('pagination.assets', session()->get('pagination.per_page', 10)));



        return Inertia::render('Manager/Assets/Index', [
            'assets' => $assets,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('Manager/Assets/Create', [
            'asset' => new Asset(),
            'options' => [
                'units' => AssetUnit::get()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        $units = $request->units ?? null;
        unset($request->units);

        $asset = Asset::create($request->validated());

        if ($units) {
            $asset->units()->sync($units);
        }

        return redirect()->route('manager.assets.index')
            ->with('success', __('success_create_item', ['item' => __('asset')]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $asset->load('units');
        return Inertia::render('Manager/Assets/Edit', [
            'asset' => $asset,
            'options' => [
                'units' => AssetUnit::get()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $units = $request->units ?? null;
        unset($request->units);

        $asset->update($request->validated());

        if ($units) {
            $asset->units()->sync($units);
        }

        return redirect()->route('manager.assets.index')
            ->with('success', __('success_update_item', ['item' => __('asset')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);
        $asset->delete();

        return redirect()->route('manager.assets.index')
            ->with('success', __('success_destroy_item', ['item' => __('asset')]));
    }
}
