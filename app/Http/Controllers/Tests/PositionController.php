<?php

namespace App\Http\Controllers\Tests;

use Inertia\Inertia;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Position::latest()
            ->paginate(50);

        return Inertia::render('Tests/Positions/Index', [
            'positions' => $positions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tests/Positions/Create');
    }
    /* Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'altitude' => 'nullable|numeric',
            'accuracy' => 'nullable|numeric',
            'altitude_accuracy' => 'nullable|numeric',
        ]);

        $postion = Position::create([
            'title' => $request->title,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'altitude' => $request->altitude,
            'accuracy' => $request->accuracy,
            'altitude_accuracy' => $request->altitude_accuracy,
        ]);

        return redirect()->back()
            ->with('success', 'position created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        //
    }
}
