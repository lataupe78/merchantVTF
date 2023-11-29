<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index()
    {
        $query = ActivityLog::query()
            ->with([
               
                'subject',
                'causer:id,name,email'
            ]);

        $activities = $query
            ->orderBy('id', 'desc')
            ->paginate(intval(session()->get('per_page.activities', 10)))
            ->withQueryString();



        return Inertia::render('Admin/Activity/Index', [
            'activities' => $activities,
            // 'activities' => ActivityResource::collection($activities),
        ]);
    }
}
