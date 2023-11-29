<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $Impersonatemanager = app('impersonate');

        return [

            ...parent::share($request),

            'impersonate' => [
                'is_impersonating' => $Impersonatemanager->isImpersonating(),
                'impersonator_id' =>  $Impersonatemanager->getImpersonatorId()
            ],

            'locale' => function () {
                return app()->getLocale();
            },

            'debug' =>  function () use ($request) {
                return session()->get('debug', false);
            },


            'per_page' => function () use ($request) {
                return session()->get('per_page', 10);
            },

            'pagination' => function () use ($request) {
                return session()->get('pagination', []);
            },

            'flash' => function () use ($request) {
                return [

                    'message' => function () use ($request) {
                        return $request->session()->get('message');
                    },
                    'success' => function () use ($request) {
                        return $request->session()->get('success');
                    },
                    'info' => function () use ($request) {
                        return $request->session()->get('info');
                    },
                    'error' => function () use ($request) {
                        return $request->session()->get('error');
                    },
                ];
            },

            'auth' => [
                'user' => function () use ($request) {

                    $user = $request->user();
                    if (auth()->check()) {

                        // $permissions =   Auth::user()->permissions->mapWithKeys(function ($pr) {
                        //     return [$pr['name'] => true];
                        // });

                        $roles = Auth::user()->roles->mapWithKeys(function ($pr) {
                            return [$pr['name'] => true];
                        });
                        $user->roles = $roles;
                    }
                    return $user;
                }
            ],

            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],

            'settings' => [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
                'app_name' => config('app.name')
            ]
        ];
    }
}
