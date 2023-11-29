<?php

namespace App\Http\Controllers\Manager;

use App\Models\WorkingRange;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkingRangeRequest;
use App\Http\Requests\UpdateWorkingRangeRequest;
use App\Http\Requests\StoreMultipleWorkingRangeRequest;
use Error;
use Exception;

class WorkingRangeController extends Controller
{
    public function store(StoreWorkingRangeRequest $request,)
    {

        $data = $request->validated();

        $range = WorkingRange::create($data);

        return redirect()->back()->with('success', 'range created');
    }


    public function storeAll(StoreMultipleWorkingRangeRequest $request,)
    {

        $data = $request->validated();
        // try {
       
        foreach ($data['ranges'] as $range) {
            dump($range);
            WorkingRange::create($range);
        }

        // dd('done');

        // } catch (Exception $e) {

        //     return redirect()->back()->with('error', $e->getMessage());
        // }



        return redirect()->back()->with('success', 'range created');
    }

    public function update(UpdateWorkingRangeRequest $request, WorkingRange $range)
    {
        $data = $request->validated();
        dd($data);

        $range->update($data);

        return redirect()->back()->with('success', 'range updated');
    }

    public function destroy(WorkingRange $range)
    {
        $range->delete();
        return redirect()->back();
    }
}
