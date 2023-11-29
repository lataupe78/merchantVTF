<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Punching;
use App\Models\WorkingRange;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlanningRangeValidationRequest;

class PlanningValidationController extends Controller
{
    public function index()
    {
        // listes des horaires passés sans validation

        $today = Carbon::now();

        //========================


// ->join(WorkingRange) 

        //========================

        $rangesToValidate = WorkingRange::query()
            ->with(['worker:id,name', 'sale_point:id,name'])
            ->whereDate('current_date', '<', $today->format('Y-m-d'))
            // ->whereNotNull(['started_at', 'ended_at'])
            ->whereNull(['validated_started_at', 'validated_ended_at'])
            ->get()
            ->groupBy('current_date');


        $punchings = Punching::query()
            ->with('user:id,name')

            ->whereDate('punchings.created_at', '<', $today->endOfDay()->format('Y-m-d H:i:s'))
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            });


        // ->paginate(50);

        // dd($rangesToValidate);
        return Inertia::render('Manager/Plannings/Validation/Index', [
            'ranges' => $rangesToValidate,
            'punchings' => $punchings,
        ]);

        // dd($rangesToValidate);
    }



    public function update(PlanningRangeValidationRequest $request, $rangeId = null)
    {
        $workingRange = WorkingRange::findOrFail($rangeId);
        $data = $request->validated();
        // dd($data);

        $workingRange->update([
            'validated_started_at' => $data['validated_started_at']['date'] . ' ' . $data['validated_started_at']['time'] . ':00',
            'validated_ended_at' => $data['validated_ended_at']['date'] . ' ' . $data['validated_ended_at']['time'] . ':00',
            'validated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with(__('success_update_item', ['item' => __('working_range')]));
        // $i        ->whereNull(['validated_at']) 
    }
}
