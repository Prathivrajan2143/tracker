<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class InviteController extends Controller
{
    public function handleInvite(Request $request, $domain)
    {
        // Your logic to handle the invite link
        return view('emails/organization_invite', ['domain' => $domain]);
    }
}