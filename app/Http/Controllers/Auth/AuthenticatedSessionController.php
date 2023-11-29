<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'userList' => $this->getListUsersByRole(),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // dd(auth()->user());
        $roles = auth()->user()->roles->pluck('name');
        
        $intended = RouteServiceProvider::HOME;

        if ($roles->contains('seller')) {
            $intended = RouteServiceProvider::SELLER_DASHBOARD;
        }
        if ($roles->contains('manager')) {
            $intended = RouteServiceProvider::MANAGER_DASHBOARD;
        }
        // if ($roles->contains('admin') || $roles->contains('Super Admin')) {
        //     $intended = RouteServiceProvider::ADMIN_DASHBOARD;
        // }


        return redirect()->intended($intended);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function getListUsersByRole()
    {

        $roles = Role::get()
            ->pluck('name')->toArray();


        $users = User::query()
            ->with('roles:id,name')
            ->get();

        $usersByRole = [];

        foreach ($users as $user) {

            foreach ($roles as $role) {

                if (isset($user->roles) && $user->roles->contains(function ($userRole) use ($role) {
                    return $userRole->name == $role;
                })) {
                    if (!isset($usersByRole[$role])) {
                        $usersByRole[$role] = [];
                    }

                    $usersByRole[$role][] = $user;
                }
            }
        }

        // dd($usersByRole);
        return $usersByRole;
    }
}
