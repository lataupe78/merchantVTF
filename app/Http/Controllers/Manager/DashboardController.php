<?php

namespace App\Http\Controllers\Manager;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Manager/Dashboard', []);
    }
}
