<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInvitation;
use App\Http\Requests\StoreUserInvitationRequest;

class UserInvitationController extends Controller
{
    // user.invitations.show
    // this view not exists yet
    
    public function requestInvitation()
    {
        return view('auth.request'); 
    }

    
    // user.invitations.store
    public function store(StoreUserInvitationRequest $request)
    {

        $invitation = new UserInvitation($request->all());
        $invitation->generateInvitationToken();
        $invitation->save();

        return redirect()->route('user.invitations.show')
            ->with('success', 'Invitation to register successfully requested. Please wait for registration link.');
    }
}
