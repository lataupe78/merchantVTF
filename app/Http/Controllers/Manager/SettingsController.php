<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
 
    public function debug(Request $request)
    {
        if ($request->has('debug')) {
            session()->put('debug', $request->input('debug'));
            return Redirect::back();
        }
    }


    public function destroy_cache()
    {

        try {
            Artisan::call('cache:clear');
            Artisan::call('config:cache');
            return Redirect::back()
                ->with('success',  __("success_update"));
        } catch (\Exception $e) {
            return Redirect::back()->with(
                'errror',
                __('Something went wrong.') . ' - ' . $e->getMessage()
            );
        }
    }
}
