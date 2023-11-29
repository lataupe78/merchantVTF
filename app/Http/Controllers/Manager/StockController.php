<?php

namespace App\Http\Controllers\Manager;

use Inertia\Inertia;
use App\Models\Asset;
use App\Models\Stock;
use App\Models\SalePoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    public function index()
    {

        request()->validate([
            'sale_points_ids',
            'assets_ids',
            'stock_min_qty',
            'stock_max_qty',
        ]);

        // liste des assets par des sale_points
        $assets = Asset::query()
            ->with('unit')
            ->orderBy('name', 'asc')
            ->get()->toArray();

        $sale_points = SalePoint::get()->toArray();

        $stocks = Stock::query()
            ->with(
                'asset.unit',
                'sale_point:id,name'
            )
            ->when(request('salepoints_ids'), function ($query) {
                $ids = request('salepoints_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('sale_point_id', $ids);
            })
            ->get()
            ->groupBy('sale_point_id');

        // dd($stocks);

        return Inertia::render('Manager/Stocks/Index', [
            'stocks' => $stocks,
            'assets' => $assets,
            'sale_points' => $sale_points,

            'filters' => [
                ...request()->all([
                    'salepoints_ids', 'assets_ids',
                    'stock_min_qty', 'stock_max_qty',
                ]),
            ]
        ]);
    }
}
