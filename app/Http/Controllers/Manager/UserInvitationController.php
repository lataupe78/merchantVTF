<?php

namespace App\Http\Controllers\Manager;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\UserInvitation;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserInvitationResource;
use App\Http\Requests\StoreUserInvitationRequest;

class UserInvitationController extends Controller
{
    public function index()
    {
        $invitations = UserInvitation::query()
            ->where('registered_at', null)
            ->orderBy('created_at', 'desc')
            ->get();
        //  dd($invitations);

        
        return Inertia::render('Manager/UserInvitations/Index', [
            'invitations' =>  UserInvitationResource::collection($invitations)
        ]);
    }


    public function store(StoreUserInvitationRequest $request)
    {

        $invitation = new UserInvitation([
            'email' => $request->email
        ]);

        $invitation->generateInvitationToken();
        $invitation->save();

        return redirect()->route('manager.invitations.index')
            ->with('success', 'Invitation to register successfully requested. Please wait for registration link.');
    }
}
