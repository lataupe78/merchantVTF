<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Report;
use App\Models\SalePoint;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        request()->validate([
            'sort_by' => ['nullable', 'in:date,created_at,sale_point_id,seller_id,cash,cash_reel,card,card_reel'],
            'dir' => ['nullable', 'in:asc,desc'],
            'date_start' => ['nullable', 'date'],
            'date_end' =>   ['nullable', 'date'],
            'sale_points_ids',
            'sellers_ids',
        ]);

        $query = Report::query()->with([
            'sale_point:id,name',
            'seller:id,name',
        ])
            ->when(request('date_start'), function ($query) {
                $query->whereDate('date', '>=', request('date_start'));
            })
            ->when(request('date_end'), function ($query) {
                $query->whereDate('date', '<=', request('date_end'));
            })
            ->when(request('salepoints_ids'), function ($query) {
                $ids = request('salepoints_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('sale_point_id', $ids);
            })
            ->when(request('sellers_ids'), function ($query) {
                $ids = request('sellers_ids');
                $ids = \is_string($ids) ? [$ids] : $ids;

                $query->whereIn('seller_id', $ids);
            });

        $sortBy = request('sort_by') ?? 'date';
        $sortDir = request('dir') ?? 'desc';

        $query
            ->orderBy($sortBy, $sortDir);

        $reports = $query
            ->paginate(session()->get('pagination.reports', 20))
            ->withQueryString();

        $sale_points = SalePoint::select('id', 'name')
            // ->with('workers:id,name')
            ->get();
        $sellers = User::role('seller')
            ->select('id', 'name')
            // ->with('workers:id,name')
            ->get();


        return Inertia::render('Manager/Reports/Index', [

            'reports' => $reports,
            'sale_points' => $sale_points,
            'sellers' => $sellers,
            'filters' => [
                ...request()->all([
                    'date_start', 'date_end',
                    'salepoints_ids', 'sellers_ids'
                ]),
                ...[
                    'sort_by' => $sortBy,
                    'dir' => $sortDir
                ],
            ],
        ]);
    }





    private function getOptions(): array
    {
        return [
            'sale_points' => SalePoint::select('id', 'name')
                ->where('is_active', true)
                ->get(),

            'sellers' => User::select('id', 'name')
                ->role('seller')
                ->where('is_active', true)
                ->get()
        ];
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $this->authorize('create', Report::class);


        return Inertia::render('Manager/Reports/Create', [
            'report' => new Report([
                'author_id' => auth()->user()->id
            ]),
            'options' =>  $this->getOptions()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        Report::create($request->validated() + [
            'author_id' => auth()->id(),
            'sent_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('manager.reports.index')
            ->with('success', __("success_create_item", ['item' => __('report')]));
    }


    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        // $this->authorize('update', $report);

        $report->load('sale_point');


        return Inertia::render('Manager/Reports/Edit', [
            'report' => $report,
            'options' =>  $this->getOptions()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        // $this->authorize('update', $report);
        $report->update($request->validated());
        return redirect()->route('manager.reports.index')
            ->with('success', __("success_update_item", ['item' => __('report')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);
        $report->delete();

        return redirect()->route('manager.reports.index')
            ->with('success', __("success_destroy_item", ['item' => __('report')]));
    }
}
