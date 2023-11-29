<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaginationController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'per_page' => ['numeric', 'in:2,5,10,25,50,100,250,1000'],
            'key' => ['sometimes']
        ]);

        $per_page = $request->input('per_page', null);
        $session_key = $request->input('key', null);
        if ($session_key == null) {
            session()->put('per_page', $per_page);
        } else {
            session()->put($session_key, $per_page);
        }

        return response()->json([
            'message'  => 'pagination items update',
            'per_page' => $per_page,
        ]);
    }
}
