<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Inertia\Inertia;
use App\Models\SalePoint;
use App\Events\UserCreated;
use Illuminate\Http\Request;
use App\Events\InviteUserEvent;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Actions\GenerateInvitationLink;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {

        request()->validate([
            'sort_by' => ['nullable', 'in:id,name,created_at,updated_at,is_active'],
            'dir' => ['nullable', 'in:asc,desc'],
            'active' =>  ['nullable', 'in:active,inactive'],
            'roles',
            'sale_points',
            'search',
        ]);

        $query = User::query()

            ->with('roles:id,name', 'permissions:id,name', 'sale_points:id,name')
            ->with('invitation')
            ->withCount('sale_points');

        $query
            ->when(request('active'), function ($query) {
                if (request("active") <> "") {
                    $query->where('is_active', (request('active') == 'inactive') ? 0 : 1);
                }
            })

            ->when(request('search'), function ($query) {
                if (request("search") <> "") {
                    $search =     request('search') . "%";

                    $query->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search);
                }
            })


            ->when(request('sale_points'), function ($query) {
                $sale_points_ids = request('sale_points');
                $sale_points_ids = \is_string($sale_points_ids) ? [$sale_points_ids] : $sale_points_ids;


                $query->WhereHas('sale_points', function ($query) use ($sale_points_ids) {
                    $query->whereIn('sale_points.id',  $sale_points_ids);
                });
            })


            // ->when(request('roles'), function ($query) {
            //     $roles = request('roles');
            //     $roles = \is_string($roles) ? [$roles] : $roles;


            //     $query->WhereHas('roles', function ($query) use ($roles) {
            //         $query->whereIn('name',  $roles);
            //     });
            // }, function ($query) {
            //     $query->doesntHave('roles')
            //         ->orWhereHas('roles', function ($query) {
            //             $query->where('name', '<>', 'Super Admin');
            //         });
            // })
        ;


        $sortBy = request('sort_by') ?? 'id';
        $sortDir = request('dir') ?? 'desc';

        $query
            ->orderBy($sortBy, $sortDir);

        $users = $query
            ->paginate(session()->get('pagination.users', 5))
            ->withQueryString();

        // return Inertia::render('Manager/Users/Index', [
        return Inertia::render('Manager/Users/Index', [
            'users' => UserResource::collection($users),

            'filters' => [
                ...request()->all([
                    'roles', 'active', 'search'
                ]),
                ...[
                    'sort_by' => $sortBy,
                    'dir' => $sortDir
                ],
            ],

            'options' => $this->getOptions()

        ]);
    }

    private function getOptions(): array
    {

        return [
            'roles' => Role::select('id', 'name')
                ->whereIn('name', ['seller', 'manager', 'admin'])
                ->get(),
            'sale_points' => SalePoint::select('id', 'name')
                ->where('is_active', true)
                ->get(),
        ];
    }
    public function create()
    {
        $this->authorize('create', User::class);

        $user = new User();

        // return Inertia::render('Manager/Users/Create', [
        return Inertia::render('Manager/Users/Create', [
            'user' => $user,
            'options' => $this->getOptions()
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        // dd($request->validated());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active,
        ]);

        if ($request->roles) {
            $roles = Role::find($request->roles)->pluck('name');

            $user->assignRole($roles);
        }

        // UserCreated is listened by CreateUserInvitationAndSendEmail 
        // email template is located at 
        // resources/views/emails/user/invitation.blade.php

        event(new InviteUserEvent($user));

        return redirect()->route('manager.users.index')
            ->with('success', __("success_create_item", [
                'item' => __('worker')
            ]));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $user->load('roles', 'sale_points');

        // return Inertia::render('Manager/Users/Edit', [
        return Inertia::render('Manager/Users/Edit', [
            'user' => $user,
            'options' =>  $this->getOptions()
        ]);
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        // dd($request->validated());
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->is_active,
        ]);

        if ($request->roles) {
            $roles = Role::find($request->roles)->pluck('name');

            $user->syncRoles($roles);
        }

        return redirect()->route('manager.users.index')
            ->with('success', __("success_update_item", ['item' => __('worker')]));
    }


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()
            ->with('success', __('success_destroy_item', ['item' => __('worker')]));
    }
}
